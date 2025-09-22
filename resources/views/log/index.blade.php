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
        background-color: #010D26 !important;
        color: #ffffff !important;
        font-weight: 700;
    }
    tr:last-child td {
        border-bottom: none;
    }
    .btn-log {
        border: none;
        padding: 6px 18px;
        border-radius: 5px;
        font-weight: 600;
        cursor: pointer;
        color: #fff;
    }
    .btn-approve-deny { background: #000; }
    .btn-edit { background: #FFD600; color: #222; }
    .btn-add { background: #2196F3; }
    /* Standardized search styles (matching other index pages) */
    .search-container {
        position: relative;
        margin-bottom: 18px;
        max-width: 350px;
    }
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }
    #searchInput {
        padding-left: 40px;
        border-radius: 8px;
        height: 40px;
        width: 100%;
        border: 1px solid #ced4da;
        font-size: 15px;
    }
    /* Modal styles */
    .modal-log {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0; top: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.3);
        justify-content: center;
        align-items: center;
    }
    .modal-content-log {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        min-width: 350px;
        max-width: 500px;
        position: relative;
    }
    .close-modal-log {
        position: absolute;
        top: 12px;
        right: 18px;
        font-size: 1.5rem;
        cursor: pointer;
        color: #222;
        border: none;
        background: none;
    }
    .compare-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .compare-table th, .compare-table td {
        border: 1px solid #e0e0e0;
        padding: 6px 10px;
        text-align: left;
    }
    .compare-table th {
        background: #f5f5f5;
    }
</style>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title">Log Aktivitas</h1>
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari: Nama Pengguna, Role, Aktivitas, Waktu, ..." style="width:280px;">
        </div>
    </div>

    <div class="table-container">
        <table id="logTable">
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
                    <td>
                        @php
                            $activity = strtolower($log->activity);
                            $btnClass = '';
                            $btnLabel = '';
                            // map activity keywords to button styles
                            // approve/deny (Indonesian + English)
                            if(str_contains($activity, 'deny') || str_contains($activity, 'approve') || str_contains($activity, 'menyetujui') || str_contains($activity, 'disetujui') || str_contains($activity, 'menolak') || str_contains($activity, 'ditolak')) {
                                $btnClass = 'btn-approve-deny';
                                $btnLabel = 'Deny/Approve Pengajuan';
                            } elseif(str_contains($activity, 'edit pengajuan') || str_contains($activity, 'mengedit') || str_contains($activity, 'mengubah') || str_contains($activity, 'mengupdate') || str_contains($activity, 'edit')) {
                                $btnClass = 'btn-edit';
                                $btnLabel = 'Edit Pengajuan';
                            } elseif(str_contains($activity, 'edit ruangan')) {
                                $btnClass = 'btn-edit';
                                $btnLabel = 'Edit Ruangan';
                            } elseif(str_contains($activity, 'add user') || str_contains($activity, 'menambah') || str_contains($activity, 'menambahkan') || str_contains($activity, 'menambahkan pengguna') || str_contains($activity, 'menambahkan ruangan') || str_contains($activity, 'menambahkan pengguna baru') || str_contains($activity, 'menambahkan ruangan baru') || str_contains($activity, 'add')) {
                                $btnClass = 'btn-add';
                                $btnLabel = 'Tambah Data';
                            } elseif(str_contains($activity, 'edit user')) {
                                $btnClass = 'btn-edit';
                                $btnLabel = 'Edit User';
                            } else {
                                $btnClass = 'btn-log';
                                $btnLabel = $log->activity;
                            }
                        @endphp
                        @php
                            $resource = $log->resource();
                            $resourceType = $log->resource_type;
                        @endphp
                        <button 
                            class="btn-log {{ $btnClass }} log-modal-btn" 
                            data-log-id="{{ $log->id }}"
                            data-log='@json($log)'
                            data-resource='@json($resource)'
                            data-resource-type='{{ $resourceType }}'
                            data-bs-toggle="modal"
                            data-bs-target="#modalLogBootstrap"
                        >{{ $btnLabel }}</button>
                    </td>
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

