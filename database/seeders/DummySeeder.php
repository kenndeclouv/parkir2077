<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Area Parkir
        $areas = [
            [
                'nama_area' => 'Area Timur (Motor)',
                'kapasitas' => 200,
                'terisi'    => 0,
            ],
            [
                'nama_area' => 'Area Barat (Mobil)',
                'kapasitas' => 100,
                'terisi'    => 0,
            ],
            [
                'nama_area' => 'Area VIP',
                'kapasitas' => 50,
                'terisi'    => 0,
            ],
        ];

        foreach ($areas as $area) {
            \App\Models\AreaParkir::updateOrCreate(['nama_area' => $area['nama_area']], $area);
        }

        // 2. Seed Tarif Parkir
        $tarifs = [
            [
                'jenis_kendaraan' => 'motor',
                'tarif_per_jam'   => 2000,
            ],
            [
                'jenis_kendaraan' => 'mobil',
                'tarif_per_jam'   => 5000,
            ],
            [
                'jenis_kendaraan' => 'lainnya',
                'tarif_per_jam'   => 1500,
            ],
        ];

        foreach ($tarifs as $tarif) {
            \App\Models\Tarif::updateOrCreate(['jenis_kendaraan' => $tarif['jenis_kendaraan']], $tarif);
        }

        // 3. Seed Kendaraan Dummy
        $kendaraans = [
            [
                'plat_nomor'      => 'B 1234 ABC',
                'jenis_kendaraan' => 'mobil',
                'warna'           => 'Hitam',
                'pemilik'         => 'Budi Santoso',
                'id_user'         => null,
            ],
            [
                'plat_nomor'      => 'D 5678 DEF',
                'jenis_kendaraan' => 'motor',
                'warna'           => 'Merah',
                'pemilik'         => 'Andi Wijaya',
                'id_user'         => null,
            ],
            [
                'plat_nomor'      => 'N 9012 GHI',
                'jenis_kendaraan' => 'mobil',
                'warna'           => 'Putih',
                'pemilik'         => 'Citra Kirana',
                'id_user'         => null,
            ],
            [
                'plat_nomor'      => 'L 3456 JKL',
                'jenis_kendaraan' => 'motor',
                'warna'           => 'Biru',
                'pemilik'         => 'Dewi Lestari',
                'id_user'         => null,
            ],
            [
                'plat_nomor'      => 'AB 7890 MNO',
                'jenis_kendaraan' => 'lainnya',
                'warna'           => 'Hijau',
                'pemilik'         => 'Eko Prasetyo',
                'id_user'         => null,
            ],
        ];

        foreach ($kendaraans as $kendaraan) {
            \App\Models\Kendaraan::updateOrCreate(['plat_nomor' => $kendaraan['plat_nomor']], $kendaraan);
        }
    }
}
