<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }
        *{
            box-sizing: border-box;
        }
        .main-content {
            padding: 80px 20px 20px 20px;
            background: #cfe4f8; /* biar sama dengan contohmu */
            min-height: 100vh;
        }

        .content {
            width: 100%;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
        }

        /* ===== Card Welcome ===== */
        .card {
            background: #ffffff;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            margin-bottom: 20px;
            display: flex;              /* teks & gambar sejajar */
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card-left{
            flex: 1;
        }
        h2{
            margin: 0 0 20px;
            font-size: 1.5rem;
            color: #1335F2;
            font-weight: bold;
        }
        p{
            margin: 0 0 10px;
            font-size: 15px;
            color: #333;
        }
        .card-right img {
            max-width: 250px; /* ukuran gambar */
            height: auto;
            margin: 0 10px;
        }

        /* ===== Kotak Statistik ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            color: #fff;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card-content {
            padding: 20px;
        }

        .card-content h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }

        .card-content p {
            margin: 5px 0 0;
            font-size: 1.1rem;
            color: #fff;
        }

        .card-footer {
            background: rgba(0, 0, 0, 0.1);
            padding: 10px 15px;
            text-align: center;
        }

        .card-footer a {
            color: #fff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }


        .blue { background: #007bff; }
        .green { background: #28a745; }
        .yellow { background: #ffc107; }
        .red { background: #dc3545; }

    </style>
</head>
<body>
    @include('sidebar.sidebar')
    @include('navbar.navbar')
    <div class="main-content">
        <div class="content" >
            <h1>Dashboard</h1>

            <!-- Card Welcome -->
            <div class="card">
                <div class="card-left">
                    <h2>Selamat Datang Administrator!</h2>
                    <p>Di Aplikasi Pengajuan Peminjaman Ruangan</p>
                    <p>Pemerintah Daerah Kab. Probolinggo</p>
                </div>
                <div class="card-right">
                    <img src="{{ asset('images/gambar.jpeg') }}" alt="Welcome Image">
                </div>
            </div>

            <!-- Kotak Statistik -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="card-content">
                        <h3>27</h3>
                        <p>Total Pengajuan</p>
                    </div>
                    <div class="card-footer">
                        <a href="#">More info</a>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="card-content">
                        <h3>23</h3>
                        <p>Pengajuan Diterima</p>
                        </div>
                    <div class="card-footer">
                        <a href="#">More info</a>
                    </div>
                </div>
                <div class="stat-card yellow">
                    <div class="card-content">
                        <h3>3</h3>
                        <p>Pengajuan Baru</p>
                        </div>
                    <div class="card-footer">
                        <a href="#">More info</a>
                    </div>
                </div>
                <div class="stat-card red">
                    <div class="card-content">
                        <h3>1</h3>
                        <p>Pengajuan Ditolak</p>
                        </div>
                    <div class="card-footer">
                        <a href="#">More info</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
