<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder untuk RBAC Aplikasi Parkir 2077
 *
 * 3 Role:
 *   - admin   : akses penuh (user, tarif, area parkir, kendaraan, log aktivitas)
 *   - petugas : cetak struk + transaksi (masuk/keluar kendaraan)
 *   - owner   : rekap transaksi (laporan)
 *
 * Permissions mengikuti format: resource:action
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ─────────────────────────────────────────────
        // PERMISSIONS
        // ─────────────────────────────────────────────
        $permissions = [

            // --- User Management (Admin only) ---
            // Tabel: users | tb_user
            'users:read',
            'users:create',
            'users:edit',
            'users:delete',

            // --- Role Management (Admin only) ---
            // Tabel: roles (Spatie)
            'roles:read',
            'roles:create',
            'roles:edit',
            'roles:delete',

            // --- Tarif Parkir (Admin only) ---
            // Tabel: tb_tarif | Kolom: id_tarif, jenis_kendaraan, tarif_per_jam
            'tarif:read',
            'tarif:create',
            'tarif:edit',
            'tarif:delete',

            // --- Area Parkir (Admin only) ---
            // Tabel: tb_area_parkir | Kolom: id_area, nama_area, kapasitas, terisi
            'area-parkir:read',
            'area-parkir:create',
            'area-parkir:edit',
            'area-parkir:delete',

            // --- Kendaraan (Admin only) ---
            // Tabel: tb_kendaraan | Kolom: id_parkir, plat_nomor, jenis_kendaraan, warna, pemilik, id_user
            'kendaraan:read',
            'kendaraan:create',
            'kendaraan:edit',
            'kendaraan:delete',

            // --- Log Aktivitas (Admin only) ---
            // Tabel: tb_log_aktivitas | Kolom: id_log, id_user, aktivitas, waktu_aktivitas
            'log-aktivitas:read',

            // --- Transaksi Parkir (Petugas) ---
            // Tabel: tb_transaksi | Kolom: id_parkir, id_kendaraan, waktu_masuk, waktu_keluar,
            //                               id_tarif, durasi_jam, biaya_total, status, id_user, id_area
            'transaksi:read',
            'transaksi:create',   // catat kendaraan masuk
            'transaksi:edit',     // catat kendaraan keluar
            'transaksi:delete',

            // --- Struk / Cetak (Petugas) ---
            'struk:cetak',        // cetak struk parkir

            // --- Laporan / Rekap (Owner) ---
            'laporan:read',       // rekap transaksi sesuai waktu yang diminta
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ─────────────────────────────────────────────
        // ROLES & ASSIGNMENT
        // ─────────────────────────────────────────────

        /**
         * ADMIN
         * Fitur: Login, Logout, CRUD User, CRUD Tarif Parkir,
         *        CRUD Area Parkir, CRUD Kendaraan, Akses Log Aktivitas
         */
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'users:read', 'users:create', 'users:edit', 'users:delete',
            'roles:read', 'roles:create', 'roles:edit', 'roles:delete',
            'tarif:read', 'tarif:create', 'tarif:edit', 'tarif:delete',
            'area-parkir:read', 'area-parkir:create', 'area-parkir:edit', 'area-parkir:delete',
            'kendaraan:read', 'kendaraan:create', 'kendaraan:edit', 'kendaraan:delete',
            'log-aktivitas:read',
            'transaksi:read', 'transaksi:create', 'transaksi:edit', 'transaksi:delete',
            'struk:cetak',
            'laporan:read',
        ]);

        /**
         * PETUGAS
         * Fitur: Login, Logout, Cetak Struk Parkir, Transaksi (masuk/keluar kendaraan)
         */
        $petugasRole = Role::firstOrCreate(['name' => 'petugas']);
        $petugasRole->syncPermissions([
            'transaksi:read',
            'transaksi:create',
            'transaksi:edit',
            'kendaraan:read',
            'kendaraan:create',
            'tarif:read',
            'area-parkir:read',
            'struk:cetak',
        ]);

        /**
         * OWNER
         * Fitur: Login, Logout, Rekap Transaksi sesuai waktu yang diminta
         */
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        $ownerRole->syncPermissions([
            'laporan:read',
            'transaksi:read',
        ]);

        // ─────────────────────────────────────────────
        // DEFAULT USERS
        // ─────────────────────────────────────────────

        // Admin
        $admin = User::firstOrCreate(
    ['email' => 'kenndeclouv@gmail.com'],
            [
                'name'     => 'kenndeclouv',
                'password' => Hash::make('kenndeclouv'),
            ]
        );
        $admin->assignRole('admin');

        // Petugas
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@parkir2077.test'],
            [
                'name'     => 'Petugas Parkir',
                'password' => Hash::make('petugas12345'),
            ]
        );
        $petugas->assignRole('petugas');

        // Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@parkir2077.test'],
            [
                'name'     => 'Owner Parkir',
                'password' => Hash::make('owner12345'),
            ]
        );
        $owner->assignRole('owner');
    }
}
