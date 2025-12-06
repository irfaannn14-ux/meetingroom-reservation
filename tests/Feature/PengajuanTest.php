<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pengajuan;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PengajuanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup method - runs before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create dummy users for testing
        $this->createDummyUsers();
        
        // Create dummy ruangan for testing
        $this->createDummyRuangan();
    }

    /**
     * Create dummy users for all roles
     */
    private function createDummyUsers()
    {
        // Create regular user (OPD role)
        User::create([
            'nama' => 'Test User Regular',
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => null,
            'no_wa' => '081234567890',
            'role' => 'OPD',
            'foto_profil' => null,
        ]);

        // Create admin user
        User::create([
            'nama' => 'Test Admin',
            'username' => 'testadmin',
            'email' => 'testadmin@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => null,
            'no_wa' => '081234567891',
            'role' => 'Admin',
            'foto_profil' => null,
        ]);

        // Create superadmin user
        User::create([
            'nama' => 'Test Superadmin',
            'username' => 'testsuperadmin',
            'email' => 'testsuperadmin@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => null,
            'no_wa' => '081234567892',
            'role' => 'Super Admin',
            'foto_profil' => null,
        ]);
    }

    /**
     * Create dummy ruangan for testing
     */
    private function createDummyRuangan()
    {
        Ruangan::create([
            'nama_ruangan' => 'Ruangan Test Meeting',
            'jml_peserta' => 30,
            'fasilitas' => 'Proyektor, AC, Whiteboard',
            'foto_ruangan' => 'ruangan/test.jpg',
        ]);
    }

    /**
     * Helper method to create dummy pengajuan
     */
    private function createDummyPengajuan($attributes = [])
    {
        $user = User::where('role', 'OPD')->first();
        $ruangan = Ruangan::first();

        return Pengajuan::create(array_merge([
            'user_id' => $user->id,
            'ruangan_id' => $ruangan->id,
            'nama_pengaju' => $user->nama,
            'judul_kegiatan' => 'Meeting Test',
            'kegiatan' => 'Pembahasan project test',
            'tanggal_mulai' => now()->addDays(1)->setTime(9, 0),
            'tanggal_selesai' => now()->addDays(1)->setTime(11, 0),
            'jml_peserta' => 20,
            'status' => 'pending',
        ], $attributes));
    }

    /**
     * Helper method to login as specific role
     */
    private function actingAsRole($role)
    {
        $user = User::where('role', $role)->first();
        
        return $this->withSession([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'user_role' => $user->role,
        ]);
    }

    /**
     * Test 1: User dapat mengakses halaman tambah pengajuan
     * 
     * @test
     */
    public function test_user_dapat_mengakses_halaman_tambah_pengajuan()
    {
        // Act: Login as user and access create page
        $response = $this->actingAsRole('OPD')
            ->get(route('pengajuan.tambah'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('pengajuan.tambah');
        $response->assertViewHas('ruangans');
    }

    /**
     * Test 2: Admin dapat mengakses halaman tambah pengajuan
     * 
     * @test
     */
    public function test_admin_dapat_mengakses_halaman_tambah_pengajuan()
    {
        // Act: Login as admin and access create page
        $response = $this->actingAsRole('Admin')
            ->get(route('pengajuan.tambah'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('pengajuan.tambah');
    }

    /**
     * Test 3: User dapat membuat pengajuan dengan data valid
     * 
     * @test
     */
    public function test_user_dapat_membuat_pengajuan_dengan_data_valid()
    {
        $ruangan = Ruangan::first();

        // Act: Login as user and create pengajuan
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Rapat Koordinasi',
                'kegiatan' => 'Pembahasan strategi marketing',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 25,
                'tanggal_pinjam' => now()->addDays(2)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(2)->format('Y-m-d'),
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Check redirect and database
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dikirim!');
        
        $this->assertDatabaseHas('pengajuans', [
            'judul_kegiatan' => 'Rapat Koordinasi',
            'kegiatan' => 'Pembahasan strategi marketing',
            'ruangan_id' => $ruangan->id,
            'jml_peserta' => 25,
            'status' => 'pending',
        ]);
    }

    /**
     * Test 4: Validasi judul kegiatan wajib diisi
     * 
     * @test
     */
    public function test_validasi_judul_kegiatan_wajib_diisi()
    {
        $ruangan = Ruangan::first();

        // Act: Submit without judul_kegiatan
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => '',
                'kegiatan' => 'Test kegiatan',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(1)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'),
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['judul_kegiatan']);
    }

    /**
     * Test 5: Validasi kegiatan wajib diisi
     * 
     * @test
     */
    public function test_validasi_kegiatan_wajib_diisi()
    {
        $ruangan = Ruangan::first();

        // Act: Submit without kegiatan
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Test Judul',
                'kegiatan' => '',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(1)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'),
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['kegiatan']);
    }

    /**
     * Test 6: Validasi ruangan wajib dipilih
     * 
     * @test
     */
    public function test_validasi_ruangan_wajib_dipilih()
    {
        // Act: Submit without ruangan_id
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Test Judul',
                'kegiatan' => 'Test kegiatan',
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(1)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'),
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['ruangan_id']);
    }

    /**
     * Test 7: Validasi tanggal kembali harus >= tanggal pinjam
     * 
     * @test
     */
    public function test_validasi_tanggal_kembali_harus_setelah_atau_sama_dengan_tanggal_pinjam()
    {
        $ruangan = Ruangan::first();

        // Act: Submit with invalid date range
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Test Judul',
                'kegiatan' => 'Test kegiatan',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(2)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'), // Earlier date
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['tanggal_kembali']);
    }

    /**
     * Test 8: Validasi waktu kembali harus > waktu pinjam
     * 
     * @test
     */
    public function test_validasi_waktu_kembali_harus_setelah_waktu_pinjam()
    {
        $ruangan = Ruangan::first();

        // Act: Submit with invalid time range
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Test Judul',
                'kegiatan' => 'Test kegiatan',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(1)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'),
                'waktu_pinjam' => '11:00',
                'waktu_kembali' => '09:00', // Earlier time
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['waktu_kembali']);
    }

    /**
     * Test 9: User dapat melihat daftar pengajuan miliknya
     * 
     * @test
     */
    public function test_user_dapat_melihat_daftar_pengajuan_miliknya()
    {
        // Arrange: Create pengajuan for user
        $user = User::where('role', 'OPD')->first();
        $this->createDummyPengajuan(['user_id' => $user->id]);

        // Act: Login and access index
        $response = $this->actingAsRole('OPD')
            ->get(route('pengajuan.index'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('pengajuan.index');
        $response->assertViewHas('pengajuans');
    }

    /**
     * Test 10: Admin dapat melihat semua pengajuan
     * 
     * @test
     */
    public function test_admin_dapat_melihat_semua_pengajuan()
    {
        // Arrange: Create multiple pengajuans from different users
        $this->createDummyPengajuan();

        // Act: Login as admin and access index
        $response = $this->actingAsRole('Admin')
            ->get(route('pengajuan.index'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('pengajuan.index');
    }

    /**
     * Test 11: Superadmin dapat melihat semua pengajuan
     * 
     * @test
     */
    public function test_superadmin_dapat_melihat_semua_pengajuan()
    {
        // Arrange: Create pengajuan
        $this->createDummyPengajuan();

        // Act: Login as superadmin and access index
        $response = $this->actingAsRole('Super Admin')
            ->get(route('pengajuan.index'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('pengajuan.index');
    }

    /**
     * Test 12: User hanya melihat pengajuan miliknya sendiri
     * 
     * @test
     */
    public function test_user_hanya_melihat_pengajuan_miliknya_sendiri()
    {
        // Arrange: Create pengajuan for current user
        $currentUser = User::where('role', 'OPD')->first();
        $pengajuan1 = $this->createDummyPengajuan([
            'user_id' => $currentUser->id,
            'judul_kegiatan' => 'My Meeting',
        ]);

        // Create another user and their pengajuan
        $otherUser = User::create([
            'nama' => 'Other User',
            'username' => 'otheruser',
            'email' => 'other@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => null,
            'no_wa' => '081234567899',
            'role' => 'OPD',
            'foto_profil' => null,
        ]);
        
        $pengajuan2 = $this->createDummyPengajuan([
            'user_id' => $otherUser->id,
            'judul_kegiatan' => 'Other Meeting',
        ]);

        // Act: Login as current user
        $response = $this->actingAsRole('OPD')
            ->get(route('pengajuan.index'));

        // Assert: Should see only own pengajuan
        $response->assertSee('My Meeting');
        $response->assertDontSee('Other Meeting');
    }

    /**
     * Test 13: Index hanya menampilkan pengajuan dengan status pending
     * 
     * @test
     */
    public function test_index_hanya_menampilkan_pengajuan_status_pending()
    {
        // Arrange: Create pengajuans with different statuses
        $pending = $this->createDummyPengajuan([
            'judul_kegiatan' => 'Pending Meeting',
            'status' => 'pending',
        ]);
        
        $approved = $this->createDummyPengajuan([
            'judul_kegiatan' => 'Approved Meeting',
            'status' => 'disetujui',
        ]);

        // Act: Access index
        $response = $this->actingAsRole('Admin')
            ->get(route('pengajuan.index'));

        // Assert: Should see pending only
        $response->assertSee('Pending Meeting');
        $response->assertDontSee('Approved Meeting');
    }

    /**
     * Test 14: User dapat melihat history pengajuan
     * 
     * @test
     */
    public function test_user_dapat_melihat_history_pengajuan()
    {
        // Arrange: Create approved and rejected pengajuans
        $this->createDummyPengajuan([
            'judul_kegiatan' => 'Approved Meeting',
            'status' => 'disetujui',
        ]);
        
        $this->createDummyPengajuan([
            'judul_kegiatan' => 'Rejected Meeting',
            'status' => 'ditolak',
        ]);

        // Act: Access history
        $response = $this->actingAsRole('OPD')
            ->get(route('history'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('history');
        $response->assertSee('Approved Meeting');
        $response->assertSee('Rejected Meeting');
    }

    /**
     * Test 15: User dapat edit pengajuan dengan status pending
     * 
     * @test
     */
    public function test_user_dapat_edit_pengajuan_dengan_status_pending()
    {
        // Arrange: Create pending pengajuan
        $pengajuan = $this->createDummyPengajuan(['status' => 'pending']);

        // Act: Update pengajuan
        $ruangan = Ruangan::first();
        $response = $this->actingAsRole('OPD')
            ->put(route('pengajuan.update', $pengajuan), [
                'judul_kegiatan' => 'Updated Meeting Title',
                'kegiatan' => 'Updated description',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 25,
                'tanggal_pinjam' => now()->addDays(2)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(2)->format('Y-m-d'),
                'waktu_pinjam' => '10:00',
                'waktu_kembali' => '12:00',
            ]);

        // Assert: Check redirect and database
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil diperbarui!');
        
        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'judul_kegiatan' => 'Updated Meeting Title',
            'kegiatan' => 'Updated description',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Mengedit pengajuan Updated Meeting Title',
            'resource_type' => 'pengajuan',
        ]);
    }

    /**
     * Test 16: User dapat mengakses halaman edit pengajuan
     * 
     * @test
     */
    public function test_user_dapat_mengakses_halaman_edit_pengajuan()
    {
        // Arrange: Create pengajuan
        $pengajuan = $this->createDummyPengajuan();

        // Act: Access edit page
        $response = $this->actingAsRole('OPD')
            ->get(route('pengajuan.edit', $pengajuan));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('pengajuan.tambah');
        $response->assertViewHas('pengajuan');
        $response->assertViewHas('ruangans');
    }

    /**
     * Test 17: User dapat menghapus pengajuan
     * 
     * @test
     */
    public function test_user_dapat_menghapus_pengajuan()
    {
        // Arrange: Create pengajuan
        $pengajuan = $this->createDummyPengajuan();

        // Act: Delete pengajuan
        $response = $this->actingAsRole('OPD')
            ->delete(route('pengajuan.destroy', $pengajuan));

        // Assert: Check redirect and database
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dihapus!');
        
        $this->assertDatabaseMissing('pengajuans', [
            'id' => $pengajuan->id,
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Menghapus pengajuan ' . $pengajuan->judul_kegiatan,
            'resource_type' => 'pengajuan',
        ]);
    }

    /**
     * Test 18: Admin dapat menghapus pengajuan
     * 
     * @test
     */
    public function test_admin_dapat_menghapus_pengajuan()
    {
        // Arrange: Create pengajuan
        $pengajuan = $this->createDummyPengajuan();

        // Act: Login as admin and delete
        $response = $this->actingAsRole('Admin')
            ->delete(route('pengajuan.destroy', $pengajuan));

        // Assert: Check redirect and database
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil dihapus!');
        
        $this->assertDatabaseMissing('pengajuans', [
            'id' => $pengajuan->id,
        ]);
    }

    /**
     * Test 19: Admin dapat approve pengajuan
     * 
     * @test
     */
    public function test_admin_dapat_approve_pengajuan()
    {
        // Arrange: Create pending pengajuan
        $pengajuan = $this->createDummyPengajuan(['status' => 'pending']);

        // Act: Approve pengajuan
        $response = $this->actingAsRole('Admin')
            ->post(route('pengajuan.updateStatus', $pengajuan), [
                'status' => 'disetujui',
            ]);

        // Assert: Check redirect and status change
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil disetujui!');
        
        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'status' => 'disetujui',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Menyetujui pengajuan ' . $pengajuan->judul_kegiatan,
            'resource_type' => 'pengajuan',
        ]);
    }

    /**
     * Test 20: Admin dapat reject pengajuan dengan alasan
     * 
     * @test
     */
    public function test_admin_dapat_reject_pengajuan()
    {
        // Arrange: Create pending pengajuan
        $pengajuan = $this->createDummyPengajuan(['status' => 'pending']);

        // Act: Reject pengajuan
        $response = $this->actingAsRole('Admin')
            ->post(route('pengajuan.updateStatus', $pengajuan), [
                'status' => 'ditolak',
            ]);

        // Assert: Check redirect and status change
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil ditolak!');
        
        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'status' => 'ditolak',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Menolak pengajuan ' . $pengajuan->judul_kegiatan,
            'resource_type' => 'pengajuan',
        ]);
    }

    /**
     * Test 21: Superadmin dapat approve pengajuan
     * 
     * @test
     */
    public function test_superadmin_dapat_approve_pengajuan()
    {
        // Arrange: Create pending pengajuan
        $pengajuan = $this->createDummyPengajuan(['status' => 'pending']);

        // Act: Approve as superadmin
        $response = $this->actingAsRole('Super Admin')
            ->post(route('pengajuan.updateStatus', $pengajuan), [
                'status' => 'disetujui',
            ]);

        // Assert: Check status change
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success', 'Pengajuan berhasil disetujui!');
        
        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'status' => 'disetujui',
        ]);
    }

    /**
     * Test 22: Tidak bisa approve jika ruangan sudah dipesan di jadwal yang sama
     * 
     * @test
     */
    public function test_tidak_bisa_approve_jika_jadwal_bentrok()
    {
        $ruangan = Ruangan::first();
        
        // Arrange: Create approved pengajuan
        $existingPengajuan = $this->createDummyPengajuan([
            'ruangan_id' => $ruangan->id,
            'tanggal_mulai' => now()->addDays(3)->setTime(9, 0),
            'tanggal_selesai' => now()->addDays(3)->setTime(11, 0),
            'status' => 'disetujui',
        ]);

        // Create new pending pengajuan with overlapping time
        $newPengajuan = $this->createDummyPengajuan([
            'ruangan_id' => $ruangan->id,
            'tanggal_mulai' => now()->addDays(3)->setTime(10, 0),
            'tanggal_selesai' => now()->addDays(3)->setTime(12, 0),
            'status' => 'pending',
        ]);

        // Act: Try to approve
        $response = $this->actingAsRole('Admin')
            ->post(route('pengajuan.updateStatus', $newPengajuan), [
                'status' => 'disetujui',
            ]);

        // Assert: Should get error
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('error');
        
        // Status should remain pending
        $this->assertDatabaseHas('pengajuans', [
            'id' => $newPengajuan->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test 23: Maksimal 3 peminjaman per hari untuk ruangan yang sama
     * 
     * @test
     */
    public function test_maksimal_3_peminjaman_per_hari_untuk_ruangan_yang_sama()
    {
        $ruangan = Ruangan::first();
        $targetDate = now()->addDays(5);

        // Arrange: Create 3 approved pengajuans on same day, different times
        for ($i = 0; $i < 3; $i++) {
            $this->createDummyPengajuan([
                'ruangan_id' => $ruangan->id,
                'tanggal_mulai' => $targetDate->copy()->setTime(8 + ($i * 3), 0),
                'tanggal_selesai' => $targetDate->copy()->setTime(10 + ($i * 3), 0),
                'status' => 'disetujui',
            ]);
        }

        // Create 4th pengajuan (pending)
        $fourthPengajuan = $this->createDummyPengajuan([
            'ruangan_id' => $ruangan->id,
            'tanggal_mulai' => $targetDate->copy()->setTime(18, 0),
            'tanggal_selesai' => $targetDate->copy()->setTime(20, 0),
            'status' => 'pending',
        ]);

        // Act: Try to approve 4th pengajuan
        $response = $this->actingAsRole('Admin')
            ->post(route('pengajuan.updateStatus', $fourthPengajuan), [
                'status' => 'disetujui',
            ]);

        // Assert: Should get error about daily limit
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('error');
        
        // Status should remain pending
        $this->assertDatabaseHas('pengajuans', [
            'id' => $fourthPengajuan->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test 24: Kalender menampilkan booking yang sudah approved
     * 
     * @test
     */
    public function test_kalender_menampilkan_booking_yang_sudah_approved()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan([
            'judul_kegiatan' => 'Important Meeting',
            'status' => 'disetujui',
        ]);

        // Act: Get calendar events
        $response = $this->actingAsRole('Admin')
            ->get(route('calendar.events'));

        // Assert: Check JSON response
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Important Meeting (Ruangan Test Meeting)',
        ]);
    }

    /**
     * Test 25: Kalender menampilkan booking pending dengan warna berbeda
     * 
     * @test
     */
    public function test_kalender_menampilkan_booking_pending_dengan_warna_berbeda()
    {
        // Arrange: Create pending and approved pengajuan
        $pendingPengajuan = $this->createDummyPengajuan([
            'judul_kegiatan' => 'Pending Meeting',
            'status' => 'pending',
        ]);

        $approvedPengajuan = $this->createDummyPengajuan([
            'judul_kegiatan' => 'Approved Meeting',
            'status' => 'disetujui',
        ]);

        // Act: Get calendar events
        $response = $this->actingAsRole('Admin')
            ->get(route('calendar.events'));

        // Assert: Should include both pending and approved
        $response->assertStatus(200);
        $json = $response->json();
        
        // Check that pending meeting is in events with yellow color
        $pendingEvent = collect($json)->first(function ($event) {
            return str_contains($event['title'], 'Pending Meeting');
        });
        
        $this->assertNotNull($pendingEvent, 'Pending event should be in calendar');
        $this->assertEquals('rgba(255, 193, 7, 0.2)', $pendingEvent['backgroundColor'], 'Pending should have yellow background');
        $this->assertEquals('#ffc107', $pendingEvent['borderColor'], 'Pending should have yellow border');
        
        // Check that approved meeting is in events with green color
        $approvedEvent = collect($json)->first(function ($event) {
            return str_contains($event['title'], 'Approved Meeting');
        });
        
        $this->assertNotNull($approvedEvent, 'Approved event should be in calendar');
        $this->assertEquals('rgba(40, 167, 69, 0.2)', $approvedEvent['backgroundColor'], 'Approved should have green background');
        $this->assertEquals('#28a745', $approvedEvent['borderColor'], 'Approved should have green border');
    }

    /**
     * Test 26: Tidak bisa booking ruangan yang sudah dibooking (bentrok jadwal)
     * 
     * @test
     */
    public function test_tidak_bisa_booking_ruangan_yang_bentrok_jadwal()
    {
        $ruangan = Ruangan::first();

        // Arrange: Create approved booking
        $existing = $this->createDummyPengajuan([
            'ruangan_id' => $ruangan->id,
            'tanggal_mulai' => now()->addDays(2)->setTime(9, 0),
            'tanggal_selesai' => now()->addDays(2)->setTime(11, 0),
            'status' => 'disetujui',
        ]);

        // Act: Try to create overlapping booking
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Conflicting Meeting',
                'kegiatan' => 'This should fail',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(2)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(2)->format('Y-m-d'),
                'waktu_pinjam' => '10:00', // Overlaps with 09:00-11:00
                'waktu_kembali' => '12:00',
            ]);

        // Assert: Should get error
        $response->assertSessionHasErrors(['ruangan_id']);
    }

    /**
     * Test 27: Kapasitas peserta tidak boleh melebihi kapasitas ruangan
     * 
     * @test
     */
    public function test_kapasitas_peserta_tidak_boleh_melebihi_kapasitas_ruangan()
    {
        $ruangan = Ruangan::first(); // Has capacity of 30

        // Act: Try to create booking with too many participants
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Large Meeting',
                'kegiatan' => 'Too many people',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 50, // Exceeds capacity of 30
                'tanggal_pinjam' => now()->addDays(1)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'),
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Should get error
        $response->assertSessionHasErrors(['jml_peserta']);
    }

    /**
     * Test 28: Multi-day booking berfungsi dengan benar
     * 
     * @test
     */
    public function test_multi_day_booking_berfungsi_dengan_benar()
    {
        $ruangan = Ruangan::first();

        // Act: Create multi-day booking
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Multi-day Conference',
                'kegiatan' => '3-day training session',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 25,
                'tanggal_pinjam' => now()->addDays(5)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(7)->format('Y-m-d'), // 3 days
                'waktu_pinjam' => '08:00',
                'waktu_kembali' => '17:00',
            ]);

        // Assert: Should be created successfully
        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('pengajuans', [
            'judul_kegiatan' => 'Multi-day Conference',
        ]);
    }

    /**
     * Test 29: Status pengajuan default adalah pending
     * 
     * @test
     */
    public function test_status_pengajuan_default_adalah_pending()
    {
        $ruangan = Ruangan::first();

        // Act: Create pengajuan
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'New Meeting',
                'kegiatan' => 'Test',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(1)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'),
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Status should be pending
        $this->assertDatabaseHas('pengajuans', [
            'judul_kegiatan' => 'New Meeting',
            'status' => 'pending',
        ]);
    }

    /**
     * Test 30: Relasi pengajuan dengan user berfungsi
     * 
     * @test
     */
    public function test_relasi_pengajuan_dengan_user_berfungsi()
    {
        // Arrange: Create pengajuan
        $user = User::where('role', 'OPD')->first();
        $pengajuan = $this->createDummyPengajuan(['user_id' => $user->id]);

        // Act: Access relationship
        $pengajuanUser = $pengajuan->user;

        // Assert: Check relationship
        $this->assertNotNull($pengajuanUser);
        $this->assertEquals($user->id, $pengajuanUser->id);
        $this->assertEquals($user->nama, $pengajuanUser->nama);
    }

    /**
     * Test 31: Relasi pengajuan dengan ruangan berfungsi
     * 
     * @test
     */
    public function test_relasi_pengajuan_dengan_ruangan_berfungsi()
    {
        // Arrange: Create pengajuan
        $ruangan = Ruangan::first();
        $pengajuan = $this->createDummyPengajuan(['ruangan_id' => $ruangan->id]);

        // Act: Access relationship
        $pengajuanRuangan = $pengajuan->ruangan;

        // Assert: Check relationship
        $this->assertNotNull($pengajuanRuangan);
        $this->assertEquals($ruangan->id, $pengajuanRuangan->id);
        $this->assertEquals($ruangan->nama_ruangan, $pengajuanRuangan->nama_ruangan);
    }

    /**
     * Test 32: Dashboard menampilkan statistik dengan benar
     * 
     * @test
     */
    public function test_dashboard_menampilkan_statistik_dengan_benar()
    {
        // Arrange: Create pengajuans with different statuses
        $this->createDummyPengajuan(['status' => 'pending']);
        $this->createDummyPengajuan(['status' => 'disetujui']);
        $this->createDummyPengajuan(['status' => 'ditolak']);

        // Act: Access dashboard
        $response = $this->actingAsRole('Admin')
            ->get(route('dashboard'));

        // Assert: Check stats
        $response->assertStatus(200);
        $response->assertViewHas('stats');
        
        $stats = $response->viewData('stats');
        $this->assertEquals(3, $stats['total']);
        $this->assertEquals(1, $stats['baru']); // pending
        $this->assertEquals(1, $stats['diterima']); // disetujui
        $this->assertEquals(1, $stats['ditolak']); // ditolak
    }

    /**
     * Test 33: User OPD hanya melihat statistik pengajuan miliknya
     * 
     * @test
     */
    public function test_user_opd_hanya_melihat_statistik_pengajuan_miliknya()
    {
        // Arrange: Create pengajuans for current user
        $currentUser = User::where('role', 'OPD')->first();
        $this->createDummyPengajuan(['user_id' => $currentUser->id, 'status' => 'pending']);
        $this->createDummyPengajuan(['user_id' => $currentUser->id, 'status' => 'disetujui']);

        // Create pengajuan for another user
        $otherUser = User::create([
            'nama' => 'Other User 2',
            'username' => 'otheruser2',
            'email' => 'other2@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => null,
            'no_wa' => '081234567898',
            'role' => 'OPD',
            'foto_profil' => null,
        ]);
        $this->createDummyPengajuan(['user_id' => $otherUser->id, 'status' => 'pending']);

        // Act: Access dashboard as current user
        $response = $this->actingAsRole('OPD')
            ->get(route('dashboard'));

        // Assert: Should only see own stats
        $stats = $response->viewData('stats');
        $this->assertEquals(2, $stats['total']); // Only own pengajuans
        $this->assertEquals(1, $stats['baru']);
        $this->assertEquals(1, $stats['diterima']);
    }

    /**
     * Test 34: Validasi jumlah peserta minimal 1
     * 
     * @test
     */
    public function test_validasi_jumlah_peserta_minimal_1()
    {
        $ruangan = Ruangan::first();

        // Act: Try with 0 participants
        $response = $this->actingAsRole('OPD')
            ->post(route('pengajuan.store'), [
                'judul_kegiatan' => 'Test',
                'kegiatan' => 'Test',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 0,
                'tanggal_pinjam' => now()->addDays(1)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(1)->format('Y-m-d'),
                'waktu_pinjam' => '09:00',
                'waktu_kembali' => '11:00',
            ]);

        // Assert: Should get validation error
        $response->assertSessionHasErrors(['jml_peserta']);
    }

    /**
     * Test 35: Pengajuan dapat diedit saat status pending
     * 
     * @test
     */
    public function test_pengajuan_dapat_diedit_saat_status_pending()
    {
        // Arrange: Create pending pengajuan
        $pengajuan = $this->createDummyPengajuan(['status' => 'pending']);

        // Act: Edit pengajuan
        $ruangan = Ruangan::first();
        $response = $this->actingAsRole('OPD')
            ->put(route('pengajuan.update', $pengajuan), [
                'judul_kegiatan' => 'Edited Title',
                'kegiatan' => 'Edited description',
                'ruangan_id' => $ruangan->id,
                'jml_peserta' => 20,
                'tanggal_pinjam' => now()->addDays(3)->format('Y-m-d'),
                'tanggal_kembali' => now()->addDays(3)->format('Y-m-d'),
                'waktu_pinjam' => '14:00',
                'waktu_kembali' => '16:00',
            ]);

        // Assert: Should be updated
        $response->assertRedirect(route('pengajuan.index'));
        
        $this->assertDatabaseHas('pengajuans', [
            'id' => $pengajuan->id,
            'judul_kegiatan' => 'Edited Title',
        ]);
    }
}
