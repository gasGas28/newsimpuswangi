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
        Schema::create('simpus_pelayanan_ptm', function (Blueprint $table) {
            $table->id();

            $table->uuid('idSkrining')->unique();

            $table->timestamps();

            $table->foreign('idSkrining')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpus_pemeriksaan_ptm');
        //
    }
};
