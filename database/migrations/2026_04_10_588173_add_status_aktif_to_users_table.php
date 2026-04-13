<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom status_aktif ke tabel users (Laravel default).
     * Merge dari skema tb_user tugas parkir — user domain cukup satu tabel.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status_aktif')->default(1)->after('remember_token');
            // 1 = aktif, 0 = nonaktif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_aktif');
        });
    }
};
