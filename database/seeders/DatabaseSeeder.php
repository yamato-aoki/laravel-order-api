<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * アプリケーション全体の初期データを投入する。
     *
     * 各Seederを順番に実行し、関連データを整合性のある状態で構築する。
     * 実行順序は依存関係を考慮（例: User → Order → Shipment）。
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,              // ユーザー（入力担当者）生成
            CustomerSeeder::class,          // 顧客データ
            ProductSeeder::class,           // 商品マスタ
            ProductStockSeeder::class,      // 商品ごとの在庫数
            OrderSeeder::class,             // 注文データ本体（Order + OrderItems）
            OrderTotalAmountSeeder::class,  // 各注文の合計金額（subtotal合算）
            OrderShipmentSeeder::class      // 注文の出荷情報（出荷済み注文のみ）
        ]);
    }
}
