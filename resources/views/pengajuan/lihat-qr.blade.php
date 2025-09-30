<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat QR</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h2 {
            color: #010D26;
            margin-bottom: 20px;
        }
        .qr-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            text-align: center;
        }
        .qr-box img {
            max-width: 300px;
            height: auto;
            border-radius: 8px;
        }
        .note {
            margin-top: 15px;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
    <script>
        // Auto-refresh QR setiap 30 detik
        setInterval(() => {
            const img = document.getElementById('barcode');
            img.src = img.dataset.src + "?rand=" + new Date().getTime();
        }, 30000);
    </script>
</head>
<body>
    <div class="qr-box">
        <h2>QR Code Pengajuan</h2>
        <img id="barcode"
             data-src="{{ url('/pengajuan/' . $pengajuan->id . '/qrcode') }}"
             src="{{ url('/pengajuan/' . $pengajuan->id . '/qrcode') }}"
             alt="QR Code">
        <div class="note">
            Halaman ini akan otomatis memperbarui QR setiap 30 detik.
        </div>
    </div>
</body>
</html>
