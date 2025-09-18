<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     */
    protected $table = "ruangans";

    /**
     * Primary key dari tabel.
     */
    protected $primaryKey = "id"; // Perbaikan: Properti yang benar adalah 'primaryKey' (dengan 'K' besar)

    /**
     * Mengaktifkan timestamps (created_at, updated_at).
     * Disarankan untuk diaktifkan agar Eloquent dapat mengelolanya secara otomatis.
     */
    public $timestamps = true;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        // 'id' tidak perlu ada di sini karena di-handle oleh database
        'nama_ruangan',
        'fasilitas',
        'jml_peserta',
        'foto_ruangan', // Penambahan: 'foto_ruangan' agar bisa disimpan
    ];

    /**
     * Mendefinisikan relasi one-to-many ke model Pengajuan.
     */
    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }
}

