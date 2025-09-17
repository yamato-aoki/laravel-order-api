<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * 商品マスタを作成し、各商品に初期在庫も同時に登録する Seeder。
     *
     * ・Product::factory()->withStock() を使うことで
     *   商品ごとに ProductStock も自動で作成される。
     * ・業務上「商品と在庫は1対1」が前提のため、商品数=在庫数。
     */
    public function run(): void
    {
        Product::factory()
            ->count(10)       // 商品10件作成
            ->withStock()     // 各商品に紐づく在庫も1件ずつ生成
            ->create();
    }
}
