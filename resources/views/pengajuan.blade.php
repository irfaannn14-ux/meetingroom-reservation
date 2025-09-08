<?php ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan</title>

    <style>
    body {
    font-family: 'Montserrat', sans-serif;
    background-color: #C9DFF2;
    margin: 0;
    padding: 0;
    }

    .main-content {
        margin-left: 220px; 
        padding: 2rem;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start; 
    }

    .content {
        width: 100%;
        max-width: 800px;
    }

    .form-container {
        background: #fff;
        padding: 3rem 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        width: 90%;
        max-width: 500px;
        margin: 5rem auto;
        min-height: 700px;  
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 1.5rem;
    }

    .form-group.full {
        grid-column: span 2; 
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 0.6rem;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 100%;
        font-size: 14px;
        font-weight: 400 !important;        
        font-family: 'Montserrat', sans-serif !important; 
        color: #000000ff;                       
        -webkit-font-smoothing: antialiased; 
        -moz-osx-font-smoothing: grayscale;  
    }

    .form-actions {
        display: flex;
        justify-content: center;
        gap: 1.2rem;
        margin-top: 5rem;
    }

    .btn-cancel {
        background: #fff;
        border: 1px solid #000;
        padding: 0.6rem 1.5rem;  /* ✅ diperbaiki */
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-submit {
        background: #010D26;
        color: #fff;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }


    </style>
</head>
<body>
    @include('sidebar.sidebar')
    @include('navbar.navbar')

    <div class="main-content">
        <div class="content">
            <form action="#" method="POST" class="form-container">
                @csrf
                <h1 style="margin-top:0; margin-bottom:3rem;">Form Pengajuan</h1>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="whatsapp">WhatsApp</label>
                        <input type="text" id="whatsapp" name="whatsapp" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group full">
                        <label for="kegiatan">Kegiatan</label>
                        <textarea id="kegiatan" name="kegiatan" rows="3" required></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ruangan">Ruangan</label>
                        <select id="ruangan" name="ruangan" required>
                            <option value="">-- Pilih Ruangan --</option>
                            <option value="Aula">Aula</option>
                            <option value="Ruang Rapat">Ruang Rapat</option>
                            <option value="Meeting 1">Meeting 1</option>
                            <option value="Meeting 2">Meeting 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_peserta">Jumlah Peserta</label>
                        <input type="number" id="jumlah_peserta" name="jumlah_peserta" required>
                    </div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_berakhir">Tanggal Berakhir</label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" required>
                    </div>
                    <div class="form-row">
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="jam_berakhir">Jam Berakhir</label>
                        <input type="time" id="jam_berakhir" name="jam_berakhir" required>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="form-actions">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


