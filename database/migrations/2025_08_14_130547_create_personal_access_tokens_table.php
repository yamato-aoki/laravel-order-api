<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Sanctumによるトークンベース認証のためのテーブルを作成。
     * ユーザーや他モデルと結びつけたアクセストークン情報を格納。
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id(); // 主キー

            $table->morphs('tokenable'); // モデル多態リレーション（例: users, admins）

            $table->text('name'); // トークンの用途など任意の名前
            $table->string('token', 64)->unique(); // ハッシュ化されたトークン
            $table->text('abilities')->nullable(); // スコープ／許可された操作（JSONなど）

            $table->timestamp('last_used_at')->nullable(); // 最終使用日時
            $table->timestamp('expires_at')->nullable()->index(); // 有効期限（期限切れで無効化）

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
        Schema::dropIfExists('personal_access_tokens');
    }
};
