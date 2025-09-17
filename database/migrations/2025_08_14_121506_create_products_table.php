<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * products テーブルの作成。
     *
     * 商品のマスターデータを保持する。
     * - SKU（商品コード）
     * - 商品名
     * - 価格
     * - 販売状態フラグ
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // 主キー（自動増分）
            $table->string('sku')->unique(); // 商品コード（SKU）※ユニーク制約あり
            $table->string('name'); // 商品名
            $table->unsignedInteger('price'); // 商品価格（マイナス不可）
            $table->boolean('is_active')->default(true); // 販売状態（true=販売中）
            $table->timestamps(); // created_at / updated_at
        });
    }

    /**
     * テーブル削除（ロールバック時）。
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
