<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $categories = [
            ['name' => 'Apotek'],
            ['name' => 'Toko Alat Kesehatan'],
            ['name' => 'Distributor Alat Kesehatan'],
            ['name' => 'Supplier Peralatan Medis'],
        ];

        foreach ($categories as &$category) {
            $category['created_at'] = $now;
            $category['updated_at'] = $now;
        }
        unset($category);

        DB::table('store_categories')->upsert(
            $categories,
            ['name'],
            ['updated_at']
        );
    }
    
}
