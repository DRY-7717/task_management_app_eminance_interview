<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        User::create(
            [
                'email' => 'administrator@gmail.com',
                'name' => "Administrator",
                'password' => Hash::make('password'),
                'role' => 'admin'
            ],
        );
        User::create(
            [
                'email' => 'developerone@gmail.com',
                'name' => "Developer One",
                'password' => Hash::make('password')
            ],
        );
        User::create(
            [
                'email' => 'developertwo@gmail.com',
                'name' => "Developer Two",
                'password' => Hash::make('password')
            ],
        );
    }
}
