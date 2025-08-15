<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::insert(
            [
                ['brand_name' => 'Honeywell'],
                ['brand_name' => 'Hikvision'],
                ['brand_name' => 'Dahua'],
            ]
        );
    }
}
