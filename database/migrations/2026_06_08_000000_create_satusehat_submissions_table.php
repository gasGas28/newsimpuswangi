<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satusehat_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('resource_type')->default('ptm');
            $table->string('bundle_id')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satusehat_submissions');
    }
};
