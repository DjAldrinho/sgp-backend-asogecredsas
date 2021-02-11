<?php

namespace Database\Factories;

use App\Models\CreditDetail;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CreditDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws Exception
     */
    public function definition(): array
    {
        $capital_value = $this->faker->randomFloat(0, 500000, 5000000);
        $capital_balance = $this->faker->randomFloat(0, 100000, 15000000);
        $value_fee = $this->faker->randomFloat(0, 100000, 950000);
        $value_interest = $this->faker->randomFloat(0, 50000, 250000);
        return [
            'code_fee' => random_int(1, 36),
            'capital_value' => $capital_value,
            'capital_balance' => $capital_balance,
            'value_fee' => $value_fee,
            'value_interest' => $value_interest,
            'expired_date' => $this->faker->date('Y/m/d', now()),
            'credit_id' => random_int(1, 10)
        ];
    }
}
