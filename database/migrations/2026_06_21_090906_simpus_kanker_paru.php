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
        Schema::create('simpus_kanker_paru', function (Blueprint $table) {
            $table->id();
            $table->uuid('skriningID');

            $table->foreign('skriningID')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();
            
            $table->string('kuesioner1', 100);
            $table->string('kuesioner2', 100);
            $table->string('kuesioner3', 100);
            $table->string('kuesioner4', 100);
            $table->string('kuesioner5', 100);
            $table->string('kuesioner6', 100);
            $table->string('kuesioner7', 100);
            $table->string('hasil_kuesioner', 100);

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
