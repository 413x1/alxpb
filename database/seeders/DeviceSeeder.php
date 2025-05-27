<?php

namespace Database\Seeders;

use App\Helpers\General;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            [
                'name' => 'Device 01',
                'code' => General::generateAccessCode(),
                'is_active' => true,
            ],
            [
                'name' => 'Device 02',
                'code' => General::generateAccessCode(),
                'is_active' => false,
            ],
            [
                'name' => 'Device 03',
                'code' => General::generateAccessCode(),
                'is_active' => false,
            ],
            [
                'name' => 'Device 04',
                'code' => General::generateAccessCode(),
                'is_active' => true,
            ],
            [
                'name' => 'Device 05',
                'code' => General::generateAccessCode(),
                'is_active' => false,
            ],
            [
                'name' => 'Device 06',
                'code' => General::generateAccessCode(),
                'is_active' => true,
            ],
        ];

        DB::table('devices')->insert($devices);
    }
}
