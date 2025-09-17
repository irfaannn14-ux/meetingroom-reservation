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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mengambil semua data pengajuan dari database,
        // dan memuat relasi 'ruangan' dan 'user' menggunakan with()
        $pengajuans = Pengajuan::with(['ruangan', 'user'])->get();
        // Mengarahkan ke halaman indeks pengajuan dengan data yang ada
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function tambah()
    {
        // Mengambil semua data ruangan dari database
        $ruangans = Ruangan::all();
        // Mengarahkan ke halaman formulir pengajuan dengan data ruangan
        return view('pengajuan.tambah', compact('ruangans'));
    }

    /**
     * Simpan sumber daya yang baru dibuat di penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Melakukan validasi pada data yang dikirimkan dari formulir
        $validatedData = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'ruangan_id' => 'required|integer|exists:ruangans,id',
            'jml_peserta' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'waktu_pinjam' => 'required|date_format:H:i',
            'waktu_kembali' => 'required|date_format:H:i|after:waktu_pinjam',
        ]);

        // Menggabungkan tanggal dan waktu menjadi format datetime
        $tanggal_mulai = $validatedData['tanggal_pinjam'] . ' ' . $validatedData['waktu_pinjam'];
        $tanggal_selesai = $validatedData['tanggal_kembali'] . ' ' . $validatedData['waktu_kembali'];

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
        $user = Auth::user(); // Menggunakan facade Auth untuk mendapatkan data pengguna yang sedang login
        $pengajuan = new Pengajuan;
        $pengajuan->user_id = session('user_id');  // JANGAN DIUBAH, TETAP JADI SESSION SAJA UNTUK SEMENTARA
        $pengajuan->ruangan_id = $validatedData['ruangan_id'];
        $pengajuan->nama_pengaju = session('user_nama'); // JANGAN DIUBAH, TETAP JADI SESSION SAJA UNTUK SEMENTARA
        $pengajuan->judul_kegiatan = $validatedData['judul_kegiatan'];
        $pengajuan->kegiatan = $validatedData['kegiatan'];
        $pengajuan->tanggal_mulai = $tanggal_mulai;
        $pengajuan->tanggal_selesai = $tanggal_selesai;
        $pengajuan->jml_peserta = $validatedData['jml_peserta'];
        $pengajuan->save();

        // Mengarahkan pengguna ke halaman indeks dengan pesan sukses
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Tampilkan sumber daya yang ditentukan.
     *
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
     */
    public function show(Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     *
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengajuan $pengajuan)
    {
        $ruangans = Ruangan::all();
        return view('pengajuan.tambah', compact('pengajuan', 'ruangans'));
    }

    /**
     * Perbarui sumber daya yang ditentukan di penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
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
            'waktu_kembali' => 'required|date_format:H:i|after:waktu_pinjam',
        ]);

        $tanggal_mulai = $validatedData['tanggal_pinjam'] . ' ' . $validatedData['waktu_pinjam'];
        $tanggal_selesai = $validatedData['tanggal_kembali'] . ' ' . $validatedData['waktu_kembali'];

        $isRuanganAvailable = Pengajuan::where('ruangan_id', $validatedData['ruangan_id'])
            ->where('id', '!=', $pengajuan->id) // Hindari pengecekan pada pengajuan yang sedang diedit
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                $query->where('tanggal_mulai', '<', $tanggal_selesai)
                      ->where('tanggal_selesai', '>', $tanggal_mulai);
            })->doesntExist();

        if (!$isRuanganAvailable) {
            return back()->withErrors(['ruangan_id' => 'Ruangan ini sudah dipesan untuk jadwal tersebut.'])->withInput();
        }

        $pengajuan->judul_kegiatan = $validatedData['judul_kegiatan'];
        $pengajuan->kegiatan = $validatedData['kegiatan'];
        $pengajuan->ruangan_id = $validatedData['ruangan_id'];
        $pengajuan->tanggal_mulai = $tanggal_mulai;
        $pengajuan->tanggal_selesai = $tanggal_selesai;
        $pengajuan->jml_peserta = $validatedData['jml_peserta'];
        $pengajuan->save();

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui!');
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     *
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pengajuan = DB::table('pengajuans')->where('id', $id);
        $pengajuan->delete();

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus!');
    }
}
