<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $cities = [
            ['name' => 'Jakarta Pusat', 'province_name' => 'DKI Jakarta'],
            ['name' => 'Bandung', 'province_name' => 'Jawa Barat'],
            ['name' => 'Semarang', 'province_name' => 'Jawa Tengah'],
            ['name' => 'Yogyakarta', 'province_name' => 'DI Yogyakarta'],
            ['name' => 'Surabaya', 'province_name' => 'Jawa Timur'],
            ['name' => 'Medan', 'province_name' => 'Sumatera Utara'],
            ['name' => 'Denpasar', 'province_name' => 'Bali'],
            ['name' => 'Balikpapan', 'province_name' => 'Kalimantan Timur'],
            ['name' => 'Makassar', 'province_name' => 'Sulawesi Selatan'],
            ['name' => 'Jayapura', 'province_name' => 'Papua'],
        ];

        foreach ($cities as &$city) {
            $city['created_at'] = $now;
            $city['updated_at'] = $now;
        }
        unset($city);

        DB::table('cities')->upsert(
            $cities,
            ['name'],
            ['province_name', 'updated_at']
        );
    }
}
