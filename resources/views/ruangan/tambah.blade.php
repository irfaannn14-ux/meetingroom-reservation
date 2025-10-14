<?php
// Logika untuk menentukan apakah ini form edit atau tambah
$isEdit = isset($ruangan);
?>
@extends('layout.main')
@section('title', $isEdit ? 'Edit Ruangan' : 'Tambah Ruangan')
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
            <h4 class="text-left mb-4 fw-bold">{{ $isEdit ? 'Edit Ruangan' : 'Tambah Ruangan' }}</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $isEdit ? route('ruangan.update', $ruangan) : route('ruangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($isEdit)
                    @method('PUT') {{-- Tambahkan method PUT untuk update --}}
                @endif

                <div class="row mb-3">
                    <div class="col">
                        <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-control" value="{{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') }}" required>
                    </div>
                    <div class="col">
                        <label for="jml_peserta" class="form-label">Jumlah Maksimal Peserta</label>
                        <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" value="{{ old('jml_peserta', $ruangan->jml_peserta ?? '') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="foto_ruangan" class="form-label">Foto Ruangan</label>
                    <input type="file" name="foto_ruangan" id="foto_ruangan" class="form-control" accept="image/*" {{ $isEdit ? '' : 'required' }}>
                    @if($isEdit && $ruangan->foto_ruangan)
                        <div class="mt-2">
                            <small class="text-muted">Foto saat ini:</small><br>
                            <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}" alt="Foto Ruangan" class="img-thumbnail" style="width: 200px;">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="fasilitas" class="form-label">Fasilitas</label>
                    <textarea name="fasilitas" id="fasilitas" class="form-control" rows="2" required>{{ old('fasilitas', $ruangan->fasilitas ?? '') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('ruangan.index') }}" class="btn btn-outline-dark px-4">Cancel</a>
                    <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>{{ $isEdit ? 'Update' : 'Submit' }}</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
