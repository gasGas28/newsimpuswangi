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
        Schema::create('simpus_thalasemia', function (Blueprint $table) {
            $table->id();
            $table->uuid('skriningID');

            $table->foreign('skriningID')
                ->references('idSkrining')
                ->on('simpus_skrining_ptm')
                ->cascadeOnDelete();
            
            $table->decimal('hemoglobin', 5, 2)->nullable();
            $table->decimal('mcv', 5, 2)->nullable();
            $table->decimal('mch', 5, 2)->nullable();
            $table->decimal('eritrosit', 5, 2)->nullable();
            $table->decimal('rdw', 5, 2)->nullable();
            
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
