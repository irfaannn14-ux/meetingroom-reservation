<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\DB;
use App\Models\Presensi;
use Dompdf\Dompdf;
use Dompdf\Options;

class PresensiController extends Controller
{
    public function create($id)
    {
        // Ambil organisasi aktif & rapikan urutan (sesuaikan dengan kebutuhanmu)
        $organizations = Organization::query()
            ->whereNotIn('organization_name', ['ADMIN', 'SUPER ADMIN'])
            ->orderBy('organization_name')
            ->get();

        return view('presensi.form', [
            'id'            => $id,
            'organizations' => $organizations,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'pengajuan_id'      => ['required', 'integer'],
                'nama'              => ['required', 'string', 'max:255'],
                'jabatan'           => ['required', 'string', 'max:100'],   // text bebas
                'organisasi'        => ['required', 'string'],              // id sync atau 'lainnya'
                'organisasi_manual' => ['required_if:organisasi,lainnya', 'string', 'max:255'],
                'ttd_path'          => ['required', 'string'],
            ],
            [
                'pengajuan_id.required'         => 'ID pengajuan tidak valid.',
                'nama.required'                 => 'Nama Lengkap wajib diisi.',
                'jabatan.required'              => 'Jabatan wajib diisi.',
                'organisasi.required'           => 'Organisasi wajib dipilih.',
                'organisasi_manual.required_if' => 'Silakan isi nama organisasi pada kolom yang muncul.',
                'ttd_path.required'                  => 'TTD Digital wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = $validator->validated();

        // Tentukan nilai organisasi yang disimpan (nama, bukan ID)
        if (strtolower($data['organisasi']) === 'lainnya') {
            $organisasi = trim($data['organisasi_manual'] ?? '');
        } else {
            // Dari dropdown sync: kiriman = bkd_organization_id
            $orgRow = Organization::where('bkd_organization_id', $data['organisasi'])->first();
            $organisasi = $orgRow ? $orgRow->organization_name : (string) $data['organisasi'];
        }

        if (strtolower($data['organisasi']) === 'lainnya' && $organisasi === '') {
            return response()->json(['ok' => false, 'message' => 'Silakan isi nama organisasi (Lainnya).'], 422);
        }




        // **Menangani Tanda Tangan Base64**
        // Menyimpan tanda tangan base64 ke file PNG di storage publik
        $dataUrl = $data['ttd_path']; // Data base64 dari formulir
        if (!Str::startsWith($dataUrl, 'data:image')) {
            return response()->json(['ok' => false, 'message' => 'Format TTD tidak valid.'], 422);
        }

        [$meta, $raw] = explode(',', $dataUrl, 2);
        $binary = base64_decode($raw, true);
        if ($binary === false) {
            return response()->json(['ok' => false, 'message' => 'Data TTD tidak valid.'], 422);
        }
        // Menyimpan file TTD di folder public/presensi/ttd
        $ttdPath = 'presensi/ttd/' . Str::uuid()->toString() . '.png'; // Menambahkan nama unik untuk file
        Storage::disk('public')->put($ttdPath, $binary); // Menyimpan file ke storage

        $presensi = \App\Models\Presensi::create([
            'pengajuan_id' => $data['pengajuan_id'],
            'user_id'      => Auth::id(),
            'nama'         => $data['nama'],
            'jabatan'      => $data['jabatan'],
            'organisasi'   => $organisasi,
            'ttd_path'     => $ttdPath,
        ]);

        try {
            \App\Models\ActivityLog::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Presensi berhasil untuk pengajuan ID ' . $data['pengajuan_id'],
                'resource_type' => 'pengajuan',
                'resource_id'   => $data['pengajuan_id'],
            ]);
        } catch (\Throwable $e) {
        }

        session()->flash('success', 'Absensi berhasil.');

        return response()->json([
            'ok'       => true,
            'redirect' => route('history'),
            'detail'   => route('presensi.show', $data['pengajuan_id']),
        ]);
    }


