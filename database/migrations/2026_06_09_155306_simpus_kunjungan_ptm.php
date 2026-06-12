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
        Schema::create('simpus_kunjungan_ptm', function (Blueprint $table) {
            $table->id();
            $table->uuid('idSkrining')->unique();
            $table->uuid('idPelayanan')->unique();
            $table->uuid('idLoket')->unique();
            $table->string('nik_pasien', 16);
            $table->datetime('tanggal_skrining');
            $table->string('id_petugas', 50);
            $table->string('fasyankes', 50);
            $table->string('jenis_kunjungan', 50);
            $table->string('keluhan_utama', 200);
            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
