<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Services\OrderService;

class OrderTotalAmountSeeder extends Seeder
{
    /**
     * すべての注文に対して合計金額を再計算し、正確な total_amount を反映する Seeder。
     *
     * 商品価格や数量が後から変わる可能性を考慮し、
     * 再集計の一括処理として用意している。
     */
    public function run(): void
    {
        // 関連する order_items を事前ロードしつつ全注文を取得
        Order::with('orderItems')->get()->each(function ($order) {
            // サービス層にある再計算ロジックを適用して total_amount を更新
            OrderService::recalculateTotal($order);
        });
    }
}
