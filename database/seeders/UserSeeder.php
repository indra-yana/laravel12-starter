<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Employee\Models\Employee;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cek dan buat admin user jika belum ada
        $admin = User::firstOrCreate(
            ['email' => 'admin@laravel.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('supersecret'),
                'is_active' => true,
            ]
        );

        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@laravel.com'],
            [
                'name' => 'Super Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('supersecret'),
                'is_active' => true,
            ]
        );

        // Buat employee untuk admin & superadmin jika belum ada
        Employee::firstOrCreate(['user_id' => $admin->id], [
            'nip' => fake()->unique()->numerify('1987##############'),
            'nik' => fake()->unique()->numerify('3278###########'),
        ]);

        Employee::firstOrCreate(['user_id' => $superadmin->id], [
            'nip' => fake()->unique()->numerify('1988#########'),
            'nik' => fake()->unique()->numerify('3278###########'),
        ]);

        // Hanya jalankan dummy di non-production
        if (!app()->isProduction()) {
            // Hapus semua user dummy kecuali admin dan superadmin
            User::whereNotIn('email', ['admin@laravel.com', 'superadmin@laravel.com'])->delete();
            Employee::whereNotIn('user_id', [$admin->id, $superadmin->id])->delete();

            // Buat 2000 user dummy dan employee terkait
            User::factory()->count(100)->create()->each(function ($user) {
                Employee::create([
                    'user_id' => $user->id,
                    'nip' => fake()->unique()->numerify('1989##############'),
                    'nik' => fake()->unique()->numerify('3278###########'),
                ]);
            });
        }
    }
}
