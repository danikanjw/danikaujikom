<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $categories = [
            ['name' => 'Alat Diagnostik dan Pemantauan (Pemeriksaan)', 'description' => 'Kategori ini mencakup alat yang digunakan untuk mengidentifikasi penyakit, memantau kondisi pasien, dan mengukur parameter vital tubuh.'],
            ['name' => 'Alat Terapi dan Perawatan (Tindakan Medis)', 'description' => 'Alat-alat ini mendukung proses penyembuhan dan perawatan pasien, baik di rumah sakit maupun di rumah.'],
            ['name' => 'Peralatan Bedah dan Instrumen Khusus', 'description' => 'Alat-alat ini digunakan dalam prosedur bedah untuk memotong, menjepit, memegang, atau menarik jaringan.'],
            ['name' => 'Perlengkapan Habis Pakai (Disposable)', 'description' => 'Produk-produk ini dirancang untuk sekali pakai guna mencegah infeksi dan menjaga kebersihan.'],
            ['name' => 'Alat Bantu dan Penunjang Aktivitas Pasien', 'description' => 'Kategori ini mencakup alat yang membantu pasien dalam mobilitas, kenyamanan, atau pemenuhan kebutuhan dasar sehari-hari.'],
            ['name' => 'Peralatan Darurat dan Pendukung Lainnya', 'description' => 'Kategori ini mencakup alat yang krusial dalam situasi darurat dan perangkat pendukung umum dalam fasilitas kesehatan.'],
        ];

        foreach ($categories as &$category) {
            $category['created_at'] = $now;
            $category['updated_at'] = $now;
        }
        unset($category);

        DB::table('categories')->upsert(
            $categories,
            ['name'],
            ['description', 'updated_at']
        );
    }
}