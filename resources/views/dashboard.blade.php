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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
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
        
        /* Developer Image Shift on Sidebar Hover */
        .developer-image {
            position: absolute;
            top: 0;
            right: 5%;
            bottom: 0;
            width: 55%;
            background-image: url('{{ asset('images/developers.png') }}');
            background-size: contain;
            background-position: center right;
            background-repeat: no-repeat;
            opacity: 1;
            z-index: 1;
            transition: right 0.3s ease, width 0.3s ease;
        }
        
        /* Shift image when sidebar is hovered */
        .sidebar:hover ~ .main-content .developer-image {
            right: -5%;
            width: 50%;
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
        <!-- Welcome Card-->
        <div class="welcome-card mb-4" style="position: relative; min-height: 250px; overflow: hidden;" title="Made with ❤️ by Tim IT Diskominfo Kab. Probolinggo, Featuring Anak Magang 😁">
            <!-- Developer Image with shift effect -->
            <div class="developer-image"></div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center" style="position: relative; z-index: 3; min-height: 210px;">
            <div class="me-md-4 mb-3 mb-md-0" style="flex: 1;">
            <h1 class="welcome-card-text">Selamat Datang, {{ session('user_nama', 'Pengguna') }}!</h1>
            <p>Di Aplikasi Pengajuan Peminjaman Ruangan</p>
            <p>Pemerintah Daerah Kab. Probolinggo</p>
            </div>
            </div>
        </div>

        <!-- Info Cards -->
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

        <!-- Additional Info Section -->
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-body">
                        <h5 class="mb-3" style="color:#010D26;font-weight:bold;font-family:'Montserrat',sans-serif;">
                            <i class="fas fa-fire me-2"></i>HeatMap Jam Tersibuk
                        </h5>
                        <div id="bookingHeatmap"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-body">
                        <h5 class="mb-3" style="color:#010D26;font-weight:bold;font-family:'Montserrat',sans-serif;">
                            <i class="fas fa-chart-pie me-2"></i>Top 5 Ruangan Terpopuler
                        </h5>
                        <div id="roomPieChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Info Section -->
        <div class="row mb-4">
            <div class="col-12">
            <div class="card info-card h-100">
                <div class="card-body">
                <h5 class="mb-3" style="color:#010D26;font-weight:bold;font-family:'Montserrat',sans-serif;">
                    <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box" style="background-color: #6f42c1;">
                        <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                        <h6 class="mb-0 card-title-text" style="color: #343a40; font-weight: bold; font-family: 'Montserrat', sans-serif;">Jam Operasional</h6>
                        <p class="mb-0" style="color: #343a40; font-weight: bold; font-family: 'Montserrat', sans-serif;">08:00 - 17:00 WIB</p>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box" style="background-color: #fd7e14;">
                        <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="ms-3">
                        <h6 class="mb-0 card-title-text" style="color: #343a40; font-weight: bold; font-family: 'Montserrat', sans-serif;">Hari Kerja</h6>
                        <p class="mb-0" style="color: #343a40; font-weight: bold; font-family: 'Montserrat', sans-serif;">Senin - Jumat</p>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box" style="background-color: #20c997;">
                        <i class="fas fa-phone"></i>
                        </div>
                        <div class="ms-3">
                        <h6 class="mb-0 card-title-text" style="color: #343a40; font-weight: bold; font-family: 'Montserrat', sans-serif;">Kontak</h6>
                        <p class="mb-0" style="color: #343a40; font-weight: bold; font-family: 'Montserrat', sans-serif;">(Insert Nomor Kontak Here)</p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>

    </div>

    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- FullCalendar JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // FullCalendar
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

        // ApexCharts - Room Pie Chart
        var roomNames = @json($roomNames);
        var roomCounts = @json($roomCounts);
        var roomColors = @json($roomColors);
        
        var totalBookings = roomCounts.reduce((a, b) => a + b, 0);

        var pieChartOptions = {
            series: roomCounts,
            chart: {
                type: 'donut',
                height: 380,
                fontFamily: 'Montserrat, sans-serif',
            },
            labels: roomNames,
            colors: roomColors,
            legend: {
                position: 'right',
                fontSize: '14px',
                fontWeight: 600,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6,
                },
                itemMargin: {
                    horizontal: 5,
                    vertical: 8
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '18px',
                                fontWeight: 700,
                                color: '#010D26',
                                offsetY: -10,
                            },
                            value: {
                                show: true,
                                fontSize: '32px',
                                fontWeight: 700,
                                color: '#010D26',
                                offsetY: 10,
                                formatter: function (val) {
                                    return val + ' kali'
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Booking',
                                fontSize: '16px',
                                fontWeight: 600,
                                color: '#6c757d',
                                formatter: function (w) {
                                    return totalBookings + ' kali'
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(value, { seriesIndex }) {
                        var percentage = ((value / totalBookings) * 100).toFixed(1);
                        return value + ' kali (' + percentage + '%)'
                    }
                },
                style: {
                    fontSize: '14px',
                    fontFamily: 'Montserrat, sans-serif'
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var pieChart = new ApexCharts(document.querySelector("#roomPieChart"), pieChartOptions);
        pieChart.render();

        // ApexCharts - Booking Heatmap
        var heatmapData = @json($heatmapData);
        var days = @json($days);

        var heatmapOptions = {
            series: heatmapData,
            chart: {
                type: 'heatmap',
                height: 450,
                fontFamily: 'Montserrat, sans-serif',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                heatmap: {
                    shadeIntensity: 0.5,
                    radius: 6,
                    useFillColorAsStroke: false,
                    colorScale: {
                        ranges: [{
                            from: 0,
                            to: 0,
                            name: 'Tidak ada',
                            color: '#F0F4F8'
                        },
                        {
                            from: 1,
                            to: 2,
                            name: 'Rendah',
                            color: '#C9DFF2'
                        },
                        {
                            from: 3,
                            to: 5,
                            name: 'Sedang',
                            color: '#7BB5E8'
                        },
                        {
                            from: 6,
                            to: 10,
                            name: 'Tinggi',
                            color: '#1D64F2'
                        },
                        {
                            from: 11,
                            to: 999,
                            name: 'Sangat Tinggi',
                            color: '#010D26'
                        }]
                    }
                }
            },
            dataLabels: {
                enabled: true,
                style: {
                    colors: ['#010D26'],
                    fontSize: '12px',
                    fontFamily: 'Montserrat, sans-serif',
                    fontWeight: 600
                },
                formatter: function(val) {
                    return val === 0 ? '' : val;
                }
            },
            xaxis: {
                categories: days,
                labels: {
                    style: {
                        colors: '#010D26',
                        fontSize: '13px',
                        fontFamily: 'Montserrat, sans-serif',
                        fontWeight: 600
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#C9DFF2'
                },
                axisTicks: {
                    show: true,
                    color: '#C9DFF2'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#010D26',
                        fontSize: '12px',
                        fontFamily: 'Montserrat, sans-serif',
                        fontWeight: 500
                    }
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '13px',
                fontFamily: 'Montserrat, sans-serif',
                fontWeight: 600,
                markers: {
                    width: 20,
                    height: 8,
                    radius: 2
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(value) {
                        return value + ' booking'
                    }
                },
                style: {
                    fontSize: '13px',
                    fontFamily: 'Montserrat, sans-serif'
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 350
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var heatmapChart = new ApexCharts(document.querySelector("#bookingHeatmap"), heatmapOptions);
        heatmapChart.render();
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