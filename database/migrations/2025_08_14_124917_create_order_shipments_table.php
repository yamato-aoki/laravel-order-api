<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 注文出荷テーブルを作成。
     * 各注文に対する出荷情報（配送業者・追跡番号・出荷日時）を記録。
     */
    public function up(): void
    {
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id(); // 主キー

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete(); // 注文削除時に紐づく出荷情報も削除

            $table->string('carrier')->nullable();     // 配送業者（例: 佐川・ヤマト）
            $table->string('tracking_no')->nullable(); // 追跡番号
            $table->timestamp('shipped_at')->nullable(); // 出荷日時（未出荷ならnull）

            $table->timestamps(); // created_at, updated_at

            $table->unique('order_id'); // 1注文 = 1出荷制約（重複防止）
            $table->index('shipped_at'); // 出荷日での検索を高速化
        });
    }

    /**
     * Reverse the migrations.
     *
     * テーブルを削除。
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shipments');
    }
};
