<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * 顧客データ用のFactoryクラス。
 *
 * シーディングやテスト時に使用するダミーデータ（name, email, phone, address）を生成。
 */
class CustomerFactory extends Factory
{
    /**
     * モデルに対するデフォルトの状態を定義。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 顧客名
            'name' => $this->faker->name(),

            // メールアドレス（一意）
            'email' => $this->faker->unique()->safeEmail(),

            // 電話番号
            'phone' => $this->faker->phoneNumber(),

            // 住所
            'address' => $this->faker->address(),
        ];
    }
}
