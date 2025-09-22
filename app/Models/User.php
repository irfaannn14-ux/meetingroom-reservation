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
    // Table name is the Laravel default 'users' so explicit declaration is optional.
    protected $table = 'users';

    // Use default primary key 'id' (no need to override unless different).

    // The users table in migrations includes timestamps (created_at/updated_at),
    // so leave $timestamps as the default (true).

    /**
     * The attributes that are mass assignable.
     * Only include columns that exist in the migrations.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'organization_id',
        'no_wa',
        'role',
        'foto_profil',
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
    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Laravel's 'hashed' cast will automatically hash passwords on set
        // (requires Laravel 9.34+). If unavailable, consider a mutator.
        'password' => 'hashed',
    ];

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

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
