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
            margin-left: 220px;
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
        }
        th, td {
            padding: 0.85rem 1rem;
            text-align: center;
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
        .ruangan-foto {
            max-width: 80px;
            height: auto;
            border-radius: 4px;
        }

        /* New styles for delete confirmation modal */
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
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease;
        }
        .modal-content h3 {
            margin-bottom: 15px;
            color: #dc3545;
        }
        .modal-content p {
            margin-bottom: 25px;
        }
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
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
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    @include('sidebar.sidebar')
    @include('navbar.navbar')

    <div class="main-content">
        <div class="content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 ms-auto">
                <h1 class="dashboard-title">List Ruangan</h1>
                <a href="{{ route('ruangan.tambah') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill"></i> Tambah Ruangan
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
                            <th>Jumlah Peserta</th>
                            <th>Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ruangans as $ruangan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ruangan->nama_ruangan }}</td>
                            <td>{{ $ruangan->jml_peserta }}</td>
                            <td>{{ $ruangan->fasilitas }}</td>
                            <td>
                                <a href="{{ route('ruangan.edit', $ruangan->id) }}" class="btn btn-success btn-sm btn-action nav-icon bi bi-pencil-square" title="Edit"></a>
                                <button type="button" class="btn btn-danger btn-sm btn-action bi bi-trash" onclick="openDeleteModal({{ $ruangan->id }})" title="Hapus"></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
        // Variable untuk menyimpan ID ruangan yang akan dihapus
        let ruanganIdToDelete = null;

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
                deleteForm.action = `/ruangan/${ruanganIdToDelete}`;
                deleteForm.submit();
            }
        }

        // Menutup modal jika klik di luar modal-content
        document.getElementById('deleteConfirmModal').addEventListener('click', function (event) {
            const modalContent = document.querySelector('#deleteConfirmModal .modal-content');
            if (!modalContent.contains(event.target)) {
                closeModal('deleteConfirmModal');
            }
        });
    </script>
@endsection
