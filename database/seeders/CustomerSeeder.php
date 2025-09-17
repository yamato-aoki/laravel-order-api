<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * 顧客データの初期投入を行う。
     *
     * ダミー顧客を10件生成。
     * Factory により name / email / phone / address が自動生成される。
     */
    public function run(): void
    {
        Customer::factory()
            ->count(10)
            ->create();
    }
}
