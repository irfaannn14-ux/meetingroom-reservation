<?php ?>
@extends('layout.main')
@section('title', 'Form Ruangan')
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
            <h4 class="text-left mb-4 fw-bold">Form Ruangan</h4>

            <form action="{{ route('ruangan.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col">
                        <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="jml_peserta" class="form-label">Jumlah Peserta</label>
                        <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="fasilitas" class="form-label">Fasilitas</label>
                    <textarea name="fasilitas" id="fasilitas" class="form-control" rows="2" required></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/ruangan" class="btn btn-outline-dark px-4">Cancel</a>
                    <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
