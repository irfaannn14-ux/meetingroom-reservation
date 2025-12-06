# FORCE ENABLE GD EXTENSION - ULTIMATE SOLUTION
# Run as Administrator

Write-Host "=== FORCE ENABLE GD EXTENSION ===" -ForegroundColor Cyan
Write-Host ""

$phpIniPath = "C:\xampp\php\php.ini"

# 1. Check if php.ini exists
if (-not (Test-Path $phpIniPath)) {
    Write-Host "ERROR: php.ini not found at $phpIniPath" -ForegroundColor Red
    exit
}

Write-Host "[1] Backing up php.ini..." -ForegroundColor Yellow
$backup = $phpIniPath + ".backup_" + (Get-Date -Format "yyyyMMdd_HHmmss")
Copy-Item $phpIniPath $backup
Write-Host "    Backup created: $backup" -ForegroundColor Green

# 2. Read php.ini content
Write-Host "[2] Reading php.ini..." -ForegroundColor Yellow
$content = Get-Content $phpIniPath -Raw

# 3. Enable extension_dir if commented
$content = $content -replace ';extension_dir = "ext"', 'extension_dir = "ext"'
$content = $content -replace ';extension_dir="ext"', 'extension_dir="ext"'

# 4. Uncomment GD extension (all possible formats)
$content = $content -replace ';extension=gd', 'extension=gd'
$content = $content -replace ';extension=php_gd', 'extension=php_gd'
$content = $content -replace ';extension=php_gd.dll', 'extension=php_gd.dll'
$content = $content -replace ';extension=php_gd2.dll', 'extension=php_gd2.dll'

# 5. Add GD extensions if not exists
if ($content -notmatch 'extension=gd\b') {
    Write-Host "    Adding extension=gd" -ForegroundColor Yellow
    $content = $content -replace '(;extension=zip)', "extension=gd`r`n`$1"
}

# 6. Write back to php.ini
Write-Host "[3] Writing changes to php.ini..." -ForegroundColor Yellow
Set-Content -Path $phpIniPath -Value $content -NoNewline
Write-Host "    Changes saved" -ForegroundColor Green

# 7. Verify php_gd.dll exists
Write-Host "[4] Checking php_gd.dll..." -ForegroundColor Yellow
$gdDll = "C:\xampp\php\ext\php_gd.dll"
if (Test-Path $gdDll) {
    Write-Host "    php_gd.dll found" -ForegroundColor Green
} else {
    Write-Host "    ERROR: php_gd.dll NOT FOUND!" -ForegroundColor Red
    Write-Host "    Path: $gdDll" -ForegroundColor Red
}

# 8. Stop Apache HARD
Write-Host "[5] Stopping Apache (hard kill)..." -ForegroundColor Yellow
Get-Process -Name "httpd" -ErrorAction SilentlyContinue | Stop-Process -Force
Start-Sleep -Seconds 3
Write-Host "    Apache stopped" -ForegroundColor Green

# 9. Start Apache
Write-Host "[6] Starting Apache..." -ForegroundColor Yellow
Start-Process "C:\xampp\apache\bin\httpd.exe" -WindowStyle Hidden
Start-Sleep -Seconds 8

# Check if started
$apache = Get-Process -Name "httpd" -ErrorAction SilentlyContinue
if ($apache) {
    Write-Host "    Apache started (PID: $($apache[0].Id))" -ForegroundColor Green
} else {
    Write-Host "    ERROR: Apache failed to start!" -ForegroundColor Red
}

# 10. Test GD via web request
Write-Host "[7] Testing GD Extension..." -ForegroundColor Yellow
Start-Sleep -Seconds 3

try {
    $response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/test-gd-check" -Method Get
    Write-Host ""
    Write-Host "=== TEST RESULT ===" -ForegroundColor Cyan
    Write-Host "GD Status: $($response.gd_extension)" -ForegroundColor $(if ($response.gd_extension -like "*AKTIF*") { "Green" } else { "Red" })
    Write-Host "PHP Version: $($response.php_version)" -ForegroundColor Gray
    Write-Host ""
    
    if ($response.gd_extension -like "*AKTIF*") {
        Write-Host "SUCCESS! GD Extension is now ACTIVE!" -ForegroundColor Green
        Write-Host "TTD Digital will now appear in PDF!" -ForegroundColor Green
    } else {
        Write-Host "FAILED! GD Extension is still not active" -ForegroundColor Red
        Write-Host ""
        Write-Host "Manual steps required:" -ForegroundColor Yellow
        Write-Host "1. Open C:\xampp\php\php.ini in Notepad" -ForegroundColor Yellow
        Write-Host "2. Find line: ;extension=gd" -ForegroundColor Yellow
        Write-Host "3. Remove semicolon: extension=gd" -ForegroundColor Yellow
        Write-Host "4. Save file" -ForegroundColor Yellow
        Write-Host "5. Restart Apache from XAMPP Control Panel" -ForegroundColor Yellow
    }
} catch {
    Write-Host "ERROR: Cannot test GD - $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Make sure Laravel dev server is running (php artisan serve)" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== COMPLETED ===" -ForegroundColor Cyan
Write-Host "Backup: $backup" -ForegroundColor Gray
Write-Host ""
