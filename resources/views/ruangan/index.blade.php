<?php ?>
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
            margin-left: 60px;
            padding: 2rem;
            min-height: 100vh;
            background-color: #C9DFF2;
            margin-top: 60px;
        }
        .content {
            width: 100%;
            max-width: 1150px;
            margin: 0 auto;
        }
        .ruangan-table-container {
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
            width: 250px;
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
                margin-top: 1rem;
            }
            table, th, td {
                font-size: 13px;
            }
            .dashboard-title {
                margin-bottom: 0.8rem;
            }
        }
        
        /* Modal styles */
        .modal {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            box-sizing: border-box;
        }
        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease;
        }
        #detailModal .modal-content {
            max-width: 500px; /* Lebar modal detail */
            text-align: left;
        }
        #deleteConfirmModal .modal-content {
            max-width: 400px;
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
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
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
                <a href="{{ route('ruangan.tambah') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> Tambah Ruangan
                </a>
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
                    <tbody>
                        @forelse($ruangans as $ruangan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ruangan->nama_ruangan }}</td>
                            <td>{{ $ruangan->jml_peserta }} Orang</td>
                            <td>{{ $ruangan->fasilitas }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Tombol View baru ditambahkan --}}
                                    <button type="button" class="btn btn-info btn-sm btn-action bi bi-search" onclick="openDetailModal({{ json_encode($ruangan) }})" title="Lihat Detail"></button>
                                    <a href="{{ route('ruangan.edit', $ruangan) }}" class="btn btn-success btn-sm btn-action bi bi-pencil-fill" title="Edit"></a>
                                    <button type="button" class="btn btn-danger btn-sm btn-action bi bi-trash-fill" onclick="openDeleteModal({{ $ruangan->id }})" title="Hapus"></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data ruangan.</td>
                        </tr>
                        @endforelse
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
        // Variabel untuk menyimpan ID ruangan yang akan dihapus
        let ruanganIdToDelete = null;

        // FUNGSI BARU: untuk membuka modal detail
        function openDetailModal(ruangan) {
            document.getElementById('modalFotoRuangan').src = ruangan.foto_ruangan ? `/storage/${ruangan.foto_ruangan}` : 'https://placehold.co/600x400?text=No+Image';
            document.getElementById('modalNamaRuangan').innerText = ruangan.nama_ruangan;
            document.getElementById('modalKapasitas').innerText = ruangan.jml_peserta + ' orang';
            document.getElementById('modalFasilitas').innerText = ruangan.fasilitas;
            document.getElementById('detailModal').style.display = 'flex';
        }

        function openDeleteModal(id) {
            ruanganIdToDelete = id;
            document.getElementById('deleteConfirmModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function executeDelete() {
            if (ruanganIdToDelete) {
                const deleteForm = document.getElementById('deleteForm');
                // Pastikan route 'ruangan.destroy' menerima objek atau ID
                let url = "{{ route('ruangan.destroy', ':id') }}";
                url = url.replace(':id', ruanganIdToDelete);
                deleteForm.action = url;
                deleteForm.submit();
            }
        }

        // Menutup modal jika klik di luar modal-content
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        });
    </script>
@endsection
