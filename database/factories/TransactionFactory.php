<?php

namespace Database\Factories;

use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'origin' => $this->faker->randomElement(['retire', 'credit', 'deposit']),
            'code' => $this->faker->unique()->randomFloat(0, 100000, 999999),
            'value' => $this->faker->randomFloat(2, 2000000, 9000000),
            'supplier_id' => random_int(1, 6),
            'account_id' => random_int(1, 6),
            'type_transaction_id' => random_int(1, 5),
            'commentary' => $this->faker->sentence,
            'user_id' => random_int(1, 8),
            'credit_id' => $this->faker->randomElement([null, random_int(1, 8)]),
        ];
    }
}
