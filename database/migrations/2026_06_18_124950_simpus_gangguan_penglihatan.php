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
        Schema::create('simpus_gangguan_penglihatan', function (Blueprint $table) {
            $table->id();
            $table->uuid('skriningID');
            $table->foreign('skriningID')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();

            $table->string('visus_od', 10)->nullable();
            $table->string('visus_os', 10)->nullable();
            $table->string('pinhole_od', 10)->nullable();
            $table->string('pinhole_os', 10)->nullable();
            $table->string('anterior_os', 10)->nullable();
            $table->string('anterior_od', 10)->nullable();
            $table->string('shadow_os', 20)->nullable();
            $table->string('shadow_od', 20)->nullable();
            $table->string('refleks_os', 10)->nullable();
            $table->string('refleks_od', 10)->nullable();
            $table->string('glaukoma_os', 10)->nullable();
            $table->string('glaukoma_od', 10)->nullable();
            $table->string('retinopati_os', 10)->nullable();
            $table->string('retinopati_od', 10)->nullable();


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
