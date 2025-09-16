<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    // Nama tabel yang sesuai di database
    protected $table = 'organization';

    // Nama primary key jika bukan 'id'
    protected $primaryKey = 'bkd_organization_id';

    // Set primary key sebagai non-incrementing jika nilainya bukan integer
    public $incrementing = false;

    // Tipe data primary key jika bukan integer
    protected $keyType = 'string';

    // Menonaktifkan timestamps jika tabel tidak memiliki created_at dan updated_at
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bkd_organization_id',
        'organization_name',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
