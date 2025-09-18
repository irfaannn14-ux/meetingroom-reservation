<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Tampilkan daftar sumber daya.
     */
    public function index()
    {
        $pengajuans = Pengajuan::with(['ruangan', 'user'])->get();
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     */
    public function tambah()
    {
        $ruangans = Ruangan::all();
        return view('pengajuan.tambah', compact('ruangans'));
    }

    /**
     * Simpan sumber daya yang baru dibuat di penyimpanan.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'ruangan_id' => 'required|integer|exists:ruangans,id',
            'jml_peserta' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'waktu_pinjam' => 'required|date_format:H:i',
            'waktu_kembali' => 'required|date_format:H:i', // Logika after divalidasi manual
        ]);

        // Pengecekan kapasitas ruangan
        $ruangan = Ruangan::find($validatedData['ruangan_id']);
        if ($validatedData['jml_peserta'] > $ruangan->jml_peserta) {
            return back()
                ->withErrors(['jml_peserta' => 'Jumlah peserta melebihi kapasitas maksimal ruangan (' . $ruangan->jml_peserta . ' orang).'])
                ->withInput();
        }

        $tanggal_mulai = Carbon::parse($validatedData['tanggal_pinjam'] . ' ' . $validatedData['waktu_pinjam']);
        $tanggal_selesai = Carbon::parse($validatedData['tanggal_kembali'] . ' ' . $validatedData['waktu_kembali']);

        // Validasi waktu kembali harus setelah waktu pinjam
        if ($tanggal_selesai->lte($tanggal_mulai)) {
            return back()->withErrors(['waktu_kembali' => 'Waktu kembali harus setelah waktu pinjam.'])->withInput();
        }

        // Memeriksa ketersediaan ruangan
        $isRuanganAvailable = Pengajuan::where('ruangan_id', $validatedData['ruangan_id'])
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                $query->where('tanggal_mulai', '<', $tanggal_selesai)
                      ->where('tanggal_selesai', '>', $tanggal_mulai);
            })->doesntExist();

        if (!$isRuanganAvailable) {
            return back()->withErrors(['ruangan_id' => 'Ruangan ini sudah dipesan untuk jadwal tersebut.'])->withInput();
        }

        // Menyimpan data pengajuan baru ke database
        Pengajuan::create([
            'user_id' => session('user_id'),
            'ruangan_id' => $validatedData['ruangan_id'],
            'nama_pengaju' => session('user_nama'),
            'judul_kegiatan' => $validatedData['judul_kegiatan'],
            'kegiatan' => $validatedData['kegiatan'],
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'jml_peserta' => $validatedData['jml_peserta'],
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     */
    public function edit(Pengajuan $pengajuan)
    {
        $ruangans = Ruangan::all();
        return view('pengajuan.tambah', compact('pengajuan', 'ruangans'));
    }

    /**
     * Perbarui sumber daya yang ditentukan di penyimpanan.
     */
    public function update(Request $request, Pengajuan $pengajuan)
    {
        $validatedData = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'ruangan_id' => 'required|integer|exists:ruangans,id',
            'jml_peserta' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'waktu_pinjam' => 'required|date_format:H:i',
            'waktu_kembali' => 'required|date_format:H:i',
        ]);
        
        // Pengecekan kapasitas ruangan
        $ruangan = Ruangan::find($validatedData['ruangan_id']);
        if ($validatedData['jml_peserta'] > $ruangan->jml_peserta) {
            return back()
                ->withErrors(['jml_peserta' => 'Jumlah peserta melebihi kapasitas maksimal ruangan (' . $ruangan->jml_peserta . ' orang).'])
                ->withInput();
        }

        $tanggal_mulai = Carbon::parse($validatedData['tanggal_pinjam'] . ' ' . $validatedData['waktu_pinjam']);
        $tanggal_selesai = Carbon::parse($validatedData['tanggal_kembali'] . ' ' . $validatedData['waktu_kembali']);
        
        if ($tanggal_selesai->lte($tanggal_mulai)) {
            return back()->withErrors(['waktu_kembali' => 'Waktu kembali harus setelah waktu pinjam.'])->withInput();
        }

        $isRuanganAvailable = Pengajuan::where('ruangan_id', $validatedData['ruangan_id'])
            ->where('id', '!=', $pengajuan->id)
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                $query->where('tanggal_mulai', '<', $tanggal_selesai)
                      ->where('tanggal_selesai', '>', $tanggal_mulai);
            })->doesntExist();

        if (!$isRuanganAvailable) {
            return back()->withErrors(['ruangan_id' => 'Ruangan ini sudah dipesan untuk jadwal tersebut.'])->withInput();
        }

        $pengajuan->update([
            'judul_kegiatan' => $validatedData['judul_kegiatan'],
            'kegiatan' => $validatedData['kegiatan'],
            'ruangan_id' => $validatedData['ruangan_id'],
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'jml_peserta' => $validatedData['jml_peserta'],
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui!');
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus!');
    }
}
