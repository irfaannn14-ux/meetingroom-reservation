<?php ?>
@extends('layout.main')
@section('title', 'Pengajuan')
@section('content')
<style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }
        .main-content { padding: 80px 20px 20px; }
        .dashboard-title {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }
        .welcome-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="main-content">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
            <h1 class="dashboard-title">List Data Pengajuan</h1>
            <div class="d-flex gap-2">
                <a href="/pengajuan" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill"></i> Buat Pengajuan
                </a>
                <a href="/ruangan/tambah" class="btn btn-success">
                    <i class="bi bi-plus-square"></i> Tambah Ruangan
                </a>
            </div>
        </div>

    <div class="welcome-card">
        <table id="myTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Pengajuan</th>
                    <th>Kegiatan</th>
                    <th>Ruangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                    <tr>
                        <td>1</td>
                        <td>Dinas Kominfo</td>
                        <td>Work Shop</td>
                        <td>Jabung 1</td>
                        <td>diproses</td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Tombol Lihat -->
                                <button class="btn btn-sm btn-outline-primary rounded-15px" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <!-- Tombol Edit/Dokumen -->
                                <button class="btn btn-sm btn-outline-secondary rounded-15px" title="Edit">
                                    <i class="bi bi-file-earmark-text"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
