<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaction::create([
            'merchant_id' => 1,
            'customer_id' => 1,
            'amount'      => 15000,
            'currency'    => 'XOF',
            'fees'        => 300,
            'net_amount'  => 14700,
            'status'      => 'pending',
            'description' => 'Test payment',
            'return_url'  => 'https://example.com/return',
            'cancel_url'  => 'https://example.com/cancel',
        ]);
    }
}
