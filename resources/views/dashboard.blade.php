<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }

        .main-content {
            padding: 80px 20px 20px;
        }

        /* Gaya untuk judul Dashboard */
        .dashboard-title {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        /* Card Welcome */
        .welcome-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .welcome-card-text {
            color: #1335F2;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .welcome-card p {
            margin-bottom: 5px; /* Mengurangi jarak antar baris */
            line-height: 1.5; /* Atur line height jika perlu */
        }
        .welcome-card img {
            max-width: 250px;
            height: auto;
        }

        /* Kotak-kotak di bawahnya */
        .info-card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 24px;
        }

        .icon-box.total { background-color: #3876F2; }
        .icon-box.accepted { background-color: #28a745; }
        .icon-box.new { background-color: #17a2b8; }
        .icon-box.rejected { background-color: #dc3545; }

        .card-title-text {
            color: #6c757d;
            font-weight: 500;
            font-size: 1rem;
        }

        .h3-custom {
            font-size: 1.75rem;
            font-weight: 700;
            color: #343a40;
        }

        /* Calendar style */
        .calendar-container {
            background: #fff;
            padding: 2rem 1rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            margin-top: 2.5rem;
            margin-bottom: 2rem;
        }
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }
        @media (max-width: 900px) {
            .calendar-container {
                padding: 1rem 0.2rem;
            }
        }
    </style>
</head>
<body>
    @include('sidebar.sidebar')
    @include('navbar.navbar')
    <div class="main-content">
        <h1 class="dashboard-title">Dashboard</h1>

        <div class="welcome-card d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <div class="me-md-4 mb-3 mb-md-0">
                <h1 class="welcome-card-text">Selamat Datang Administrator!</h1>
                <p>Di Aplikasi Pengajuan Peminjaman Ruangan</p>
                <p>Pemerintah Daerah Kab. Probolinggo</p>
            </div>
            <div>
                <img src="{{ asset('images/gambar.jpeg') }}" alt="Welcome Image">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box total me-3">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 card-title-text">Total Pengajuan</h5>
                                <div class="h3 h3-custom">{{ $stats['total'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box accepted me-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 card-title-text">Pengajuan Diterima</h5>
                                <div class="h3 h3-custom">{{ $stats['diterima'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box new me-3">
                                <i class="fas fa-folder-plus"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 card-title-text">Pengajuan Baru</h5>
                                <div class="h3 h3-custom">{{ $stats['baru'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box rejected me-3">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 card-title-text">Pengajuan Ditolak</h5>
                                <div class="h3 h3-custom">{{ $stats['ditolak'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="calendar-container">
            <h4 class="mb-3" style="color:#1335F2;font-weight:bold;font-family:'Montserrat',sans-serif;">Kalender Pengajuan</h4>
            <div id='calendar'></div>
        </div>
        <!-- End Calendar Section -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
          },
          height: 600,
          navLinks: true,
          editable: false,
          eventLimit: true,
          // Gunakan font Montserrat untuk seluruh kalender
          eventDidMount: function(info) {
            info.el.style.fontFamily = "'Montserrat', sans-serif";
          },
          dayHeaderDidMount: function(info) {
            info.el.style.fontFamily = "'Montserrat', sans-serif";
          },
          locale: 'id', // Menambahkan lokalisasi Indonesia
          views: {
            dayGridMonth: {
              titleFormat: { year: 'numeric', month: 'long' }
            },
            timeGridWeek: {
              titleFormat: { day: 'numeric', month: 'long', year: 'numeric' }
            },
            timeGridDay: {
              titleFormat: { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
            }
          },
          events: '{{ route('calendar.events') }}' // Mengambil data dari route
        });

        calendar.render();
      });
    </script>
</body>
</html>
