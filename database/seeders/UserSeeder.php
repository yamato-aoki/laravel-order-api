<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * ユーザー（入力担当者）データを10件生成する Seeder。
     *
     * - ログイン用のデモユーザーや、データ入力担当者のシミュレーションに活用。
     * - パスワードは全件 `password`（Hash化済）として統一されている。
     */
    public function run(): void
    {
        // 🧑‍💻 ダミーユーザーを10件生成（全員がログイン可能な状態）
        User::factory()->count(10)->create();
    }
}
