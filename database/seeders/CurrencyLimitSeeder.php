<?php

namespace Database\Seeders;

use App\Models\CurrencyLimit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencyLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $limits = [
            [
                'currency'    => 'XOF',
                'max_amount'  => 2000000,   // 2 millions
                'daily_count' => 50,
                'daily_sum'   => 5000000,   // 5 millions
                'scope'       => 'sandbox'
            ],
            [
                'currency'    => 'XOF',
                'max_amount'  => 5000000,
                'daily_count' => 200,
                'daily_sum'   => 20000000,
                'scope'       => 'live'
            ],
        ];

        foreach ($limits as $limit) {
            CurrencyLimit::create($limit);
        }
    }
}
