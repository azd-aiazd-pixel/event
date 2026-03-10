<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            
          
            'nfc_code' => null, 
            
            'balance' => fake()->randomFloat(2, 0, 500),
           
        ];
    }
}