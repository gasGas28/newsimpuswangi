<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skrining_ptm_status_pasien', function (Blueprint $table) {
            if (Schema::hasColumn('skrining_ptm_status_pasien', 'kategori_risiko_ptm')) {
                $table->dropColumn('kategori_risiko_ptm');
            }
            if (Schema::hasColumn('skrining_ptm_status_pasien', 'status_kasus')) {
                $table->dropColumn('status_kasus');
            }
            if (Schema::hasColumn('skrining_ptm_status_pasien', 'keputusan_klinis')) {
                $table->dropColumn('keputusan_klinis');
            }
            if (Schema::hasColumn('skrining_ptm_status_pasien', 'catatan_kesimpulan')) {
                $table->dropColumn('catatan_kesimpulan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('skrining_ptm_status_pasien', function (Blueprint $table) {
            if (! Schema::hasColumn('skrining_ptm_status_pasien', 'kategori_risiko_ptm')) {
                $table->string('kategori_risiko_ptm', 50)->nullable();
            }
            if (! Schema::hasColumn('skrining_ptm_status_pasien', 'status_kasus')) {
                $table->string('status_kasus', 50)->nullable();
            }
            if (! Schema::hasColumn('skrining_ptm_status_pasien', 'keputusan_klinis')) {
                $table->string('keputusan_klinis', 50)->nullable();
            }
            if (! Schema::hasColumn('skrining_ptm_status_pasien', 'catatan_kesimpulan')) {
                $table->text('catatan_kesimpulan')->nullable();
            }
        });
    }
};
