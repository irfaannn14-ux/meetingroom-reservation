<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization; // Import model Organization
use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Tambahkan Super Admin
        User::create([
            'nama' => 'Super Admin',
            'email' => 'superadmin@example.com', // Email dummy
            'username' => 'superadmin', // Username dummy
            'no_wa' => '081234567890', // Nomor WhatsApp dummy
            'role' => 'Super Admin', // Sesuaikan dengan nilai di form
            'organization_id' => '2', // Kaitkan dengan organization SUPER ADMIN
            'password' => Hash::make('admin'), // Password 5 karakter
        ]);

        Organization::create([
            'organization_id' => 'ORG001',
            'organization_name' => 'ADMIN',
            'bkd_organization_id' => '1',
            'active' => 'true',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Organization::create([
            'organization_id' => 'ORG002',
            'organization_name' => 'SUPER ADMIN',
            'bkd_organization_id' => '2',
            'active' => 'true',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Ruangan::create([
            'nama_ruangan' => 'Ruang Rapat A',
            'fasilitas' => 'Proyektor, AC, WiFi',
            'jml_peserta' => 200,
            'foto_ruangan' => 'ruangan/Ruangan-A.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Ruangan::create([
            'nama_ruangan' => 'Ruang Kelas B',
            'fasilitas' => 'Papan Tulis, AC',
            'jml_peserta' => 300,
            'foto_ruangan' => 'ruangan/Ruangan-B.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
