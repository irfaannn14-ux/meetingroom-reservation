<?php ?>
@extends('layout.main')
@section('title', 'Form Pengajuan')
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

            <form action="{{ route('pengajuan.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col">
                    <label for="user_id" class="form-label">Nama</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- Pilih User --</option>
                        @foreach($user as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_opd }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="nama_opd" id="nama_opd" class="form-control" hidden required>

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
                    <label for="ruangan_id" class="form-label">Ruangan</label>
                    <select name="ruangan_id" id="ruangan_id" class="form-control" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="jml_peserta" class="form-label">Jumlah Peserta</label>
                    <input type="number" name="jml_peserta" id="jml_peserta" class="form-control" required>
                </div>
            </div>

            <div class="col mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
            </div>



            <div class="row mb-4">
                <div class="col">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                </div>
                <div class="col">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-dark px-4">Cancel</a>
                <button type="submit" class="btn btn-dark px-4"><i class="bi bi-floppy"></i>Submit</button>
            </div>
        </form>

        </div>
    </div>
    </div>


<script>
    document.getElementById('user_id').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let namaApd = selectedOption.getAttribute('data-nama');
        document.getElementById('nama_opd').value = namaApd;
    });
</script>

@endsection
