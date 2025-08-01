<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductBannerSeeder::class);
        $this->call(VoucherSeeder::class);
    }
}
