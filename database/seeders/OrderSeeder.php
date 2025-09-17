<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductStock;

class OrderSeeder extends Seeder
{
    /**
     * 注文データと注文商品（OrderItems）を生成し、
     * 注文ごとに合計金額を計算して更新する。
     * 同時に、各商品の在庫数を減算する。
     */
    public function run(): void
    {
        // 50件の注文を生成し、各注文に1〜3個の商品を紐付ける
        $orders = Order::factory()
            ->has(OrderItem::factory()->count(rand(1, 3)))
            ->count(50)
            ->create();

        // 各注文に対して合計金額計算と在庫調整を実行
        foreach ($orders as $order) {
            $order = $order->refresh(); // 関連モデルも含め最新状態を取得

            $total = 0;

            foreach ($order->orderItems as $item) {
                // 小計を計算（unit_price × 数量）
                $subtotal = $item->unit_price * $item->quantity;
                $total += $subtotal;

                // 商品在庫を差し引く（マイナス在庫になる可能性あり → 実務ならバリデーション必要）
                ProductStock::where('product_id', $item->product_id)
                    ->decrement('quantity', $item->quantity);
            }

            // 合計金額を注文に反映
            $order->update([
                'total_amount' => $total,
            ]);
        }
    }
}
