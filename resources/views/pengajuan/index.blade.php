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
            <a href="{{route('pengajuan.tambah')}}" class="btn btn-primary" ><i class="bi bi-person-plus-fill"></i>Buat Pengajuan</a>
        </div>

    <div class="welcome-card">
        <table id="myTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kegiatan</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($all as $key => $item)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$item->nama_apd}}</td>
                        <td>{{$item->kegiatan}}</td>
                        <td>{{$item->ruangan}}</td>
                        <td>{{$item->tanggal}}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">Detail</a>
                            <form action="#" method="POST" style="display:inline">
                                @csrf
                                <input type="text" name="komentar" placeholder="Komentar">
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <a href="#" class="btn btn-warning btn-sm">Edit</a>
                            <form action="#" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
