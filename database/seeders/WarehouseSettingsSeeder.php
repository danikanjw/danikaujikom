<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $warehouse = [
            'name' => 'Gudang Pusat AlkesHub',
            'address' => 'Jl. Merdeka No. 1',
            'city' => 'Kota Jakarta',
            'contact_phone' => '0000000000',
        ];

        $now = now();
        $settings = DB::table('warehouse_settings')->where('warehouse_id', 1);
        $values = $warehouse + ['updated_at' => $now];

        if ($settings->exists()) {
            $settings->update($values);

            return;
        }

        DB::table('warehouse_settings')->insert($values + [
            'warehouse_id' => 1,
            'created_at' => $now,
        ]);
    }
}
