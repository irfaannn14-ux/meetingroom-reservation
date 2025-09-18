<?php

namespace App\Http\Controllers;

use App\Models\Ruangan; // Menggunakan Model Ruangan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index(){
        $ruangans = Ruangan::all(); // Menggunakan Eloquent Model
        return view('ruangan.index', compact('ruangans'));
    }

    public function tambah(){
        return view('ruangan.tambah');
    }

    public function store(Request $request){
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'jml_peserta' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto_ruangan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('foto_ruangan')->store('public/images/ruangan');
        $filename = basename($path);

        Ruangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'jml_peserta' => $request->jml_peserta,
            'fasilitas' => $request->fasilitas,
            'foto_ruangan' => $filename,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }
    
    // Menggunakan Route Model Binding
    public function edit(Ruangan $ruangan){
        // Menggunakan view 'tambah' yang sudah dibuat dinamis
        return view('ruangan.tambah', compact('ruangan'));
    }

    // Menggunakan Route Model Binding
    public function update(Request $request, Ruangan $ruangan){
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'jml_peserta' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto_ruangan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto tidak wajib diisi saat update
        ]);

        if ($request->hasFile('foto_ruangan')) {
            // Hapus foto lama
            if ($ruangan->foto_ruangan) {
                Storage::delete('public/images/ruangan/' . $ruangan->foto_ruangan);
            }
            // Simpan foto baru
            $path = $request->file('foto_ruangan')->store('public/images/ruangan');
            $validated['foto_ruangan'] = basename($path);
        }
        
        $ruangan->update($validated);
        
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui!');
    }

    // Menggunakan Route Model Binding
    public function destroy(Ruangan $ruangan){
        // Hapus foto dari storage
        if ($ruangan->foto_ruangan) {
            Storage::delete('public/images/ruangan/' . $ruangan->foto_ruangan);
        }
        
        // Hapus data dari database
        $ruangan->delete();
        
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus!');
    }
}
