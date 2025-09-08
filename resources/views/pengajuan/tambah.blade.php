<?php ?>
@extends('layout.main')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
    .main-content { padding: 80px 20px 20px; }
    .btn i {
        margin-right: 6px;      /* jarak antara icon & teks */
        font-size: 13px;        /* default ukuran icon */
        vertical-align: middle; /* biar sejajar dengan teks */
    }
</style>
    <div class="main-content">
        <div class="container d-flex justify-content-center mt-4">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%; border-radius: 12px;">
            <h4 class="text-left mb-4 fw-bold">Form Pengajuan</h4>

            <form action="#" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" id="whatsapp" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kegiatan" class="form-label">Kegiatan</label>
                    <textarea name="kegiatan" id="kegiatan" class="form-control" rows="2" required></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="ruangan" class="form-label">Ruangan</label>
                        <input type="text" name="ruangan" id="ruangan" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                        <input type="number" name="jumlah_peserta" id="jumlah_peserta" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir</label>
                        <input type="date" name="tanggal_berakhir" id="tanggal_berakhir" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="jam_berakhir" class="form-label">Jam Berakhir</label>
                        <input type="time" name="jam_berakhir" id="jam_berakhir" class="form-control" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="#" class="btn btn-outline-dark px-4">Cancel</a>
                    <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
