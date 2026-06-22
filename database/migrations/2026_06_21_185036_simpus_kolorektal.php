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
        Schema::create('simpus_kolorektal', function (Blueprint $table) {
            $table->id();
            $table->uuid('skriningID');

            $table->foreign('skriningID')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();

            $table->string('kuesioner1', 50);
            $table->string('kuesioner2', 50);
            $table->string('hasil_kuesioner', 50);
            $table->string('colok_dbr', 50);
            $table->string('darah_samar', 50);

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
