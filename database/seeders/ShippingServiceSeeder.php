<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingServiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $services = [
            ['name' => 'JNE', 'is_active' => true],
            ['name' => 'J&T Express', 'is_active' => true],
            ['name' => 'SiCepat', 'is_active' => true],
            ['name' => 'Pos Indonesia', 'is_active' => true],
            ['name' => 'TIKI', 'is_active' => true],
        ];

        foreach ($services as &$service) {
            $service['created_at'] = $now;
            $service['updated_at'] = $now;
        }
        unset($service);

        DB::table('shipping_services')->upsert(
            $services,
            ['name'],
            ['updated_at']
        );
    }
}
