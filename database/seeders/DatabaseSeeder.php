<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'user_id' => 1,
            'username' => 'admin',
            'password' => Hash::make('AdminPass2024!'),
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create regular user
        User::factory()->create([
            'user_id' => 2,
            'username' => 'user',
            'password' => Hash::make('UserPass2024!'),
            'email' => 'cust@example.com',
            'role' => 'customer',
        ]);
    }
}
