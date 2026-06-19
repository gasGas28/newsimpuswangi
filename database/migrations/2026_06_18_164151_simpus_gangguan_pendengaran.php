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
        Schema::create('simpus_gangguan_pendengaran', function (Blueprint $table) {
            $table->id();
            $table->uuid('skriningID');

            $table->foreign('skriningID')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();
            $table->string('tuli_kiri')->nullable();
            $table->string('tuli_kanan')->nullable();
            $table->string('omsk_kiri')->nullable();
            $table->string('omsk_kanan')->nullable();
            $table->string('serumen_kanan')->nullable();
            $table->string('serumen_kiri')->nullable();
            $table->string('presbi_kiri')->nullable();
            $table->string('presbi_kanan')->nullable();
            $table->string('bisik_kiri')->nullable();
            $table->string('bisik_kanan')->nullable();

            $table->unique('skriningID');
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
