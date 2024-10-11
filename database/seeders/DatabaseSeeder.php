<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting; 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Setting::create([
            'app_name' => 'My App',
            'app_logo' => null,
        ]);

        // Uncomment to create 10 random users
        // User::factory(10)->create();

        // Create a specific user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
