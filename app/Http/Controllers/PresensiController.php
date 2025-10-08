<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\ActivityLog;
use App\Models\Organization;

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
                // 2048 KB = 2 MB
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

        // === Simpan data/file bila diperlukan ===
        // Contoh penyimpanan file PDF TTD:
        // $ttdPath = $request->file('ttd')->store('presensi/ttd', 'public');

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
        ]);
    }
}
