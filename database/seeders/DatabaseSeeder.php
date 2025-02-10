<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            EventSeeder::class
        ]);
    
        // Get the user role
        $userRole = Role::where('name', 'user')->first();
        
        // Create 10 regular users with user role
        User::factory(10)->unverified()->create([
            'role_id' => $userRole->id
        ]);
    
        // Get the admin role
        $adminRole = Role::where('name', 'admin')->first();
        
        // Create admin user with admin role
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.es',
            'role_id' => $adminRole->id
            
        ]);
    }
}
