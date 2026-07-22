<?php

namespace App\Http\Controllers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        if (session('user_role') !== 'Super Admin') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        // Get audits for Pengajuan model
        $logs = Audit::with(['user', 'auditable.ruangan', 'auditable.approver'])
            ->where('auditable_type', 'App\\Models\\Pengajuan')
            ->latest()
            ->get();

        return view('log.index', ['logs' => $logs]);
    }
}