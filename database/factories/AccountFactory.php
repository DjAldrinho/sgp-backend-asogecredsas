<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'account_number' => $this->faker->unique()->creditCardNumber,
            'value' => $this->faker->randomFloat(2, 1000000, 20000000),
            'old_value' => $this->faker->randomFloat(2, 0, 20000000)
        ];
    }
}
