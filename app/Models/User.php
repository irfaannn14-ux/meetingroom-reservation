<?php

namespace App\Models;

// Import class BelongsTo untuk relasi
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Organization;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $table = "users";
    protected $primarykey = "id";
    public $timestamps = false; // Menonaktifkan timestamps (created_at, updated_at)
    protected $fillable = [
        'id',
        'nama',
        'email',
        'password',
        'username',
        'no_wa',
        'role',
        'foto_profil',
        'admin',
        'superadmin',
        'organization_id', // Pastikan kolom ini ada di fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function komentars()
    {
        return $this->hasMany(Komentar::class);
    }
    
    /**
     * Definisikan relasi "belongs to" ke tabel organizations.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        // Menghubungkan User dengan Organization melalui foreign key 'organization_id'
        // di tabel 'users'
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
