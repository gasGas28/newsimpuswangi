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
        Schema::create('simpus_profil_lipid', function (Blueprint $table) {
            $table->id();


            $table->foreignId('skriningID')
                ->constrained('simpus_skrining_ptm')
                ->cascadeOnDelete();

            $table->decimal('kolesterol_total', 5, 2)->nullable();
            $table->decimal('ldl', 5, 2)->nullable();
            $table->decimal('hdl', 5, 2)->nullable();
            $table->decimal('trigliserida', 5, 2)->nullable();

            $table->string('interpretasi_kolesterol_total', 30)->nullable();
            $table->string('interpretasi_ldl', 30)->nullable();
            $table->string('interpretasi_hdl', 30)->nullable();
            $table->string('interpretasi_trigliserida', 30)->nullable();

            $table->unique('simpus_skrining_ptm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpus_profil_lipid');
    }
};
