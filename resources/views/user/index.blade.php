<?php ?>
@extends('layout.main')
@section('title', 'User')
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
            <h1 class="dashboard-title">User</h1>
            <a href="{{ route('user.tambah') }}" class="btn btn-primary" ><i class="bi bi-person-plus-fill"></i> Tambah User</a>
        </div>
        @if (session('success'))
            <div class="alern alert-success">
                {{session('success')}}
            </div>
        @endif
        <div class="welcome-card">
            <table id="myTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama </th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Admin</th>
                        <th>Super Admin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->nama_apd}}</td>
                            <td>{{$item->username}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->admin}}</td>
                            <td>{{$item->superadmin}}</td>
                            <td>
                                {{-- nim ada primary key --}}
                                <a href="{{route('user.edit',['id' => $item->id])}}" class="btn btn-success btn-sm nav-icon bi bi-pencil-square"></a>
                                <form action="{{route('user.destroy', ['id' => $item->id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm bi bi-trash" onclick="return confirm('Yakin ingin menghapus data?')"></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
