<?php

namespace Database\Seeders;

use App\Models\MasterEdukasiPTM;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterEdukasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterEdukasiPTM::insert([
            [
                'kode_snomed' => '183063000',
                'nama_edukasi' => 'Edukasi diet rendah garam',
                'display' => 'Low salt diet education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_snomed' => '698360004',
                'nama_edukasi' => 'Edukasi diet diabetes',
                'display' => 'Diabetes self-management education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_snomed' => '171207006',
                'nama_edukasi' => 'Edukasi berhenti merokok',
                'display' => 'Smoking cessation education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_snomed' => '409073007',
                'nama_edukasi' => 'Edukasi aktivitas fisik',
                'display' => 'Education about exercise',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_snomed' => '710824005',
                'nama_edukasi' => 'Edukasi penurunan berat badan',
                'display' => 'Weight management education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_snomed' => '311401005',
                'nama_edukasi' => 'Edukasi nutrisi',
                'display' => 'Nutrition education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_snomed' => '409063005',
                'nama_edukasi' => 'Edukasi gaya hidup sehat',
                'display' => 'Health education',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
