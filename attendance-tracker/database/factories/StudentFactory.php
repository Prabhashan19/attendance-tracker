<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'registration_number' => 'STD' . $this->faker->unique()->numberBetween(100000, 999999),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail, // Note the unique() here
        ];
    }
}
