<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek dan buat admin user jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@laravel.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('supersecret'),
                'is_active' => true,
            ]
        );

        // Hanya jalankan di non-production
        if (!app()->isProduction()) {
            // Hapus semua user dummy kecuali admin
            User::where('email', '!=', 'admin@laravel.com')->delete();

            // Buat 1000 user dummy
            User::factory()->count(2000)->create();
        }
    }
}