<!-- Bootstrap Modal -->
<div class="modal fade" id="modalLogBootstrap" tabindex="-1" aria-labelledby="modalLogLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLogLabel">Detail Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBodyLog">
                <!-- populated by JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Standardized filter function (compatible with other index pages)
    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#logTable tbody tr');
        let visible = 0;
        rows.forEach(row => {
            if(row.querySelector('td[colspan]')) return; // skip no-data rows
            const text = row.textContent.toLowerCase();
            if(text.indexOf(filter) > -1) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });
        const noResults = document.getElementById('noResultsRowLog');
        if(!noResults) {
            const tr = document.createElement('tr');
            tr.id = 'noResultsRowLog';
            tr.innerHTML = '<td colspan="5" class="text-center py-4">Tidak ada aktivitas yang cocok dengan pencarian Anda.</td>';
            document.querySelector('#logTable tbody').appendChild(tr);
        }
        document.getElementById('noResultsRowLog').style.display = visible === 0 ? 'table-row' : 'none';
    }

    // Populate Bootstrap modal when it's shown
    var modalLog = document.getElementById('modalLogBootstrap');
    modalLog.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var raw = button.getAttribute('data-log') || button.dataset.log || null;
        var resRaw = button.getAttribute('data-resource') || button.dataset.resource || null;
        var resourceType = button.getAttribute('data-resource-type') || button.dataset.resourceType || null;
        var log = null;
        var resource = null;
        try { log = JSON.parse(raw); } catch (err) { log = null; }
        try { resource = JSON.parse(resRaw); } catch (err) { resource = null; }

        var modalBody = document.getElementById('modalBodyLog');
        var html = '';

        // If resource object provided, render resource details depending on type
        if(resource && resourceType) {
            if(resourceType === 'pengajuan') {
                html += '<h5>Detail Pengajuan</h5>';
                html += '<table class="table table-sm"><tbody>';
                html += '<tr><th>Judul Kegiatan</th><td>' + (resource.judul_kegiatan || '-') + '</td></tr>';
                html += '<tr><th>Nama Pengaju</th><td>' + (resource.nama_pengaju || (resource.user && resource.user.nama) || '-') + '</td></tr>';
                html += '<tr><th>Ruangan</th><td>' + (resource.ruangan && resource.ruangan.nama_ruangan ? resource.ruangan.nama_ruangan : '-') + '</td></tr>';
                html += '<tr><th>Tanggal Mulai</th><td>' + (resource.tanggal_mulai || '-') + '</td></tr>';
                html += '<tr><th>Tanggal Selesai</th><td>' + (resource.tanggal_selesai || '-') + '</td></tr>';
                html += '<tr><th>Jumlah Peserta</th><td>' + (resource.jml_peserta || '-') + '</td></tr>';
                html += '<tr><th>Status</th><td>' + (resource.status || '-') + '</td></tr>';
                html += '</tbody></table>';
            } else if(resourceType === 'ruangan') {
                html += '<h5>Detail Ruangan</h5>';
                html += '<table class="table table-sm"><tbody>';
                html += '<tr><th>Nama Ruangan</th><td>' + (resource.nama_ruangan || '-') + '</td></tr>';
                html += '<tr><th>Kapasitas</th><td>' + (resource.jml_peserta || '-') + ' orang</td></tr>';
                html += '<tr><th>Fasilitas</th><td>' + (resource.fasilitas || '-') + '</td></tr>';
                if(resource.foto_ruangan) {
                    html += '<tr><th>Foto</th><td><img src="/storage/' + resource.foto_ruangan + '" style="max-width:100%;height:auto;border-radius:6px;"></td></tr>';
                }
                html += '</tbody></table>';
            } else if(resourceType === 'user') {
                html += '<h5>Detail Pengguna</h5>';
                html += '<table class="table table-sm"><tbody>';
                html += '<tr><th>Nama</th><td>' + (resource.nama || '-') + '</td></tr>';
                html += '<tr><th>Email</th><td>' + (resource.email || '-') + '</td></tr>';
                html += '<tr><th>Username</th><td>' + (resource.username || '-') + '</td></tr>';
                html += '<tr><th>Role</th><td>' + (resource.role || '-') + '</td></tr>';
                html += '<tr><th>No. WhatsApp</th><td>' + (resource.no_wa || '-') + '</td></tr>';
                if(resource.foto_profil) {
                    html += '<tr><th>Foto Profil</th><td><img src="/storage/' + resource.foto_profil + '" style="max-width:120px;height:auto;border-radius:6px;"></td></tr>';
                }
                html += '</tbody></table>';
            }
        } else if(log) {
            // fallback previous behavior: render old/new data or details based on activity
            var activity = (log.activity || '').toLowerCase();
            if(activity.includes('deny') || activity.includes('approve')) {
                html += '<h5>Detail Pengajuan</h5>';
                if(log.details) {
                    try { html += '<pre>' + JSON.stringify(log.details, null, 2) + '</pre>'; } catch(e) { html += '<div>Tidak ada detail pengajuan.</div>'; }
                } else {
                    html += '<div>Tidak ada detail pengajuan.</div>';
                }
            } else if(activity.includes('edit')) {
                html += '<h5>Perbandingan Data Lama & Baru</h5>';
                if(log.old_data && log.new_data) {
                    html += '<table class="compare-table"><thead><tr><th>Field</th><th>Lama</th><th>Baru</th></tr></thead><tbody>';
                    for(var key in log.new_data) {
                        html += '<tr><td>' + key + '</td><td>' + (log.old_data[key] ?? '-') + '</td><td>' + (log.new_data[key] ?? '-') + '</td></tr>';
                    }
                    html += '</tbody></table>';
                } else {
                    html += '<div>Data lama dan baru tidak tersedia.</div>';
                }
            } else if(activity.includes('add')) {
                html += '<h5>Data Baru Ditambahkan</h5>';
                if(log.new_data) {
                    html += '<table class="compare-table"><thead><tr><th>Field</th><th>Nilai</th></tr></thead><tbody>';
                    for(var key in log.new_data) {
                        html += '<tr><td>' + key + '</td><td>' + (log.new_data[key] ?? '-') + '</td></tr>';
                    }
                    html += '</tbody></table>';
                } else {
                    html += '<div>Data baru tidak tersedia.</div>';
                }
            } else {
                html += '<div>Detail aktivitas tidak tersedia.</div>';
            }
        } else {
            html += '<div>Data detail tidak tersedia.</div>';
        }

        modalBody.innerHTML = html;
    });
</script>
@endsection
