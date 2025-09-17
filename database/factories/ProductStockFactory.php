<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * 商品在庫（ProductStock）モデルのファクトリクラス。
 *
 * 通常は Product モデルと関連づけて利用される。
 */
class ProductStockFactory extends Factory
{
    /**
     * デフォルトのダミーデータ定義。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 通常は親Productから渡されるが、独立使用時は自動でProductを作成
            'product_id' => Product::factory(),

            // 在庫数量（10〜100の範囲でランダム）
            'quantity' => $this->faker->numberBetween(10, 100),
        ];
    }
}
