<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\Presensi;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

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
        // Pakai Validator agar respons error berupa JSON 422 (bukan redirect HTML)
        $validator = Validator::make(
            $request->all(),
            [
                'pengajuan_id' => 'required|integer',
                'nama'         => 'required|string|max:255',
                'jabatan'      => 'required|in:OPD,Lainnya',
                'organisasi'   => 'required|string|max:100',
                'ttd'          => 'required|mimes:pdf|max:2048',
            ],
            [
                'pengajuan_id.required' => 'ID pengajuan tidak valid.',
                'nama.required'         => 'Nama Lengkap wajib diisi.',
                'jabatan.required'      => 'Jabatan wajib dipilih.',
                'jabatan.in'            => 'Jabatan harus OPD atau Lainnya.',
                'organisasi.required'   => 'Organisasi wajib dipilih.',
                'ttd.required'          => 'TTD Digital wajib diunggah.',
                'ttd.mimes'             => 'TTD Digital wajib berupa file PDF.',
                'ttd.max'               => 'Ukuran file TTD melebihi 2 MB.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'ok'      => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $data = $validator->validated();

        // Aturan bisnis: kombinasi jabatan vs organisasi
        if ($data['jabatan'] === 'OPD' && $data['organisasi'] === 'eksternal') {
            return response()->json([
                'ok'      => false,
                'message' => 'Jika Jabatan “OPD”, Organisasi tidak boleh “eksternal”.',
            ], 422);
        }

        if ($data['jabatan'] === 'Lainnya' && $data['organisasi'] !== 'eksternal') {
            return response()->json([
                'ok'      => false,
                'message' => 'Jika Jabatan “Lainnya”, Organisasi harus “eksternal”.',
            ], 422);
        }
        // Simpan file TTD
        $ttdPath = null;
        if ($request->hasFile('ttd')) {
            $ttdPath = $request->file('ttd')->store('presensi/ttd', 'public');
        }

        // Simpan data presensi
        $presensi = Presensi::create([
            'pengajuan_id' => $data['pengajuan_id'],
            'user_id'      => Auth::id(),
            'nama'         => $data['nama'],
            'jabatan'      => $data['jabatan'],
            'organisasi'   => $data['organisasi'],
            'ttd_path'     => $ttdPath,
        ]);

        // Catat aktivitas (dibungkus try agar tidak mengganggu flow jika tabel/log belum siap)
        try {
            ActivityLog::create([
                'user_id'       => Auth::id(),
                'activity'      => 'Presensi berhasil untuk pengajuan ID '.$data['pengajuan_id'],
                'resource_type' => 'pengajuan',
                'resource_id'   => $data['pengajuan_id'],
            ]);
        } catch (\Throwable $e) {
            // boleh diabaikan/log jika perlu
        }

        // Flash untuk ditampilkan di /history
        session()->flash('success', 'Absensi berhasil.');

        // Front-end akan:
        // 1) window.open(SUKMA, '_blank')
        // 2) window.location.href = redirect
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

    public function downloadAllTtd($pengajuanId)
{
    $presensis = Presensi::where('pengajuan_id', $pengajuanId)
        ->whereNotNull('ttd_path')
        ->orderBy('created_at')
        ->get();

    if ($presensis->isEmpty()) {
        return back()->with('error', 'Tidak ada TTD untuk pengajuan ini.');
    }

    $mpdf = new Mpdf([
        'tempDir' => storage_path('app/mpdf-temp'),
        'format'  => 'A4',
    ]);

    $imported = 0;

    foreach ($presensis as $p) {
        $pdfPath = storage_path('app/public/' . ltrim($p->ttd_path, '/'));
        if (!is_file($pdfPath)) continue;

        // Pastikan benar‐benar PDF (jaga-jaga)
        $isPdf = strtolower(pathinfo($pdfPath, PATHINFO_EXTENSION)) === 'pdf';
        if (function_exists('mime_content_type')) {
            $isPdf = $isPdf && mime_content_type($pdfPath) === 'application/pdf';
        }
        if (!$isPdf) continue;

        try {
            $pageCount = $mpdf->SetSourceFile($pdfPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl  = $mpdf->ImportPage($i);
                $size = $mpdf->getTemplateSize($tpl); // menjaga orientasi asli
                $mpdf->AddPage($size['orientation']);
                $mpdf->UseTemplate($tpl);
                $imported++;
            }
        } catch (\Throwable $e) {
            // Lewati file bermasalah, lanjut lainnya
            continue;
        }
    }

    if ($imported === 0) {
        return back()->with('error', 'Tidak ada file PDF TTD yang valid untuk digabung.');
    }

    return $mpdf->Output("ttd-pengajuan-{$pengajuanId}.pdf", Destination::INLINE);
}

}