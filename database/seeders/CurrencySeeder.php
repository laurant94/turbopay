<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'XOF', 'name' => 'F CFA', 'decimals' => 0, 'symbol' => 'F'],
            ['code' => 'USD', 'name' => 'US Dollar', 'decimals' => 2, 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'decimals' => 2, 'symbol' => '€'],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
