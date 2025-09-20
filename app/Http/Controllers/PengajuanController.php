<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengajuanController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan statistik.
     */
    public function dashboard()
    {
        $stats = [
            'total' => Pengajuan::count(),
            'diterima' => Pengajuan::where('status', 'disetujui')->count(),
            'baru' => Pengajuan::where('status', 'pending')->count(),
            'ditolak' => Pengajuan::where('status', 'ditolak')->count(),
        ];

        return view('dashboard', compact('stats'));
    }

    /**
     * Tampilkan daftar pengajuan yang berstatus 'pending'.
     */
    public function index()
    {
        // Memuat relasi ruangan dan juga relasi user beserta organisasinya
        $pengajuans = Pengajuan::with(['ruangan', 'user.organization'])
            ->where('status', 'pending')
            ->get();
            
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * Tampilkan riwayat pengajuan (yang statusnya bukan 'pending').
     */
    public function history()
    {
        $pengajuans = Pengajuan::with(['ruangan', 'user.organization'])
            ->where('status', '!=', 'pending')
            ->get();
            
        return view('history', compact('pengajuans'));
    }

    /**
     * Tampilkan formulir untuk membuat pengajuan baru.
     */
    public function tambah()
    {
        $ruangans = Ruangan::all();
        return view('pengajuan.tambah', compact('ruangans'));
    }

    /**
     * Simpan pengajuan baru ke database.
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
        ]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dikirim!');
    }

    /**
     * Tampilkan formulir untuk mengedit pengajuan.
     */
    public function edit(Pengajuan $pengajuan)
    {
        $ruangans = Ruangan::all();
        return view('pengajuan.tambah', compact('pengajuan', 'ruangans'));
    }

    /**
     * Perbarui pengajuan yang ada di database.
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
            ->where('status', 'disetujui')
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
     * Perbarui status pengajuan (disetujui/ditolak).
     */
    public function updateStatus(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        // Jika status yang diminta adalah 'disetujui', cek ketersediaan ruangan
        if ($validated['status'] === 'disetujui') {
            $tanggal_mulai = $pengajuan->tanggal_mulai;
            $tanggal_selesai = $pengajuan->tanggal_selesai;

            $isRuanganAvailable = Pengajuan::where('ruangan_id', $pengajuan->ruangan_id)
                ->where('id', '!=', $pengajuan->id) // Abaikan pengajuan saat ini
                ->where('status', 'disetujui')      // Hanya cek jadwal yang sudah disetujui
                ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                    $query->where('tanggal_mulai', '<', $tanggal_selesai)
                          ->where('tanggal_selesai', '>', $tanggal_mulai);
                })->doesntExist();

            // Jika ruangan tidak tersedia (ada jadwal bentrok), kembalikan dengan error
            if (!$isRuanganAvailable) {
                return redirect()->route('pengajuan.index')->with('error', 'Gagal menyetujui: Jadwal bentrok dengan pengajuan lain yang sudah disetujui.');
            }
        }

        $pengajuan->update(['status' => $validated['status']]);

        $message = $validated['status'] === 'disetujui' ? 'Pengajuan berhasil disetujui!' : 'Pengajuan berhasil ditolak!';

        return redirect()->route('pengajuan.index')->with('success', $message);
    }

    /**
     * Hapus pengajuan dari database.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus!');
    }

    /**
     * Sediakan data event untuk FullCalendar.
     */
    public function calendarEvents()
    {
        $pengajuans = Pengajuan::with('ruangan')
            ->where('status', 'disetujui')
            ->get();

        $events = $pengajuans->map(function ($pengajuan) {
            return [
                'title' => $pengajuan->judul_kegiatan . ' (' . ($pengajuan->ruangan->nama_ruangan ?? 'N/A') . ')',
                'start' => $pengajuan->tanggal_mulai,
                'end' => $pengajuan->tanggal_selesai,
                'color' => '#28a745', // Warna hijau untuk acara yang disetujui
            ];
        });

        return response()->json($events);
    }
}
