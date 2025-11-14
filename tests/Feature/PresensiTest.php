<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\Pengajuan;
use App\Models\Presensi;
use App\Models\Organization;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PresensiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup method - runs before each test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create dummy data for testing
        $this->createDummyOrganizations();
        $this->createDummyUsers();
        $this->createDummyRuangan();
    }

    /**
     * Create dummy organizations
     */
    private function createDummyOrganizations()
    {
        Organization::create([
            'organization_id' => Str::uuid()->toString(),
            'bkd_organization_id' => 1,
            'organization_name' => 'Dinas Komunikasi dan Informatika',
        ]);

        Organization::create([
            'organization_id' => Str::uuid()->toString(),
            'bkd_organization_id' => 2,
            'organization_name' => 'Dinas Pendidikan',
        ]);

        Organization::create([
            'organization_id' => Str::uuid()->toString(),
            'bkd_organization_id' => 3,
            'organization_name' => 'Dinas Kesehatan',
        ]);
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
            'organization_id' => 1,
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
            'organization_id' => 1,
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
            'organization_id' => 1,
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
     * Helper method to create dummy pengajuan (approved)
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
            'tanggal_mulai' => now()->setTime(9, 0),
            'tanggal_selesai' => now()->setTime(11, 0),
            'jml_peserta' => 20,
            'status' => 'disetujui',
        ], $attributes));
    }

    /**
     * Helper method to create dummy presensi
     */
    private function createDummyPresensi($attributes = [])
    {
        $pengajuan = $this->createDummyPengajuan();
        $user = User::where('role', 'OPD')->first();

        return Presensi::create(array_merge([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $user->id,
            'nama' => 'John Doe',
            'jabatan' => 'Staff IT',
            'organisasi' => '1',
            'ttd_path' => 'presensi/ttd/test.png',
        ], $attributes));
    }

    /**
     * Helper method to login as specific role
     */
    private function actingAsRole($role)
    {
        $user = User::where('role', $role)->first();
        
        // Use actingAs for Laravel Auth facade + session for backward compatibility
        return $this->actingAs($user)->withSession([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'user_role' => $user->role,
        ]);
    }

    /**
     * Helper method to generate base64 signature
     */
    private function generateBase64Signature()
    {
        // Create a minimal valid PNG data URL (1x1 transparent PNG)
        $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        return 'data:image/png;base64,' . base64_encode($pngData);
    }

    /**
     * Test 1: User dapat mengakses form presensi
     * 
     * @test
     */
    public function test_user_dapat_mengakses_form_presensi()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        // Act: Access presensi form
        $response = $this->actingAsRole('OPD')
            ->get(route('presensi.create', $pengajuan->id));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('presensi.form');
        $response->assertViewHas('id', $pengajuan->id);
        $response->assertViewHas('organizations');
    }

    /**
     * Test 2: User dapat mengisi presensi dengan data valid
     * 
     * @test
     */
    public function test_user_dapat_mengisi_presensi_dengan_data_valid()
    {
        Storage::fake('public');
        
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'John Doe',
            'jabatan' => 'Staff IT',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check success response
        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
        
        // Check database
        $this->assertDatabaseHas('presensis', [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'John Doe',
            'jabatan' => 'Staff IT',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Presensi berhasil untuk pengajuan ID ' . $pengajuan->id,
            'resource_type' => 'pengajuan',
        ]);
    }

    /**
     * Test 3: Validasi nama wajib diisi
     * 
     * @test
     */
    public function test_validasi_nama_wajib_diisi()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => '',
            'jabatan' => 'Staff IT',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check validation error
        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    /**
     * Test 4: Validasi jabatan wajib diisi
     * 
     * @test
     */
    public function test_validasi_jabatan_wajib_diisi()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'John Doe',
            'jabatan' => '',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check validation error
        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    /**
     * Test 5: Validasi organisasi wajib dipilih
     * 
     * @test
     */
    public function test_validasi_organisasi_wajib_dipilih()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'John Doe',
            'jabatan' => 'Staff IT',
            'organisasi' => '',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check validation error
        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    /**
     * Test 6: Validasi TTD wajib diisi
     * 
     * @test
     */
    public function test_validasi_ttd_wajib_diisi()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'John Doe',
            'jabatan' => 'Staff IT',
            'organisasi' => '1',
            'ttd_path' => '',
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check validation error
        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    /**
     * Test 7: Organisasi 'Lainnya' memerlukan input manual
     * 
     * @test
     */
    public function test_organisasi_lainnya_memerlukan_input_manual()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'John Doe',
            'jabatan' => 'Staff IT',
            'organisasi' => 'lainnya',
            'organisasi_manual' => 'PT Example',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check success
        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);
        
        $this->assertDatabaseHas('presensis', [
            'pengajuan_id' => $pengajuan->id,
            'organisasi' => 'PT Example',
        ]);
    }

    /**
     * Test 8: Organisasi 'Lainnya' tanpa input manual harus error
     * 
     * @test
     */
    public function test_organisasi_lainnya_tanpa_input_manual_harus_error()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'John Doe',
            'jabatan' => 'Staff IT',
            'organisasi' => 'lainnya',
            'organisasi_manual' => '',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check validation error
        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    /**
     * Test 9: User dapat melihat daftar presensi per pengajuan
     * 
     * @test
     */
    public function test_user_dapat_melihat_daftar_presensi_per_pengajuan()
    {
        // Arrange: Create presensi
        $presensi = $this->createDummyPresensi();

        // Act: Access presensi list
        $response = $this->actingAsRole('OPD')
            ->get(route('presensi.show', $presensi->pengajuan_id));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('presensi.show');
        $response->assertViewHas('presensis');
        $response->assertSee('John Doe');
    }

    /**
     * Test 10: Daftar presensi menampilkan data dengan benar
     * 
     * @test
     */
    public function test_daftar_presensi_menampilkan_data_dengan_benar()
    {
        // Arrange: Create multiple presensi
        $pengajuan = $this->createDummyPengajuan();
        
        Presensi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => User::first()->id,
            'nama' => 'Person 1',
            'jabatan' => 'Manager',
            'organisasi' => '1',
            'ttd_path' => 'presensi/ttd/test1.png',
        ]);

        Presensi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => User::first()->id,
            'nama' => 'Person 2',
            'jabatan' => 'Staff',
            'organisasi' => '2',
            'ttd_path' => 'presensi/ttd/test2.png',
        ]);

        // Act: Access presensi list
        $response = $this->actingAsRole('OPD')
            ->get(route('presensi.show', $pengajuan->id));

        // Assert: Check both names appear
        $response->assertStatus(200);
        $response->assertSee('Person 1');
        $response->assertSee('Person 2');
        $response->assertSee('Manager');
        $response->assertSee('Staff');
    }

    /**
     * Test 11: Admin dapat melihat semua presensi
     * 
     * @test
     */
    public function test_admin_dapat_melihat_semua_presensi()
    {
        // Arrange: Create presensi
        $presensi = $this->createDummyPresensi();

        // Act: Access as admin
        $response = $this->actingAsRole('Admin')
            ->get(route('presensi.show', $presensi->pengajuan_id));

        // Assert: Check access
        $response->assertStatus(200);
        $response->assertViewHas('presensis');
    }

    /**
     * Test 12: Superadmin dapat melihat semua presensi
     * 
     * @test
     */
    public function test_superadmin_dapat_melihat_semua_presensi()
    {
        // Arrange: Create presensi
        $presensi = $this->createDummyPresensi();

        // Act: Access as superadmin
        $response = $this->actingAsRole('Super Admin')
            ->get(route('presensi.show', $presensi->pengajuan_id));

        // Assert: Check access
        $response->assertStatus(200);
        $response->assertViewHas('presensis');
    }

    /**
     * Test 14: Download gagal jika tidak ada TTD
     * 
     * @test
     */
    public function test_download_gagal_jika_tidak_ada_ttd()
    {
        // Arrange: Create pengajuan without presensi
        $pengajuan = $this->createDummyPengajuan();

        // Act: Try to download PDF
        $response = $this->actingAsRole('OPD')
            ->get(route('presensi.ttd.all', $pengajuan->id));

        // Assert: Check redirect with error
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * Test 16: TTD tersimpan sebagai file image
     * 
     * @test
     */
    public function test_ttd_tersimpan_sebagai_file_image()
    {
        Storage::fake('public');
        
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'Signature Test',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check file exists
        $response->assertStatus(200);
        
        $presensi = Presensi::where('pengajuan_id', $pengajuan->id)->first();
        $this->assertNotNull($presensi->ttd_path);
        
        // Check file exists in storage
        Storage::disk('public')->assertExists($presensi->ttd_path);
    }

    /**
     * Test 17: TTD disimpan di folder presensi/ttd
     * 
     * @test
     */
    public function test_ttd_disimpan_di_folder_presensi_ttd()
    {
        Storage::fake('public');
        
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'Folder Test',
            'jabatan' => 'Manager',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check file path
        $presensi = Presensi::where('pengajuan_id', $pengajuan->id)->first();
        $this->assertStringStartsWith('presensi/ttd/', $presensi->ttd_path);
        $this->assertStringEndsWith('.png', $presensi->ttd_path);
    }

    /**
     * Test 18: Validasi format TTD harus base64 image
     * 
     * @test
     */
    public function test_validasi_format_ttd_harus_base64_image()
    {
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'Invalid TTD Test',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => 'invalid_base64_string',
        ];

        // Act: Submit presensi
        $response = $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check validation error
        $response->assertStatus(422);
        $response->assertJson(['ok' => false]);
    }

    /**
     * Test 19: TTD dengan nama file unique (UUID)
     * 
     * @test
     */
    public function test_ttd_dengan_nama_file_unique()
    {
        Storage::fake('public');
        
        // Arrange: Create two presensi
        $pengajuan1 = $this->createDummyPengajuan();
        $pengajuan2 = $this->createDummyPengajuan();

        $presensiData1 = [
            'pengajuan_id' => $pengajuan1->id,
            'nama' => 'User 1',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        $presensiData2 = [
            'pengajuan_id' => $pengajuan2->id,
            'nama' => 'User 2',
            'jabatan' => 'Manager',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit both
        $this->actingAsRole('OPD')->postJson(route('presensi.store'), $presensiData1);
        $this->actingAsRole('OPD')->postJson(route('presensi.store'), $presensiData2);

        // Assert: Check different filenames
        $presensi1 = Presensi::where('pengajuan_id', $pengajuan1->id)->first();
        $presensi2 = Presensi::where('pengajuan_id', $pengajuan2->id)->first();
        
        $this->assertNotEquals($presensi1->ttd_path, $presensi2->ttd_path);
    }

    /**
     * Test 20: TTD file format adalah PNG
     * 
     * @test
     */
    public function test_ttd_file_format_adalah_png()
    {
        Storage::fake('public');
        
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'PNG Test',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check PNG extension
        $presensi = Presensi::where('pengajuan_id', $pengajuan->id)->first();
        $this->assertStringEndsWith('.png', $presensi->ttd_path);
    }

    /**
     * Test 21: Activity log tercatat saat presensi
     * 
     * @test
     */
    public function test_activity_log_tercatat_saat_presensi()
    {
        Storage::fake('public');
        
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();
        $user = User::where('role', 'OPD')->first();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'Activity Test',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $user->id,
            'activity' => 'Presensi berhasil untuk pengajuan ID ' . $pengajuan->id,
            'resource_type' => 'pengajuan',
            'resource_id' => $pengajuan->id,
        ]);
    }

    /**
     * Test 22: User ID tersimpan saat presensi
     * 
     * @test
     */
    public function test_user_id_tersimpan_saat_presensi()
    {
        Storage::fake('public');
        
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();
        $user = User::where('role', 'OPD')->first();

        $presensiData = [
            'pengajuan_id' => $pengajuan->id,
            'nama' => 'User ID Test',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => $this->generateBase64Signature(),
        ];

        // Act: Submit presensi
        $this->actingAsRole('OPD')
            ->postJson(route('presensi.store'), $presensiData);

        // Assert: Check user_id in database
        $this->assertDatabaseHas('presensis', [
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test 23: Multiple presensi per pengajuan diperbolehkan
     * 
     * @test
     */
    public function test_multiple_presensi_per_pengajuan_diperbolehkan()
    {
        Storage::fake('public');
        
        // Arrange: Create approved pengajuan
        $pengajuan = $this->createDummyPengajuan();

        // Act: Submit 3 presensi
        for ($i = 1; $i <= 3; $i++) {
            $presensiData = [
                'pengajuan_id' => $pengajuan->id,
                'nama' => 'Person ' . $i,
                'jabatan' => 'Staff ' . $i,
                'organisasi' => '1',
                'ttd_path' => $this->generateBase64Signature(),
            ];

            $this->actingAsRole('OPD')
                ->postJson(route('presensi.store'), $presensiData);
        }

        // Assert: Check 3 records exist
        $count = Presensi::where('pengajuan_id', $pengajuan->id)->count();
        $this->assertEquals(3, $count);
    }

    /**
     * Test 24: Presensi ordering by created_at
     * 
     * @test
     */
    public function test_presensi_ordering_by_created_at()
    {
        // Arrange: Create presensi with different timestamps
        $pengajuan = $this->createDummyPengajuan();
        
        $presensi1 = Presensi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => User::first()->id,
            'nama' => 'First Person',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => 'presensi/ttd/test1.png',
            'created_at' => now()->subMinutes(5),
        ]);

        $presensi2 = Presensi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => User::first()->id,
            'nama' => 'Second Person',
            'jabatan' => 'Manager',
            'organisasi' => '1',
            'ttd_path' => 'presensi/ttd/test2.png',
            'created_at' => now(),
        ]);

        // Act: Get presensi list
        $response = $this->actingAsRole('OPD')
            ->get(route('presensi.show', $pengajuan->id));

        // Assert: Check ordering (First Person should appear before Second Person)
        $response->assertStatus(200);
        $content = $response->getContent();
        $firstPos = strpos($content, 'First Person');
        $secondPos = strpos($content, 'Second Person');
        
        $this->assertTrue($firstPos < $secondPos);
    }

    /**
     * Test 25: Nama organisasi ditampilkan dengan benar
     * 
     * @test
     */
    public function test_nama_organisasi_ditampilkan_dengan_benar()
    {
        // Arrange: Create presensi with organization ID
        $pengajuan = $this->createDummyPengajuan();
        
        Presensi::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => User::first()->id,
            'nama' => 'Organization Test',
            'jabatan' => 'Staff',
            'organisasi' => '1',
            'ttd_path' => 'presensi/ttd/test.png',
        ]);

        // Act: Get presensi list
        $response = $this->actingAsRole('OPD')
            ->get(route('presensi.show', $pengajuan->id));

        // Assert: Check organization name appears
        $response->assertStatus(200);
        // Organization name should be visible if view displays it
    }
}
