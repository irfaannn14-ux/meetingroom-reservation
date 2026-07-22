<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pengajuan extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = "pengajuans";
    protected $primarykey = "id";
    public $timestamps = true; // Mengaktifkan timestamps (created_at, updated_at)
    protected $fillable = [
        'user_id',
        'ruangan_id',
        'approver_id',
        'nama_pengaju',
        'judul_kegiatan',
        'kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'jml_peserta',
        'status',
        'alasan_penolakan',
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
     * Relasi ke User (Admin yang menyetujui/menolak)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

}
