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
        Schema::create('simpus_obesitas', function (Blueprint $table){
            $table->id();

            $table->foreignId('pemeriksaan_ptm_id')
                ->constrained('simpus_pemeriksaan_ptm')
                ->cascadeOnDelete();
    
            $table->unsignedSmallInteger('berat_badan')->nullable();
            $table->unsignedSmallInteger('tinggi_badan')->nullable();
            $table->decimal('imt', 5, 2)->nullable();
            $table->unsignedSmallInteger('lingkar_pinggang')->nullable();

            $table->string('interpretasi_ptm', 30)->nullable();
            $table->string('interpretasi_lp', 30)->nullable();

            $table->unique('pemeriksaan_ptm_id');

            $table->timestamps();
            
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpus_obesitas');
    }
};
