<?php ?>
@extends('layout.main')
@section('title', 'Dashboard')
@section('content')
    <style>
        .main-content {
            padding: 80px 20px 20px;
        }

        /* Gaya untuk judul Dashboard */
        .dashboard-title {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }

        /* Card Welcome */
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px 100px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
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
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
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
            margin-top: 0;
            margin-bottom: 2rem;
        }
        #calendar {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Custom styling for calendar events */
        .fc-event.approved-event {
            border-width: 1px;
            border-style: solid;
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
        <div class="welcome-card mb-4" style="position: relative; min-height: 250px; overflow: hidden;">
            <!-- Background Image -->
            <div style="position: absolute; top: 0; right: 5%; bottom: 0; width: 55%; background-image: url('{{ asset('images/developers.png') }}'); background-size: contain; background-position: center right; background-repeat: no-repeat; opacity: 1; z-index: 1;"></div>
            
            <!-- White Gradient Overlay -->
            <!-- White Gradient Overlay removed -->
            
            <!-- Content -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center" style="position: relative; z-index: 3; min-height: 210px;">
            <div class="me-md-4 mb-3 mb-md-0" style="flex: 1;">
            <h1 class="welcome-card-text">Selamat Datang, {{ session('user_nama', 'Pengguna') }}!</h1>
            <p>Di Aplikasi Pengajuan Peminjaman Ruangan</p>
            <p>Pemerintah Daerah Kab. Probolinggo</p>
            </div>
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
        <h4 class="mb-3" style="color:#010D26;font-weight:bold;font-family:'Montserrat',sans-serif;">Kalender Pengajuan</h4>
            <div id='calendar'></div>
        </div>
        <!-- End Calendar Section -->

    </div>

    <!-- FullCalendar JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          themeSystem: 'bootstrap5',
          initialView: 'dayGridMonth',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
          },
          height: 650,
          navLinks: true,
          editable: false,
          eventLimit: true,
          locale: 'id',
          eventClassNames: 'approved-event',
          eventDidMount: function(info) {
            info.el.style.fontFamily = "'Montserrat', sans-serif";
            info.el.style.fontWeight = '500';
          },
          views: {
            dayGridMonth: { titleFormat: { year: 'numeric', month: 'long' } },
            timeGridWeek: { titleFormat: { day: 'numeric', month: 'long', year: 'numeric' } },
            timeGridDay: { titleFormat: { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' } }
          },
          events: '{{ route('calendar.events') }}'
        });

        calendar.render();
      });
    </script>
@endsection

@section('javascript')
    <script>
        // Additional JavaScript if needed
    </script>
    <style>
        /* Dashboard-specific color overrides */
        .welcome-card-text {
            color: #010D26 !important;
        }

        /* FullCalendar toolbar buttons - Updated with Gradient like Sign In */
        .fc .fc-toolbar .fc-button,
        .fc .fc-toolbar .fc-button-primary,
        .fc .fc-toolbar .btn,
        .fc .fc-toolbar .btn-primary,
        .fc .fc-toolbar-chunk .fc-button,
        .fc .fc-toolbar-chunk .btn,
        .fc-button-primary:not(:disabled):not(.disabled),
        .fc-button-primary:not(:disabled),
        .fc .fc-button-primary:not(:disabled):not(.fc-button-active):not(:active) {
            background: linear-gradient(135deg, #1D64F2 0%, #010D26 100%) !important;
            background-color: #1D64F2 !important;
            color: #ffffff !important;
            border: none !important;
            border-left: none !important;
            border-right: none !important;
            background-image: linear-gradient(135deg, #1D64F2 0%, #010D26 100%) !important;
            box-shadow: 0 4px 15px rgba(29, 100, 242, 0.3) !important;
            transition: all 0.3s ease !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            padding: 0.5em 1em !important;
            margin: 0 5px !important;
        }

        .fc .fc-button-primary,
        .fc .btn-primary,
        .fc .fc-toolbar .fc-button.active,
        .fc .fc-toolbar .fc-button.fc-button-active,
        .fc .fc-toolbar .fc-button[aria-pressed="true"],
        .fc-button-primary.fc-button-active {
            background: linear-gradient(135deg, #1D64F2 0%, #010D26 100%) !important;
            background-color: #1D64F2 !important;
            color: #ffffff !important;
            border: none !important;
            background-image: linear-gradient(135deg, #1D64F2 0%, #010D26 100%) !important;
            box-shadow: 0 4px 15px rgba(29, 100, 242, 0.4) !important;
        }

        .fc .fc-toolbar .fc-button:hover,
        .fc .fc-toolbar .btn:hover,
        .fc .fc-toolbar-chunk .fc-button:hover,
        .fc .fc-toolbar .fc-button:focus,
        .fc .fc-toolbar .btn:focus,
        .fc-button-primary:hover,
        .fc-button-primary:not(:disabled):not(.disabled):hover {
            background: linear-gradient(135deg, #1D64F2 0%, #010D26 100%) !important;
            background-color: #1D64F2 !important;
            color: #ffffff !important;
            border: none !important;
            background-image: linear-gradient(135deg, #1D64F2 0%, #010D26 100%) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(29, 100, 242, 0.4) !important;
        }

        .fc .fc-toolbar .fc-button:active,
        .fc .fc-toolbar .btn:active {
            transform: translateY(0) !important;
        }

        .fc .fc-toolbar .fc-button:focus-visible,
        .fc .fc-toolbar .btn:focus-visible {
            outline: 3px solid rgba(29, 100, 242, 0.3) !important;
            outline-offset: 2px !important;
        }

        .fc .fc-toolbar .fc-button[disabled],
        .fc .fc-toolbar .btn[disabled],
        .fc .fc-toolbar .fc-button.disabled,
        .fc .fc-toolbar .btn.disabled {
            background: #6c757d !important;
            color: #ffffff !important;
            border: none !important;
            opacity: 0.5 !important;
            box-shadow: none !important;
            transform: none !important;
        }
        
        /* Fix for button icons visibility */
        .fc .fc-toolbar .fc-button .fc-icon,
        .fc .fc-toolbar .fc-button span {
            color: #ffffff !important;
            font-weight: bold !important;
        }
        
        /* Ensure proper spacing and visibility */
        .fc .fc-toolbar .fc-button {
            min-width: 40px !important;
            min-height: 40px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 6px !important;
        }
        
        /* Remove default button group styling that makes buttons stick together */
        .fc .fc-button-group > .fc-button {
            border-radius: 6px !important;
            margin: 0 5px !important;
            border-left: none !important;
            border-right: none !important;
        }
        
        .fc .fc-button-group > .fc-button:not(:last-child) {
            margin-right: 5px !important;
            border-top-right-radius: 6px !important;
            border-bottom-right-radius: 6px !important;
        }
        
        .fc .fc-button-group > .fc-button:not(:first-child) {
            margin-left: 5px !important;
            border-top-left-radius: 6px !important;
            border-bottom-left-radius: 6px !important;
        }
        
        .fc .fc-button-group > .fc-button:first-child {
            border-radius: 6px !important;
        }
        
        .fc .fc-button-group > .fc-button:last-child {
            border-radius: 6px !important;
        }
        
        /* Add gap between button groups */
        .fc .fc-toolbar-chunk {
            display: flex !important;
            gap: 12px !important;
            align-items: center !important;
        }
        
        .fc .fc-button-group {
            display: flex !important;
            gap: 6px !important;
        }
        
        /* Calendar header styling to ensure proper contrast */
        .fc .fc-toolbar-title {
            color: #010D26 !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
        }
    </style>
@endsection