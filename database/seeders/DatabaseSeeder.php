<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use App\Models\UnitMeasure;
use App\Enum\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
       $units = [
       
            ['name' => 'u', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'g', 'created_at' => now(), 'updated_at' => now()],   
            ['name' => 'kg', 'created_at' => now(), 'updated_at' => now()],  
            ['name' => 'L', 'created_at' => now(), 'updated_at' => now()],   
            ['name' => 'ml', 'created_at' => now(), 'updated_at' => now()],  
           
        ];

        DB::table('unit_measures')->insertOrIgnore($units);
/*
        $event = Event::factory()->create(['name' => 'Mawazine 2026']);

      
        User::factory(15)->create(['role' => Role::Participant])
            ->each(function ($user) use ($event) {
                Participant::factory()->create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'nfc_code' => fake()->unique()->bothify('04:##:##:##:##:##:##'),
                    'balance' => rand(100, 500), 
                ]);
            });

        
        User::factory(5)->create(['role' => Role::Store])
            ->each(function ($user) use ($event) {
                
    
                Store::factory()->create([
                    'user_id' => $user->id,
                    'event_id' => $event->id, // <--- Le lien direct
                    'name' => fake()->company() . ' Shop',
                    'status' => 'active',
                ]);
                
              
            });
     */
        // 4. Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'a@gmail.ma',
            'password' => bcrypt('123456789'),
            'role' => Role::Admin,
        ]);
    }
}
