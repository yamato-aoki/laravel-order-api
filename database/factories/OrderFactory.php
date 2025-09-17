<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * 注文データ用のFactoryクラス。
 *
 * テストやシーディング時に使用するダミーの注文データを生成。
 */
class OrderFactory extends Factory
{
    // 対象モデルを指定（Laravel 10 では明示することが推奨されている）
    protected $model = Order::class;

    /**
     * モデルに対するデフォルトの状態を定義。
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // ユーザーID：ユーザーを新規に作成
            'user_id' => User::factory(),

            // 顧客ID：既存のCustomerがあれば使用、なければ新規に作成
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),

            // 初期ステータスは pending
            'status' => 'pending',

            // 合計金額は初期は 0（後で OrderItem により上書き）
            'total_amount' => 0,
        ];
    }
}
