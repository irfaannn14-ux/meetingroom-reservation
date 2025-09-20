@extends('layout.main')

@section('title', 'History Pengajuan')
@section('content')
<style>
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #C9DFF2;
        margin: 0;
        padding: 0;
    }
    .main-content {
        padding: 2rem;
        min-height: 100vh;
        background-color: #C9DFF2;
        margin-top: 60px;
    }
    .content {
        width: 100%;
        max-width: 2000px;
        margin: 0 auto;
    }
    .history-table-container {
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
        vertical-align: middle;
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
    .status-badge {
        color: #fff;
        padding: 0.35em 1.1em;
        border-radius: 16px;
        font-weight: 600;
        font-size: 0.9em;
        display: inline-block;
    }
    .status-disetujui { background: #28a745; }
    .status-ditolak { background: #dc3545; }

    /* Styles for the new search bar */
    .search-container {
        position: relative;
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    #searchInput {
        padding-left: 40px;
        border-radius: 8px;
        height: 44px;
        width: 320px;
        border: 1px solid #ced4da;
    }
</style>

<div class="main-content">
    <div class="content">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
            <h1 class="dashboard-title">History Pengajuan</h1>
            <div class="search-container">
                <i class="bi bi-search search-icon"></i>
                <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari pengaju, kegiatan, ruangan...">
            </div>
        </div>

        <div class="history-table-container">
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
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    @forelse($pengajuans as $pengajuan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pengajuan->nama_pengaju }}</td>
                            <td>{{ $pengajuan->judul_kegiatan }}</td>
                            <td>{{ $pengajuan->ruangan->nama_ruangan ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d-m-Y H:i') }} WIB</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d-m-Y H:i') }} WIB</td>
                            <td>{{ $pengajuan->jml_peserta }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($pengajuan->status) }}">
                                    {{ ucfirst($pengajuan->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Belum ada history pengajuan.</td>
                        </tr>
                    @endforelse
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="8" class="text-center py-4">Tidak ada riwayat yang cocok dengan pencarian Anda.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const tableBody = document.getElementById("historyTableBody");
        const rows = tableBody.getElementsByTagName("tr");
        let visibleRows = 0;

        for (let i = 0; i < rows.length; i++) {
            if (rows[i].id === 'noResultsRow' || rows[i].querySelector('td[colspan="8"]')) {
                continue;
            }

            const pengajuCell = rows[i].getElementsByTagName("td")[1];
            const kegiatanCell = rows[i].getElementsByTagName("td")[2];
            const ruanganCell = rows[i].getElementsByTagName("td")[3];

            if (pengajuCell && kegiatanCell && ruanganCell) {
                const rowText = (pengajuCell.textContent || pengajuCell.innerText) +
                              (kegiatanCell.textContent || kegiatanCell.innerText) +
                              (ruanganCell.textContent || ruanganCell.innerText);
                
                if (rowText.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                    visibleRows++;
                } else {
                    rows[i].style.display = "none";
                }
            }       
        }

        const noResultsRow = document.getElementById('noResultsRow');
        if (visibleRows === 0 && !tableBody.querySelector('td[colspan="8"]')) {
            noResultsRow.style.display = "table-row";
        } else {
            noResultsRow.style.display = "none";
        }
    }
</script>
@endsection

