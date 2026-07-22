<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;

class Presensi extends Model
{
    protected $table = 'presensis'; // sesuaikan
    protected $fillable = [
        'pengajuan_id', 'nama', 'jabatan', 'no_wa', 'organisasi', 'ttd_path', 'user_id'
    ];

    // Akses nama organisasi untuk tampilan
    public function getOrganisasiNamaAttribute(): string
    {
        // jika sudah berupa teks (mis. 'eksternal'), langsung kembalikan
        if (!ctype_digit((string) $this->organisasi)) {
            return (string) $this->organisasi;
        }

        // kalau angka → lookup ke tabel organization (PK: bkd_organization_id)
        $org = Organization::find($this->organisasi);
        return $org->organization_name ?? (string) $this->organisasi; // fallback angka kalau tak ketemu
    }
}
