<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaParkir extends Model
{
    protected $table = 'tb_area_parkir';

    protected $primaryKey = 'id_area';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'terisi'    => 'integer',
        ];
    }

    // Relasi: satu area bisa punya banyak transaksi
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_area', 'id_area');
    }
}
