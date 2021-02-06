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
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'document_type' => $this->faker->randomElement(['cc', 'ce', 'tc', 'pp']),
            'document_number' => $this->faker->numberBetween(6, 12),
            'professional_card' => $this->faker->imageUrl(300, 200)
        ];
    }
}
