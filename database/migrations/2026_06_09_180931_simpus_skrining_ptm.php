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
        Schema::create('simpus_skrining_ptm', function (Blueprint $table) {
            $table->id();

            $table->uuid('idSkrining')->unique();

            $table->string('idPelayanan')->unique();
            $table->string('idLoket');

            $table->string('status')->default('draft');

            $table->enum('sync_status', [
                'pending',
                'success',
                'failed'
            ])->default('pending');

            $table->dateTime('sync_time')->nullable();

            $table->text('error_message')->nullable();

            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::dropIfExists('simpus_skrining_ptm');
        //
    }
};
