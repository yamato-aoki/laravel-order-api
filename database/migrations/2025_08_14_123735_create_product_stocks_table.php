<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * product_stocks テーブルの作成。
     *
     * 商品ごとの在庫数を管理する補助テーブル。
     * - 商品ID（product_id）と紐づく
     * - 数量（quantity）は整数で保持、0を初期値に設定
     * - 各商品につき1行のみ（ユニーク制約）
     */
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete(); // products に外部キー制約 + 親削除時に連動削除

            $table->unsignedInteger('quantity')->default(0); // 在庫数（初期値0）
            $table->timestamps();

            $table->unique('product_id'); // 各商品に対して1在庫行のみ
        });
    }

    /**
     * テーブル削除（ロールバック用）
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
