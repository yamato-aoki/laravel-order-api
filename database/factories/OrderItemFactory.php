<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * 注文商品のダミーデータを生成するFactoryクラス。
 */
class OrderItemFactory extends Factory
{
    // 紐づくモデルを明示
    protected $model = OrderItem::class;

    /**
     * デフォルトの状態定義。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 商品情報をランダムに取得（存在しない場合は新規作成）
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        // 数量は1〜5のランダム
        $quantity = $this->faker->numberBetween(1, 5);

        // 単価は商品モデルから取得
        $unitPrice = $product->price;

        // 小計は単価 × 数量（bcmathで精度管理）
        $subtotal = bcmul((string)$unitPrice, (string)$quantity, 2);

        return [
            'product_id' => $product->id,
            'quantity'   => $quantity,
            'unit_price' => $unitPrice,
            'subtotal'   => $subtotal,
        ];
    }
}
