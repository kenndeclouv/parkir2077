<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'status_aktif'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'status_aktif'      => 'integer', // 1=aktif, 0=nonaktif
        ];
    }

    // Relasi: satu user bisa punya banyak kendaraan terdaftar
    public function kendaraans(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'id_user', 'id');
    }

    // Relasi: satu user (petugas) bisa mencatat banyak transaksi
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'id');
    }

    // Relasi: satu user bisa punya banyak log aktivitas
    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'id_user', 'id');
    }

    /**
     * Scope untuk filter user aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', 1);
    }
}
