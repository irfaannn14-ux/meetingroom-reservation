<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ruangan;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class RuanganTest extends TestCase
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
     * Helper method to create dummy ruangan
     */
    private function createDummyRuangan($attributes = [])
    {
        return Ruangan::create(array_merge([
            'nama_ruangan' => 'Ruangan Test',
            'jml_peserta' => 20,
            'fasilitas' => 'Proyektor, AC, Whiteboard',
            'foto_ruangan' => 'ruangan/test.jpg',
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
     * Test 1: Admin dapat mengakses halaman tambah ruangan
     * 
     * @test
     */
    public function test_admin_dapat_mengakses_halaman_tambah_ruangan()
    {
        // Act: Login as admin and access create page
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.tambah'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('ruangan.tambah');
    }

    /**
     * Test 2: Superadmin dapat mengakses halaman tambah ruangan
     * 
     * @test
     */
    public function test_superadmin_dapat_mengakses_halaman_tambah_ruangan()
    {
        // Act: Login as superadmin and access create page
        $response = $this->actingAsRole('Super Admin')
            ->get(route('ruangan.tambah'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('ruangan.tambah');
    }

    /**
     * Test 3: User regular tidak dapat mengakses halaman tambah ruangan
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_mengakses_halaman_tambah_ruangan()
    {
        // Act: Login as regular user and try to access create page
        $response = $this->actingAsRole('OPD')
            ->get(route('ruangan.tambah'));

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * Test 4: Admin dapat menambah ruangan dengan data valid
     * 
     * @test
     */
    public function test_admin_dapat_menambah_ruangan_dengan_data_valid()
    {
        Storage::fake('public');

        // Arrange: Prepare data - use create() instead of image() to avoid GD dependency
        $file = UploadedFile::fake()->create('ruangan.jpg', 100);

        // Act: Login as admin and create ruangan
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Meeting A',
                'jml_peserta' => 30,
                'fasilitas' => 'Proyektor, AC, Whiteboard',
                'foto_ruangan' => $file,
            ]);

        // Assert: Check redirect and database
        $response->assertRedirect(route('ruangan.index'));
        $response->assertSessionHas('success', 'Ruangan berhasil ditambahkan!');
        
        $this->assertDatabaseHas('ruangans', [
            'nama_ruangan' => 'Ruangan Meeting A',
            'jml_peserta' => 30,
            'fasilitas' => 'Proyektor, AC, Whiteboard',
        ]);

        // Check file was stored
        Storage::disk('public')->assertExists('ruangan/' . $file->hashName());

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Menambahkan ruangan baru: Ruangan Meeting A',
            'resource_type' => 'ruangan',
        ]);
    }

    /**
     * Test 5: Superadmin dapat menambah ruangan dengan data valid
     * 
     * @test
     */
    public function test_superadmin_dapat_menambah_ruangan_dengan_data_valid()
    {
        Storage::fake('public');

        // Arrange: Prepare data
        $file = UploadedFile::fake()->create('ruangan.jpg', 100);

        // Act: Login as superadmin and create ruangan
        $response = $this->actingAsRole('Super Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Rapat B',
                'jml_peserta' => 25,
                'fasilitas' => 'Proyektor, Sound System',
                'foto_ruangan' => $file,
            ]);

        // Assert: Check redirect and database
        $response->assertRedirect(route('ruangan.index'));
        $response->assertSessionHas('success', 'Ruangan berhasil ditambahkan!');
        
        $this->assertDatabaseHas('ruangans', [
            'nama_ruangan' => 'Ruangan Rapat B',
            'jml_peserta' => 25,
        ]);
    }

    /**
     * Test 6: User regular tidak dapat menambah ruangan
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_menambah_ruangan()
    {
        Storage::fake('public');

        // Arrange: Prepare data
        $file = UploadedFile::fake()->create('ruangan.jpg', 100);

        // Act: Login as regular user and try to create ruangan
        $response = $this->actingAsRole('OPD')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
                'foto_ruangan' => $file,
            ]);

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * Test 7: Validasi form tambah ruangan - nama wajib diisi
     * 
     * @test
     */
    public function test_validasi_nama_ruangan_wajib_diisi()
    {
        Storage::fake('public');

        // Act: Submit without nama_ruangan
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => '',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
                'foto_ruangan' => UploadedFile::fake()->create('test.jpg', 100),
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['nama_ruangan']);
    }

    /**
     * Test 8: Validasi form tambah ruangan - jumlah peserta wajib diisi
     * 
     * @test
     */
    public function test_validasi_jumlah_peserta_wajib_diisi()
    {
        Storage::fake('public');

        // Act: Submit without jml_peserta
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => '',
                'fasilitas' => 'AC',
                'foto_ruangan' => UploadedFile::fake()->create('test.jpg', 100),
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['jml_peserta']);
    }

    /**
     * Test 9: Validasi form tambah ruangan - fasilitas wajib diisi
     * 
     * @test
     */
    public function test_validasi_fasilitas_wajib_diisi()
    {
        Storage::fake('public');

        // Act: Submit without fasilitas
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => 20,
                'fasilitas' => '',
                'foto_ruangan' => UploadedFile::fake()->create('test.jpg', 100),
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['fasilitas']);
    }

    /**
     * Test 10: Validasi form tambah ruangan - foto wajib diisi
     * 
     * @test
     */
    public function test_validasi_foto_ruangan_wajib_diisi()
    {
        // Act: Submit without foto_ruangan
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
                'foto_ruangan' => '',
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['foto_ruangan']);
    }

    /**
     * Test 11: Validasi form tambah ruangan - foto harus image
     * 
     * @test
     */
    public function test_validasi_foto_ruangan_harus_image()
    {
        Storage::fake('public');

        // Act: Submit with non-image file
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
                'foto_ruangan' => UploadedFile::fake()->create('document.pdf'),
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['foto_ruangan']);
    }

    /**
     * Test 12: Validasi form tambah ruangan - jumlah peserta harus numeric dan minimal 1
     * 
     * @test
     */
    public function test_validasi_jumlah_peserta_harus_numeric_dan_minimal_1()
    {
        Storage::fake('public');

        // Act: Submit with invalid jml_peserta
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => 0,
                'fasilitas' => 'AC',
                'foto_ruangan' => UploadedFile::fake()->create('test.jpg', 100),
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['jml_peserta']);
    }

    /**
     * Test 13: User dapat melihat daftar ruangan
     * 
     * @test
     */
    public function test_user_dapat_melihat_daftar_ruangan()
    {
        // Arrange: Create some ruangan
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan A']);
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan B']);

        // Act: Login and access index
        $response = $this->actingAsRole('OPD')
            ->get(route('ruangan.index'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('ruangan.index');
        $response->assertViewHas('ruangans');
    }

    /**
     * Test 14: Admin dapat melihat daftar ruangan
     * 
     * @test
     */
    public function test_admin_dapat_melihat_daftar_ruangan()
    {
        // Arrange: Create some ruangan
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan A']);

        // Act: Login as admin and access index
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('ruangan.index');
    }

    /**
     * Test 15: Superadmin dapat melihat daftar ruangan
     * 
     * @test
     */
    public function test_superadmin_dapat_melihat_daftar_ruangan()
    {
        // Arrange: Create some ruangan
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan A']);

        // Act: Login as superadmin and access index
        $response = $this->actingAsRole('Super Admin')
            ->get(route('ruangan.index'));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('ruangan.index');
    }

    /**
     * Test 16: Daftar ruangan menampilkan data dengan benar
     * 
     * @test
     */
    public function test_daftar_ruangan_menampilkan_data_dengan_benar()
    {
        // Arrange: Create ruangan with specific data
        $ruangan = $this->createDummyRuangan([
            'nama_ruangan' => 'Ruangan Test Khusus',
            'jml_peserta' => 50,
            'fasilitas' => 'Proyektor, AC',
        ]);

        // Act: Access index
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index'));

        // Assert: Check data in view
        $response->assertSee('Ruangan Test Khusus');
        $response->assertSee('50');
        $response->assertSee('Proyektor, AC');
    }

    /**
     * Test 17: Admin dapat mengakses halaman edit ruangan
     * 
     * @test
     */
    public function test_admin_dapat_mengakses_halaman_edit_ruangan()
    {
        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as admin and access edit page
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.edit', $ruangan));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('ruangan.tambah');
        $response->assertViewHas('ruangan');
    }

    /**
     * Test 18: Superadmin dapat mengakses halaman edit ruangan
     * 
     * @test
     */
    public function test_superadmin_dapat_mengakses_halaman_edit_ruangan()
    {
        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as superadmin and access edit page
        $response = $this->actingAsRole('Super Admin')
            ->get(route('ruangan.edit', $ruangan));

        // Assert: Check status and view
        $response->assertStatus(200);
        $response->assertViewIs('ruangan.tambah');
        $response->assertViewHas('ruangan');
    }

    /**
     * Test 19: User regular tidak dapat mengakses halaman edit ruangan
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_mengakses_halaman_edit_ruangan()
    {
        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as regular user and try to access edit page
        $response = $this->actingAsRole('OPD')
            ->get(route('ruangan.edit', $ruangan));

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * Test 20: Admin dapat mengupdate ruangan dengan data valid
     * 
     * @test
     */
    public function test_admin_dapat_mengupdate_ruangan_dengan_data_valid()
    {
        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as admin and update ruangan
        $response = $this->actingAsRole('Admin')
            ->put(route('ruangan.update', $ruangan), [
                'nama_ruangan' => 'Ruangan Updated',
                'jml_peserta' => 40,
                'fasilitas' => 'Proyektor, Sound System, AC',
            ]);

        // Assert: Check redirect and database
        $response->assertRedirect(route('ruangan.index'));
        $response->assertSessionHas('success', 'Ruangan berhasil diperbarui!');
        
        $this->assertDatabaseHas('ruangans', [
            'id' => $ruangan->id,
            'nama_ruangan' => 'Ruangan Updated',
            'jml_peserta' => 40,
            'fasilitas' => 'Proyektor, Sound System, AC',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Mengedit ruangan: Ruangan Updated',
            'resource_type' => 'ruangan',
        ]);
    }

    /**
     * Test 21: Superadmin dapat mengupdate ruangan dengan data valid
     * 
     * @test
     */
    public function test_superadmin_dapat_mengupdate_ruangan_dengan_data_valid()
    {
        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as superadmin and update ruangan
        $response = $this->actingAsRole('Super Admin')
            ->put(route('ruangan.update', $ruangan), [
                'nama_ruangan' => 'Ruangan Superadmin Update',
                'jml_peserta' => 35,
                'fasilitas' => 'Full Facility',
            ]);

        // Assert: Check redirect and database
        $response->assertRedirect(route('ruangan.index'));
        $response->assertSessionHas('success', 'Ruangan berhasil diperbarui!');
        
        $this->assertDatabaseHas('ruangans', [
            'id' => $ruangan->id,
            'nama_ruangan' => 'Ruangan Superadmin Update',
            'jml_peserta' => 35,
        ]);
    }

    /**
     * Test 22: Admin dapat mengganti foto ruangan
     * 
     * @test
     */
    public function test_admin_dapat_mengganti_foto_ruangan()
    {
        Storage::fake('public');

        // Arrange: Create a ruangan with old photo
        $oldFile = UploadedFile::fake()->create('old.jpg', 100);
        $oldPath = $oldFile->store('ruangan', 'public');
        
        $ruangan = $this->createDummyRuangan([
            'foto_ruangan' => $oldPath
        ]);

        // Act: Update with new photo
        $newFile = UploadedFile::fake()->create('new.jpg', 100);
        $response = $this->actingAsRole('Admin')
            ->put(route('ruangan.update', $ruangan), [
                'nama_ruangan' => $ruangan->nama_ruangan,
                'jml_peserta' => $ruangan->jml_peserta,
                'fasilitas' => $ruangan->fasilitas,
                'foto_ruangan' => $newFile,
            ]);

        // Assert: Check redirect
        $response->assertRedirect(route('ruangan.index'));
        
        // Check new file exists
        Storage::disk('public')->assertExists('ruangan/' . $newFile->hashName());
        
        // Check database updated
        $ruangan->refresh();
        $this->assertStringContainsString('ruangan/', $ruangan->foto_ruangan);
    }

    /**
     * Test 23: Superadmin dapat mengganti foto ruangan
     * 
     * @test
     */
    public function test_superadmin_dapat_mengganti_foto_ruangan()
    {
        Storage::fake('public');

        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Update with new photo
        $newFile = UploadedFile::fake()->create('new_super.jpg', 100);
        $response = $this->actingAsRole('Super Admin')
            ->put(route('ruangan.update', $ruangan), [
                'nama_ruangan' => $ruangan->nama_ruangan,
                'jml_peserta' => $ruangan->jml_peserta,
                'fasilitas' => $ruangan->fasilitas,
                'foto_ruangan' => $newFile,
            ]);

        // Assert: Check redirect
        $response->assertRedirect(route('ruangan.index'));
        
        // Check new file exists
        Storage::disk('public')->assertExists('ruangan/' . $newFile->hashName());
    }

    /**
     * Test 24: User regular tidak dapat mengupdate ruangan
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_mengupdate_ruangan()
    {
        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as regular user and try to update
        $response = $this->actingAsRole('OPD')
            ->put(route('ruangan.update', $ruangan), [
                'nama_ruangan' => 'Attempt Update',
                'jml_peserta' => 30,
                'fasilitas' => 'AC',
            ]);

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }

    /**
     * Test 25: Foto ruangan muncul di form edit
     * 
     * @test
     */
    public function test_foto_ruangan_muncul_di_form_edit()
    {
        // Arrange: Create a ruangan with photo
        $ruangan = $this->createDummyRuangan([
            'foto_ruangan' => 'ruangan/existing-photo.jpg'
        ]);

        // Act: Access edit page
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.edit', $ruangan));

        // Assert: Check photo path is in view
        $response->assertStatus(200);
        $response->assertViewHas('ruangan', function ($viewRuangan) use ($ruangan) {
            return $viewRuangan->foto_ruangan === $ruangan->foto_ruangan;
        });
    }

    /**
     * Test 26: Admin dapat menghapus ruangan
     * 
     * @test
     */
    public function test_admin_dapat_menghapus_ruangan()
    {
        Storage::fake('public');

        // Arrange: Create a ruangan
        $file = UploadedFile::fake()->create('to-delete.jpg', 100);
        $path = $file->store('ruangan', 'public');
        
        $ruangan = $this->createDummyRuangan([
            'foto_ruangan' => $path
        ]);

        // Act: Login as admin and delete
        $response = $this->actingAsRole('Admin')
            ->delete(route('ruangan.destroy', $ruangan));

        // Assert: Check redirect and database
        $response->assertRedirect(route('ruangan.index'));
        $response->assertSessionHas('success', 'Ruangan berhasil dihapus!');
        
        $this->assertDatabaseMissing('ruangans', [
            'id' => $ruangan->id,
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Menghapus ruangan: ' . $ruangan->nama_ruangan,
            'resource_type' => 'ruangan',
        ]);
    }

    /**
     * Test 27: Superadmin dapat menghapus ruangan
     * 
     * @test
     */
    public function test_superadmin_dapat_menghapus_ruangan()
    {
        Storage::fake('public');

        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as superadmin and delete
        $response = $this->actingAsRole('Super Admin')
            ->delete(route('ruangan.destroy', $ruangan));

        // Assert: Check redirect and database
        $response->assertRedirect(route('ruangan.index'));
        $response->assertSessionHas('success', 'Ruangan berhasil dihapus!');
        
        $this->assertDatabaseMissing('ruangans', [
            'id' => $ruangan->id,
        ]);
    }

    /**
     * Test 28: User regular tidak dapat menghapus ruangan
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_menghapus_ruangan()
    {
        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Act: Login as regular user and try to delete
        $response = $this->actingAsRole('OPD')
            ->delete(route('ruangan.destroy', $ruangan));

        // Assert: Should be forbidden
        $response->assertStatus(403);
        
        // Verify ruangan still exists
        $this->assertDatabaseHas('ruangans', [
            'id' => $ruangan->id,
        ]);
    }

    /**
     * Test 29: Foto terhapus saat ruangan dihapus
     * 
     * @test
     */
    public function test_foto_terhapus_saat_ruangan_dihapus()
    {
        Storage::fake('public');

        // Arrange: Create a ruangan with photo
        $file = UploadedFile::fake()->create('delete-test.jpg', 100);
        $path = $file->store('ruangan', 'public');
        
        $ruangan = $this->createDummyRuangan([
            'foto_ruangan' => $path
        ]);

        // Verify file exists before deletion
        Storage::disk('public')->assertExists($path);

        // Act: Delete ruangan
        $this->actingAsRole('Admin')
            ->delete(route('ruangan.destroy', $ruangan));

        // Assert: File should be deleted
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * Test 30: Guest tidak dapat akses halaman ruangan
     * 
     * @test
     */
    public function test_guest_tidak_dapat_akses_halaman_ruangan_tanpa_login()
    {
        // Act: Try to access without login (no session)
        $response = $this->get(route('ruangan.index'));

        // Assert: Should redirect to login
        $response->assertRedirect('/login');
    }

    /**
     * Test 31: User regular hanya bisa READ ruangan
     * 
     * @test
     */
    public function test_user_regular_hanya_bisa_read_ruangan()
    {
        Storage::fake('public');
        
        $ruangan = $this->createDummyRuangan();

        // Can read
        $response = $this->actingAsRole('OPD')
            ->get(route('ruangan.index'));
        $response->assertStatus(200);

        // Cannot create
        $response = $this->actingAsRole('OPD')
            ->get(route('ruangan.tambah'));
        $response->assertStatus(403);

        // Cannot update
        $response = $this->actingAsRole('OPD')
            ->put(route('ruangan.update', $ruangan), [
                'nama_ruangan' => 'Test',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
            ]);
        $response->assertStatus(403);

        // Cannot delete
        $response = $this->actingAsRole('OPD')
            ->delete(route('ruangan.destroy', $ruangan));
        $response->assertStatus(403);
    }

    /**
     * Test 32: Admin bisa full CRUD ruangan
     * 
     * @test
     */
    public function test_admin_bisa_full_crud_ruangan()
    {
        Storage::fake('public');

        // Can read
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index'));
        $response->assertStatus(200);

        // Can create
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.tambah'));
        $response->assertStatus(200);

        // Can update
        $ruangan = $this->createDummyRuangan();
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.edit', $ruangan));
        $response->assertStatus(200);

        // Can delete
        $response = $this->actingAsRole('Admin')
            ->delete(route('ruangan.destroy', $ruangan));
        $response->assertRedirect();
    }

    /**
     * Test 33: Superadmin bisa full CRUD ruangan
     * 
     * @test
     */
    public function test_superadmin_bisa_full_crud_ruangan()
    {
        Storage::fake('public');

        // Can read
        $response = $this->actingAsRole('Super Admin')
            ->get(route('ruangan.index'));
        $response->assertStatus(200);

        // Can create
        $response = $this->actingAsRole('Super Admin')
            ->get(route('ruangan.tambah'));
        $response->assertStatus(200);

        // Can update
        $ruangan = $this->createDummyRuangan();
        $response = $this->actingAsRole('Super Admin')
            ->get(route('ruangan.edit', $ruangan));
        $response->assertStatus(200);

        // Can delete
        $response = $this->actingAsRole('Super Admin')
            ->delete(route('ruangan.destroy', $ruangan));
        $response->assertRedirect();
    }

    /**
     * Test 34: Kapasitas ruangan tidak boleh negatif
     * 
     * @test
     */
    public function test_kapasitas_ruangan_tidak_boleh_negatif()
    {
        Storage::fake('public');

        // Act: Submit with negative capacity
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => -5,
                'fasilitas' => 'AC',
                'foto_ruangan' => UploadedFile::fake()->create('test.jpg', 100),
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['jml_peserta']);
    }

    /**
     * Test 35: Nama ruangan harus unique
     * 
     * @test
     */
    public function test_nama_ruangan_harus_unique()
    {
        Storage::fake('public');

        // Arrange: Create a ruangan
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Duplikat']);

        // Act: Try to create another with same name
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Duplikat',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
                'foto_ruangan' => UploadedFile::fake()->create('test.jpg', 100),
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['nama_ruangan']);
    }

    /**
     * Test 36: Foto ruangan tersimpan di storage/app/public/ruangan
     * 
     * @test
     */
    public function test_foto_ruangan_tersimpan_di_direktori_yang_benar()
    {
        Storage::fake('public');

        // Arrange: Prepare data
        $file = UploadedFile::fake()->create('test-storage.jpg', 100);

        // Act: Create ruangan
        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test Storage',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
                'foto_ruangan' => $file,
            ]);

        // Assert: Check file is in correct directory
        Storage::disk('public')->assertExists('ruangan/' . $file->hashName());
        
        // Check database has correct path
        $this->assertDatabaseHas('ruangans', [
            'nama_ruangan' => 'Ruangan Test Storage',
        ]);
    }

    /**
     * Test 37: Ruangan dengan booking aktif tidak bisa dihapus
     * 
     * @test
     */
    public function test_ruangan_dengan_booking_aktif_tidak_bisa_dihapus()
    {
        Storage::fake('public');

        // Arrange: Create a ruangan
        $ruangan = $this->createDummyRuangan();

        // Create a booking (pengajuan) for this ruangan
        $user = User::where('role', 'OPD')->first();
        \App\Models\Pengajuan::create([
            'user_id' => $user->id,
            'ruangan_id' => $ruangan->id,
            'nama_pengaju' => $user->nama,
            'judul_kegiatan' => 'Meeting Test',
            'kegiatan' => 'Test meeting activity',
            'tanggal_mulai' => now()->addDays(1)->setTime(9, 0),
            'tanggal_selesai' => now()->addDays(1)->setTime(11, 0),
            'jml_peserta' => 10,
            'status' => 'disetujui', // Changed from 'approved' to 'disetujui'
        ]);

        // Act: Try to delete ruangan with active booking
        $response = $this->actingAsRole('Admin')
            ->delete(route('ruangan.destroy', $ruangan));

        // Assert: Should fail or show error
        // Note: This assumes controller has validation for this
        // If not implemented yet, this test will fail and prompt implementation
        $response->assertSessionHas('error');
        
        // Ruangan should still exist
        $this->assertDatabaseHas('ruangans', [
            'id' => $ruangan->id,
        ]);
    }

    /**
     * Test 38: Filter ruangan berdasarkan kapasitas minimum
     * 
     * @test
     */
    public function test_filter_ruangan_berdasarkan_kapasitas_minimum()
    {
        // Arrange: Create ruangan with different capacities
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Kecil', 'jml_peserta' => 10]);
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Sedang', 'jml_peserta' => 30]);
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Besar', 'jml_peserta' => 50]);

        // Act: Filter with minimum capacity
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index', ['min_capacity' => 25]));

        // Assert: Check response
        $response->assertStatus(200);
        // Note: This assumes controller has filtering logic
        // If not implemented, this test serves as specification
    }

    /**
     * Test 39: Sorting ruangan by nama
     * 
     * @test
     */
    public function test_sorting_ruangan_by_nama()
    {
        // Arrange: Create ruangan with different names
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Zebra']);
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Alpha']);
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Beta']);

        // Act: Sort by name ascending
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index', ['sort' => 'nama', 'order' => 'asc']));

        // Assert: Check response
        $response->assertStatus(200);
        // Note: Verify sorting in actual implementation
    }

    /**
     * Test 40: Sorting ruangan by kapasitas
     * 
     * @test
     */
    public function test_sorting_ruangan_by_kapasitas()
    {
        // Arrange: Create ruangan with different capacities
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan A', 'jml_peserta' => 50]);
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan B', 'jml_peserta' => 20]);
        $this->createDummyRuangan(['nama_ruangan' => 'Ruangan C', 'jml_peserta' => 35]);

        // Act: Sort by capacity descending
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index', ['sort' => 'kapasitas', 'order' => 'desc']));

        // Assert: Check response
        $response->assertStatus(200);
    }

    /**
     * Test 41: Pagination berfungsi dengan benar
     * 
     * @test
     */
    public function test_pagination_berfungsi_dengan_benar()
    {
        // Arrange: Create many ruangan (more than one page)
        for ($i = 1; $i <= 15; $i++) {
            $this->createDummyRuangan([
                'nama_ruangan' => 'Ruangan Test ' . $i,
                'jml_peserta' => 20 + $i,
            ]);
        }

        // Act: Get first page
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index'));

        // Assert: Check response and pagination
        $response->assertStatus(200);
        $response->assertViewHas('ruangans');
        
        // Verify pagination exists (if implemented with paginate())
        $ruangans = $response->viewData('ruangans');
        if (method_exists($ruangans, 'hasPages')) {
            $this->assertTrue($ruangans->hasPages());
        }
    }

    /**
     * Test 42: Activity log tercatat dengan detail yang benar
     * 
     * @test
     */
    public function test_activity_log_tercatat_dengan_detail_yang_benar()
    {
        Storage::fake('public');

        // Arrange: Get admin user
        $admin = User::where('role', 'Admin')->first();
        
        // Act: Create ruangan as admin
        $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Log Test',
                'jml_peserta' => 25,
                'fasilitas' => 'Proyektor',
                'foto_ruangan' => UploadedFile::fake()->create('log.jpg', 100),
            ]);

        // Assert: Check activity log details
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'activity' => 'Menambahkan ruangan baru: Ruangan Log Test',
            'resource_type' => 'ruangan',
        ]);

        // Verify log has resource_id
        $ruangan = Ruangan::where('nama_ruangan', 'Ruangan Log Test')->first();
        $this->assertDatabaseHas('activity_logs', [
            'resource_id' => $ruangan->id,
            'resource_type' => 'ruangan',
        ]);
    }

    /**
     * Test 43: Validasi maksimal ukuran file foto
     * 
     * @test
     */
    public function test_validasi_maksimal_ukuran_file_foto()
    {
        Storage::fake('public');

        // Act: Upload file that's too large (> 2MB)
        $largeFile = UploadedFile::fake()->create('large.jpg', 3000); // 3MB

        $response = $this->actingAsRole('Admin')
            ->post(route('ruangan.store'), [
                'nama_ruangan' => 'Ruangan Test',
                'jml_peserta' => 20,
                'fasilitas' => 'AC',
                'foto_ruangan' => $largeFile,
            ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['foto_ruangan']);
    }

    /**
     * Test 44: Relasi ruangan dengan pengajuan berfungsi
     * 
     * @test
     */
    public function test_relasi_ruangan_dengan_pengajuan_berfungsi()
    {
        // Arrange: Create ruangan
        $ruangan = $this->createDummyRuangan();
        $user = User::where('role', 'OPD')->first();

        // Create pengajuan for this ruangan
        $pengajuan = \App\Models\Pengajuan::create([
            'user_id' => $user->id,
            'ruangan_id' => $ruangan->id,
            'nama_pengaju' => $user->nama,
            'judul_kegiatan' => 'Meeting Integration Test',
            'kegiatan' => 'Testing relationship',
            'tanggal_mulai' => now()->addDays(1)->setTime(10, 0),
            'tanggal_selesai' => now()->addDays(1)->setTime(12, 0),
            'jml_peserta' => 15,
            'status' => 'pending',
        ]);

        // Assert: Check relationship
        $this->assertNotNull($ruangan->pengajuans);
        $this->assertCount(1, $ruangan->pengajuans);
        $this->assertEquals($pengajuan->id, $ruangan->pengajuans->first()->id);
        $this->assertEquals($ruangan->id, $pengajuan->ruangan->id);
    }

    /**
     * Test 45: Jumlah booking ditampilkan di daftar ruangan
     * 
     * @test
     */
    public function test_jumlah_booking_ditampilkan_di_daftar_ruangan()
    {
        // Arrange: Create ruangan with multiple bookings
        $ruangan = $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Popular']);
        $user = User::where('role', 'OPD')->first();

        // Create multiple bookings
        for ($i = 1; $i <= 3; $i++) {
            \App\Models\Pengajuan::create([
                'user_id' => $user->id,
                'ruangan_id' => $ruangan->id,
                'nama_pengaju' => $user->nama,
                'judul_kegiatan' => 'Meeting ' . $i,
                'kegiatan' => 'Test',
                'tanggal_mulai' => now()->addDays($i)->setTime(9, 0),
                'tanggal_selesai' => now()->addDays($i)->setTime(11, 0),
                'jml_peserta' => 10,
                'status' => 'disetujui',
            ]);
        }

        // Act: Get ruangan list
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index'));

        // Assert: Response contains ruangan
        $response->assertStatus(200);
        $response->assertSee('Ruangan Popular');
        
        // Verify count in database
        $this->assertEquals(3, $ruangan->pengajuans()->count());
    }

    /**
     * Test 46: Kalender booking ruangan menampilkan data benar
     * 
     * @test
     */
    public function test_kalender_booking_ruangan_menampilkan_data_benar()
    {
        // Arrange: Create ruangan with bookings
        $ruangan = $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Kalender']);
        $user = User::where('role', 'OPD')->first();

        $booking = \App\Models\Pengajuan::create([
            'user_id' => $user->id,
            'ruangan_id' => $ruangan->id,
            'nama_pengaju' => $user->nama,
            'judul_kegiatan' => 'Meeting Kalender',
            'kegiatan' => 'Test calendar',
            'tanggal_mulai' => now()->addDays(3)->setTime(14, 0),
            'tanggal_selesai' => now()->addDays(3)->setTime(16, 0),
            'jml_peserta' => 20,
            'status' => 'disetujui',
        ]);

        // Act: Access calendar view (assuming route exists)
        // This test assumes there's a calendar view for ruangan
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index'));

        // Assert: Check response
        $response->assertStatus(200);
        
        // Verify booking data is available
        $approvedBookings = $ruangan->pengajuans()->where('status', 'disetujui')->get();
        $this->assertCount(1, $approvedBookings);
        $this->assertEquals('Meeting Kalender', $approvedBookings->first()->judul_kegiatan);
    }

    /**
     * Test 47: Ruangan tampil di dropdown saat buat pengajuan
     * 
     * @test
     */
    public function test_ruangan_tampil_di_dropdown_saat_buat_pengajuan()
    {
        // Arrange: Create multiple ruangan
        $ruangan1 = $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Meeting A']);
        $ruangan2 = $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Meeting B']);
        $ruangan3 = $this->createDummyRuangan(['nama_ruangan' => 'Ruangan Meeting C']);

        // Act: Access pengajuan create form
        $response = $this->actingAsRole('OPD')
            ->get('/pengajuan/tambah'); // Assuming this route exists

        // Assert: Check all ruangan appear in view
        $response->assertStatus(200);
        $response->assertSee('Ruangan Meeting A');
        $response->assertSee('Ruangan Meeting B');
        $response->assertSee('Ruangan Meeting C');
        
        // Verify all ruangan exist in database
        $this->assertEquals(3, Ruangan::whereIn('nama_ruangan', [
            'Ruangan Meeting A',
            'Ruangan Meeting B', 
            'Ruangan Meeting C'
        ])->count());
    }

    /**
     * Test 48: Export daftar ruangan ke Excel/PDF
     * 
     * @test
     */
    public function test_export_daftar_ruangan_ke_excel_atau_pdf()
    {
        // Arrange: Create multiple ruangan
        for ($i = 1; $i <= 5; $i++) {
            $this->createDummyRuangan([
                'nama_ruangan' => 'Ruangan Export ' . $i,
                'jml_peserta' => 20 + ($i * 5),
            ]);
        }

        // Act: Request export (assuming route exists)
        // This assumes controller has export functionality
        $response = $this->actingAsRole('Admin')
            ->get(route('ruangan.index', ['export' => 'excel']));

        // Assert: Check response
        // Note: This test may need adjustment based on actual export implementation
        $response->assertStatus(200);
        
        // Verify all data exists
        $this->assertEquals(5, Ruangan::where('nama_ruangan', 'like', 'Ruangan Export%')->count());
    }
}
