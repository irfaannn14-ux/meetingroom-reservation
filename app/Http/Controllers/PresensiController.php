<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Models\Organization;

class PresensiController extends Controller
{
    public function create($id)
    {
        // Ambil semua organisasi dari database
        $organizations = Organization::all();

        return view('presensi.form', [
            'id' => $id,
            'organizations' => $organizations,
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
