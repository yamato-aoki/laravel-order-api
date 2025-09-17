<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Enums\ShippingCarrier;

/**
 * 発送情報（OrderShipment）のダミーデータを生成するFactoryクラス。
 */
class OrderShipmentFactory extends Factory
{
    /**
     * デフォルトの状態を定義。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 紐づく注文ID（必要に応じて新規生成）
            'order_id' => Order::factory(),

            // 発送業者（Enum ShippingCarrier からランダムに選択）
            'carrier' => $this->faker->randomElement(ShippingCarrier::cases())->value,

            // 追跡番号（例: TRK-12345678）
            'tracking_no' => 'TRK-' . $this->faker->unique()->numerify('########'),

            // 出荷日（過去5日以内のランダムな日時）
            'shipped_at' => $this->faker->dateTimeBetween('-5 days', 'now'),
        ];
    }
}
