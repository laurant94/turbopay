<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\FeeSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\MerchantSeeder;
use Database\Seeders\TransactionSeeder;
use Database\Seeders\CurrencyLimitSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            CurrencySeeder::class,
            CurrencyLimitSeeder::class,
            MerchantSeeder::class,
            // FeeSeeder::class,
            // CustomerSeeder::class,      // optionnel
            // TransactionSeeder::class,   // optionnel
        ]);
    }
}
