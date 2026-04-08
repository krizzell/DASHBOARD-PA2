<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Perkembangan;

class PerkembanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample perkembangan data using correct siswa IDs from AkunSeeder
        $perkembanganData = [
            [
                'id_guru' => 1,
                'nomor_induk_siswa' => 1001,  // Adi
                'bulan' => 3,
                'tahun' => 2026,
                'kategori' => 'Kognitif',
                'deskripsi' => 'Anak dapat mengenali angka 1-10 dengan baik. Perkembangan kognitif sudah sangat baik, terutama dalam hal pengenalan warna, bentuk, dan ukuran. Anak juga sudah mampu memecahkan puzzle sederhana dengan lancar.',
                'status_utama' => 'BSB'
            ],
            [
                'id_guru' => 1,
                'nomor_induk_siswa' => 1001,  // Adi
                'bulan' => 3,
                'tahun' => 2026,
                'kategori' => 'Akademik',
                'deskripsi' => 'Kemampuan membaca anak sudah berkembang sesuai harapan. Dapat membaca beberapa kata sederhana dan memahami cerita pendek. Keterampilan menulis juga menunjukkan perkembangan yang baik.',
                'status_utama' => 'BSH'
            ],
        ];

        foreach ($perkembanganData as $data) {
            Perkembangan::create($data);
        }
    }
}
