<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Kendaraan extends Model
{
    protected $table = 'tb_kendaraan';

    protected $primaryKey = 'id_parkir';

    protected $fillable = [
        'plat_nomor',
        'jenis_kendaraan',
        'warna',
        'pemilik',
        'id_user',
    ];

    // Relasi: kendaraan dimiliki oleh satu user (opsional)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi: satu kendaraan bisa punya banyak transaksi parkir
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_kendaraan', 'id_parkir');
    }
}
