<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

        // Get all booked rooms (not just top 5)
        $topRoomsQuery = Pengajuan::select('ruangan_id', DB::raw('count(*) as total'))
            ->groupBy('ruangan_id')
            ->orderBy('total', 'desc')
            ->with('ruangan');

        // (Dihapus: Filter OPD untuk Top Rooms agar user biasa bisa melihat ruangan mana yang sering dipakai secara global)

        $topRooms = $topRoomsQuery->get();

        // Prepare data for chart
        $roomNames = [];
        $roomCounts = [];
        
        // Generate gradient colors for all rooms
        $roomColors = [];
        $baseColors = ['#6366F1', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981', '#06B6D4', '#EF4444', '#F97316', '#84CC16', '#14B8A6'];
        
        foreach ($topRooms as $index => $room) {
            $roomNames[] = $room->ruangan ? $room->ruangan->nama_ruangan : 'Unknown';
            $roomCounts[] = $room->total;
            // Cycle through colors if more rooms than colors available
            $roomColors[] = $baseColors[$index % count($baseColors)];
        }

        // Get heatmap data - booking count by time slots (2 hour intervals)
        $heatmapQuery = Pengajuan::select(
                DB::raw('HOUR(tanggal_mulai) as hour'),
                DB::raw('DAYNAME(tanggal_mulai) as day_name'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'disetujui');

        // (Dihapus: Filter OPD untuk Heatmap agar user biasa bisa melihat jam sibuk secara global)

        $bookingsByTime = $heatmapQuery
            ->groupBy('hour', 'day_name')
            ->get();

        // Define time slots (2-hour intervals)
        $timeSlots = [
            '00:00-02:00',
            '02:00-04:00',
            '04:00-06:00',
            '06:00-08:00',
            '08:00-10:00',
            '10:00-12:00',
            '12:00-14:00',
            '14:00-16:00',
            '16:00-18:00',
            '18:00-20:00',
            '20:00-22:00',
            '22:00-00:00'
        ];

        // Define days mapping
        $daysMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        $days = array_values($daysMap);

        // Initialize heatmap data structure
        $heatmapData = [];
        foreach ($timeSlots as $slot) {
            $row = ['name' => $slot];
            foreach ($days as $day) {
                $row['data'][] = 0;
            }
            $heatmapData[] = $row;
        }

        // Fill in actual booking counts
        foreach ($bookingsByTime as $booking) {
            $hour = $booking->hour;
            $dayName = $booking->day_name;
            $count = $booking->count;

            // Find time slot index (every 2 hours)
            $slotIndex = floor($hour / 2);
            
            // Find day index
            $dayIndex = array_search($daysMap[$dayName] ?? $dayName, $days);

            if ($slotIndex !== false && $dayIndex !== false && isset($heatmapData[$slotIndex])) {
                $heatmapData[$slotIndex]['data'][$dayIndex] = (int)$count;
            }
        }

        return view('dashboard', compact('stats', 'roomNames', 'roomCounts', 'roomColors', 'heatmapData', 'days'));
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

        // Urutkan dari yang terbaru ke yang terlama
        $pengajuans = $query->orderBy('created_at', 'desc')->get();

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

        // Urutkan dari yang terbaru ke yang terlama
        $pengajuans = $query->orderBy('created_at', 'desc')->get();

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

        $now = Carbon::now();
        $tanggalPinjam = Carbon::parse($validatedData['tanggal_pinjam']);
        $daysUntilEvent = $now->startOfDay()->diffInDays($tanggalPinjam->startOfDay(), false);
        
        // ATURAN BARU #5: Validasi Lead Time - Minimal 2 hari sebelum kegiatan
        // if ($daysUntilEvent < 2) {
        //     return back()->withErrors([
        //         'tanggal_pinjam' => 'Pengajuan harus dilakukan minimal 2 hari sebelum kegiatan. Anda hanya dapat mengajukan untuk tanggal ' . $now->copy()->addDays(2)->format('d M Y') . ' atau setelahnya.'
        //     ])->withInput();
        // }

        // ATURAN BARU #6: Validasi Maximum Advance Booking - Maksimal 1 tahun ke depan
        $maxBookingDate = $now->copy()->addYear();
        if ($tanggalPinjam->gt($maxBookingDate)) {
            return back()->withErrors([
                'tanggal_pinjam' => 'Booking maksimal untuk 1 tahun ke depan. Tanggal maksimal: ' . $maxBookingDate->format('d M Y')
            ])->withInput();
        }

        $tanggal_mulai = Carbon::parse($validatedData['tanggal_pinjam'] . ' ' . $validatedData['waktu_pinjam']);
        $tanggal_selesai = Carbon::parse($validatedData['tanggal_kembali'] . ' ' . $validatedData['waktu_kembali']);

        // ATURAN BARU #1: Validasi Jam Operasional - Hanya 06:00 - 18:00 WIB
        $jamMulai = $tanggal_mulai->hour + ($tanggal_mulai->minute / 60);
        $jamSelesai = $tanggal_selesai->hour + ($tanggal_selesai->minute / 60);
        
        if ($jamMulai < 6 || $jamMulai >= 18) {
            return back()->withErrors([
                'waktu_pinjam' => 'Jam mulai harus antara 06:00 - 18:00 WIB'
            ])->withInput();
        }
        
        if ($jamSelesai < 6 || $jamSelesai > 18) {
            return back()->withErrors([
                'waktu_kembali' => 'Jam selesai harus antara 06:00 - 18:00 WIB'
            ])->withInput();
        }

        // ATURAN BARU #3: Validasi Kelipatan 30 Menit
        if ($tanggal_mulai->minute % 30 !== 0) {
            return back()->withErrors([
                'waktu_pinjam' => 'Waktu pinjam harus dalam kelipatan 30 menit (contoh: 08:00, 08:30, 09:00, dst)'
            ])->withInput();
        }
        
        if ($tanggal_selesai->minute % 30 !== 0) {
            return back()->withErrors([
                'waktu_kembali' => 'Waktu kembali harus dalam kelipatan 30 menit (contoh: 10:00, 10:30, 11:00, dst)'
            ])->withInput();
        }

        if ($tanggal_selesai->lte($tanggal_mulai)) {
            return back()->withErrors(['waktu_kembali' => 'Waktu kembali harus setelah waktu pinjam.'])->withInput();
        }

        // ATURAN BARU #2: Validasi Durasi Minimum - 2 Jam
        $durasiMenit = $tanggal_mulai->diffInMinutes($tanggal_selesai, false);
        if ($durasiMenit < 120) { // 2 jam = 120 menit
            $waktuMinimal = $tanggal_mulai->copy()->addHours(2);
            return back()->withErrors([
                'waktu_kembali' => 'Waktu kembali harus minimal 2 jam setelah waktu pinjam. Minimal: ' . $waktuMinimal->format('d M Y H:i')
            ])->withInput();
        }

        // ATURAN BARU #4: Validasi Durasi Maksimum - 6 Hari
        $durasiHari = $tanggal_mulai->diffInDays($tanggal_selesai, true);
        if ($durasiHari > 6) {
            return back()->withErrors([
                'tanggal_kembali' => 'Durasi peminjaman maksimal 6 hari'
            ])->withInput();
        }

        // Validasi Kapasitas Ruangan
        $ruangan = Ruangan::find($validatedData['ruangan_id']);
        if ($validatedData['jml_peserta'] > $ruangan->jml_peserta) {
            return back()
                ->withErrors(['jml_peserta' => 'Jumlah peserta melebihi kapasitas maksimal ruangan (' . $ruangan->jml_peserta . ' orang).'])
                ->withInput();
        }

        // ATURAN BARU #7 & #8: Validasi Konflik Jadwal - Termasuk PENDING dan Overlap Detail
        $conflictingBookings = Pengajuan::where('ruangan_id', $validatedData['ruangan_id'])
            ->whereIn('status', ['disetujui', 'pending']) // Cek APPROVED dan PENDING
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                // Case 1: New starts during existing [09:00-11:00] vs [10:00-12:00]
                $query->where(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '<=', $tanggal_mulai)
                      ->where('tanggal_selesai', '>', $tanggal_mulai);
                })
                // Case 2: New ends during existing [08:00-10:00] vs [09:00-11:00]
                ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '<', $tanggal_selesai)
                      ->where('tanggal_selesai', '>=', $tanggal_selesai);
                })
                // Case 3: New covers existing [08:00-12:00] vs [09:00-11:00]
                ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '>=', $tanggal_mulai)
                      ->where('tanggal_selesai', '<=', $tanggal_selesai);
                })
                // Case 4: New inside existing [09:30-10:30] vs [09:00-11:00]
                ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '<=', $tanggal_mulai)
                      ->where('tanggal_selesai', '>=', $tanggal_selesai);
                });
            })
            ->first();

        if ($conflictingBookings) {
            $statusText = $conflictingBookings->status === 'pending' ? 'menunggu persetujuan' : 'sudah disetujui';
            $conflictStart = Carbon::parse($conflictingBookings->tanggal_mulai)->format('d M Y H:i');
            $conflictEnd = Carbon::parse($conflictingBookings->tanggal_selesai)->format('d M Y H:i');
            
            return back()->withErrors([
                'ruangan_id' => "Ruangan ini sudah dipesan untuk jadwal yang bertabrakan ({$conflictStart} - {$conflictEnd}, status: {$statusText}). Silakan pilih waktu lain."
            ])->withInput();
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

        // ATURAN BARU #5: Validasi Lead Time - Minimal 2 hari sebelum kegiatan
        $now = Carbon::now();
        $tanggalPinjam = Carbon::parse($validatedData['tanggal_pinjam']);
        $daysUntilEvent = $now->startOfDay()->diffInDays($tanggalPinjam->startOfDay(), false);
        
        if ($daysUntilEvent < 2) {
            return back()->withErrors([
                'tanggal_pinjam' => 'Pengajuan harus dilakukan minimal 2 hari sebelum kegiatan. Anda hanya dapat mengajukan untuk tanggal ' . $now->copy()->addDays(2)->format('d M Y') . ' atau setelahnya.'
            ])->withInput();
        }

        // ATURAN BARU #6: Validasi Maximum Advance Booking - Maksimal 1 tahun ke depan
        $maxBookingDate = $now->copy()->addYear();
        if ($tanggalPinjam->gt($maxBookingDate)) {
            return back()->withErrors([
                'tanggal_pinjam' => 'Booking maksimal untuk 1 tahun ke depan. Tanggal maksimal: ' . $maxBookingDate->format('d M Y')
            ])->withInput();
        }

        $tanggal_mulai = Carbon::parse($validatedData['tanggal_pinjam'] . ' ' . $validatedData['waktu_pinjam']);
        $tanggal_selesai = Carbon::parse($validatedData['tanggal_kembali'] . ' ' . $validatedData['waktu_kembali']);

        // ATURAN BARU #1: Validasi Jam Operasional - Hanya 06:00 - 18:00 WIB
        $jamMulai = $tanggal_mulai->hour + ($tanggal_mulai->minute / 60);
        $jamSelesai = $tanggal_selesai->hour + ($tanggal_selesai->minute / 60);
        
        if ($jamMulai < 6 || $jamMulai >= 18) {
            return back()->withErrors([
                'waktu_pinjam' => 'Jam mulai harus antara 06:00 - 18:00 WIB'
            ])->withInput();
        }
        
        if ($jamSelesai < 6 || $jamSelesai > 18) {
            return back()->withErrors([
                'waktu_kembali' => 'Jam selesai harus antara 06:00 - 18:00 WIB'
            ])->withInput();
        }

        // ATURAN BARU #3: Validasi Kelipatan 30 Menit
        if ($tanggal_mulai->minute % 30 !== 0) {
            return back()->withErrors([
                'waktu_pinjam' => 'Waktu pinjam harus dalam kelipatan 30 menit (contoh: 08:00, 08:30, 09:00, dst)'
            ])->withInput();
        }
        
        if ($tanggal_selesai->minute % 30 !== 0) {
            return back()->withErrors([
                'waktu_kembali' => 'Waktu kembali harus dalam kelipatan 30 menit (contoh: 10:00, 10:30, 11:00, dst)'
            ])->withInput();
        }

        if ($tanggal_selesai->lte($tanggal_mulai)) {
            return back()->withErrors(['waktu_kembali' => 'Waktu kembali harus setelah waktu pinjam.'])->withInput();
        }

        // ATURAN BARU #2: Validasi Durasi Minimum - 2 Jam
        $durasiMenit = $tanggal_mulai->diffInMinutes($tanggal_selesai, false);
        if ($durasiMenit < 120) { // 2 jam = 120 menit
            $waktuMinimal = $tanggal_mulai->copy()->addHours(2);
            return back()->withErrors([
                'waktu_kembali' => 'Waktu kembali harus minimal 2 jam setelah waktu pinjam. Minimal: ' . $waktuMinimal->format('d M Y H:i')
            ])->withInput();
        }

        // ATURAN BARU #4: Validasi Durasi Maksimum - 6 Hari
        $durasiHari = $tanggal_mulai->diffInDays($tanggal_selesai, true);
        if ($durasiHari > 6) {
            return back()->withErrors([
                'tanggal_kembali' => 'Durasi peminjaman maksimal 6 hari'
            ])->withInput();
        }

        // Validasi Kapasitas Ruangan
        $ruangan = Ruangan::find($validatedData['ruangan_id']);
        if ($validatedData['jml_peserta'] > $ruangan->jml_peserta) {
            return back()
                ->withErrors(['jml_peserta' => 'Jumlah peserta melebihi kapasitas maksimal ruangan (' . $ruangan->jml_peserta . ' orang).'])
                ->withInput();
        }

        // ATURAN BARU #7 & #8: Validasi Konflik Jadwal - Termasuk PENDING dan Overlap Detail
        $conflictingBookings = Pengajuan::where('ruangan_id', $validatedData['ruangan_id'])
            ->where('id', '!=', $pengajuan->id) // Exclude pengajuan yang sedang diedit
            ->whereIn('status', ['disetujui', 'pending']) // Cek APPROVED dan PENDING
            ->where(function ($query) use ($tanggal_mulai, $tanggal_selesai) {
                // Case 1: New starts during existing [09:00-11:00] vs [10:00-12:00]
                $query->where(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '<=', $tanggal_mulai)
                      ->where('tanggal_selesai', '>', $tanggal_mulai);
                })
                // Case 2: New ends during existing [08:00-10:00] vs [09:00-11:00]
                ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '<', $tanggal_selesai)
                      ->where('tanggal_selesai', '>=', $tanggal_selesai);
                })
                // Case 3: New covers existing [08:00-12:00] vs [09:00-11:00]
                ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '>=', $tanggal_mulai)
                      ->where('tanggal_selesai', '<=', $tanggal_selesai);
                })
                // Case 4: New inside existing [09:30-10:30] vs [09:00-11:00]
                ->orWhere(function ($q) use ($tanggal_mulai, $tanggal_selesai) {
                    $q->where('tanggal_mulai', '<=', $tanggal_mulai)
                      ->where('tanggal_selesai', '>=', $tanggal_selesai);
                });
            })
            ->first();

        if ($conflictingBookings) {
            $statusText = $conflictingBookings->status === 'pending' ? 'menunggu persetujuan' : 'sudah disetujui';
            $conflictStart = Carbon::parse($conflictingBookings->tanggal_mulai)->format('d M Y H:i');
            $conflictEnd = Carbon::parse($conflictingBookings->tanggal_selesai)->format('d M Y H:i');
            
            return back()->withErrors([
                'ruangan_id' => "Ruangan ini sudah dipesan untuk jadwal yang bertabrakan ({$conflictStart} - {$conflictEnd}, status: {$statusText}). Silakan pilih waktu lain."
            ])->withInput();
        }

        // Capture old data
        $oldData = $pengajuan->only(['judul_kegiatan', 'kegiatan', 'ruangan_id', 'tanggal_mulai', 'tanggal_selesai', 'jml_peserta']);

        $pengajuan->update([
            'judul_kegiatan' => $validatedData['judul_kegiatan'],
            'kegiatan' => $validatedData['kegiatan'],
            'ruangan_id' => $validatedData['ruangan_id'],
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'jml_peserta' => $validatedData['jml_peserta'],
        ]);

        // Capture new data
        $newData = $pengajuan->only(['judul_kegiatan', 'kegiatan', 'ruangan_id', 'tanggal_mulai', 'tanggal_selesai', 'jml_peserta']);

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Mengedit pengajuan ' . $pengajuan->judul_kegiatan,
            'resource_type' => 'pengajuan',
            'resource_id' => $pengajuan->id,
            'details' => [
                'old_data' => $oldData,
                'new_data' => $newData,
            ]
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
     * Menampilkan booking dengan status APPROVED (hijau) dan PENDING (kuning/oranye)
     */
    public function calendarEvents()
    {
        $pengajuans = Pengajuan::with('ruangan')
            ->whereIn('status', ['disetujui', 'pending'])
            ->get();

        $events = $pengajuans->map(function ($pengajuan) {
            // Tentukan warna berdasarkan status
            if ($pengajuan->status === 'disetujui') {
                // APPROVED: Hijau
                $backgroundColor = 'rgba(40, 167, 69, 0.2)'; // Light green background
                $borderColor = '#28a745'; // Solid green border
                $textColor = '#0f5132'; // Dark green text
                $statusLabel = '✓';
            } else {
                // PENDING: Kuning/Oranye
                $backgroundColor = 'rgba(255, 193, 7, 0.2)'; // Light yellow/amber background
                $borderColor = '#ffc107'; // Solid yellow border
                $textColor = '#856404'; // Dark yellow text
                $statusLabel = '⏳';
            }

            return [
                'title' => $statusLabel . ' ' . $pengajuan->judul_kegiatan . ' (' . ($pengajuan->ruangan->nama_ruangan ?? 'N/A') . ')',
                'start' => $pengajuan->tanggal_mulai,
                'end' => $pengajuan->tanggal_selesai,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'textColor' => $textColor,
                'extendedProps' => [
                    'status' => $pengajuan->status,
                    'kegiatan' => $pengajuan->kegiatan,
                    'jml_peserta' => $pengajuan->jml_peserta
                ]
            ];
        });

        return response()->json($events);
    }
    public function generateQrCode($id)
    {
        // generate token sementara
        $token = Str::random(32);

        // simpan ke DB dengan expired 
        DB::table('pengajuan_tokens')->updateOrInsert(
            ['pengajuan_id' => $id],
            ['token' => $token, 'expired_at' => Carbon::now()->addMinutes(1)]
        );

        return response()->json(['token' => $token]);
    }
}
