<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
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

        // Create test2 user
        $test2User = User::factory()->create([
            'name' => 'Test 2 User',
            'email' => 'test2@test.com',
            'password' => bcrypt('123'),
        ]);

        // Create users for Acme Corporation (3 users)
        $acmeUsers = User::factory()->count(3)->create();

        // Attach Test User to both teams
        $testUser->teams()->attach([$defaultTeam->id, $acmeTeam->id]);

        // Attach Test2 User to Default Team only
        $test2User->teams()->attach($defaultTeam->id);

        // Attach Acme users to their team
        $acmeUsers->each(fn($user) => $user->teams()->attach($acmeTeam));

        // Create roles for each team
        setPermissionsTeamId($defaultTeam->id);

        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'team_id' => $defaultTeam->id,
        ]);
        $defaultAdminRole = Role::create([
            'name' => 'Admin',
            'team_id' => $defaultTeam->id,
        ]);
        $defaultUserRole = Role::create([
            'name' => 'User',
            'team_id' => $defaultTeam->id,
        ]);

        setPermissionsTeamId($acmeTeam->id);
        $acmeSuperAdminRole = Role::create([
            'name' => 'Super Admin',
            'team_id' => $acmeTeam->id,
        ]);
        $acmeAdminRole = Role::create([
            'name' => 'Admin',
            'team_id' => $acmeTeam->id,
        ]);
        $acmeManagerRole = Role::create([
            'name' => 'Manager',
            'team_id' => $acmeTeam->id,
        ]);

        // Assign roles to users
        setPermissionsTeamId($defaultTeam->id);
        $testUser->assignRole($superAdminRole);
        $test2User->assignRole($defaultAdminRole);

        setPermissionsTeamId($acmeTeam->id);
        $testUser->assignRole($acmeSuperAdminRole);
        $acmeUsers->first()->assignRole($acmeAdminRole);
        $acmeUsers->skip(1)->each(fn($user) => $user->assignRole($acmeManagerRole));

        // Sync permissions first (since they're global with scope_premissions_to_tenant = false)
        $this->command->info('Syncing permissions...');
        \Artisan::call('permissions:sync');
        
        // Get all permissions
        $webPermissions = Permission::where('guard_name', 'web')->get();
        
        setPermissionsTeamId($defaultTeam->id);
        $superAdminRole->givePermissionTo($webPermissions);
        
        setPermissionsTeamId($acmeTeam->id);
        $acmeSuperAdminRole->givePermissionTo($webPermissions);

        // Give specific permissions to Admin roles
        $userPermissions = Permission::where('guard_name', 'web')
            ->where('name', 'like', '%User%')
            ->get();

        setPermissionsTeamId($defaultTeam->id);
        $defaultAdminRole->givePermissionTo($userPermissions);

        setPermissionsTeamId($acmeTeam->id);
        $acmeAdminRole->givePermissionTo($userPermissions);

        $this->command->info('Users created successfully!');
        $this->command->info('Test User (in both teams): test@test.com - password: 123');
        $this->command->info('- Default Team: Super Admin role');
        $this->command->info('- Acme Team: Super Admin role');
        $this->command->info('Test 2 User (Default Team only): test2@test.com - password: 123');
        $this->command->info('- Default Team: Admin role (can manage users)');
        $this->command->info('Acme Corporation - 1 Admin (can manage users) + 2 Managers + Test User');
    }
}
