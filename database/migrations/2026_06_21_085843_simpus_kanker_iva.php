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
        Schema::create('simpus_kanker_iva', function (Blueprint $table){
            $table->id();
            $table->uuid('skriningID');

            $table->foreign('skriningID')
            ->references('idSkrining')
            ->on('simpus_skrining_ptm')
            ->cascadeOnDelete();

            $table->string('inspekulo', 50);
            $table->string('iva', 50);
            $table->string('hpv_dna', 50);
            $table->string('sadanis', 50);
            $table->string('usg', 50);

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