    // NEW: halaman detail data presensi per pengajuan
    public function show($pengajuanId)
    {

        $presensis = Presensi::where('pengajuan_id', $pengajuanId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('presensi.show', [
            'pengajuanId' => $pengajuanId,
            'presensis'   => $presensis,
        ]);
    }

public function downloadAllTtd(int $pengajuanId)
{   
    // Set Carbon locale to Indonesian
    \Carbon\Carbon::setLocale('id');
    
    $pengajuan = Pengajuan::with(['user.organization', 'ruangan'])->find($pengajuanId);
    
    if (!$pengajuan) {
        return back()->with('error', 'Pengajuan tidak ditemukan.');
    }
    
    $items = Presensi::where('pengajuan_id', $pengajuanId)
        ->orderBy('created_at', 'asc')
        ->get();

    if ($items->isEmpty()) {
        return back()->with('error', 'Tidak ada data presensi untuk diunduh.');
    }

    $orgMap = Organization::pluck('organization_name', 'bkd_organization_id')->all();
    
    // Pengajuan information
    $judulKegiatan = e($pengajuan->judul_kegiatan ?? 'Kegiatan');
    $ruanganNama = e($pengajuan->ruangan->nama_ruangan ?? 'Ruangan');
    
    // Format tanggal kegiatan dalam bahasa Indonesia
    $tanggalKegiatan = '';
    if ($pengajuan->tanggal_mulai) {
        $dt = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
        $tanggalKegiatan = $dt->translatedFormat('l, d F Y') . ' pukul ' . $dt->format('H:i') . ' WIB';
    }
    
    $tanggalCetak = now()->translatedFormat('d F Y');
    
    // Load logo image
    $logoPath = public_path('storage/LogoKabProbolinggo.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $logoData = file_get_contents($logoPath);
        $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
    }

    // Prepare table rows
    $rows = '';
    foreach ($items as $i => $p) {
        $nama = e($p->nama ?? '');
        $jab  = e($p->jabatan ?? '');
        $orgRaw  = $p->organisasi ?? '';
        $orgName = $orgMap[$orgRaw] ?? ($orgMap[(int)$orgRaw] ?? $orgRaw);
        $org  = e($orgName);
        
        $waktuObj = isset($p->presensi_at) && $p->presensi_at ? $p->presensi_at : $p->created_at;
        $waktu = $waktuObj ? $waktuObj->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') . ' WIB' : '';

        // Handle TTD image
        $ttdHtml = '';
        if ($p->ttd_path) {
            $relPath = ltrim(str_replace('public/', '', (string) $p->ttd_path), '/');
            
            if (Storage::disk('public')->exists($relPath)) {
                $fullPath = storage_path('app/public/' . $relPath);
                $pngData  = @file_get_contents($fullPath);
                
                if ($pngData !== false) {
                    $src  = 'data:image/png;base64,' . base64_encode($pngData);
                    $ttdHtml = '<img src="' . $src . '" alt="TTD" style="max-width: 120px; max-height: 60px; height:auto; display:block; margin:0 auto;">';
                } else {
                    $ttdHtml = '<div style="padding:5px;text-align:center;color:#666;font-size:10px;">[Gagal Memuat]</div>';
                }
            } else {
                $ttdHtml = '<div style="padding:5px;text-align:center;color:#999;font-size:10px;">-</div>';
            }
        } else {
            $ttdHtml = '<div style="padding:5px;text-align:center;color:#999;font-size:10px;">-</div>';
        }

        $rows .= '
        <tr>
            <td class="text-center">' . ($i + 1) . '</td>
            <td>' . $nama . '</td>
            <td>' . $jab . '</td>
            <td>' . $org . '</td>
            <td class="text-center">' . $waktu . '</td>
            <td class="text-center">' . $ttdHtml . '</td>
        </tr>';
    }

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            @page {
                margin: 2.5cm 2cm;
                header: page-header;
                footer: page-footer;
            }
            
            body {
                font-family: "Times New Roman", Times, serif;
                font-size: 12pt;
                color: #000;
                line-height: 1.6;
            }
            
            .kop-surat {
                text-align: center;
                margin-bottom: 25px;
                padding-bottom: 15px;
                border-bottom: 4px double #000;
                position: relative;
            }
            
            .logo-container {
                position: absolute;
                top: -15px;
                right: 0;
                width: 80px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #fff;
            }
            
            .logo-container img {
                max-width: 70px;
                max-height: 70px;
            }
            
            .kop-surat .instansi {
                font-size: 18pt;
                font-weight: bold;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin: 0 0 5px 0;
            }
            
            .kop-surat .instansi-sub {
                font-size: 16pt;
                font-weight: bold;
                text-transform: uppercase;
                margin: 0 0 10px 0;
            }
            
            .kop-surat .alamat {
                font-size: 11pt;
                margin: 5px 0;
            }
            
            .kop-surat .kontak {
                font-size: 11pt;
                margin: 3px 0;
            }
            
            .info-kegiatan {
                margin: 20px 0;
                padding: 15px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            .info-kegiatan table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .info-kegiatan td {
                padding: 8px 5px;
                vertical-align: top;
            }
            
            .info-kegiatan td:first-child {
                width: 30%;
                font-weight: bold;
                padding-right: 15px;
            }
            
            .judul-dokumen {
                text-align: center;
                font-size: 16pt;
                font-weight: bold;
                margin: 25px 0 20px;
                text-transform: uppercase;
                text-decoration: underline;
                letter-spacing: 1px;
            }
            
            table.data-presensi {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                page-break-inside: avoid;
            }
            
            table.data-presensi th,
            table.data-presensi td {
                border: 1px solid #000;
                padding: 10px 8px;
                vertical-align: top;
            }
            
            table.data-presensi th {
                background-color: #f2f2f2;
                text-align: center;
                font-weight: bold;
                font-size: 11pt;
            }
            
            table.data-presensi td {
                font-size: 11pt;
            }
            
            .text-center {
                text-align: center;
            }
            
            .footer-info {
                text-align: center;
                margin-top: 40px;
                font-size: 11pt;
                padding-top: 15px;
            }
            
            .ttd-container {
                margin-top: 40px;
                text-align: center;
            }
            
            .ttd-box {
                display: inline-block;
                min-width: 350px;
                border-top: 2px solid #000;
                padding-top: 10px;
                margin-top: 5px;
                font-weight: bold;
            }
            
            .signature-line {
                text-align: right;
                margin-top: 30px;
                width: 350px;
                float: right;
            }
            
            .clear {
                clear: both;
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <div class="kop-surat">
            <div class="logo-container">
                ' . ($logoBase64 ? '<img src="' . $logoBase64 . '" alt="Logo Kabupaten Probolinggo">' : '<span style="font-size:8pt;color:#666">LOGO</span>') . '
            </div>
            <div class="instansi">PEMERINTAH KABUPATEN PROBOLINGGO</div>
            <div class="instansi-sub">Dinas Komunikasi, Informatika, Statistik, dan Persandian</div>
            <div class="alamat">Jl. Raya Panglima Sudirman No.134, Patokan, Kec. Kraksaan, Kabupaten Probolinggo, Jawa Timur 67282</div>
            <div class="kontak">Telp. (0335) 846667</div>
        </div>

        <!-- Document Title -->
        <div class="judul-dokumen">
            DAFTAR HADIR
        </div>

        <!-- Event Information -->
        <div class="info-kegiatan">
            <table>
                <tr>
                    <td>Nama Kegiatan</td>
                    <td>: ' . $judulKegiatan . '</td>
                </tr>
                <tr>
                    <td>Ruangan</td>
                    <td>: ' . $ruanganNama . '</td>
                </tr>
                <tr>
                    <td>Tanggal dan Waktu</td>
                    <td>: ' . $tanggalKegiatan . '</td>
                </tr>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>: ' . $tanggalCetak . '</td>
                </tr>
            </table>
        </div>

        <!-- Attendance Table -->
        <table class="data-presensi">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:25%;">Nama</th>
                    <th style="width:20%;">Jabatan</th>
                    <th style="width:25%;">Organisasi</th>
                    <th style="width:15%;">Waktu Hadir</th>
                    <th style="width:10%;">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                ' . $rows . '
            </tbody>
        </table>

        <div class="clear"></div>

        <!-- Signature Section -->
        <div class="signature-line">
            <div style="text-align: center; margin-bottom: 150px;">
                Probolinggo, ' . now()->translatedFormat('d F Y') . '
            </div>
            <div class="ttd-box" style="text-align: center">
                Mengetahui,<br>
                Kepala DisKominfo
            </div>
        </div>

        <div class="clear"></div>

        <!-- Footer -->
        <div class="footer-info">
            <p>Dokumen ini dicetak secara otomatis melalui Sistem Presensi BKD Kabupaten Probolinggo</p>
        </div>
    </body>
    </html>';

    // PDF Generation
    $options = new Options([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isPhpEnabled' => false,
        'isFontSubsettingEnabled' => true,
        'defaultFont' => 'DejaVu Sans',
        'dpi' => 150,
        'isJavascriptEnabled' => false,
        'debugKeepTemp' => false,
        'logOutputFile' => storage_path('logs/dompdf.log'),
    ]);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    
    try {
        $dompdf->render();
    } catch (\Exception $e) {
        \Log::error('PDF Generation Error: ' . $e->getMessage());
        return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
    }

    $filename = 'Daftar_Hadir_' . $pengajuanId . '_' . date('YmdHis') . '.pdf';
    return response($dompdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}
}
