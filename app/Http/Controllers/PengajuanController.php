<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ActivityLog;

class PengajuanController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan statistik.
     */
    public function dashboard()
    {
        $userRole = session('user_role');
        $userId = session('user_id');

        $query = Pengajuan::query();

        // Jika role adalah OPD, filter berdasarkan user_id
        if ($userRole === 'OPD') {
            $query->where('user_id', $userId);
        }

        // Kloning query untuk setiap status agar tidak saling mempengaruhi
        $totalQuery = clone $query;
        $diterimaQuery = clone $query;
        $baruQuery = clone $query;
        $ditolakQuery = clone $query;

        $stats = [
            'total' => $totalQuery->count(),
            'diterima' => $diterimaQuery->where('status', 'disetujui')->count(),
            'baru' => $baruQuery->where('status', 'pending')->count(),
            'ditolak' => $ditolakQuery->where('status', 'ditolak')->count(),
        ];

        return view('dashboard', compact('stats'));
    }

    /**
     * Tampilkan daftar pengajuan yang berstatus 'pending'.
     */
    public function index()
    {
        $userRole = session('user_role');
        $userId = session('user_id');

        $query = Pengajuan::with(['ruangan', 'user.organization'])
            ->where('status', 'pending');

        // Jika role adalah OPD, hanya tampilkan pengajuan milik user tersebut
        if ($userRole === 'OPD') {
            $query->where('user_id', $userId);
        }

        $pengajuans = $query->get();
            
        return view('pengajuan.index', compact('pengajuans'));
    }

    /**
     * Tampilkan riwayat pengajuan (yang statusnya bukan 'pending').
     */
    public function history()
    {
        $userRole = session('user_role');
        $userId = session('user_id');

        $query = Pengajuan::with(['ruangan', 'user.organization'])
            ->where('status', '!=', 'pending');

        // Jika role adalah OPD, hanya tampilkan riwayat pengajuan milik user tersebut
        if ($userRole === 'OPD') {
            $query->where('user_id', $userId);
        }

        $pengajuans = $query->get();
            
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

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Mengedit pengajuan ' . $pengajuan->judul_kegiatan,
            'resource_type' => 'pengajuan',
            'resource_id' => $pengajuan->id,
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

        // Jika status yang diminta adalah 'disetujui', cek ketersediaan dan batas peminjaman
        if ($validated['status'] === 'disetujui') {
            $tanggal_pinjam = Carbon::parse($pengajuan->tanggal_mulai)->toDateString();

            // Cek 1: Batas peminjaman harian (maksimal 3 kali)
            $peminjamanHarian = Pengajuan::where('ruangan_id', $pengajuan->ruangan_id)
                ->where('status', 'disetujui')
                ->whereDate('tanggal_mulai', $tanggal_pinjam)
                ->count();

            if ($peminjamanHarian >= 3) {
                return redirect()->route('pengajuan.index')->with('error', 'Gagal menyetujui: Ruangan telah mencapai batas maksimal peminjaman (3 kali) pada tanggal tersebut.');
            }

            // Cek 2: Ketersediaan ruangan (jadwal tidak bentrok)
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

        $activity = $validated['status'] === 'disetujui' ? 'Menyetujui' : 'Menolak';
        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => $activity . ' pengajuan ' . $pengajuan->judul_kegiatan,
            'resource_type' => 'pengajuan',
            'resource_id' => $pengajuan->id,
        ]);

        $message = $validated['status'] === 'disetujui' ? 'Pengajuan berhasil disetujui!' : 'Pengajuan berhasil ditolak!';

        return redirect()->route('pengajuan.index')->with('success', $message);
    }

    /**
     * Hapus pengajuan dari database.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $judul_kegiatan = $pengajuan->judul_kegiatan;
        $pengajuan->delete();

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Menghapus pengajuan ' . $judul_kegiatan,
            'resource_type' => 'pengajuan',
            'resource_id' => $pengajuan->id,
        ]);

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
                'backgroundColor' => 'rgba(40, 167, 69, 0.2)', // Light green background
                'borderColor' => '#28a745', // Solid green border
                'textColor' => '#0f5132' // Dark green text for contrast
            ];
        });

        return response()->json($events);
    }
}
