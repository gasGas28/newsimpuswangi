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
        Schema::create('simpus_ekg', function (Blueprint $table) {
            $table->id();
            $table->uuid('skriningID');

            $table->foreign('skriningID')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();
            
            $table->decimal('hr', 5, 2)->nullable();
            $table->string('irama', 50)->nullable();
            $table->string('axis', 50)->nullable();
            $table->string('segmen_st', 50)->nullable();
            $table->string('qrs', 50)->nullable();
            $table->string('kesimpulan_ekg', 50)->nullable();

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
