<?php ?>
@extends('layout.main')
@section('title', 'Ruangan')
@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');
        .main-content {
            margin-left: 60px;
            padding: 2rem;
            min-height: 100vh;
            margin-top: 60px;
        }
        .content {
            width: 100%;
            max-width: 1150px;
            margin: 0 auto;
        }
        .ruangan-table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 2.5rem 2rem;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            margin-top: 1.5rem;
        }
        .dashboard-title {
            margin-top: 0;
            margin-bottom: 1.2rem;
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            background: #fff;
            table-layout: fixed;
        }
        th, td {
            padding: 0.85rem 1rem;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
        }
        th:first-child,
        td:first-child {
            width: 60px;
        }
        
        th:last-child,
        td:last-child {
            width: 160px;
        }
        th {
            background-color: #010D26 !important;
            color: #ffffff !important;
            font-weight: 700;
            border-bottom: 2px solid rgba(255,255,255,0.06);
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
        
        /* Diperbarui: Menyesuaikan ukuran tombol aksi agar seragam */
        td .btn-action.btn-sm {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
            /* ... media query styles ... */
        }
        
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
            width: 280px;
            border: 1px solid #ced4da;
        }

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
        }
        /* Base z-index for modals */
        #detailModal { z-index: 1000; }
        #deleteConfirmModal { z-index: 1001; }
        
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease;
            position: relative;
        }
        #detailModal .modal-dialog {
            max-width: 500px;
        }
        #detailModal .modal-content {
            text-align: left;
        }
        #deleteConfirmModal .modal-dialog {
            max-width: 400px;
        }
        .modal-header {
            background-color: #010D26;
            color: #ffffff;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        .modal-content h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #010D26;
        }
        .modal-content p {
            margin-bottom: 25px;
        }
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            border: none;
        }
        /* Diperbarui: Menambahkan warna tombol agar seragam */
        .btn-info { background-color: #17a2b8; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        /* Custom Add Button with Gradient like Sign In */
        .btn-add-custom {
            background: linear-gradient(135deg, #1D64F2 0%, #010D26 100%);
            color: white !important;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(29, 100, 242, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-add-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(29, 100, 242, 0.4);
            color: white !important;
        }
        .btn-add-custom:active {
            transform: translateY(0);
        }
        
        /* Brand primary for add buttons */
        .btn-primary {
            background-color: #010D26 !important;
            color: #ffffff !important;
            border-color: #010D26 !important;
            box-shadow: none !important;
        }
        .btn-primary:hover {
            background-color: #ffffff !important;
            color: #010D26 !important;
            border-color: transparent !important;
            box-shadow: inset 0 0 0 1px rgba(1,13,38,0.06) !important;
        }
        #modalFotoRuangan {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <div class="main-content">
        <div class="content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
                <h1 class="dashboard-title">List Ruangan</h1>
                <div class="d-flex align-items-center gap-3">
                    <div class="search-container">
                        <i class="bi bi-search search-icon"></i>
                        <input type="search" id="searchInput" onkeyup="filterTable()" class="form-control" placeholder="Cari nama, kapasitas, fasilitas...">
                    </div>
                    @if(session('user_role') !== 'OPD')
                    <a href="{{ route('ruangan.tambah') }}" class="btn btn-add-custom">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Ruangan
                    </a>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="ruangan-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ruangan</th>
                            <th>Kapasitas</th>
                            <th>Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="ruanganTableBody">
                        @forelse($ruangans as $ruangan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ruangan->nama_ruangan }}</td>
                            <td>{{ $ruangan->jml_peserta }} Orang</td>
                            <td>{{ $ruangan->fasilitas }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-info btn-sm btn-action bi bi-search" onclick="openDetailModal({{ json_encode($ruangan) }})" title="Lihat Detail"></button>
                                    @if(session('user_role') !== 'OPD')
                                    <a href="{{ route('ruangan.edit', $ruangan) }}" class="btn btn-success btn-sm btn-action bi bi-pencil-fill" title="Edit"></a>
                                    <button type="button" class="btn btn-danger btn-sm btn-action bi bi-trash-fill" onclick="openDeleteModal({{ $ruangan->id }})" title="Hapus"></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data ruangan.</td>
                        </tr>
                        @endforelse
                        <tr id="noResultsRow" style="display: none;">
                            <td colspan="5" class="text-center py-4">Tidak ada ruangan yang cocok dengan pencarian Anda.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- MODAL BARU: Detail Ruangan --}}
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <h3>Detail Ruangan</h3>
            <img id="modalFotoRuangan" src="" alt="Foto Ruangan">
            <table class="table table-bordered text-start">
                <tr><th width="35%">Nama Ruangan</th><td id="modalNamaRuangan"></td></tr>
                <tr><th>Kapasitas Maksimal</th><td id="modalKapasitas"></td></tr>
                <tr><th>Fasilitas</th><td id="modalFasilitas"></td></tr>
            </table>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal('detailModal')">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Hapus Ruangan</h3>
            <p>Apakah Anda yakin ingin menghapus ruangan ini?</p>
            <div class="modal-actions">
                <button class="btn btn-danger" onclick="executeDelete()">Ya, Hapus</button>
                <button class="btn btn-secondary" onclick="closeModal('deleteConfirmModal')">Batal</button>
            </div>
        </div>
    </div>

    {{-- Formulir tersembunyi untuk mengirim permintaan DELETE --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let ruanganIdToDelete = null;

        function openDetailModal(ruangan) {
            document.getElementById('modalFotoRuangan').src = ruangan.foto_ruangan ? `/storage/${ruangan.foto_ruangan}` : 'https://placehold.co/600x400?text=No+Image';
            document.getElementById('modalNamaRuangan').innerText = ruangan.nama_ruangan;
            document.getElementById('modalKapasitas').innerText = ruangan.jml_peserta + ' orang';
            document.getElementById('modalFasilitas').innerText = ruangan.fasilitas;
            openModal('detailModal');
        }

        function openDeleteModal(id) {
            ruanganIdToDelete = id;
            openModal('deleteConfirmModal');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
            
            // Re-enable scrolling on body when all modals are closed
            if (!document.querySelector('.modal[style*="display: flex"]')) {
                document.body.style.overflow = '';
            }
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'flex';
            // Disable scrolling on body when modal is open
            document.body.style.overflow = 'hidden';
        }

        function executeDelete() {
            if (ruanganIdToDelete) {
                const deleteForm = document.getElementById('deleteForm');
                let url = "{{ route('ruangan.destroy', ':id') }}";
                url = url.replace(':id', ruanganIdToDelete);
                deleteForm.action = url;
                deleteForm.submit();
            }
        }

        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const tableBody = document.getElementById("ruanganTableBody");
            const rows = tableBody.getElementsByTagName("tr");
            let visibleRows = 0;

            for (let i = 0; i < rows.length; i++) {
                if (rows[i].id === 'noResultsRow' || rows[i].querySelector('td[colspan="5"]')) {
                    continue;
                }

                const namaRuanganCell = rows[i].getElementsByTagName("td")[1];
                const kapasitasCell = rows[i].getElementsByTagName("td")[2];
                const fasilitasCell = rows[i].getElementsByTagName("td")[3];

                if (namaRuanganCell && kapasitasCell && fasilitasCell) {
                    const rowText = (namaRuanganCell.textContent || namaRuanganCell.innerText) +
                                  (kapasitasCell.textContent || kapasitasCell.innerText) +
                                  (fasilitasCell.textContent || fasilitasCell.innerText);
                    
                    if (rowText.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = "";
                        visibleRows++;
                    } else {
                        rows[i].style.display = "none";
                    }
                }       
            }

            const noResultsRow = document.getElementById('noResultsRow');
            if (visibleRows === 0 && !tableBody.querySelector('td[colspan="5"]')) {
                noResultsRow.style.display = "table-row";
            } else {
                noResultsRow.style.display = "none";
            }
        }

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        });
    </script>
@endsection

