<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UserTest extends TestCase
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
    }

    /**
     * Create dummy organizations
     */
    private function createDummyOrganizations()
    {
        Organization::create([
            'organization_id' => Str::uuid()->toString(),
            'bkd_organization_id' => '1',
            'organization_name' => 'Dinas Komunikasi dan Informatika',
        ]);

        Organization::create([
            'organization_id' => Str::uuid()->toString(),
            'bkd_organization_id' => '2',
            'organization_name' => 'Dinas Pendidikan',
        ]);

        Organization::create([
            'organization_id' => Str::uuid()->toString(),
            'bkd_organization_id' => '99',
            'organization_name' => 'ADMIN',
        ]);

        Organization::create([
            'organization_id' => Str::uuid()->toString(),
            'bkd_organization_id' => '100',
            'organization_name' => 'SUPER ADMIN',
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
            'organization_id' => '1',
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
            'organization_id' => '99',
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
            'organization_id' => '100',
            'no_wa' => '081234567892',
            'role' => 'Super Admin',
            'foto_profil' => null,
        ]);
    }

    /**
     * Helper method to act as specific role
     */
    private function actingAsRole($role)
    {
        $user = User::where('role', $role)->first();
        
        return $this->actingAs($user)->withSession([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'user_role' => $user->role,
        ]);
    }

    // ========================================
    // A. CRUD OPERATIONS - CREATE/STORE TESTS
    // ========================================

    /**
     * Test 1: Admin dapat mengakses halaman tambah user
     * 
     * @test
     */
    public function test_admin_dapat_mengakses_halaman_tambah_user()
    {
        // Arrange & Act
        $response = $this->actingAsRole('Admin')
            ->get(route('user.tambah'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.tambah');
        $response->assertViewHas('organizations');
    }

    /**
     * Test 2: Superadmin dapat mengakses halaman tambah user
     * 
     * @test
     */
    public function test_superadmin_dapat_mengakses_halaman_tambah_user()
    {
        // Arrange & Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('user.tambah'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.tambah');
    }

    /**
     * Test 3: User regular tidak dapat mengakses halaman tambah user
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_mengakses_halaman_tambah_user()
    {
        // Arrange & Act
        $response = $this->actingAsRole('OPD')
            ->get(route('user.tambah'));

        // Assert
        $response->assertStatus(403); // Middleware aborts dengan 403
    }

    /**
     * Test 4: Admin dapat menambah user dengan data valid
     * 
     * @test
     */
    public function test_admin_dapat_menambah_user_dengan_data_valid()
    {
        Storage::fake('public');
        
        $userData = [
            'nama' => 'New User Test',
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'no_wa' => '081234567899',
            'password' => 'password123',
            'organization_id' => '1',
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];

        // Act
        $response = $this->actingAsRole('Admin')
            ->post(route('user.store'), $userData);

        // Assert
        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'nama' => 'New User Test',
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'organization_id' => '1',
            'role' => 'OPD',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Menambahkan pengguna baru: New User Test',
            'resource_type' => 'user',
        ]);
    }

    /**
     * Test 5: Validasi semua field wajib diisi
     * 
     * @test
     */
    public function test_validasi_semua_field_wajib_diisi()
    {
        // Act
        $response = $this->actingAsRole('Admin')
            ->post(route('user.store'), []);

        // Assert
        $response->assertSessionHasErrors([
            'nama', 
            'username', 
            'email', 
            'no_wa', 
            'password', 
            'organization_id',
            'foto_profil'
        ]);
    }

    /**
     * Test 6: Email harus unique
     * 
     * @test
     */
    public function test_email_harus_unique()
    {
        Storage::fake('public');
        
        $userData = [
            'nama' => 'Duplicate Email User',
            'username' => 'duplicateuser',
            'email' => 'testuser@example.com', // Email sudah ada
            'no_wa' => '081234567899',
            'password' => 'password123',
            'organization_id' => '1',
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];

        // Act
        $response = $this->actingAsRole('Admin')
            ->post(route('user.store'), $userData);

        // Assert
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test 7: Username harus unique
     * 
     * @test
     */
    public function test_username_harus_unique()
    {
        Storage::fake('public');
        
        $userData = [
            'nama' => 'Duplicate Username User',
            'username' => 'testuser', // Username sudah ada
            'email' => 'uniqueemail@example.com',
            'no_wa' => '081234567899',
            'password' => 'password123',
            'organization_id' => '1',
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];

        // Act
        $response = $this->actingAsRole('Admin')
            ->post(route('user.store'), $userData);

        // Assert
        $response->assertSessionHasErrors('username');
    }

    /**
     * Test 8: Password minimal 8 karakter
     * 
     * @test
     */
    public function test_password_minimal_8_karakter()
    {
        Storage::fake('public');
        
        $userData = [
            'nama' => 'Short Password User',
            'username' => 'shortpw',
            'email' => 'shortpw@example.com',
            'no_wa' => '081234567899',
            'password' => 'short', // Kurang dari 8 karakter
            'organization_id' => '1',
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];

        // Act
        $response = $this->actingAsRole('Admin')
            ->post(route('user.store'), $userData);

        // Assert
        $response->assertSessionHasErrors('password');
    }

    /**
     * Test 9: Foto profil harus berupa image
     * 
     * @test
     */
    public function test_foto_profil_harus_image()
    {
        Storage::fake('public');
        
        $userData = [
            'nama' => 'Invalid Photo User',
            'username' => 'invalidphoto',
            'email' => 'invalidphoto@example.com',
            'no_wa' => '081234567899',
            'password' => 'password123',
            'organization_id' => '1',
            'foto_profil' => UploadedFile::fake()->create('document.pdf'), // Bukan image
        ];

        // Act
        $response = $this->actingAsRole('Admin')
            ->post(route('user.store'), $userData);

        // Assert
        $response->assertSessionHasErrors('foto_profil');
    }

    /**
     * Test 10: Role otomatis ditentukan berdasarkan organization
     * 
     * @test
     */
    public function test_role_otomatis_ditentukan_berdasarkan_organization()
    {
        Storage::fake('public');
        
        // Test untuk ADMIN organization
        $adminUserData = [
            'nama' => 'Auto Admin User',
            'username' => 'autoadmin',
            'email' => 'autoadmin@example.com',
            'no_wa' => '081234567899',
            'password' => 'password123',
            'organization_id' => '99', // ADMIN organization
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];

        $this->actingAsRole('Admin')
            ->post(route('user.store'), $adminUserData);

        $this->assertDatabaseHas('users', [
            'username' => 'autoadmin',
            'role' => 'Admin',
        ]);

        // Test untuk SUPER ADMIN organization
        $superAdminUserData = [
            'nama' => 'Auto SuperAdmin User',
            'username' => 'autosuperadmin',
            'email' => 'autosuperadmin@example.com',
            'no_wa' => '081234567898',
            'password' => 'password123',
            'organization_id' => '100', // SUPER ADMIN organization
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];

        $this->actingAsRole('Admin')
            ->post(route('user.store'), $superAdminUserData);

        $this->assertDatabaseHas('users', [
            'username' => 'autosuperadmin',
            'role' => 'Super Admin',
        ]);
    }

    // ========================================
    // B. CRUD OPERATIONS - READ/INDEX TESTS
    // ========================================

    /**
     * Test 11: Admin dapat melihat daftar semua user
     * 
     * @test
     */
    public function test_admin_dapat_melihat_daftar_semua_user()
    {
        // Act
        $response = $this->actingAsRole('Admin')
            ->get(route('user.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.index');
        $response->assertViewHas('all');
    }

    /**
     * Test 12: Superadmin dapat melihat daftar semua user
     * 
     * @test
     */
    public function test_superadmin_dapat_melihat_daftar_semua_user()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('user.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.index');
    }

    /**
     * Test 13: User regular tidak dapat melihat daftar user
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_melihat_daftar_user()
    {
        // Act
        $response = $this->actingAsRole('OPD')
            ->get(route('user.index'));

        // Assert
        $response->assertStatus(403); // Middleware aborts dengan 403
    }

    /**
     * Test 14: Daftar user menampilkan relasi organization
     * 
     * @test
     */
    public function test_daftar_user_menampilkan_relasi_organization()
    {
        // Act
        $response = $this->actingAsRole('Admin')
            ->get(route('user.index'));

        // Assert
        $response->assertStatus(200);
        $users = $response->viewData('all');
        
        // Pastikan relasi organization di-load
        $this->assertTrue($users->first()->relationLoaded('organization'));
    }

    // ========================================
    // C. CRUD OPERATIONS - UPDATE/EDIT TESTS
    // ========================================

    /**
     * Test 15: Admin dapat mengakses halaman edit user
     * 
     * @test
     */
    public function test_admin_dapat_mengakses_halaman_edit_user()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();

        // Act
        $response = $this->actingAsRole('Admin')
            ->get(route('user.edit', $user->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.tambah');
        $response->assertViewHas('user');
        $response->assertViewHas('organizations');
    }

    /**
     * Test 16: Admin dapat update user dengan data valid
     * 
     * @test
     */
    public function test_admin_dapat_update_user_dengan_data_valid()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();
        
        $updateData = [
            'nama' => 'Updated User Name',
            'username' => 'updateduser',
            'email' => 'updated@example.com',
            'no_wa' => '089999999999',
            'organization_id' => '2',
        ];

        // Act
        $response = $this->actingAsRole('Admin')
            ->put(route('user.update', $user->id), $updateData);

        // Assert
        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama' => 'Updated User Name',
            'email' => 'updated@example.com',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Mengedit pengguna: Updated User Name',
            'resource_type' => 'user',
        ]);
    }

    /**
     * Test 17: Update password bersifat optional
     * 
     * @test
     */
    public function test_update_password_bersifat_optional()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();
        $oldPassword = $user->password;
        
        $updateData = [
            'nama' => 'Updated Without Password',
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => '089999999999',
            'organization_id' => '1',
            // Tidak mengisi password
        ];

        // Act
        $this->actingAsRole('Admin')
            ->put(route('user.update', $user->id), $updateData);

        // Assert
        $user->refresh();
        $this->assertEquals($oldPassword, $user->password); // Password tidak berubah
    }

    /**
     * Test 18: Admin dapat mengganti foto profil user
     * 
     * @test
     */
    public function test_admin_dapat_mengganti_foto_profil_user()
    {
        Storage::fake('public');
        
        // Arrange
        $user = User::where('role', 'OPD')->first();
        $oldPhoto = $user->foto_profil;
        
        $updateData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
            'organization_id' => $user->organization_id,
            'foto_profil' => UploadedFile::fake()->create('new_profile.jpg', 100),
        ];

        // Act
        $this->actingAsRole('Admin')
            ->put(route('user.update', $user->id), $updateData);

        // Assert
        $user->refresh();
        $this->assertNotEquals($oldPhoto, $user->foto_profil);
        Storage::disk('public')->assertExists($user->foto_profil);
    }

    /**
     * Test 19: Foto lama terhapus saat upload foto baru
     * 
     * @test
     */
    public function test_foto_lama_terhapus_saat_upload_foto_baru()
    {
        Storage::fake('public');
        
        // Arrange: Create user dengan foto
        $oldPhoto = UploadedFile::fake()->create('old_profile.jpg', 100);
        $path = $oldPhoto->store('foto_profil', 'public');
        
        $user = User::create([
            'nama' => 'User With Photo',
            'username' => 'userwithphoto',
            'email' => 'userwithphoto@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => '1',
            'no_wa' => '081111111111',
            'role' => 'OPD',
            'foto_profil' => $path,
        ]);

        Storage::disk('public')->assertExists($path);
        
        $updateData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
            'organization_id' => $user->organization_id,
            'foto_profil' => UploadedFile::fake()->create('new_profile.jpg', 100),
        ];

        // Act
        $this->actingAsRole('Admin')
            ->put(route('user.update', $user->id), $updateData);

        // Assert
        Storage::disk('public')->assertMissing($path); // Foto lama terhapus
    }

    // ========================================
    // D. CRUD OPERATIONS - DELETE TESTS
    // ========================================

    /**
     * Test 20: Superadmin dapat menghapus user
     * 
     * @test
     */
    public function test_superadmin_dapat_menghapus_user()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();
        $userId = $user->id;

        // Act
        $response = $this->actingAsRole('Super Admin')
            ->delete(route('user.destroy', $user->id));

        // Assert
        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('users', ['id' => $userId]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'activity' => 'Menghapus pengguna: Test User Regular',
            'resource_type' => 'user',
        ]);
    }

    /**
     * Test 21: Foto profil terhapus saat user dihapus
     * 
     * @test
     */
    public function test_foto_profil_terhapus_saat_user_dihapus()
    {
        Storage::fake('public');
        
        // Arrange: Create user dengan foto
        $photo = UploadedFile::fake()->create('profile_to_delete.jpg', 100);
        $path = $photo->store('foto_profil', 'public');
        
        $user = User::create([
            'nama' => 'User To Delete',
            'username' => 'usertodelete',
            'email' => 'usertodelete@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => '1',
            'no_wa' => '082222222222',
            'role' => 'OPD',
            'foto_profil' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        // Act
        $this->actingAsRole('Super Admin')
            ->delete(route('user.destroy', $user->id));

        // Assert
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * Test 22: User regular tidak dapat menghapus user lain
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_menghapus_user_lain()
    {
        // Arrange
        $userToDelete = User::where('role', 'Admin')->first();

        // Act
        $response = $this->actingAsRole('OPD')
            ->delete(route('user.destroy', $userToDelete->id));

        // Assert
        $response->assertStatus(403); // Middleware aborts dengan 403
        $this->assertDatabaseHas('users', ['id' => $userToDelete->id]); // User masih ada
    }

    // ========================================
    // E. PROFILE MANAGEMENT TESTS
    // ========================================

    /**
     * Test 23: User dapat mengakses halaman profil sendiri
     * 
     * @test
     */
    public function test_user_dapat_mengakses_halaman_profil_sendiri()
    {
        // Act
        $response = $this->actingAsRole('OPD')
            ->get(route('profile.show'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('user.profile');
        $response->assertViewHas('user');
    }

    /**
     * Test 24: User dapat update profil sendiri
     * 
     * @test
     */
    public function test_user_dapat_update_profil_sendiri()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();
        
        $updateData = [
            'nama' => 'Updated Own Name',
            'username' => 'updatedownusername',
            'email' => 'updatedown@example.com',
            'no_wa' => '083333333333',
        ];

        // Act
        $response = $this->actingAsRole('OPD')
            ->put(route('profile.update'), $updateData);

        // Assert
        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama' => 'Updated Own Name',
            'email' => 'updatedown@example.com',
        ]);
    }

    /**
     * Test 25: User dapat update foto profil sendiri
     * 
     * @test
     */
    public function test_user_dapat_update_foto_profil_sendiri()
    {
        Storage::fake('public');
        
        // Arrange
        $user = User::where('role', 'OPD')->first();
        
        $updateData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
            'foto_profil' => UploadedFile::fake()->create('my_new_profile.jpg', 100),
        ];

        // Act
        $this->actingAsRole('OPD')
            ->put(route('profile.update'), $updateData);

        // Assert
        $user->refresh();
        $this->assertNotNull($user->foto_profil);
        Storage::disk('public')->assertExists($user->foto_profil);
    }

    /**
     * Test 26: Foto profil lama terhapus saat update
     * 
     * @test
     */
    public function test_foto_profil_lama_terhapus_saat_update_profil()
    {
        Storage::fake('public');
        
        // Arrange: Create user dengan foto
        $oldPhoto = UploadedFile::fake()->create('old_own_profile.jpg', 100);
        $oldPath = $oldPhoto->store('foto_profil', 'public');
        
        $user = User::create([
            'nama' => 'User Own Profile',
            'username' => 'userownprofile',
            'email' => 'userownprofile@example.com',
            'password' => Hash::make('password123'),
            'organization_id' => '1',
            'no_wa' => '084444444444',
            'role' => 'OPD',
            'foto_profil' => $oldPath,
        ]);

        Storage::disk('public')->assertExists($oldPath);
        
        $updateData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
            'foto_profil' => UploadedFile::fake()->create('new_own_profile.jpg', 100),
        ];

        // Act
        $this->actingAs($user)->withSession([
            'user_id' => $user->id,
            'user_nama' => $user->nama,
            'user_role' => $user->role,
        ])->put(route('profile.update'), $updateData);

        // Assert
        Storage::disk('public')->assertMissing($oldPath);
    }

    /**
     * Test 27: User dapat mengganti password sendiri
     * 
     * @test
     */
    public function test_user_dapat_mengganti_password_sendiri()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();
        $oldPassword = $user->password;
        
        $updateData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        // Act
        $this->actingAsRole('OPD')
            ->put(route('profile.update'), $updateData);

        // Assert
        $user->refresh();
        $this->assertNotEquals($oldPassword, $user->password);
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test 28: Password confirmation harus sama
     * 
     * @test
     */
    public function test_password_confirmation_harus_sama()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();
        
        $updateData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword', // Tidak sama
        ];

        // Act
        $response = $this->actingAsRole('OPD')
            ->put(route('profile.update'), $updateData);

        // Assert
        $response->assertSessionHasErrors('password');
    }

    /**
     * Test 29: Session diupdate saat nama berubah
     * 
     * @test
     */
    public function test_session_diupdate_saat_nama_berubah()
    {
        // Arrange
        $user = User::where('role', 'OPD')->first();
        
        $updateData = [
            'nama' => 'Completely New Name',
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
        ];

        // Act
        $response = $this->actingAsRole('OPD')
            ->put(route('profile.update'), $updateData);

        // Assert
        $response->assertSessionHas('user_nama', 'Completely New Name');
    }

    /**
     * Test 30: Session foto diupdate saat foto berubah
     * 
     * @test
     */
    public function test_session_foto_diupdate_saat_foto_berubah()
    {
        Storage::fake('public');
        
        // Arrange
        $user = User::where('role', 'OPD')->first();
        
        $updateData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'email' => $user->email,
            'no_wa' => $user->no_wa,
            'foto_profil' => UploadedFile::fake()->create('session_test.jpg', 100),
        ];

        // Act
        $response = $this->actingAsRole('OPD')
            ->put(route('profile.update'), $updateData);

        // Assert
        $user->refresh();
        $response->assertSessionHas('user_foto', $user->foto_profil);
    }

    // ========================================
    // F. PERMISSION & AUTHORIZATION TESTS
    // ========================================

    /**
     * Test 31: Guest tidak dapat akses user management
     * 
     * @test
     */
    public function test_guest_tidak_dapat_akses_user_management()
    {
        // Act
        $response = $this->get(route('user.index'));

        // Assert
        $response->assertStatus(302); // Redirect to login
    }

    /**
     * Test 32: User regular tidak dapat CRUD user lain
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_crud_user_lain()
    {
        Storage::fake('public');
        
        // Arrange
        $adminUser = User::where('role', 'Admin')->first();
        
        // Test: Tidak bisa create
        $createResponse = $this->actingAsRole('OPD')
            ->post(route('user.store'), [
                'nama' => 'Should Not Create',
                'username' => 'shouldnotcreate',
                'email' => 'shouldnotcreate@example.com',
                'no_wa' => '085555555555',
                'password' => 'password123',
                'organization_id' => '1',
                'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
            ]);
        
        $createResponse->assertStatus(403); // Middleware aborts dengan 403
        
        // Test: Tidak bisa update
        $updateResponse = $this->actingAsRole('OPD')
            ->put(route('user.update', $adminUser->id), [
                'nama' => 'Should Not Update',
            ]);
        
        $updateResponse->assertStatus(403);
        
        // Test: Tidak bisa delete
        $deleteResponse = $this->actingAsRole('OPD')
            ->delete(route('user.destroy', $adminUser->id));
        
        $deleteResponse->assertStatus(403);
    }

    /**
     * Test 33: Admin dapat CRUD user
     * 
     * @test
     */
    public function test_admin_dapat_crud_user()
    {
        Storage::fake('public');
        
        // Test CREATE
        $createData = [
            'nama' => 'Admin Created User',
            'username' => 'admincreateduser',
            'email' => 'admincreated@example.com',
            'no_wa' => '086666666666',
            'password' => 'password123',
            'organization_id' => '1',
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];
        
        $createResponse = $this->actingAsRole('Admin')
            ->post(route('user.store'), $createData);
        
        $createResponse->assertRedirect(route('user.index'));
        $this->assertDatabaseHas('users', ['username' => 'admincreateduser']);
        
        // Test UPDATE
        $createdUser = User::where('username', 'admincreateduser')->first();
        $updateResponse = $this->actingAsRole('Admin')
            ->put(route('user.update', $createdUser->id), [
                'nama' => 'Admin Updated User',
                'username' => 'admincreateduser',
                'email' => 'admincreated@example.com',
                'no_wa' => '086666666666',
                'organization_id' => '1',
            ]);
        
        $updateResponse->assertRedirect(route('user.index'));
        $this->assertDatabaseHas('users', ['nama' => 'Admin Updated User']);
        
        // Test DELETE
        $deleteResponse = $this->actingAsRole('Admin')
            ->delete(route('user.destroy', $createdUser->id));
        
        $deleteResponse->assertRedirect(route('user.index'));
        $this->assertDatabaseMissing('users', ['id' => $createdUser->id]);
    }

    /**
     * Test 34: Superadmin dapat full CRUD semua user
     * 
     * @test
     */
    public function test_superadmin_dapat_full_crud_semua_user()
    {
        Storage::fake('public');
        
        // Superadmin dapat create, update, dan delete user
        // Termasuk admin dan superadmin lain
        
        $createData = [
            'nama' => 'SuperAdmin Created User',
            'username' => 'superadmincreated',
            'email' => 'superadmincreated@example.com',
            'no_wa' => '087777777777',
            'password' => 'password123',
            'organization_id' => '99', // Admin organization
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];
        
        $response = $this->actingAsRole('Super Admin')
            ->post(route('user.store'), $createData);
        
        $response->assertRedirect(route('user.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'superadmincreated',
            'role' => 'Admin', // Role auto-assigned based on organization
        ]);
    }

    // ========================================
    // G. ORGANIZATION RELATIONSHIP TESTS
    // ========================================

    /**
     * Test 35: User terelasi dengan organization
     * 
     * @test
     */
    public function test_user_terelasi_dengan_organization()
    {
        // Arrange
        $user = User::with('organization')->where('role', 'OPD')->first();

        // Assert
        $this->assertNotNull($user->organization);
        $this->assertInstanceOf(Organization::class, $user->organization);
    }

    /**
     * Test 36: Organization ditampilkan di daftar user
     * 
     * @test
     */
    public function test_organization_ditampilkan_di_daftar_user()
    {
        // Act
        $response = $this->actingAsRole('Admin')
            ->get(route('user.index'));

        // Assert
        $users = $response->viewData('all');
        $firstUser = $users->first();
        
        $this->assertTrue($firstUser->relationLoaded('organization'));
        $this->assertNotNull($firstUser->organization);
    }

    /**
     * Test 37: Dropdown organization tersedia di form
     * 
     * @test
     */
    public function test_dropdown_organization_tersedia_di_form()
    {
        // Act - Create form
        $createResponse = $this->actingAsRole('Admin')
            ->get(route('user.tambah'));

        $createResponse->assertViewHas('organizations');
        
        // Act - Edit form
        $user = User::where('role', 'OPD')->first();
        $editResponse = $this->actingAsRole('Admin')
            ->get(route('user.edit', $user->id));

        $editResponse->assertViewHas('organizations');
    }

    /**
     * Test 38: Validasi organization_id harus exist
     * 
     * @test
     */
    public function test_validasi_organization_id_harus_exist()
    {
        Storage::fake('public');
        
        $userData = [
            'nama' => 'Invalid Org User',
            'username' => 'invalidorg',
            'email' => 'invalidorg@example.com',
            'no_wa' => '088888888888',
            'password' => 'password123',
            'organization_id' => '999', // Tidak exist
            'foto_profil' => UploadedFile::fake()->create('profile.jpg', 100),
        ];

        // Act
        $response = $this->actingAsRole('Admin')
            ->post(route('user.store'), $userData);

        // Assert
        $response->assertSessionHasErrors('organization_id');
    }
}
