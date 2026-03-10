<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {$start = fake()->dateTimeBetween('now', '+1 month');
        return [
           'name' => fake()->sentence(3) . ' Festival',
        'start_date' => $start,
        'end_date' => (clone $start)->modify('+3 days'),
        'is_active' => true,
        ];
    }
}
