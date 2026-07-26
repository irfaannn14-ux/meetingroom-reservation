@extends('layout.main')

@section('content')
<style>
    /* Styling khusus Rekap Laporan */
    .rekap-container {
        padding: 1.5rem;
    }

    .rekap-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .rekap-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-top: 4px solid var(--secondary-color);
    }

    /* Stat Cards */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 4px solid var(--primary-color);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #e0e7ff;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-info h4 {
        margin: 0;
        font-size: 0.9rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .stat-info p {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
    }

    /* Table Styling */
    .rekap-table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .rekap-table-header {
        background: var(--primary-color);
        color: white;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .table-responsive {
        padding: 0;
    }

    .rekap-table {
        margin-bottom: 0;
    }

    .rekap-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .detail-row {
        display: none;
        background-color: #f8fafc;
    }

    .detail-row.show {
        display: table-row;
    }

    .detail-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        margin: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }

        .rekap-container,
        .rekap-container * {
            visibility: visible;
        }

        .rekap-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0;
        }

        .filter-card,
        .btn-print,
        .btn-toggle-detail {
            display: none !important;
        }

        .detail-row {
            display: table-row !important;
            /* Force show details on print */
        }

        .stat-card {
            border: 1px solid #ddd;
            box-shadow: none;
        }

        .rekap-table-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .rekap-table-header {
            color: black;
            background: #eee !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<div class="main-content">
    <div class="rekap-container">
        <div class="rekap-header">
            <h1 class="rekap-title">
                <i class="bi bi-file-earmark-bar-graph"></i>
                Rekap Penggunaan Ruangan
            </h1>
            <button onclick="window.print()" class="btn btn-primary btn-print">
                <i class="bi bi-printer"></i> Cetak Laporan
            </button>
        </div>

        <!-- Form Filter -->
        <div class="filter-card">
            <form action="{{ route('rekap.index') }}" method="GET" class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted">Tipe Laporan</label>
                    <select name="tipe_filter" id="tipe_filter" class="form-select" onchange="toggleFilterInput()">
                        <option value="harian" {{ $tipeFilter == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="bulanan" {{ $tipeFilter == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="tahunan" {{ $tipeFilter == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>

                <div class="col-md-4" id="input_harian"
                    style="display: {{ $tipeFilter == 'harian' ? 'block' : 'none' }}">
                    <label class="form-label fw-bold text-muted">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>

                <div class="col-md-4" id="input_bulanan"
                    style="display: {{ $tipeFilter == 'bulanan' ? 'block' : 'none' }}">
                    <label class="form-label fw-bold text-muted">Pilih Bulan</label>
                    <input type="month" name="bulan" class="form-control" value="{{ $bulan }}">
                </div>

                <div class="col-md-4" id="input_tahunan"
                    style="display: {{ $tipeFilter == 'tahunan' ? 'block' : 'none' }}">
                    <label class="form-label fw-bold text-muted">Pilih Tahun</label>
                    <select name="tahun" class="form-select">
                        @for($i = date('Y'); $i >= 2023; $i--)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>

        <h4 class="mb-3 text-dark fw-bold">Periode: <span class="text-primary">{{ $periodeTeks }}</span></h4>

        <!-- Ringkasan Statistik -->
        <div class="stat-row">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-building-check"></i></div>
                <div class="stat-info">
                    <h4>Ruangan Terpakai</h4>
                    <p>{{ $totalRuanganAktif }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
                <div class="stat-info">
                    <h4>Total Kegiatan</h4>
                    <p>{{ $totalPenggunaan }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div class="stat-info">
                    <h4>Total Peserta</h4>
                    <p>{{ $totalPeserta }}</p>
                </div>
            </div>
        </div>

        <!-- Tabel Rekap Per Ruangan -->
        @forelse($rekapPerRuangan as $ruanganId => $data)
            <div class="rekap-table-card">
                <div class="rekap-table-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-building me-2"></i> {{ $data['nama_ruangan'] }}
                    </div>
                    <div class="badge bg-light text-primary">
                        {{ $data['jumlah_kegiatan'] }} Kegiatan | {{ $data['total_peserta'] }} Peserta
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table rekap-table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Judul Kegiatan</th>
                                <th style="width: 20%">Pengaju (OPD)</th>
                                <th style="width: 25%">Waktu Pelaksanaan</th>
                                <th style="width: 15%">Peserta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['kegiatan'] as $kegiatan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-bold">{{ $kegiatan->judul_kegiatan }}</td>
                                    <td>{{ $kegiatan->nama_pengaju ?? ($kegiatan->user->nama ?? '-') }}</td>
                                    <td>
                                        <div class="small text-muted mb-1"><i class="bi bi-calendar"></i>
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('d M Y') }} -
                                            {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('d M Y') }}</div>
                                        <div class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-clock"></i>
                                            {{ \Carbon\Carbon::parse($kegiatan->jam_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($kegiatan->jam_selesai)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td><i class="bi bi-person me-1"></i> {{ $kegiatan->jml_peserta }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div>
                    <strong>Tidak ada data penggunaan ruangan!</strong><br>
                    Tidak ada kegiatan yang disetujui pada periode yang Anda pilih.
                </div>
            </div>
        @endforelse

    </div>
</div>

<script>
    function toggleFilterInput() {
        const tipe = document.getElementById('tipe_filter').value;
        document.getElementById('input_harian').style.display = 'none';
        document.getElementById('input_bulanan').style.display = 'none';
        document.getElementById('input_tahunan').style.display = 'none';

        if (tipe === 'harian') {
            document.getElementById('input_harian').style.display = 'block';
        } else if (tipe === 'bulanan') {
            document.getElementById('input_bulanan').style.display = 'block';
        } else if (tipe === 'tahunan') {
            document.getElementById('input_tahunan').style.display = 'block';
        }
    }
</script>
@endsection