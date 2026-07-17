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
        Schema::create('simpus_data_edukasi', function (Blueprint $table) {
            $table->id();
            $table->uuid('skriningID');
            $table->foreign('skriningID')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();
            $table->string('kode_snomed', 100);
            $table->string('keterangan', 255);
            $table->string('procedureId', 50);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpus_data_edukasi');
    }
};
