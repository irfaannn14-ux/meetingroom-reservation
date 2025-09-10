@extends('layout.main')
@section('title', 'Ruangan')
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
            /* margin-top: 4rem; */
            margin-top: 1.5rem; /* Jarak antara H1 dan tabel diperkecil */
        }
        .dashboard-title {
            margin-top: 0;
            margin-bottom: 1.2rem; /* Jarak bawah H1 diperkecil */
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
                margin-top: 1rem; /* Responsive: jarak tabel juga diperkecil di mobile */
            }
            table, th, td {
                font-size: 13px;
            }
            .dashboard-title {
                margin-bottom: 0.8rem;
            }
        }
    </style>

    @include('sidebar.sidebar')
    @include('navbar.navbar')

    <div class="main-content">
        <div class="content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
                <h1 class="dashboard-title">List Ruangan</h1>
                <a href="/ruangan/tambah" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill"></i> Tambah Ruangan
                </a>
            </div>
            <div class="ruangan-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>Jumlah Peserta</th>
                            <th>Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <a href="#" class="btn btn-success btn-sm btn-action nav-icon bi bi-pencil-square" title="Edit"></a>
                                <form action="" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-action bi bi-trash" onclick="return confirm('Yakin ingin menghapus data?')" title="Hapus"></button>
                                </form>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
