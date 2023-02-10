<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StandardValue>
 */
class StandardValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'efficiency' => $this->faker->text(10),
            'quality' => $this->faker->text(5),
            'timeliness' => $this->faker->text(10),
        ];
    }
}
