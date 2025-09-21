<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        if (session('user_role') !== 'Super Admin') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        $logs = ActivityLog::with('user')
            ->whereHas('user', function ($query) {
                $query->whereIn('role', ['Admin', 'Super Admin']);
            })
            ->latest()
            ->get();

        return view('log.index', ['logs' => $logs]);
    }
}