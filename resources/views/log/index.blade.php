@extends('layout.main')
@section('title', 'Log Aktivitas')
@section('content')
<style>
    .main-content {
        padding: 80px 20px 20px;
        background-color: #C9DFF2;
        min-height: 100vh;
    }
    .page-title {
        font-size: 2rem;
        font-weight: bold;
        color: #010D26;
        margin-bottom: 20px;
    }
    .table-container {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }
    th, td {
        padding: 0.85rem 1rem;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e0e0e0;
    }
    th {
        background-color: #C9DFF2;
        color: #010D26;
        font-weight: 700;
    }
    tr:last-child td {
        border-bottom: none;
    }
</style>

<div class="main-content">
    <h1 class="page-title">Log Aktivitas</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Role Pengguna</th>
                    <th>Aktivitas</th>
                    <th>Waktu Kejadian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->user->nama ?? 'N/A' }}</td>
                    <td>{{ $log->user->role ?? 'N/A' }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada aktivitas yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
