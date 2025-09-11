<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create teams
        $defaultTeam = Team::create([
            'name' => 'Default Team',
            'slug' => 'default-team',
        ]);

        $acmeTeam = Team::create([
            'name' => 'Acme Corporation',
            'slug' => 'acme-corp',
        ]);

        // Create test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('123'),
        ]);

        // Create users for Acme Corporation (3 users)
        $acmeUsers = User::factory()->count(3)->create();

        // Attach Test User to both teams
        $testUser->teams()->attach([$defaultTeam->id, $acmeTeam->id]);

        // Attach Acme users to their team
        $acmeUsers->each(fn($user) => $user->teams()->attach($acmeTeam));

        $this->command->info('Users created successfully!');
        $this->command->info('Test User (in both teams): test@test.com - password: 123');
        $this->command->info('Acme Corporation - Test User + 3 users with fake data');
    }
}
