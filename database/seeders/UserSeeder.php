<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User admin tetap
        // User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@laravel.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'is_active' => true,
        // ]);

        // 1000 user dummy
        User::factory(1000)->create()->each(function ($user) {
            $user->update([
                'is_active' => rand(0, 1),
                'created_at' => now()->subDays(rand(1, 365)),
            ]);
        });
    }
}
