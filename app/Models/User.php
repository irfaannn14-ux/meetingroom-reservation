<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $table = "users";
    protected $primarykey = "id";
    public $timestamps = false; // Menonaktifkan timestamps (created_at, updated_at)
    protected $fillable = [
        'id',
        'nama_apd',
        'email',
        'password',
        'username',
        'admin',
        'superadmin',
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

}
