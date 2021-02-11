<?php

namespace Database\Factories;

use App\Models\Credit;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Credit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        $capital = $this->faker->randomFloat(0, 800000, 10000000);
        return [
            'code' => $this->faker->unique()->randomFloat(0, 100000, 999999),
            'payroll_id' => random_int(1, 5),
            'credit_type_id' => random_int(1, 8),
            'debtor_id' => random_int(1, 20),
            'first_co_debtor' => random_int(1, 20),
            'second_co_debtor' => random_int(1, 20),
            'start_date' => $this->faker->date('Y/m/d', now()),
            'capital_value' => $capital,
            'transport_value' => $this->faker->randomFloat(0, 5000, 50000),
            'other_value' => $this->faker->randomFloat(0, 0, 100000),
            'interest' => $this->faker->randomFloat(2, 3, 10),
            'commission' => $this->faker->randomFloat(2, 2, 12),
            'fee' => random_int(1, 36),
            'adviser_id' => $this->faker->randomElement([null, random_int(1, 10)])
        ];
    }
}
