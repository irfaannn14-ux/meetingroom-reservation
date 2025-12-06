<?php
// Test GD Extension untuk Apache/Web
echo "<h2>GD Extension Test</h2>";

if (extension_loaded('gd')) {
    echo "✅ <strong style='color:green;'>GD Extension: AKTIF</strong><br>";
    echo "<p>TTD Digital akan muncul di PDF!</p>";
    
    // Show GD info
    $gd_info = gd_info();
    echo "<h3>GD Information:</h3>";
    echo "<pre>";
    print_r($gd_info);
    echo "</pre>";
} else {
    echo "❌ <strong style='color:red;'>GD Extension: TIDAK AKTIF</strong><br>";
    echo "<p>TTD Digital tidak akan muncul di PDF.</p>";
    echo "<p>Edit file: <code>C:\\xampp\\php\\php.ini</code></p>";
    echo "<p>Cari: <code>;extension=gd</code></p>";
    echo "<p>Ubah menjadi: <code>extension=gd</code></p>";
    echo "<p>Restart Apache di XAMPP Control Panel</p>";
}

echo "<hr>";
echo "<p><a href='/'>← Kembali ke Aplikasi</a></p>";
