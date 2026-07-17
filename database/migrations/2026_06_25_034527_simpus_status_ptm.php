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
        Schema::create('simpus_status_ptm', function(Blueprint $table){
            $table->id();
            $table->uuid('skriningID');
            $table->foreign('skriningID')
            ->references('idSkrining')
            ->on('simpus_skrining_ptm')
            ->cascadeOnDelete();
            $table->string('cara_keluar', 30);
            $table->string('kondisi_pasien', 30);
            $table->date('jadwal_kontrol');
            $table->string('rujukan', 30);
            $table->string('transportasi', 30);

            $table->unique('skriningID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
