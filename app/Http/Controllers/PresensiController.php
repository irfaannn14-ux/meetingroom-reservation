<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use App\Models\Organization;
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
        // Check if GD extension is loaded (needed for image processing in PDF)
        if (!extension_loaded('gd')) {
            return back()->with('warning', 
                'PHP GD extension belum aktif. PDF akan dibuat tanpa gambar tanda tangan. ' .
                'Untuk menampilkan tanda tangan di PDF, aktifkan extension=gd di php.ini dan restart Apache.');
        }
        
        $items = Presensi::where('pengajuan_id', $pengajuanId)
            ->whereNotNull('ttd_path')
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Tidak ada TTD untuk diunduh.');
        }

        $orgMap = Organization::pluck('organization_name', 'bkd_organization_id')->all();

        $organization_name = DB::selectOne("SELECT
            organization_name
        FROM pengajuans
        JOIN users ON pengajuans.user_id=users.id
        JOIN organization ON organization.bkd_organization_id=users.organization_id;");
        $instansi = 'PEMERINTAH KABUPATEN PROBOLINGGO';
        $tanggalCetak = now()->format('d F Y');

        $rows = '';
        foreach ($items as $i => $p) {
            $relPath = ltrim(str_replace('public/', '', (string) $p->ttd_path), '/');
            if (!Storage::disk('public')->exists($relPath)) continue;

            $fullPath = storage_path('app/public/' . $relPath);
            $pngData  = @file_get_contents($fullPath);
            if ($pngData === false) continue;

            $src  = 'data:image/png;base64,' . base64_encode($pngData);

            $nama = e($p->nama ?? '');
            $jab  = e($p->jabatan ?? '');
            $orgRaw  = $p->organisasi ?? '';
            $orgName = $orgMap[$orgRaw] ?? ($orgMap[(int)$orgRaw] ?? $orgRaw);
            $org  = e($orgName);
            $waktu = $p->created_at ? $p->created_at->format('d-m-Y H:i') . ' WIB' : '';

            $rows .= '
            <tr>
                <td style="text-align:center;">' . ($i + 1) . '</td>
                <td>
                    <strong>' . $nama . '</strong><br>
                    ' . ($jab ? '<em>' . $jab . '</em><br>' : '') . '
                    ' . ($org ? '<small>' . $org . '</small><br>' : '') . '
                    ' . ($waktu ? '<small>' . $waktu . '</small>' : '') . '
                </td>
                <td style="text-align:center;">
                    <img src="' . $src . '" alt="TTD" style="max-width: 150px; height:auto;">
                </td>
            </tr>';
        }

        if (empty($rows)) {
            return back()->with('error', 'Semua file TTD tidak ditemukan.');
        }

        $html = '
        <html>
        <head>
            <style>
                body {
                    font-family: DejaVu Sans, sans-serif;
                    font-size: 12px;
                    margin: 40px;
                }

                h2 {
                    text-align: center;
                    margin: 0;
                    text-transform: uppercase;
                }

                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 25px;
                    border-bottom: 2px solid #000;
                    padding-bottom: 10px;
                }

                .header-left {
                    flex: 1;
                    text-align: left;
                }

                .header-center {
                    flex: 2;
                    text-align: center;
                }

                .header p {
                    margin: 2px 0;
                }

                .logo {
                    width: 70px;
                    height: auto;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }

                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    vertical-align: top;
                }

                th {
                    background-color: #f2f2f2;
                    text-align: center;
                }

                .info {
                    text-align: center;
                    margin-top: 25px;
                    font-size: 11px;
                    font-style: italic;
                }
            </style>
        </head>

        <body>
            <div class="header">
                <div class="header-left"></div>

                <div class="header-center">
                    <p><strong style="font-size:18px;">' . e($instansi) . '</strong></p>
                    <p style="font-size:16px;">' . e($organization_name->organization_name) . '</p>
                    <p style="font-size:16px;"><strong>DAFTAR LIST PRESENSI</strong></p>
                    <p style="font-size:14px;margin-top:5px;">Tanggal Cetak: ' . e($tanggalCetak) . '</p>
                </div>

            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:5%;">No</th>
                        <th style="width:45%;">Data Pegawai</th>
                        <th style="width:50%;">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $rows . '
                </tbody>
            </table>

            <div class="info">
                <p>Dicetak otomatis dari sistem presensi pada ' . e($tanggalCetak) . '.</p>
            </div>
        </body>
        </html>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        // Disable image rendering if GD extension is not available
        if (!extension_loaded('gd')) {
            $options->set('isPhpEnabled', false);
            // Replace images with placeholder text in HTML
            $html = preg_replace('/<img[^>]*src="data:image\/png;base64,[^"]*"[^>]*>/i', 
                '<div style="border:1px solid #ccc;padding:10px;text-align:center;color:#666;">[Tanda Tangan Digital]</div>', 
                $html);
        }
        
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        
        try {
            $dompdf->render();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage() . 
                '. Pastikan PHP GD extension sudah diaktifkan di php.ini');
        }

        $filename = 'TTD_Pengajuan_' . $pengajuanId . '.pdf';
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
