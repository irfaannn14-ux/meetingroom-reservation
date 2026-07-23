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

        // Get all audits
        $logs = Audit::with(['user'])
            ->latest()
            ->get();

        return view('log.index', ['logs' => $logs]);
    }
}