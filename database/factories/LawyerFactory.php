<?php

namespace Database\Factories;

use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Factories\Factory;

class LawyerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lawyer::class;

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
            'professional_card' => $this->faker->imageUrl(300, 200)
        ];
    }
}
