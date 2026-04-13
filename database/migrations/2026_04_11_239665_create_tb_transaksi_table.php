<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id('id_parkir');
            $table->unsignedBigInteger('id_kendaraan');
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();
            $table->unsignedBigInteger('id_tarif');
            $table->integer('durasi_jam')->nullable();
            $table->decimal('biaya_total', 10, 0)->nullable();
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->unsignedBigInteger('id_user'); // petugas yang mencatat
            $table->unsignedBigInteger('id_area');
            $table->timestamps();

            $table->foreign('id_kendaraan')
                ->references('id_parkir')
                ->on('tb_kendaraan')
                ->cascadeOnDelete();

            $table->foreign('id_tarif')
                ->references('id_tarif')
                ->on('tb_tarif')
                ->restrictOnDelete();

            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->foreign('id_area')
                ->references('id_area')
                ->on('tb_area_parkir')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
    }
};
