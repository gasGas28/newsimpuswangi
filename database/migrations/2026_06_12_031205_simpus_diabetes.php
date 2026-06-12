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
        Schema::create('simpus_diabetes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pemeriksaan_ptm_id')
                ->constrained('simpus_pemeriksaan_ptm')
                ->cascadeOnDelete();

            $table->decimal('gula_darah_puasa', 5, 2)->nullable();
            $table->decimal('gula_darah_2_jam_pp', 5, 2)->nullable();
            $table->decimal('gula_darah_sewaktu', 5, 2)->nullable();
            $table->decimal('hba1c', 4, 2)->nullable();

            $table->string('kategori_gula_darah_puasa', 30)->nullable();
            $table->string('kategori_gula_darah_2_jam_pp', 30)->nullable();
            $table->string('kategori_gula_darah_sewaktu', 30)->nullable();
            $table->string('kategori_hba1c', 30)->nullable();

            $table->unique('pemeriksaan_ptm_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpus_diabetes');
        //
    }
};
