@extends('layout.main')

@section('title', 'History Pengajuan')
@section('content')
    @php use Illuminate\Support\Facades\Storage; @endphp

    <style>
        .main-content { padding: 2rem; min-height: 100vh; margin-top: 60px; }
        .content { width: 100%; max-width: 2000px; margin: 0 auto; }
        .history-table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            padding: 2.5rem 2rem; border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            margin-top: 1.5rem;
        }
        .dashboard-title {
            margin-top: 0; margin-bottom: 1.2rem; font-size: 2rem; font-weight: 700;
            color: #ffffff !important; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }
        table { width: 100%; border-collapse: collapse; font-size: 15px; background: #fff; }
        th, td { padding: 0.85rem 1rem; text-align: center; vertical-align: middle; }
        th {
            background-color: #010D26 !important; color: #ffffff !important;
            font-weight: 700; border-bottom: 2px solid rgba(255,255,255,0.06);
        }
        tr { border-bottom: 1px solid #e0e0e0; }
        tr:last-child { border-bottom: none; }
        td { color: #222; }
        .btn-darkback { background:#010D26; color:#fff; border:none; padding:.5rem 1rem; border-radius:8px; text-decoration:none; }
        .btn-darkback:hover { opacity:.9; color:#fff; }
        .file-link { word-break: break-all; }
    </style>

    <div class="main-content">
        <div class="content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
                <h1 class="dashboard-title">Detail Presensi</h1>
                <div>
                    <a href="{{ route('history') }}" class="btn-darkback">Kembali ke History</a>
                </div>
            </div>

            <div class="history-table-container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Organisasi</th>
                            <th>Waktu Presensi</th>
                            <th>TTD Digital</th>
                        </tr>
                    </thead>
                    <tbody>
  @forelse($presensis as $presensi)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $presensi->nama }}</td>
      <td>{{ $presensi->jabatan }}</td>
      <td>{{ $presensi->organisasi_nama ?? $presensi->organisasi }}</td>
      <td>{{ optional($presensi->created_at)->format('d-m-Y H:i') }} WIB</td>
      <td>
  @if($presensi->ttd_path)
    <a href="{{ route('presensi.ttd', $presensi) }}"
       class="btn btn-sm btn-primary"
       target="_blank" rel="noopener">
      Lihat / Unduh
    </a>
  @else
    <button class="btn btn-sm btn-secondary" disabled>TTD tidak ada</button>
  @endif
</td>

    </tr>
  @empty
    <tr>
      <td colspan="7" class="text-center py-4">Belum ada data presensi untuk pengajuan ID: {{ $pengajuanId }}.</td>
    </tr>
  @endforelse
</tbody>

                </table>

                {{-- kalau pakai paginate: --}}
                {{-- <div class="mt-3">{{ $presensis->links() }}</div> --}}
            </div>
        </div>
    </div>
@endsection
