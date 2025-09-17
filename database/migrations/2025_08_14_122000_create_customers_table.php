<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * customers テーブルの作成。
     *
     * 注文対象となる顧客の情報を保持。
     * - 氏名
     * - メールアドレス（任意）
     * - 電話番号（任意）
     * - 住所（任意）
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // 主キー（自動増分）
            $table->string('name'); // 顧客名
            $table->string('email')->unique()->nullable(); // メールアドレス（ユニーク制約、任意）
            $table->string('phone')->nullable(); // 電話番号（任意）
            $table->string('address')->nullable(); // 住所（任意）
            $table->timestamps(); // created_at / updated_at
        });
    }

    /**
     * テーブル削除（ロールバック用）
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
