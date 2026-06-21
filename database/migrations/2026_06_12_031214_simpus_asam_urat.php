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
        Schema::create('simpus_asam_urat', function (Blueprint $table) {
            $table->id();


            $table->foreignId('skriningID')
                ->constrained('simpus_skrining_ptm')
                ->cascadeOnDelete();

            $table->decimal('asam_urat', 5, 2)->nullable();
            $table->string('kategori_asam_urat', 30)->nullable();

            $table->unique('simpus_skrining_ptm');

            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpus_asam_urat');
        //
    }
};
