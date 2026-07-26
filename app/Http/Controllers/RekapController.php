<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Ruangan;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: hari ini
        $tipeFilter = $request->input('tipe_filter', 'harian');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $bulan = $request->input('bulan', date('Y-m'));
        $tahun = $request->input('tahun', date('Y'));
        
        $query = Pengajuan::with(['user', 'user.organization', 'ruangan'])
            ->where('status', 'disetujui');
            
        $periodeTeks = "";
            
        if ($tipeFilter == 'harian') {
            $query->whereDate('tanggal_mulai', '<=', $tanggal)
                  ->whereDate('tanggal_selesai', '>=', $tanggal);
            $periodeTeks = Carbon::parse($tanggal)->translatedFormat('d F Y');
        } elseif ($tipeFilter == 'bulanan') {
            $tahunBulan = explode('-', $bulan);
            $tahunFilter = $tahunBulan[0];
            $bulanFilter = $tahunBulan[1];
            
            $query->where(function($q) use ($bulanFilter, $tahunFilter) {
                $q->whereMonth('tanggal_mulai', $bulanFilter)
                  ->whereYear('tanggal_mulai', $tahunFilter)
                  ->orWhere(function($q2) use ($bulanFilter, $tahunFilter) {
                      $q2->whereMonth('tanggal_selesai', $bulanFilter)
                         ->whereYear('tanggal_selesai', $tahunFilter);
                  });
            });
            $periodeTeks = Carbon::parse($bulan . '-01')->translatedFormat('F Y');
        } elseif ($tipeFilter == 'tahunan') {
            $query->where(function($q) use ($tahun) {
                $q->whereYear('tanggal_mulai', $tahun)
                  ->orWhereYear('tanggal_selesai', $tahun);
            });
            $periodeTeks = "Tahun " . $tahun;
        }

        $pengajuanDisetujui = $query->get();
        
        // Grouping berdasarkan ruangan
        $rekapPerRuangan = [];
        $totalPenggunaan = $pengajuanDisetujui->count();
        $totalPeserta = 0;
        
        foreach ($pengajuanDisetujui as $pengajuan) {
            $ruanganId = $pengajuan->ruangan_id;
            if (!isset($rekapPerRuangan[$ruanganId])) {
                $rekapPerRuangan[$ruanganId] = [
                    'nama_ruangan' => $pengajuan->ruangan->nama_ruangan ?? 'Ruangan Dihapus',
                    'kegiatan' => [],
                    'jumlah_kegiatan' => 0,
                    'total_peserta' => 0
                ];
            }
            
            $rekapPerRuangan[$ruanganId]['kegiatan'][] = $pengajuan;
            $rekapPerRuangan[$ruanganId]['jumlah_kegiatan']++;
            $rekapPerRuangan[$ruanganId]['total_peserta'] += $pengajuan->jml_peserta;
            
            $totalPeserta += $pengajuan->jml_peserta;
        }
        
        // Urutkan berdasarkan ruangan yang paling banyak digunakan
        usort($rekapPerRuangan, function($a, $b) {
            return $b['jumlah_kegiatan'] <=> $a['jumlah_kegiatan'];
        });
        
        $totalRuanganAktif = count($rekapPerRuangan);

        return view('rekap.index', compact(
            'rekapPerRuangan', 
            'totalPenggunaan', 
            'totalPeserta', 
            'totalRuanganAktif',
            'tipeFilter',
            'tanggal',
            'bulan',
            'tahun',
            'periodeTeks'
        ));
    }
}
