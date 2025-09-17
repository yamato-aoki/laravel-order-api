<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 注文明細テーブルを作成。
     * 各注文ごとに、購入した商品の情報（単価・数量・小計）を保持。
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // 主キー

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete(); // 注文が削除されたら明細も削除

            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete(); // 商品が紐づいている間は削除不可

            $table->unsignedInteger('unit_price'); // 注文時点の単価（商品価格が変動しても影響しない）
            $table->unsignedInteger('quantity')->default(1); // 注文数量（初期値: 1）
            $table->unsignedInteger('subtotal'); // 小計（unit_price × quantity）

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * テーブルを削除。
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
