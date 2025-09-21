<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::all();
        return view('ruangan.index', compact('ruangans'));
    }

    public function tambah()
    {
        if (session('user_role') === 'OPD') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }
        return view('ruangan.tambah');
    }

    public function store(Request $request)
    {
        if (session('user_role') === 'OPD') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        $validatedData = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'jml_peserta' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto_ruangan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menyimpan file ke 'storage/app/public/ruangan'
        // dan mengembalikan path 'ruangan/namafile.jpg' untuk database.
        $path = $request->file('foto_ruangan')->store('ruangan', 'public');
        $validatedData['foto_ruangan'] = $path;

        Ruangan::create($validatedData);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function edit(Ruangan $ruangan)
    {
        if (session('user_role') === 'OPD') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }
        return view('ruangan.tambah', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        if (session('user_role') === 'OPD') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        $validatedData = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'jml_peserta' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto_ruangan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_ruangan')) {
            if ($ruangan->foto_ruangan) {
                Storage::disk('public')->delete($ruangan->foto_ruangan);
            }
            $path = $request->file('foto_ruangan')->store('ruangan', 'public');
            $validatedData['foto_ruangan'] = $path;
        }

        $ruangan->update($validatedData);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function destroy(Ruangan $ruangan)
    {
        if (session('user_role') === 'OPD') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        if ($ruangan->foto_ruangan) {
            Storage::disk('public')->delete($ruangan->foto_ruangan);
        }
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus!');
    }
}
