<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * orders テーブルの作成。
     *
     * 顧客からの注文情報を管理するテーブル。
     * - ユーザー（入力担当者）と顧客情報に紐づく
     * - ステータスは enum で管理（pending, paid, shipped, canceled）
     * - 合計金額は整数で保持（円単位想定）
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // 主キー

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete(); // 入力担当者（users.id）

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete(); // 注文者（customers.id）

            $table->enum('status', ['pending', 'paid', 'shipped', 'canceled'])
                ->default('pending'); // 注文ステータス

            $table->unsignedInteger('total_amount')->default(0); // 合計金額（円）

            $table->timestamps(); // created_at, updated_at

            $table->index(['customer_id', 'status']); // 顧客と状態の検索用インデックス
        });
    }

    /**
     * テーブル削除（ロールバック用）
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
