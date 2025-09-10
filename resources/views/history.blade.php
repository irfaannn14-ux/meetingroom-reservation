@extends('layout.main')

@section('content')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pengajuan</title>

    <style>
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
        }

        .content {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .history-table-container {
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            margin-top: 4rem;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 2.5rem;
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

        .status-accepted {
            color: #fff;
            background: #1D64F2;
            padding: 0.35em 1.1em;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.98em;
            display: inline-block;
        }

        .status-denied {
            color: #fff;
            background: #e74c3c;
            padding: 0.35em 1.1em;
            border-radius: 16px;
            font-weight: 600;
            font-size: 0.98em;
            display: inline-block;
        }

        @media (max-width: 900px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            .content {
                max-width: 100%;
            }
            .history-table-container {
                padding: 1rem 0.5rem;
            }
            table, th, td {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    @include('sidebar.sidebar')
    @include('navbar.navbar')

    <div class="main-content">
        <div class="content">
            <div class="history-table-container">
                <h1>History Pengajuan</h1>
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
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Andi Wijaya</td>
                            <td>Rapat Koordinasi Proyek</td>
                            <td>Ruangan 1</td>
                            <td>2024-06-10 09:00</td>
                            <td>2024-06-10 12:00</td>
                            <td>15</td>
                            <td><span class="status-accepted">accepted</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Siti Rahma</td>
                            <td>Pelatihan IT Dasar</td>
                            <td>Ruangan 2</td>
                            <td>2024-06-12 13:00</td>
                            <td>2024-06-12 16:00</td>
                            <td>25</td>
                            <td><span class="status-denied">denied</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
@endsection
