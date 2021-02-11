<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JsonException;

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
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'document_type' => $this->faker->randomElement(['cc', 'ce', 'tc', 'pp']),
            'document_number' => $this->faker->unique()->numberBetween(102345678, 196325874),
            'sign' => $this->faker->imageUrl(300, 200),
            'client_type' => $this->faker->randomElement([json_encode(['debtor']), json_encode(['debtor', 'co_debtor'])])
        ];
    }
}
