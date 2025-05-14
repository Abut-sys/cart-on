<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => "admin",
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'User',
            'email' => 'syafaatfebriansyah2@gmail.com',
            'password' => Hash::make('password'),
            'role' => "user",
            'email_verified_at' => now(),
        ]);
    }
}
