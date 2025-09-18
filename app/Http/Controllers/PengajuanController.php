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
     * Tampilkan daftar pengajuan yang berstatus 'pending'.
     */
    public function index()
    {
        // Mengambil data pengajuan yang statusnya 'pending'
        $pengajuans = Pengajuan::with(['ruangan', 'user'])
                                ->where('status', 'pending')
                                ->get();
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * Tampilkan history pengajuan (disetujui dan ditolak).
     */
    public function history()
    {
        $pengajuans = Pengajuan::with(['ruangan', 'user'])
                                ->where('status', '!=', 'pending')
                                ->get();
        return view('history', compact('pengajuans'));
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
            'waktu_kembali' => 'required|date_format:H:i',
        ]);

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
            ->where('status', 'disetujui') // Hanya cek jadwal yang sudah disetujui
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                $query->where('tanggal_mulai', '<', $tanggal_selesai)
                      ->where('tanggal_selesai', '>', $tanggal_mulai);
            })->doesntExist();

        if (!$isRuanganAvailable) {
            return back()->withErrors(['ruangan_id' => 'Ruangan ini sudah dipesan untuk jadwal tersebut.'])->withInput();
        }

        Pengajuan::create([
            'user_id' => session('user_id'),
            'ruangan_id' => $validatedData['ruangan_id'],
            'nama_pengaju' => session('user_nama'),
            'judul_kegiatan' => $validatedData['judul_kegiatan'],
            'kegiatan' => $validatedData['kegiatan'],
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'jml_peserta' => $validatedData['jml_peserta'],
            'status' => 'pending', // Status default saat dibuat
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dikirim!');
    }


    /**
     * Method baru untuk mengubah status pengajuan (Approve/Deny).
     */
    public function updateStatus(Request $request, Pengajuan $pengajuan)
    {
        // Validasi input status
        $request->validate([
            'status' => ['required', Rule::in(['disetujui', 'ditolak'])],
        ]);

        // Update status pengajuan
        $pengajuan->status = $request->status;
        $pengajuan->save();

        // Redirect kembali ke index dengan pesan sukses
        return redirect()->route('pengajuan.index')->with('success', 'Status pengajuan berhasil diperbarui!');
    }


    // ... (method edit, update, dan destroy tidak berubah)

    public function edit(Pengajuan $pengajuan)
    {
        $ruangans = Ruangan::all();
        return view('pengajuan.tambah', compact('pengajuan', 'ruangans'));
    }

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
            ->where('status', 'disetujui')
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

    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus!');
    }
}

