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
        Schema::create('simpus_hipertensi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pemeriksaan_ptm_id')
                ->constrained('simpus_pemeriksaan_ptm')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('sistolik')->nullable();
            $table->unsignedSmallInteger('tekanan_diastolik')->nullable();
            $table->string('kategori_tekanan_darah', 30)->nullable();

            $table->decimal('suhu', 4, 1)->nullable();
            $table->unsignedSmallInteger('nadi')->nullable();
            $table->unsignedSmallInteger('pernapasan')->nullable();

            $table->unique('pemeriksaan_ptm_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpus_hipertensi');
    }
};
