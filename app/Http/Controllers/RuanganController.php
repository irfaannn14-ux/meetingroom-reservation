<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::latest()->get();
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
            'nama_ruangan' => 'required|string|max:255|unique:ruangans,nama_ruangan',
            'jml_peserta' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto_ruangan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menyimpan file ke 'storage/app/public/ruangan'
        // dan mengembalikan path 'ruangan/namafile.jpg' untuk database.
        $path = $request->file('foto_ruangan')->store('ruangan', 'public');
        $validatedData['foto_ruangan'] = $path;

        $ruangan = Ruangan::create($validatedData);

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Menambahkan data ruangan baru dengan nama: ' . $ruangan->nama_ruangan . ' (Kapasitas: ' . $ruangan->jml_peserta . ' orang)',
            'resource_type' => 'ruangan',
            'resource_id' => $ruangan->id,
        ]);

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

        // Capture old data
        $oldData = $ruangan->only(['nama_ruangan', 'jml_peserta', 'fasilitas']);
        
        $ruangan->update($validatedData);

        // Capture new data
        $newData = $ruangan->only(['nama_ruangan', 'jml_peserta', 'fasilitas']);

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Mengubah data ruangan: ' . $ruangan->nama_ruangan,
            'resource_type' => 'ruangan',
            'resource_id' => $ruangan->id,
            'details' => [
                'old_data' => $oldData,
                'new_data' => $newData,
            ]
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function destroy(Ruangan $ruangan)
    {
        if (session('user_role') === 'OPD') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // Check if ruangan has active bookings
        $activeBookings = $ruangan->pengajuans()
            ->whereIn('status', ['pending', 'disetujui'])
            ->where('tanggal_selesai', '>=', now())
            ->count();

        if ($activeBookings > 0) {
            return redirect()->route('ruangan.index')
                ->with('error', 'Ruangan tidak dapat dihapus karena memiliki booking aktif!');
        }

        if ($ruangan->foto_ruangan) {
            Storage::disk('public')->delete($ruangan->foto_ruangan);
        }
        
        $nama_ruangan = $ruangan->nama_ruangan;
        $ruangan->delete();

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Menghapus data ruangan: ' . $nama_ruangan,
            'resource_type' => 'ruangan',
            'resource_id' => $ruangan->id,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus!');
    }
}