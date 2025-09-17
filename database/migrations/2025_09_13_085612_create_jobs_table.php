<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Laravelのキューワーカー用のジョブ管理テーブルを作成。
     * 非同期で処理されるジョブの内容や状態を記録・制御する。
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id'); // 主キー（自動増分）

            $table->string('queue')->index(); // キュー名（例: default, emails）

            $table->longText('payload'); // シリアライズされたジョブデータ（クラス・引数など）

            $table->unsignedTinyInteger('attempts'); // 試行回数（失敗→再実行のカウント）

            $table->unsignedInteger('reserved_at')->nullable(); // ワーカーが予約した時刻（Unixタイム）
            $table->unsignedInteger('available_at'); // 実行可能になる時刻（Unixタイム）
            $table->unsignedInteger('created_at'); // 作成日時（Unixタイム）
        });
    }

    /**
     * Reverse the migrations.
     *
     * ジョブテーブルを削除。
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
