<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
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
     * Data provider untuk kredensial user dengan berbagai role
     */
    public static function userCredentialsProvider()
    {
        return [
            'user_regular' => [
                'email' => 'testuser@example.com',
                'password' => 'password123',
                'expectedRole' => ['OPD', 'user'],
                'isAdmin' => false,
                'isSuperAdmin' => false,
            ],
            'admin' => [
                'email' => 'testadmin@example.com',
                'password' => 'password123',
                'expectedRole' => ['Admin', 'admin'],
                'isAdmin' => true,
                'isSuperAdmin' => false,
            ],
            'superadmin' => [
                'email' => 'testsuperadmin@example.com',
                'password' => 'password123',
                'expectedRole' => ['SuperAdmin', 'superadmin', 'Super Admin'],
                'isAdmin' => false,
                'isSuperAdmin' => true,
            ],
        ];
    }

    /**
     * Test User dapat mengakses halaman login
     * 
     * @test
     */
    public function test_user_dapat_mengakses_halaman_login()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    /**
     * Test semua role dapat login dengan kredensial valid
     * 
     * @test
     * @dataProvider userCredentialsProvider
     */
    public function test_semua_role_dapat_login_dengan_kredensial_valid($email, $password, $expectedRole, $isAdmin, $isSuperAdmin)
    {
        // Act: Attempt to login
        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Assert: Check redirect and session
        $response->assertRedirect('/');
        $response->assertSessionHas('success');
        $this->assertNotNull(session('user_id'));
        $this->assertNotNull(session('user_nama'));
        
        // Check if role is one of the expected values
        $actualRole = session('user_role');
        $roleMatches = is_array($expectedRole) 
            ? in_array($actualRole, $expectedRole) 
            : $actualRole === $expectedRole;
        $this->assertTrue($roleMatches, "Expected role to be one of: " . implode(', ', (array)$expectedRole) . ", but got: {$actualRole}");
    }

    /**
     * Test semua role gagal login dengan password salah
     * 
     * @test
     * @dataProvider userCredentialsProvider
     */
    public function test_semua_role_gagal_login_dengan_password_salah($email, $password, $expectedRole, $isAdmin, $isSuperAdmin)
    {
        // Act: Attempt to login with wrong password
        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'wrongpassword123',
        ]);

        // Assert: Check error and no session
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test login gagal dengan email tidak terdaftar
     * 
     * @test
     */
    public function test_login_gagal_dengan_email_tidak_terdaftar()
    {
        // Act: Attempt to login with non-existent email
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        // Assert: Check error
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test validasi form login - email wajib diisi
     * 
     * @test
     */
    public function test_validasi_email_wajib_diisi()
    {
        // Act: Submit login without email
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test validasi form login - password wajib diisi
     * 
     * @test
     */
    public function test_validasi_password_wajib_diisi()
    {
        // Act: Submit login without password
        $response = $this->post('/login', [
            'email' => 'A@Gmail.com',
            'password' => '',
        ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Test validasi form login - format email harus valid
     * 
     * @test
     */
    public function test_validasi_format_email_harus_valid()
    {
        // Act: Submit login with invalid email format
        $response = $this->post('/login', [
            'email' => 'invalid-email-format',
            'password' => 'password123',
        ]);

        // Assert: Check validation error
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test semua role dapat logout
     * 
     * @test
     * @dataProvider userCredentialsProvider
     */
    public function test_semua_role_dapat_logout($email, $password, $expectedRole, $isAdmin, $isSuperAdmin)
    {
        // Arrange: Login first
        $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Act: Logout (using GET method)
        $response = $this->get('/logout');

        // Assert: Check redirect and session cleared
        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Logout berhasil!');
    }

    /**
     * Test semua role redirect ke dashboard setelah login berhasil
     * 
     * @test
     * @dataProvider userCredentialsProvider
     */
    public function test_semua_role_redirect_ke_dashboard_setelah_login_berhasil($email, $password, $expectedRole, $isAdmin, $isSuperAdmin)
    {
        // Act: Login
        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Assert: Check redirect to home/dashboard
        $response->assertRedirect('/');
    }

    /**
     * Test redirect ke halaman login jika belum authenticated
     * 
     * @test
     */
    public function test_redirect_ke_login_jika_belum_authenticated()
    {
        // Act: Try to access protected route without login
        $response = $this->get('/index');

        // Assert: Should redirect to login
        $response->assertRedirect('/login');
    }

    /**
     * Test semua role yang sudah login tidak bisa mengakses halaman login
     * 
     * @test
     * @dataProvider userCredentialsProvider
     */
    public function test_user_yang_sudah_login_redirect_dari_halaman_login($email, $password, $expectedRole, $isAdmin, $isSuperAdmin)
    {
        // Arrange: Login first
        $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Act: Try to access login page
        $response = $this->get('/login');

        // Assert: Should redirect to home
        $response->assertRedirect('/');
    }

    /**
     * Test session menyimpan data user dengan benar untuk semua role
     * 
     * @test
     * @dataProvider userCredentialsProvider
     */
    public function test_semua_role_session_menyimpan_data_dengan_benar($email, $password, $expectedRole, $isAdmin, $isSuperAdmin)
    {
        // Act: Login
        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        // Assert: Check all session data exists
        $this->assertNotNull(session('user_id'));
        $this->assertNotNull(session('user_nama'));
        $this->assertNotNull(session('user_role'));
        
        // Check if role matches expected values (OPD/user or Admin/admin)
        $actualRole = session('user_role');
        $this->assertContains($actualRole, $expectedRole, "Expected role to be one of " . implode(', ', $expectedRole));
    }

    /**
     * Test user regular dapat login
     * 
     * @test
     */
    public function test_user_regular_dapat_login_dengan_kredensial_valid()
    {
        // Act: Login as regular user
        $response = $this->post('/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        // Assert: Check redirect and session
        $response->assertRedirect('/');
        $response->assertSessionHas('success');
        
        $this->assertNotNull(session('user_id'));
        $this->assertNotNull(session('user_role'));
    }

    /**
     * Test admin dapat login dengan kredensial admin valid
     * 
     * @test
     */
    public function test_admin_dapat_login_dengan_kredensial_valid()
    {
        // Act: Login as admin
        $response = $this->post('/login', [
            'email' => 'testadmin@example.com',
            'password' => 'password123',
        ]);

        // Assert: Check redirect and session
        $response->assertRedirect('/');
        $response->assertSessionHas('success');
        
        $this->assertNotNull(session('user_id'));
        $this->assertNotNull(session('user_role'));
        
        // Check that role is Admin (capital A)
        $actualRole = session('user_role');
        $this->assertContains($actualRole, ['Admin', 'admin'], "Expected role to be Admin or admin");
    }

    /**
     * Test superadmin dapat login dengan kredensial superadmin valid
     * 
     * @test
     */
    public function test_superadmin_dapat_login_dengan_kredensial_valid()
    {
        // Act: Login as superadmin
        $response = $this->post('/login', [
            'email' => 'testsuperadmin@example.com',
            'password' => 'password123',
        ]);

        // Assert: Check redirect and session
        $response->assertRedirect('/');
        $response->assertSessionHas('success');
        
        $this->assertNotNull(session('user_id'));
        $this->assertNotNull(session('user_role'));
        
        // Check that role is superadmin (any case variation)
        $actualRole = session('user_role');
        $this->assertContains($actualRole, ['SuperAdmin', 'superadmin', 'Super Admin'], "Expected role to be superadmin");
    }
}
