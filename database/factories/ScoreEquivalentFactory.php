<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScoreEquivalent>
 */
class ScoreEquivalentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'out_from' => 5,
            'out_to' => 5,
            'verysat_from' => 4,
            'verysat_to' => 4.99,
            'sat_from' => 3,
            'sat_to' => 3.99,
            'unsat_from' => 2,
            'unsat_to' => 2.99,
            'poor_from' => 1,
            'poor_to' => 1.99,
        ];
    }
}
