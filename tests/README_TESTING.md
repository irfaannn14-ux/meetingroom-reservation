# Testing Guide - Meeting Room Reservation System

## 🗄️ Database Configuration

### **Development Database**
- **Name**: `meetingroomreservation`
- **Used for**: Development, seeding, manual testing
- **Config**: `.env` file

### **Testing Database**
- **Name**: `meetingroomreservation_test`
- **Used for**: Automated testing only
- **Config**: `phpunit.xml` file

---

## ✅ **Why Separate Databases?**

Tests use the `RefreshDatabase` trait which:
1. **Migrates fresh** database before each test
2. **Rolls back** all changes after each test
3. **Ensures clean state** for every test

If we use the same database for testing and development:
- ❌ **All your development data will be deleted** when you run tests
- ❌ Your seeded data (users, ruangan, pengajuan) will disappear
- ❌ You'll need to re-run seeders after every test

With separate databases:
- ✅ **Development data is safe** and never touched by tests
- ✅ Test database is isolated and can be wiped without worry
- ✅ You can run tests anytime without fear

---

## 🚀 **Setup Instructions**

### **1. Create Test Database**

The test database `meetingroomreservation_test` should already be created. If not, run:

```bash
# Option 1: Using PHP
php -r "new PDO('mysql:host=127.0.0.1', 'root', '')->exec('CREATE DATABASE IF NOT EXISTS meetingroomreservation_test');"

# Option 2: Using MySQL CLI
mysql -u root -e "CREATE DATABASE IF NOT EXISTS meetingroomreservation_test;"

# Option 3: Using Laravel Tinker
php artisan tinker --execute="DB::statement('CREATE DATABASE IF NOT EXISTS meetingroomreservation_test');"
```

### **2. Verify Configuration**

Check `phpunit.xml`:
```xml
<env name="DB_DATABASE" value="meetingroomreservation_test"/>
```

Check `.env`:
```env
DB_DATABASE=meetingroomreservation
```

They should be **different**! ✅

---

## 🧪 **Running Tests**

### **Run All Tests**
```bash
php artisan test
```

### **Run Specific Test File**
```bash
php artisan test --filter=NotificationTest
php artisan test tests/Feature/RuanganTest.php
```

### **Run with Coverage**
```bash
php artisan test --coverage
```

### **Run with Verbose Output**
```bash
php artisan test --testdox
```

---

## 📊 **What Happens During Testing**

1. **Before All Tests**:
   - Test database is connected (`meetingroomreservation_test`)
   - Fresh migrations run (creates all tables)

2. **Before Each Test**:
   - Database transaction starts
   - Test-specific data is created

3. **After Each Test**:
   - Transaction is rolled back (data deleted)
   - Database returns to clean state

4. **After All Tests**:
   - Test database remains (but empty)
   - Development database is **untouched** ✅

---

## ⚠️ **Important Notes**

### **DO NOT**:
- ❌ Change `DB_DATABASE` in `phpunit.xml` to `meetingroomreservation`
- ❌ Run tests if you're unsure about database configuration
- ❌ Delete `meetingroomreservation_test` database (it's needed for tests)

### **DO**:
- ✅ Always use separate test database
- ✅ Run `php artisan migrate:fresh --seed` for development database when needed
- ✅ Check database name in `phpunit.xml` before running tests
- ✅ Commit `phpunit.xml` changes to Git

---

## 🔍 **Verify Setup**

Run this command to check which database tests are using:

```bash
php artisan test --filter=test_authenticated_user_dapat_mengakses_api_notifications
```

After test completes:
1. Check your development app (browser)
2. Your seeded data should still be there ✅
3. No data was lost! 🎉

---

## 🛠️ **Troubleshooting**

### **Problem: Tests fail with database connection error**

**Solution**: Make sure test database exists:
```bash
php artisan tinker --execute="DB::statement('CREATE DATABASE IF NOT EXISTS meetingroomreservation_test');"
```

### **Problem: Development data gets deleted when running tests**

**Solution**: Check `phpunit.xml` - make sure it says:
```xml
<env name="DB_DATABASE" value="meetingroomreservation_test"/>
```

NOT:
```xml
<env name="DB_DATABASE" value="meetingroomreservation"/>  <!-- WRONG! -->
```

### **Problem: Test database has old schema**

**Solution**: Tests automatically run fresh migrations. If issues persist:
```bash
# Drop and recreate test database
php artisan tinker --execute="DB::statement('DROP DATABASE IF EXISTS meetingroomreservation_test'); DB::statement('CREATE DATABASE meetingroomreservation_test');"
```

---

## 📈 **Current Test Statistics**

- **Total Tests**: 210
- **Controllers Tested**: 7/7 (100%)
- **Test Files**: 7 files
- **Coverage**: Comprehensive

### **Test Files**:
1. `AuthenticationTest.php` - 27 tests
2. `RuanganTest.php` - 48 tests
3. `PengajuanTest.php` - 35 tests
4. `PresensiTest.php` - 25 tests (23 + 2 skipped)
5. `UserTest.php` - 38 tests
6. `ActivityLogTest.php` - 20 tests
7. `NotificationTest.php` - 18 tests (17 + 1 skipped)

---

## 🎯 **Best Practices**

1. **Always run tests before committing code**
   ```bash
   php artisan test
   ```

2. **Write tests for new features**
   - Follow existing test structure
   - Use RefreshDatabase trait
   - Create helper methods for dummy data

3. **Keep test database separate**
   - Never use development database for testing
   - Document database configuration in README

4. **Maintain test quality**
   - All tests should pass before merging
   - Update tests when modifying features
   - Keep test coverage high (aim for 80%+)

---

## 📚 **Additional Resources**

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- Project Test Design: `tests/TESTCASE_DESIGN.md`

---

**Last Updated**: December 6, 2025
**Status**: ✅ All tests passing (210/210)
