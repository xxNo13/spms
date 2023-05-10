<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrintImage>
 */
class PrintImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'header_link' => $this->faker->text(10),
            'footer_link' => $this->faker->text(10),
            'form_link' => $this->faker->text(10),
        ];
    }
}
