<?php

namespace Database\Seeders;

use App\Models\RuangLayanan\SkriningPTM\KategoriMapSatuSehat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class kategorimap_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriMapSatuSehat::insert(
            [
                [
                    'kategori' => 'normal',
                    'jenis_pemeriksaan' => 'asam_urat',
                    'kode_kategori' => 'Z03.8',
                    'display_kategori' => 'No diagnosis'
                ],
                [
                    'kategori' => 'hiperurisemia',
                    'jenis_pemeriksaan' => 'asam_urat',
                    'kode_kategori' => 'M10.9',
                    'display_kategori' => 'Gout, unspecified'
                ],
                [
                    'kategori' => 'normal',
                    'jenis_pemeriksaan' => 'diabetes',
                    'kode_kategori' => 'Z03.8',
                    'display_kategori' => 'No diagnosis'
                ],
                [
                    'kategori' => 'prediabetes',
                    'jenis_pemeriksaan' => 'diabetes',
                    'kode_kategori' => 'R73.0',
                    'display_kategori' => 'Prediabetes'
                ],
                [
                    'kategori' => 'diabetes',
                    'jenis_pemeriksaan' => 'diabetes',
                    'kode_kategori' => 'R73.0',
                    'display_kategori' => 'Prediabetes'
                ],
            ]
        );
        //
    }
}
