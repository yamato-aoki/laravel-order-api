<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * 商品（Product）モデルのダミーデータを生成するファクトリクラス。
 */
class ProductFactory extends Factory
{
    /**
     * デフォルト状態の定義。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // SKU（商品識別コード）をランダム生成（重複防止）
            'sku' => $this->faker->unique()->bothify('SKU-####'),

            // 商品名をランダムな2語で生成（例: "Steel Table"）
            'name' => $this->faker->words(2, true),

            // 価格（100〜5000円の整数）
            'price' => $this->faker->randomFloat(0, 100, 5000),

            // 有効フラグ（デフォルトは true）
            'is_active' => true,
        ];
    }

    /**
     * 関連する在庫（ProductStock）を自動で生成。
     *
     * @param int|null $quantity 任意の在庫数。未指定時はランダム。
     * @return static
     */
    public function withStock(int $quantity = null): static
    {
        return $this->has(
            \App\Models\ProductStock::factory()->state([
                'quantity' => $quantity ?? $this->faker->numberBetween(10, 50),
            ])
        );
    }
}
