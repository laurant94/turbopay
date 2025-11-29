<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'merchant_id' => 1,
            'name'        => 'John Doe',
            'email'       => 'john@example.com',
            'phone'       => '+22961000000',
        ]);
    }
}
