<?php ?>
@extends('layout.main')
@section('title', 'Pengajuan')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #C9DFF2;
        margin: 0;
        padding: 0;
    }
    .main-content {
        margin-left: 220px;
        padding: 2rem;
        min-height: 100vh;
        background-color: #C9DFF2;
        margin-top: 60px;
    }
    .content {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }
    .ruangan-table-container {
        background: #fff;
        padding: 2.5rem 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-top: 1.5rem;
    }
    .dashboard-title {
        margin-top: 0;
        margin-bottom: 1.2rem;
        font-size: 2rem;
        font-weight: 700;
        color: #010D26;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
        background: #fff;
    }
    th, td {
        padding: 0.85rem 1rem;
        text-align: center;
    }
    th {
        background-color: #C9DFF2;
        color: #010D26;
        font-weight: 700;
        border-bottom: 2px solid #B0C4DE;
    }
    tr {
        border-bottom: 1px solid #e0e0e0;
    }
    tr:last-child {
        border-bottom: none;
    }
    td {
        color: #222;
    }
    .btn-action {
        margin: 0 2px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 0.75rem 1.25rem;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
    @media (max-width: 900px) {
        .main-content {
            margin-left: 0;
            padding: 1rem;
        }
        .content {
            max-width: 100%;
        }
        .ruangan-table-container {
            padding: 1rem 0.5rem;
            margin-top: 1rem;
        }
        table, th, td {
            font-size: 13px;
        }
        .dashboard-title {
            margin-bottom: 0.8rem;
        }
    }
</style>

<div class="main-content">
    <div class="content">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
            <h1 class="dashboard-title">List Data Pengajuan</h1>
            <a href="/pengajuan/tambah" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Buat Pengajuan
            </a>
        </div>

        <div class="ruangan-table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pengaju</th>
                        <th>Kegiatan</th>
                        <th>Ruangan</th>
                        <th>Waktu Pinjam</th>
                        <th>Waktu Kembali</th>
                        <th>Jumlah Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Dinas Kominfo</td>
                        <td>Work Shop</td>
                        <td>Jabung 1</td>
                        <td>2024-10-26 09:00:00</td>
                        <td>2024-10-26 15:00:00</td>
                        <td>50</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-success btn-sm btn-action nav-icon bi bi-pencil-square" title="Edit"></a>
                                <form action="" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-action bi bi-trash" onclick="return confirm('Yakin ingin menghapus data?')" title="Hapus"></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
