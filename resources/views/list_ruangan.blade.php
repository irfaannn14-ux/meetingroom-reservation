<?php?>
@extends('layout.main')
@section('content')
    <div class="main-content">
        <h1 class="dashboard-title">List Ruangan</h1>

        <div class="welcome-card d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <table id="myTable" class="table table-stripped">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama Ruangan</th>
                        <th>Jumlah Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all as $item )
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->nama_ruangan}}</td>
                        <td>{{$item->jml_peserta}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
