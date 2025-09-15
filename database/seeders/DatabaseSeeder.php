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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'nama' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Tambahkan Super Admin
        User::create([
            'nama' => 'Super Admin',
            'email' => 'superadmin@example.com', // Email dummy
            'username' => 'superadmin', // Username dummy
            'no_wa' => '081234567890', // Nomor WhatsApp dummy
            'role' => 'superadmin', // Tambahkan role jika ada
            'password' => Hash::make('admin'), // Password 5 karakter
        ]);
    }
}
