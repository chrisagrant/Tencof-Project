<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users with known credentials
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@tencof.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'kasir',
        ]);
    }
}
