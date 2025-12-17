<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MerchantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Merchant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->company,
            'registration_number' => $this->faker->unique()->numerify('##########'),
            'tax_id' => $this->faker->unique()->numerify('###########'),
            'address' => $this->faker->address,
            'country' => $this->faker->countryCode,
            'status' => 'active',
            'activated_at' => now(),
        ];
    }
}
