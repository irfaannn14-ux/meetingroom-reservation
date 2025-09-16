<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Tambahkan facade Auth

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fungsi ini akan mengarahkan ke halaman indeks pengajuan
        // Anda dapat menambahkan logika untuk mengambil data pengajuan di sini
        $pengajuans = Pengajuan::all();
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tambah()
    {
        // Fungsi ini akan mengarahkan ke form pengajuan
        return view('pengajuan.tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Lakukan validasi data yang dikirimkan dari form
        $validatedData = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'ruangan_id' => 'required|integer|exists:ruangans,id', // Pastikan ruangan_id ada di tabel ruangans
            'jml_peserta' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'waktu_pinjam' => 'required|date_format:H:i',
            'waktu_kembali' => 'required|date_format:H:i|after:waktu_pinjam',
        ]);

        // Gabungkan tanggal dan waktu menjadi format datetime
        $tanggal_mulai = $validatedData['tanggal_pinjam'] . ' ' . $validatedData['waktu_pinjam'];
        $tanggal_selesai = $validatedData['tanggal_kembali'] . ' ' . $validatedData['waktu_kembali'];

        // Cek ketersediaan ruangan
        $isRuanganAvailable = Pengajuan::where('ruangan_id', $validatedData['ruangan_id'])
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                $query->where('tanggal_mulai', '<', $tanggal_selesai)
                      ->where('tanggal_selesai', '>', $tanggal_mulai);
            })->doesntExist();

        if (!$isRuanganAvailable) {
            return back()->withErrors(['ruangan_id' => 'Ruangan ini sudah dipesan untuk jadwal tersebut.'])->withInput();
        }

        // Simpan data pengajuan baru ke database
        $pengajuan = new Pengajuan;
        $pengajuan->judul_kegiatan = $validatedData['judul_kegiatan'];
        $pengajuan->kegiatan = $validatedData['kegiatan'];
        $pengajuan->ruangan_id = $validatedData['ruangan_id'];
        $pengajuan->jml_peserta = $validatedData['jml_peserta'];
        $pengajuan->tanggal_mulai = $tanggal_mulai;
        $pengajuan->tanggal_selesai = $tanggal_selesai;
        $pengajuan->user_id = Auth::id(); // Tambahkan user_id yang mengajukan
        $pengajuan->nama_pengaju = Auth::user()->name; // Ambil nama pengguna yang mengajukan
        $pengajuan->save();

        // Redirect pengguna ke halaman indeks dengan pesan sukses
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
     */
    public function show(Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengajuan  $pengajuan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengajuan $pengajuan)
    {
        //
    }
}
