<?php

/**
 * 注文関連のビジネスロジックを提供するサービスクラス。
 *
 * ControllerとRepositoryの中間に位置し、
 * データ整形やドメインロジックを担う役割を持つ。
 */

namespace App\Services;

// --- モデル関連 ---
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\OrderShipment;

// --- Laravelファサード（認証・DBトランザクション・ログ・メール送信） ---
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// --- メール通知クラス ---
use App\Mail\OrderCreatedMail;
use App\Mail\OrderShipmentMail;
use App\Mail\OrderStatusUpdatedMail;

use Carbon\Carbon;

class OrderService
{
    /**
     * 注文を作成する（在庫チェック・明細登録・合計金額算出含む）
     */
    public function createOrder(array $data): Order
    {
        Log::debug('Serviceに到達！（items対応版）');

        return DB::transaction(function () use ($data) {
            $total = '0';
            $items = $data['items'] ?? [];

            // [1] 商品ID一覧を抽出し、一括で取得（クエリ最適化）
            $productIds = collect($items)->pluck('product_id')->all();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            // [2] 合計金額を計算（bcmathで誤差対策）
            foreach ($items as $item) {
                $productId = $item['product_id'];
                $product   = $products[$productId] ?? null;

                if (!$product) {
                    throw new \Exception("Product ID {$productId} not found.");
                }

                $quantity = (int) $item['quantity'];
                $subtotal = bcmul((string)$product->price, (string)$quantity, 2);
                $total    = bcadd($total, $subtotal, 2);
            }

            // [3] 注文データを作成（認証ユーザー前提）
            $userId = Auth::id();
            $order = Order::create([
                'user_id'      => $userId,
                'customer_id'  => $data['customer_id'],
                'status'       => 'pending',
                'total_amount' => $total,
            ]);

            // [4] 明細レコードの作成＆在庫の減算（悲観的ロック）
            foreach ($items as $item) {
                $productId = $item['product_id'];
                $product   = $products[$productId] ?? null;

                if (!$product) {
                    throw new \Exception("Product ID {$productId} not found in cached products.");
                }

                $quantity  = (int) $item['quantity'];
                $unitPrice = $product->price;

                if (!isset($unitPrice)) {
                    throw new \Exception("unit_price is missing for product_id {$productId}");
                }

                // 悲観的ロックで在庫取得
                $stock = ProductStock::where('product_id', $productId)
                    ->lockForUpdate()
                    ->first();

                if (!$stock) {
                    throw new \Exception("在庫情報が存在しません（product_id={$productId}）");
                }

                Log::debug("在庫チェック: product_id={$productId}, 在庫={$stock->quantity}, 注文数量={$quantity}");

                if ($stock->quantity < $quantity) {
                    throw new \DomainException("在庫不足（product_id={$productId}）: 残り{$stock->quantity}");
                }

                // 在庫差し引き
                $stock->decrement('quantity', $quantity);

                // 明細レコード登録
                $subtotal = bcmul((string)$unitPrice, (string)$quantity, 2);
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal'   => $subtotal,
                ]);
            }

            // [5] 注文確認メール送信（非同期キュー）
            Mail::to($order->customer->email)->queue(new OrderCreatedMail($order));

            return $order;
        });
    }

    /**
     * 出荷処理を実施し、出荷レコードの登録とメール通知を行う
     */
    public function ship(int $orderId, array $data): OrderShipment
    {
        return DB::transaction(function () use ($orderId, $data) {
            $order = Order::with('shipment', 'customer')->findOrFail($orderId);

            if ($order->shipment) {
                throw new \Exception("Shipment already exists for this order.");
            }

            $shipment = OrderShipment::create([
                'order_id'    => $order->id,
                'carrier'     => $data['carrier'] ?? null,
                'tracking_no' => $data['tracking_no'] ?? null,
                'shipped_at'  => Carbon::now(),
            ]);

            $order->status = 'shipped';
            $order->save();

            Mail::to($order->customer->email)->queue(
                new OrderShipmentMail($order, $shipment)
            );

            return $shipment;
        });
    }

    /**
     * 注文ステータスを更新し、必要に応じて在庫戻しと通知を行う
     */
    public function updateStatus(Order $order, array $validated): Order
    {
        $newStatus = $validated['status'];

        return DB::transaction(function () use ($order, $newStatus) {
            $order->status = $newStatus;
            $order->save();

            if ($newStatus === 'canceled') {
                foreach ($order->items as $item) {
                    ProductStock::where('product_id', $item->product_id)
                        ->increment('quantity', $item->quantity);
                }
            }

            Mail::to($order->customer->email)->queue(new OrderStatusUpdatedMail($order));

            return $order;
        });
    }

    /**
     * 合計金額を再計算する（Seederなどで使用）
     */
    public static function recalculateTotal(Order $order): void
    {
        $order->loadMissing('orderItems');
        $order->update([
            'total_amount' => $order->orderItems->sum('subtotal'),
        ]);
    }
}
