<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        };
    }
}
