<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'name' => 'Banner 1',
                'url' => '/assets/images/slider/photo-1.jpg',
                'type' => 'banner',
                'is_active' => true,
            ],
            [
                'name' => 'Banner 2',
                'url' => '/assets/images/slider/photo-2.jpg',
                'type' => 'banner',
                'is_active' => true,
            ],
            [
                'name' => 'Banner 3',
                'url' => '/assets/images/slider/photo-3.jpg',
                'type' => 'banner',
                'is_active' => true,
            ],
        ];

        DB::table('banners')->insert($banners);
    }
}
