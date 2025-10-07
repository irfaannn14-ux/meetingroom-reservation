<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class PresensiController extends Controller
{
    public function create($id)
    {
        // contoh opsi organisasi (silakan ganti dari DB kalau ada)
        $organisasiOptions = [
            'biro-umum'   => 'Biro Umum',
            'bappeda'     => 'Bappeda',
            'dispendik'   => 'Dinas Pendidikan',
            'kominfo'     => 'Diskominfo',
            'sekretariat' => 'Sekretariat',
        ];

        return view('presensi.form', [
            'id' => $id,
            'organisasiOptions' => $organisasiOptions,
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'pengajuan_id' => 'required|integer',
            'nama'         => 'required|string|max:255',
            'jabatan'      => 'required|in:OPD,Lainnya',
            'organisasi'   => 'required|string|max:100',
            // PDF only:
            'ttd'          => 'required|mimes:pdf|max:2048',
        ]);

        // enforce aturan: jika jabatan Lainnya -> organisasi harus 'eksternal'
        if ($data['jabatan'] === 'OPD' && $data['organisasi'] === 'eksternal') {
            return response()->json([
                'ok' => false,
                'message' => 'Jika Jabatan “OPD”, Organisasi tidak boleh “eksternal”.'
            ], 422);
        }


        // Simpan file TTD jika perlu
        // $ttdPath = $request->file('ttd')->store('presensi/ttd', 'public');

        // catat sukses + flash
        session()->flash('success', 'Absensi berhasil dicatat.');

        return response()->json([
            'ok'       => true,
            'redirect' => route('history'),
        ]);
    }

}
