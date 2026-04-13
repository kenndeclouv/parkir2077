<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Transaksi extends Model
{
    protected $table = 'tb_transaksi';

    protected $primaryKey = 'id_parkir';

    protected $fillable = [
        'id_kendaraan',
        'waktu_masuk',
        'waktu_keluar',
        'id_tarif',
        'durasi_jam',
        'biaya_total',
        'status',
        'id_user',
        'id_area',
    ];

    protected function casts(): array
    {
        return [
            'waktu_masuk'  => 'datetime',
            'waktu_keluar' => 'datetime',
            'durasi_jam'   => 'integer',
            'biaya_total'  => 'decimal:0',
            'status'       => 'string',
        ];
    }

    // Relasi: transaksi milik satu kendaraan
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id_parkir');
    }

    // Relasi: transaksi menggunakan satu tarif
    public function tarif(): BelongsTo
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }

    // Relasi: transaksi dicatat oleh satu petugas (users)
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // Relasi: transaksi di satu area parkir
    public function areaParkir(): BelongsTo
    {
        return $this->belongsTo(AreaParkir::class, 'id_area', 'id_area');
    }
}
