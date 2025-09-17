<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Tambahkan ini untuk mengelola file

class RuanganController extends Controller
{
    public function index(){
        $ruangans = DB::table('ruangans')->get();
        return view('ruangan.index', compact('ruangans'));
    }

    public function tambah(){
        return view('ruangan.tambah');
    }

    public function store(Request $request){
        $save = $request->except('_token'); // Ambil semua data kecuali token CSRF
        
        // Proses unggahan foto ruangan
        if ($request->hasFile('foto_ruangan')) {
            $file = $request->file('foto_ruangan');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/ruangan'), $filename);
            $save['foto_ruangan'] = $filename;
        } else {
            $save['foto_ruangan'] = null;
        }
    
        DB::table('ruangans')->insert($save);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }
    
    public function edit($id){
        $one = DB::table('ruangans')->where('id',$id)->first();
        return view('ruangan.edit',compact('one'));
    }

    public function update(Request $request, $id){
        $save = $request->except('_token', '_method'); // Ambil semua data kecuali token dan metode

        // Proses unggahan foto ruangan baru
        if ($request->hasFile('foto_ruangan')) {
            // Hapus foto lama jika ada
            $old_foto = DB::table('ruangans')->where('id', $id)->first()->foto_ruangan;
            if ($old_foto && File::exists(public_path('images/ruangan/' . $old_foto))) {
                File::delete(public_path('images/ruangan/' . $old_foto));
            }
            
            // Unggah foto baru
            $file = $request->file('foto_ruangan');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/ruangan'), $filename);
            $save['foto_ruangan'] = $filename;
        }
        
        DB::table('ruangans')->where('id',$id)->update($save);
        return redirect()->route('ruangan.index');
    }

    public function destroy($id){
        // Temukan nama file foto ruangan yang akan dihapus
        $ruangan = DB::table('ruangans')->where('id', $id)->first();
        
        if ($ruangan) {
            // Hapus file foto dari penyimpanan
            $foto_path = public_path('images/ruangan/' . $ruangan->foto_ruangan);
            if (File::exists($foto_path)) {
                File::delete($foto_path);
            }
            // Hapus data dari database
            DB::table('ruangans')->where('id', $id)->delete();
        }
        
        return redirect()->route('ruangan.index');
    }
}
