<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * users テーブルの作成。
     *
     * システムにログインする「社内ユーザー（担当者）」情報を保持。
     * - 氏名
     * - メールアドレス（ログインID）
     * - パスワード（ハッシュ化前提）
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // 主キー（ユーザーID）
            $table->string('name'); // 氏名
            $table->string('email')->unique(); // メールアドレス（ユニーク／ログイン用）
            $table->string('password'); // パスワード（bcrypt前提）
            $table->timestamps(); // created_at / updated_at
        });
    }

    /**
     * テーブル削除（ロールバック用）
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
