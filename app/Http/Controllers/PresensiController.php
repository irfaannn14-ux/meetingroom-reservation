<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use App\Models\Organization;
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
                'ttd'               => ['required', 'file', 'image', 'mimes:png', 'max:2048'], // PNG ≤ 2MB
            ],
            [
                'pengajuan_id.required'         => 'ID pengajuan tidak valid.',
                'nama.required'                 => 'Nama Lengkap wajib diisi.',
                'jabatan.required'              => 'Jabatan wajib diisi.',
                'organisasi.required'           => 'Organisasi wajib dipilih.',
                'organisasi_manual.required_if' => 'Silakan isi nama organisasi pada kolom yang muncul.',
                'ttd.required' => 'TTD Digital wajib diunggah.',
                'ttd.image'    => 'TTD tidak valid.',
                'ttd.mimes'    => 'TTD harus berupa PNG.',
                'ttd.max'      => 'Ukuran file TTD melebihi 2 MB.',
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



        // Simpan TTD (PNG) ke storage public
        $ttdPath = $request->file('ttd')->store('presensi/ttd', 'public');

        $presensi = \App\Models\Presensi::create([
            'pengajuan_id' => $data['pengajuan_id'],
            'user_id'      => Auth::id(),
            'nama'         => $data['nama'],
            'jabatan'      => $data['jabatan'],
            'organisasi'   => $organisasi,   // <— simpan NAMA organisasi
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
        // Ambil semua presensi dengan TTD untuk pengajuan ini
        $items = Presensi::where('pengajuan_id', $pengajuanId)
            ->whereNotNull('ttd_path')
            ->orderBy('id')
            ->get();


        if ($items->isEmpty()) {
            return back()->with('error', 'Tidak ada TTD untuk diunduh.');
        }

        // Sudah didefinisikan DI ATAS loop:
        $orgMap = Organization::pluck('organization_name', 'bkd_organization_id')->all();

        $pages = [];
        foreach ($items as $p) {
            // 1) Ambil file PNG & jadikan data-URI
            $relPath = ltrim(str_replace('public/', '', (string) $p->ttd_path), '/');
            if (!Storage::disk('public')->exists($relPath)) continue;

            $fullPath = storage_path('app/public/' . $relPath);
            $pngData  = @file_get_contents($fullPath);
            if ($pngData === false) continue;
            $src = 'data:image/png;base64,' . base64_encode($pngData);

            // 2) Siapkan teks (aman di-HTML-kan)
            $nama = e($p->nama ?? '');
            $jab  = e($p->jabatan ?? '');

            // 3) **INI BAGIAN YANG DIMAKSUD**: resolve organisasi
            $orgRaw  = $p->organisasi ?? '';
            $orgName = $orgMap[$orgRaw] ?? ($orgMap[(int)$orgRaw] ?? $orgRaw); // jika ID → nama, kalau sudah nama pakai apa adanya
            $org     = e($orgName);

            $waktu = $p->created_at ? $p->created_at->format('d-m-Y H:i') . ' WIB' : '';

            // 4) Susun halaman
            $pages[] = '
      <div class="page">
        <div class="meta">
          <strong>' . $nama . '</strong>' . ($jab ? ' &mdash; ' . $jab : '') . '
          ' . ($org ? '<br><span class="org">' . $org . '</span>' : '') . '
          ' . ($waktu ? '<br><small class="time">' . $waktu . '</small>' : '') . '
        </div>
        <img src="' . $src . '" alt="TTD">
      </div>';
        }


        if (!$pages) {
            return back()->with('error', 'Semua file TTD tidak ditemukan.');
        }

        // HTML untuk Dompdf
        $html = '<html><head><meta charset="utf-8">
      <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; }
        .page { page-break-after: always; text-align: center; }
        .page:last-child { page-break-after: auto; }
        .meta { margin-bottom: 12px; font-size: 12px; color:#333; }
        .org  { color:#555; }
        .time { color:#777; }
        img { max-width: 90%; height: auto; border: 1px solid #e5e5e5; }
      </style>
    </head><body>' . implode('', $pages) . '</body></html>';

        // Render PDF
        $opts = new Options();
        $opts->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($opts);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Nama file
        $pdfName = 'ttd_pengajuan_' . $pengajuanId . '_' . date('Ymd_His') . '.pdf';

        // Kirim ke browser
        return response($dompdf->output(), 200, [
            'Content-Type'              => 'application/pdf',
            'Content-Disposition'       => 'attachment; filename="' . $pdfName . '"',
            'Cache-Control'             => 'private, max-age=0, must-revalidate',
            'X-Content-Type-Options'    => 'nosniff',
        ]);
    }
}
