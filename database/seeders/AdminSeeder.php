<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('users')->upsert(
            [[
                'email' => 'admin@example.com',
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('AdminPass2024!'),
                'role' => 'admin',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]],
            ['email'],
            ['name', 'username', 'password', 'role', 'is_active', 'updated_at']
        );
    }
}
