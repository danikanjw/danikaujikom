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
            ['city_name' => 'Jakarta Pusat', 'province_name' => 'DKI Jakarta'],
            ['city_name' => 'Bandung', 'province_name' => 'Jawa Barat'],
            ['city_name' => 'Semarang', 'province_name' => 'Jawa Tengah'],
            ['city_name' => 'Yogyakarta', 'province_name' => 'DI Yogyakarta'],
            ['city_name' => 'Surabaya', 'province_name' => 'Jawa Timur'],
            ['city_name' => 'Medan', 'province_name' => 'Sumatera Utara'],
            ['city_name' => 'Denpasar', 'province_name' => 'Bali'],
            ['city_name' => 'Balikpapan', 'province_name' => 'Kalimantan Timur'],
            ['city_name' => 'Makassar', 'province_name' => 'Sulawesi Selatan'],
            ['city_name' => 'Jayapura', 'province_name' => 'Papua'],
        ];

        foreach ($cities as &$city) {
            $city['created_at'] = $now;
            $city['updated_at'] = $now;
        }

        DB::table('cities')->upsert(
            $cities,
            ['city_name', 'province_name'],
            ['updated_at']
        );
    }
}
