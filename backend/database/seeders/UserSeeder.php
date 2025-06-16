<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->firstUser()
            ->create(['password' => Hash::make('password123')]);

        User::factory()
            ->secondUser()
            ->create(['password' => Hash::make('password123')]);

        User::factory()
            ->count(10)
            ->create();
    }
}
