<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Laravelのファイル/データベースキャッシュ・ロック用のテーブルを作成。
     * キャッシュドライバが `database` に設定されている場合に使用される。
     */
    public function up(): void
    {
        // キャッシュ保存テーブル（key-valueストア形式）
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();       // キャッシュキー
            $table->mediumText('value');            // シリアライズされた値
            $table->integer('expiration');          // 有効期限（Unixタイムスタンプ）
        });

        // キャッシュロックテーブル（分散ロック用）
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();       // ロック対象のキー
            $table->string('owner');                // 所有者識別子（通常はUUID）
            $table->integer('expiration');          // ロックの有効期限
        });
    }

    /**
     * Reverse the migrations.
     *
     * キャッシュ関連テーブルを削除。
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
