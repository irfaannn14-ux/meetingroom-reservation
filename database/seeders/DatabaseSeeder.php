<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization; // Import model Organization
use App\Models\Ruangan;
use App\Models\Pengajuan;
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

        // Ambil Super Admin yang baru dibuat
        $superAdmin = User::where('username', 'superadmin')->first();
        $ruanganA = Ruangan::where('nama_ruangan', 'Ruang Rapat A')->first();
        $ruanganB = Ruangan::where('nama_ruangan', 'Ruang Kelas B')->first();

        // Pengajuan 1 - Status: disetujui (besok, pagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Rapat Koordinasi Tim',
            'kegiatan' => 'Rapat koordinasi bulanan untuk membahas progress proyek dan planning bulan depan',
            'tanggal_mulai' => now()->addDay()->setTime(9, 0, 0),
            'tanggal_selesai' => now()->addDay()->setTime(12, 0, 0),
            'jml_peserta' => 50,
            'status' => 'disetujui',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(2),
        ]);

        // Pengajuan 2 - Status: pending (3 hari lagi, siang)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganB->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Pelatihan Sistem Informasi',
            'kegiatan' => 'Pelatihan penggunaan sistem informasi baru untuk seluruh staff',
            'tanggal_mulai' => now()->addDays(3)->setTime(13, 0, 0),
            'tanggal_selesai' => now()->addDays(3)->setTime(16, 0, 0),
            'jml_peserta' => 100,
            'status' => 'pending',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        // Pengajuan 3 - Status: disetujui (minggu depan)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Sosialisasi Kebijakan Baru',
            'kegiatan' => 'Sosialisasi kebijakan baru kepada seluruh pegawai',
            'tanggal_mulai' => now()->addWeek()->setTime(8, 0, 0),
            'tanggal_selesai' => now()->addWeek()->setTime(11, 0, 0),
            'jml_peserta' => 150,
            'status' => 'disetujui',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(4),
        ]);

        // Pengajuan 4 - Status: ditolak (kapasitas melebihi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Seminar Nasional',
            'kegiatan' => 'Seminar nasional tentang perkembangan teknologi informasi',
            'tanggal_mulai' => now()->addDays(10)->setTime(9, 0, 0),
            'tanggal_selesai' => now()->addDays(10)->setTime(15, 0, 0),
            'jml_peserta' => 250, // Melebihi kapasitas Ruang Rapat A (200)
            'status' => 'ditolak',
            'created_at' => now()->subWeek(),
            'updated_at' => now()->subDays(6),
        ]);

        // Pengajuan 5 - Status: pending (2 minggu lagi, multi-day)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganB->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Workshop Pengembangan SDM',
            'kegiatan' => 'Workshop intensif 2 hari untuk pengembangan sumber daya manusia',
            'tanggal_mulai' => now()->addDays(14)->setTime(8, 0, 0),
            'tanggal_selesai' => now()->addDays(15)->setTime(16, 0, 0),
            'jml_peserta' => 80,
            'status' => 'pending',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        // Pengajuan 6 - Status: disetujui (besok sore)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganB->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Evaluasi Kinerja Triwulan',
            'kegiatan' => 'Meeting evaluasi kinerja triwulan dengan seluruh kepala seksi',
            'tanggal_mulai' => now()->addDay()->setTime(14, 0, 0),
            'tanggal_selesai' => now()->addDay()->setTime(17, 0, 0),
            'jml_peserta' => 120,
            'status' => 'disetujui',
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(3),
        ]);

        // Pengajuan 7 - Status: pending (5 hari lagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Rapat Pleno',
            'kegiatan' => 'Rapat pleno pembahasan APBD tahun anggaran mendatang',
            'tanggal_mulai' => now()->addDays(5)->setTime(10, 0, 0),
            'tanggal_selesai' => now()->addDays(5)->setTime(14, 0, 0),
            'jml_peserta' => 180,
            'status' => 'pending',
            'created_at' => now()->subHours(12),
            'updated_at' => now()->subHours(12),
        ]);

        // Pengajuan 8 - Status: disetujui (4 hari lagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Pembinaan Pegawai',
            'kegiatan' => 'Sesi pembinaan dan motivasi untuk pegawai baru',
            'tanggal_mulai' => now()->addDays(4)->setTime(9, 0, 0),
            'tanggal_selesai' => now()->addDays(4)->setTime(12, 0, 0),
            'jml_peserta' => 60,
            'status' => 'disetujui',
            'created_at' => now()->subDays(6),
            'updated_at' => now()->subDays(5),
        ]);

        // Pengajuan 9 - Status: ditolak (jadwal bentrok)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Diskusi Teknis',
            'kegiatan' => 'Diskusi teknis implementasi sistem baru',
            'tanggal_mulai' => now()->addDay()->setTime(10, 0, 0),
            'tanggal_selesai' => now()->addDay()->setTime(13, 0, 0),
            'jml_peserta' => 40,
            'status' => 'ditolak',
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(2),
        ]);

        // Pengajuan 10 - Status: pending (1 minggu lagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganB->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Pelatihan Aplikasi E-Office',
            'kegiatan' => 'Pelatihan penggunaan aplikasi e-office untuk administrasi perkantoran',
            'tanggal_mulai' => now()->addDays(7)->setTime(8, 30, 0),
            'tanggal_selesai' => now()->addDays(7)->setTime(15, 30, 0),
            'jml_peserta' => 200,
            'status' => 'pending',
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // Pengajuan 11 - Status: disetujui (10 hari lagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Rapat Koordinasi Lintas Sektor',
            'kegiatan' => 'Koordinasi program kerja lintas sektor untuk pencapaian target',
            'tanggal_mulai' => now()->addDays(10)->setTime(13, 0, 0),
            'tanggal_selesai' => now()->addDays(10)->setTime(16, 0, 0),
            'jml_peserta' => 90,
            'status' => 'disetujui',
            'created_at' => now()->subDays(8),
            'updated_at' => now()->subDays(7),
        ]);

        // Pengajuan 12 - Status: pending (6 hari lagi, pagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganB->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Launching Program Inovasi',
            'kegiatan' => 'Peluncuran program inovasi pelayanan publik',
            'tanggal_mulai' => now()->addDays(6)->setTime(9, 0, 0),
            'tanggal_selesai' => now()->addDays(6)->setTime(12, 0, 0),
            'jml_peserta' => 250,
            'status' => 'pending',
            'created_at' => now()->subHours(6),
            'updated_at' => now()->subHours(6),
        ]);

        // Pengajuan 13 - Status: disetujui (12 hari lagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Audit Internal',
            'kegiatan' => 'Kegiatan audit internal untuk evaluasi sistem dan prosedur',
            'tanggal_mulai' => now()->addDays(12)->setTime(8, 0, 0),
            'tanggal_selesai' => now()->addDays(12)->setTime(17, 0, 0),
            'jml_peserta' => 30,
            'status' => 'disetujui',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(9),
        ]);

        // Pengajuan 14 - Status: pending (8 hari lagi)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganB->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Focus Group Discussion',
            'kegiatan' => 'FGD untuk pengembangan kebijakan pelayanan publik',
            'tanggal_mulai' => now()->addDays(8)->setTime(10, 0, 0),
            'tanggal_selesai' => now()->addDays(8)->setTime(15, 0, 0),
            'jml_peserta' => 150,
            'status' => 'pending',
            'created_at' => now()->subHours(18),
            'updated_at' => now()->subHours(18),
        ]);

        // Pengajuan 15 - Status: disetujui (lusa)
        Pengajuan::create([
            'user_id' => $superAdmin->id,
            'ruangan_id' => $ruanganA->id,
            'nama_pengaju' => $superAdmin->nama,
            'judul_kegiatan' => 'Briefing Mingguan',
            'kegiatan' => 'Briefing rutin mingguan untuk koordinasi tim',
            'tanggal_mulai' => now()->addDays(2)->setTime(7, 30, 0),
            'tanggal_selesai' => now()->addDays(2)->setTime(9, 0, 0),
            'jml_peserta' => 75,
            'status' => 'disetujui',
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(3),
        ]);
    }
}
