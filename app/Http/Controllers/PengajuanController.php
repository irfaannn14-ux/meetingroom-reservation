<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index(){
    $all = DB::table('pengajuans')
        ->leftJoin('users','users.id','=','pengajuans.user_id')
        ->leftJoin('ruangans','ruangans.id','=','pengajuans.ruangan_id')
        ->select([
            'pengajuans.*',
            'users.nama_apd as nama_apd',
            'ruangans.nama_ruangan as ruangan',
        ])
    ->get();
    return view('listdata', compact('all'));
    }

    public function tambah(){
        $user = User::all();
        $ruangan = Ruangan::all();

        return view('pengajuan.tambah', compact('user', 'ruangan'));
    }


    public function store(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'kegiatan' => 'required',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'jml_peserta' => 'required|integer',
            'whatsapp' => 'required',
            'nama_apd' => 'required|string|max:255',
        ]);

        Pengajuan::create([
            'user_id' => $request->user_id,
            'ruangan_id' => $request->ruangan_id,
            'kegiatan' => $request->kegiatan,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'jml_peserta' => $request->jml_peserta,
            'whatsapp' => $request->whatsapp,
            'status' => 'pending',
            'nama_apd' => $request->nama_apd,
        ]);

        return redirect()->route('pengajuan.index')->with('success','Pengajuan berhasil disimpan');
    }

}
