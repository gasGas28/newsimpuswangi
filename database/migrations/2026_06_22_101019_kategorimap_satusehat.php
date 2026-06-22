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
        Schema::create('kategorimap_satusehat', function (Blueprint $table){
            $table->id();
            $table->string('kategori');
            $table->string('jenis_pemeriksaan');
            $table->string('kode_kategori');
            $table->string('display_kategori');

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
