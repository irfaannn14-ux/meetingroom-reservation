<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // Allow mass assignment for main fields and optional resource linkage
    protected $fillable = ['user_id', 'activity', 'resource_type', 'resource_id', 'details'];
    
    // Cast details to array/JSON
    protected $casts = [
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper to fetch the related resource if resource_type/resource_id are set
    public function resource()
    {
        if (!$this->resource_type || !$this->resource_id) return null;
        switch ($this->resource_type) {
            case 'pengajuan':
                return \App\Models\Pengajuan::with(['ruangan','user'])->find($this->resource_id);
            case 'ruangan':
                return \App\Models\Ruangan::find($this->resource_id);
            case 'user':
                return \App\Models\User::with('organization')->find($this->resource_id);
            default:
                return null;
        }
    }
}