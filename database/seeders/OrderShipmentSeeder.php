<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderShipment;
use App\Enums\ShippingCarrier;

class OrderShipmentSeeder extends Seeder
{
    /**
     * 注文データのうち一部に発送情報を紐付けて、
     * 併せて注文ステータスも "shipped" に更新する Seeder。
     */
    public function run(): void
    {
        // ランダムな注文を50件取得（母集団）
        $orders = Order::inRandomOrder()->take(50)->get();

        // 発送を必ず行う最低件数（30%）
        $minShippedCount = ceil($orders->count() * 0.3);

        // 発送対象として必ず使う注文（最低分）
        $shippedOrders = $orders->take($minShippedCount);

        foreach ($orders as $order) {
            /**
             * - 最低30%は強制的に発送
             * - 残りは 30% の確率で追加発送（結果的に 30〜60% 程度に）
             */
            if ($shippedOrders->contains($order) || fake()->boolean(30)) {
                // 発送レコードを作成
                OrderShipment::create([
                    'order_id'    => $order->id,
                    'carrier'     => fake()->randomElement(ShippingCarrier::cases())->value,
                    'tracking_no' => 'TRK-' . fake()->unique()->randomNumber(8, true),
                    'shipped_at'  => now()->subDays(rand(1, 5))->format('Y-m-d H:i:s'),
                ]);

                // 注文ステータスを shipped に更新
                $order->update([
                    'status' => 'shipped',
                ]);
            }
        }
    }
}
