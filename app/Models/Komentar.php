<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;
    protected $table = "komentars";
    protected $primarykey = "id";
    public $timestamps = false; // Menonaktifkan timestamps (created_at, updated_at)
    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'isi_komentar',
    ];

    /**
     * Relasi ke Pengajuan (komentar dimiliki satu pengajuan)
     */
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    /**
     * Relasi ke User (komentar dibuat oleh satu user/admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
