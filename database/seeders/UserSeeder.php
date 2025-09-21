<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'username' => 'caplix',
                'status' => 1, // Active by default
                'created_by' => '1',
                'updated_by' => '1',
            ]
        );

        // Investor user
        User::updateOrCreate(
            ['email' => 'investor@gmail.com'],
            [
                'name' => 'Test Investor',
                'password' => Hash::make('12345678'),
                'role' => 'investor',
                'username' => 'investor',
                'status' => 1,
                'created_by' => '1',
                'updated_by' => '1',
            ]
        );

        // Entrepreneur user
        User::updateOrCreate(
            ['email' => 'entrepreneur@gmail.com'],
            [
                'name' => 'Test Entrepreneur',
                'password' => Hash::make('12345678'),
                'role' => 'entrepreneur',
                'username' => 'entrepreneur',
                'status' => 1,
                'created_by' => '1',
                'updated_by' => '1',
            ]
        );
    }
}
