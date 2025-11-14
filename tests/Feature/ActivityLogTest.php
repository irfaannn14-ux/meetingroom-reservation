<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\ActivityLog;
use App\Models\Ruangan;
use App\Models\Pengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ActivityLogTest extends TestCase
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
        $this->createDummyActivityLogs();
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
     * Create dummy activity logs
     */
    private function createDummyActivityLogs()
    {
        $opdUser = User::where('role', 'OPD')->first();
        $adminUser = User::where('role', 'Admin')->first();
        $superadminUser = User::where('role', 'Super Admin')->first();

        // Create logs for OPD user (should NOT appear in log list)
        ActivityLog::create([
            'user_id' => $opdUser->id,
            'activity' => 'OPD User mengajukan peminjaman ruangan',
            'resource_type' => null,
            'resource_id' => null,
        ]);

        ActivityLog::create([
            'user_id' => $opdUser->id,
            'activity' => 'OPD User mengedit pengajuan',
            'resource_type' => null,
            'resource_id' => null,
        ]);

        // Create logs for Admin user (should appear in log list)
        ActivityLog::create([
            'user_id' => $adminUser->id,
            'activity' => 'Admin menambahkan ruangan baru: Ruang Rapat A',
            'resource_type' => 'ruangan',
            'resource_id' => null,
        ]);

        ActivityLog::create([
            'user_id' => $adminUser->id,
            'activity' => 'Admin mengapprove pengajuan',
            'resource_type' => 'pengajuan',
            'resource_id' => null,
        ]);

        ActivityLog::create([
            'user_id' => $adminUser->id,
            'activity' => 'Admin menambahkan pengguna baru: User Test',
            'resource_type' => 'user',
            'resource_id' => null,
        ]);

        // Create logs for Super Admin user (should appear in log list)
        ActivityLog::create([
            'user_id' => $superadminUser->id,
            'activity' => 'Super Admin menghapus ruangan',
            'resource_type' => 'ruangan',
            'resource_id' => null,
        ]);

        ActivityLog::create([
            'user_id' => $superadminUser->id,
            'activity' => 'Super Admin menghapus pengguna',
            'resource_type' => 'user',
            'resource_id' => null,
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
    // A. ACCESS CONTROL TESTS
    // ========================================

    /**
     * Test 1: Super Admin dapat mengakses halaman log aktivitas
     * 
     * @test
     */
    public function test_superadmin_dapat_mengakses_halaman_log_aktivitas()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('log.index');
        $response->assertViewHas('logs');
    }

    /**
     * Test 2: Admin tidak dapat mengakses halaman log aktivitas
     * 
     * @test
     */
    public function test_admin_tidak_dapat_mengakses_halaman_log_aktivitas()
    {
        // Act
        $response = $this->actingAsRole('Admin')
            ->get(route('log.index'));

        // Assert
        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test 3: User regular tidak dapat mengakses halaman log aktivitas
     * 
     * @test
     */
    public function test_user_regular_tidak_dapat_mengakses_halaman_log_aktivitas()
    {
        // Act
        $response = $this->actingAsRole('OPD')
            ->get(route('log.index'));

        // Assert
        $response->assertStatus(403); // Forbidden
    }

    /**
     * Test 4: Guest tidak dapat mengakses halaman log aktivitas
     * 
     * @test
     */
    public function test_guest_tidak_dapat_mengakses_halaman_log_aktivitas()
    {
        // Act
        $response = $this->get(route('log.index'));

        // Assert
        $response->assertStatus(302); // Redirect to login
    }

    // ========================================
    // B. LOG FILTERING TESTS
    // ========================================

    /**
     * Test 5: Hanya menampilkan log dari Admin dan Super Admin
     * 
     * @test
     */
    public function test_hanya_menampilkan_log_dari_admin_dan_superadmin()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        // Should have 5 logs (3 from Admin + 2 from Super Admin)
        $this->assertCount(5, $logs);
        
        // All logs should be from Admin or Super Admin
        foreach ($logs as $log) {
            $this->assertContains($log->user->role, ['Admin', 'Super Admin']);
        }
    }

    /**
     * Test 6: Tidak menampilkan log dari user regular (OPD)
     * 
     * @test
     */
    public function test_tidak_menampilkan_log_dari_user_regular()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        // Verify no OPD user logs appear
        foreach ($logs as $log) {
            $this->assertNotEquals('OPD', $log->user->role);
        }
    }

    /**
     * Test 7: Log diurutkan dari yang terbaru (latest)
     * 
     * @test
     */
    public function test_log_diurutkan_dari_yang_terbaru()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        // Check if logs are sorted by created_at descending
        $timestamps = $logs->pluck('created_at')->toArray();
        $sortedTimestamps = collect($timestamps)->sortDesc()->values()->toArray();
        
        $this->assertEquals($sortedTimestamps, $timestamps);
    }

    // ========================================
    // C. RELATIONSHIP TESTS
    // ========================================

    /**
     * Test 8: Log memuat relasi user
     * 
     * @test
     */
    public function test_log_memuat_relasi_user()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        // Verify user relationship is loaded
        $this->assertTrue($logs->first()->relationLoaded('user'));
        
        // Verify user data is accessible
        $firstLog = $logs->first();
        $this->assertNotNull($firstLog->user);
        $this->assertInstanceOf(User::class, $firstLog->user);
    }

    /**
     * Test 9: Setiap log memiliki user yang valid
     * 
     * @test
     */
    public function test_setiap_log_memiliki_user_yang_valid()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        foreach ($logs as $log) {
            $this->assertNotNull($log->user);
            $this->assertNotNull($log->user->nama);
            $this->assertNotNull($log->user->role);
        }
    }

    // ========================================
    // D. ACTIVITY LOG MODEL TESTS
    // ========================================

    /**
     * Test 10: ActivityLog model memiliki relasi dengan User
     * 
     * @test
     */
    public function test_activitylog_model_memiliki_relasi_dengan_user()
    {
        // Arrange
        $log = ActivityLog::with('user')->first();

        // Assert
        $this->assertNotNull($log);
        $this->assertNotNull($log->user);
        $this->assertInstanceOf(User::class, $log->user);
    }

    /**
     * Test 11: ActivityLog dapat dibuat dengan resource_type dan resource_id
     * 
     * @test
     */
    public function test_activitylog_dapat_dibuat_dengan_resource_type_dan_id()
    {
        // Arrange
        $adminUser = User::where('role', 'Admin')->first();
        
        // Act
        $log = ActivityLog::create([
            'user_id' => $adminUser->id,
            'activity' => 'Test activity with resource',
            'resource_type' => 'ruangan',
            'resource_id' => 123,
        ]);

        // Assert
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $adminUser->id,
            'activity' => 'Test activity with resource',
            'resource_type' => 'ruangan',
            'resource_id' => 123,
        ]);
    }

    /**
     * Test 12: ActivityLog dapat dibuat tanpa resource_type dan resource_id
     * 
     * @test
     */
    public function test_activitylog_dapat_dibuat_tanpa_resource_type_dan_id()
    {
        // Arrange
        $adminUser = User::where('role', 'Admin')->first();
        
        // Act
        $log = ActivityLog::create([
            'user_id' => $adminUser->id,
            'activity' => 'Test activity without resource',
        ]);

        // Assert
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $adminUser->id,
            'activity' => 'Test activity without resource',
            'resource_type' => null,
            'resource_id' => null,
        ]);
    }

    /**
     * Test 13: Log activity field menyimpan deskripsi aktivitas
     * 
     * @test
     */
    public function test_log_activity_field_menyimpan_deskripsi_aktivitas()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        foreach ($logs as $log) {
            $this->assertNotEmpty($log->activity);
            $this->assertIsString($log->activity);
        }
    }

    // ========================================
    // E. DATA INTEGRITY TESTS
    // ========================================

    /**
     * Test 14: Log count sesuai dengan filter Admin dan Super Admin
     * 
     * @test
     */
    public function test_log_count_sesuai_dengan_filter()
    {
        // Arrange
        $expectedCount = ActivityLog::whereHas('user', function ($query) {
            $query->whereIn('role', ['Admin', 'Super Admin']);
        })->count();

        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        $this->assertCount($expectedCount, $logs);
        $this->assertEquals(5, $expectedCount); // 3 Admin + 2 Super Admin
    }

    /**
     * Test 15: Semua log memiliki timestamp
     * 
     * @test
     */
    public function test_semua_log_memiliki_timestamp()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        foreach ($logs as $log) {
            $this->assertNotNull($log->created_at);
            $this->assertNotNull($log->updated_at);
        }
    }

    /**
     * Test 16: Log dengan berbagai resource_type dapat ditampilkan
     * 
     * @test
     */
    public function test_log_dengan_berbagai_resource_type_dapat_ditampilkan()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $logs = $response->viewData('logs');
        
        // Verify different resource types exist
        $resourceTypes = $logs->pluck('resource_type')->filter()->unique()->toArray();
        
        $this->assertContains('ruangan', $resourceTypes);
        $this->assertContains('pengajuan', $resourceTypes);
        $this->assertContains('user', $resourceTypes);
    }

    // ========================================
    // F. INTEGRATION TESTS
    // ========================================

    /**
     * Test 17: Log activity terekam saat Admin melakukan aksi
     * 
     * @test
     */
    public function test_log_activity_terekam_saat_admin_melakukan_aksi()
    {
        // Arrange
        $adminUser = User::where('role', 'Admin')->first();
        $initialCount = ActivityLog::count();

        // Act - Create new log manually (simulating controller action)
        ActivityLog::create([
            'user_id' => $adminUser->id,
            'activity' => 'Admin melakukan test action',
            'resource_type' => 'test',
            'resource_id' => 1,
        ]);

        // Assert
        $newCount = ActivityLog::count();
        $this->assertEquals($initialCount + 1, $newCount);
        
        // Verify it appears in the log view
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));
        
        $logs = $response->viewData('logs');
        $this->assertTrue(
            $logs->contains(fn($log) => $log->activity === 'Admin melakukan test action')
        );
    }

    /**
     * Test 18: Log activity terekam saat Super Admin melakukan aksi
     * 
     * @test
     */
    public function test_log_activity_terekam_saat_superadmin_melakukan_aksi()
    {
        // Arrange
        $superadminUser = User::where('role', 'Super Admin')->first();
        $initialCount = ActivityLog::count();

        // Act - Create new log manually (simulating controller action)
        ActivityLog::create([
            'user_id' => $superadminUser->id,
            'activity' => 'Super Admin melakukan test action',
            'resource_type' => 'test',
            'resource_id' => 2,
        ]);

        // Assert
        $newCount = ActivityLog::count();
        $this->assertEquals($initialCount + 1, $newCount);
        
        // Verify it appears in the log view
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));
        
        $logs = $response->viewData('logs');
        $this->assertTrue(
            $logs->contains(fn($log) => $log->activity === 'Super Admin melakukan test action')
        );
    }

    /**
     * Test 19: Query whereHas user dengan role filter berfungsi dengan benar
     * 
     * @test
     */
    public function test_query_wherehas_user_dengan_role_filter_berfungsi()
    {
        // Act - Query sama seperti di controller
        $filteredLogs = ActivityLog::with('user')
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['Admin', 'Super Admin']);
            })
            ->latest()
            ->get();

        // Assert
        $this->assertCount(5, $filteredLogs);
        
        foreach ($filteredLogs as $log) {
            $this->assertContains($log->user->role, ['Admin', 'Super Admin']);
        }
    }

    /**
     * Test 20: Response view memiliki data logs yang benar
     * 
     * @test
     */
    public function test_response_view_memiliki_data_logs_yang_benar()
    {
        // Act
        $response = $this->actingAsRole('Super Admin')
            ->get(route('log.index'));

        // Assert
        $response->assertViewIs('log.index');
        $response->assertViewHas('logs');
        
        $logs = $response->viewData('logs');
        $this->assertNotNull($logs);
        $this->assertIsIterable($logs);
        $this->assertGreaterThan(0, $logs->count());
    }
}
