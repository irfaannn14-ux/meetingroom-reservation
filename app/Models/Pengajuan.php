<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;
    protected $table = "pengajuans";
    protected $primarykey = "id";
    public $timestamps = true; // Mengaktifkan timestamps (created_at, updated_at)
    protected $fillable = [
        'user_id',
        'ruangan_id',
        'nama_pengaju',
        'judul_kegiatan',
        'kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'jml_peserta',
        'status',
    ];

     /**
     * Relasi ke User (satu pengajuan diajukan oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Ruangan (satu pengajuan pakai satu ruangan)
     */
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    /**
     * Relasi ke Komentar (satu pengajuan bisa punya banyak komentar)
     */
    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }
}
