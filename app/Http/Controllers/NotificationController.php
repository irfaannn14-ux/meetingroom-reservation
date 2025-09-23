<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        try {
            // Get activity logs for the last 10 events
            $logs = ActivityLog::with(['user'])
                ->where('activity', 'LIKE', '%pengajuan%')  // Match any activity containing 'pengajuan'
                ->where(function($query) {
                    $query->where('activity', 'LIKE', 'Menyetujui pengajuan%')
                          ->orWhere('activity', 'LIKE', 'Menolak pengajuan%')
                          ->orWhere('activity', 'LIKE', 'Mengedit pengajuan%')
                          ->orWhere('activity', 'LIKE', 'Menghapus pengajuan%');
                })
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $notifications = [];
            foreach ($logs as $log) {
                try {
                    // Find the pengajuan
                    $pengajuan = Pengajuan::find($log->resource_id);
                    if (!$pengajuan) continue; // Skip if pengajuan was deleted
                    
                    $userName = $log->user ? $log->user->nama : 'System';
                    
                    // Parse the activity string to determine status
                    if (str_contains($log->activity, 'Menyetujui pengajuan')) {
                        $status = 'disetujui';
                        $type = 'menyetujui';
                    } elseif (str_contains($log->activity, 'Menolak pengajuan')) {
                        $status = 'ditolak';
                        $type = 'menolak';
                    } elseif (str_contains($log->activity, 'Mengedit pengajuan')) {
                        $status = 'diubah';
                        $type = 'mengubah';
                    } elseif (str_contains($log->activity, 'Menghapus pengajuan')) {
                        $status = 'dihapus';
                        $type = 'menghapus';
                    } else {
                        continue; // Skip if not a recognized activity
                    }

                    $notifications[] = [
                        'message' => "Pengajuan \"{$pengajuan->judul_kegiatan}\" {$status} oleh {$userName}",
                        'created_at' => Carbon::parse($log->created_at)->diffForHumans(),
                        'type' => $type
                    ];
                } catch (\Exception $e) {
                    \Log::error("Error processing notification {$log->id}: " . $e->getMessage());
                    continue;
                }
            }

            return response()->json($notifications);
            
        } catch (\Exception $e) {
            \Log::error('Error in getNotifications: ' . $e->getMessage());
            return response()->json(['error' => true, 'message' => 'Failed to load notifications'], 500);
        }
    }
}