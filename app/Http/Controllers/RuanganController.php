<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class RuanganController extends Controller
{
    public function index(){
        $all = DB::table('ruangans')->get();
            return view('ruangan.index', compact('all'));
    }

    public function tambah(){
        return view('ruangan.tambah');
    }

    public function store(Request $request){
        $save['id'] = $request->id;
        $save['nama_ruangan'] = $request->nama_ruangan;
        $save['jml_peserta'] = $request->jml_peserta;
        $save['fasilitas'] = $request->fasilitas;

        DB::table('ruangans')->insert($save);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function edit($id){
        $one = DB::table('ruangans')->where('id',$id)->first();
        return view('ruangan.edit',compact('one'));
    }

    public function update(Request $request, $id){
        $save['id'] = $request->id;
        $save['nama_ruangan'] = $request->nama_ruangan;
        $save['jml_peserta'] = $request->jml_peserta;
        $save['fasilitas'] = $request->fasilitas;

        DB::table('ruangans')->where('id',$id)->update($save);
        return redirect()->route('ruangan.index');
    }

    public function destroy($id){
        // DELETE FROM mahasiswa where id;
        DB::table('ruangans')->where('id',$id)->delete();
        return redirect()->route('ruangan.index');
    }
}
