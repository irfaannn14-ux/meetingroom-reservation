# Authentication Test - Penjelasan

## Perubahan yang Dilakukan

### 1. Menggunakan Dummy Data (Bukan Data Real dari Database)

Sebelumnya, test menggunakan akun yang sudah ada di database:
- `A@Gmail.com` - User Regular
- `B@Gmail.com` - Admin  
- `superadmin@example.com` - Superadmin

**Masalah**: Test bergantung pada data yang sudah ada di database, sehingga jika data berubah atau terhapus, test akan gagal.

### 2. Solusi: RefreshDatabase + Dummy Users

Sekarang test menggunakan trait `RefreshDatabase` dari Laravel yang:
- ✅ Membuat database fresh sebelum setiap test
- ✅ Menjalankan migrasi otomatis
- ✅ Membuat dummy users di method `setUp()`
- ✅ **Menghapus semua data setelah test selesai**
- ✅ Database kembali bersih, tidak ada data test yang tersimpan

### 3. Dummy Users yang Dibuat

Setiap kali test dijalankan, 3 akun dummy dibuat otomatis:

#### User Regular (OPD)
```php
Email: testuser@example.com
Password: password123
Role: OPD
```

#### Admin
```php
Email: testadmin@example.com
Password: password123
Role: Admin
```

#### Superadmin
```php
Email: testsuperadmin@example.com
Password: password123
Role: Super Admin
```

### 4. Alur Test

```
1. Test dimulai
   ↓
2. RefreshDatabase membersihkan database
   ↓
3. Migrasi dijalankan
   ↓
4. setUp() membuat 3 dummy users
   ↓
5. Test dijalankan (27 test cases)
   ↓
6. RefreshDatabase menghapus semua data
   ↓
7. Database kembali bersih
```

### 5. Keuntungan Pendekatan Ini

✅ **Isolated Testing**: Setiap test berjalan di lingkungan yang bersih dan terisolasi

✅ **Tidak Bergantung pada Data Real**: Test tidak akan gagal jika data di database production berubah

✅ **Clean Database**: Tidak ada data test yang tertinggal di database

✅ **Consistent Results**: Test akan selalu memberikan hasil yang konsisten

✅ **Safe**: Data production tidak terpengaruh oleh test

### 6. Hasil Test

```
Tests:    27 passed (90 assertions)
Duration: 4.05s

✓ All authentication scenarios tested
✓ All roles tested (User, Admin, Superadmin)
✓ No data left in database after tests
```

### 7. Verifikasi Database Bersih

Setelah test selesai, database dikonfirmasi bersih:
```
Users with test emails: 0
```

Tidak ada dummy users yang tersimpan di database.

## Cara Menjalankan Test

```bash
# Run semua authentication tests
php artisan test tests/Feature/AuthenticationTest.php

# Run specific test
php artisan test --filter test_user_dapat_mengakses_halaman_login

# Run dengan output verbose
php artisan test tests/Feature/AuthenticationTest.php --verbose
```

## Catatan Penting

- **RefreshDatabase** hanya bekerja di testing environment (DB_CONNECTION=mysql dengan database meetingroom_test)
- Password dummy users di-hash menggunakan `Hash::make()`
- Semua dummy users dibuat dengan `organization_id = null` karena field tersebut nullable
- Test menggunakan **data provider** untuk testing multiple roles secara DRY (Don't Repeat Yourself)

## File yang Dimodifikasi

- `tests/Feature/AuthenticationTest.php` - Test file utama dengan RefreshDatabase trait
- Email credentials diganti dari real accounts ke dummy accounts
- Semua password disamakan menjadi `password123` untuk konsistensi
