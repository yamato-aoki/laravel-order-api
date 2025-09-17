<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductStockSeeder extends Seeder
{
    /**
     * 商品ごとの在庫データを初期化し、全商品に対して固定在庫数を設定する Seeder。
     *
     * - Factoryの `withStock()` を使わず、Seederで明示的に生成する方法。
     * - `truncate()` によって事前に全件削除し、ユニーク制約違反を防止。
     * - 本Seederを使うことで、すべての商品に「在庫999」を強制的に設定可能。
     */
    public function run(): void
    {
        // 🔁 在庫テーブルを一旦空にする（UNIQUE制約 product_id 対策）
        DB::table('product_stocks')->truncate();

        // 🏷 全商品の ID を取得
        $productIds = DB::table('products')->pluck('id');

        // ✅ 各商品に対して初期在庫を追加
        foreach ($productIds as $productId) {
            DB::table('product_stocks')->insert([
                'product_id' => $productId,
                'quantity'   => 999, // 👉 固定在庫数（デモ・テスト用）
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
