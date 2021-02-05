<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'document_type' => $this->faker->randomElement(['cc', 'ce', 'tc', 'pp']),
            'document_number' => $this->faker->numberBetween(6, 12),
            'sign_url' => $this->faker->imageUrl(300, 200),
            'client_type' => $this->faker->randomElement([json_encode(['debtor']), json_encode(['debtor', 'co_debtor'])])
        ];
    }
}
