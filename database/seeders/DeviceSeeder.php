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
                'identifier' => General::generateRandomCode(12),
                'is_active' => true,
                'api_url' => 'http://localhost:1500/',
                'api_key' => '****',
                'trigger_url' => 'http://localhost:3020/',
            ],
//            [
//                'name' => 'Device 02',
//                'code' => General::generateAccessCode(),
//                'identifier' => General::generateRandomCode(12),
//                'is_active' => false,
//                'api_url' => 'http://localhost:1500/',
//                'api_key' => '****',
//                'trigger_url' => 'http://localhost:3020/',
//            ],
//            [
//                'name' => 'Device 03',
//                'code' => General::generateAccessCode(),
//                'identifier' => General::generateRandomCode(12),
//                'is_active' => false,
//                'api_url' => 'http://localhost:1500/',
//                'api_key' => '****',
//                'trigger_url' => 'http://localhost:3020/',
//            ],
//            [
//                'name' => 'Device 04',
//                'code' => General::generateAccessCode(),
//                'identifier' => General::generateRandomCode(12),
//                'is_active' => true,
//                'api_url' => 'http://localhost:1500/',
//                'api_key' => '****',
//                'trigger_url' => 'http://localhost:3020/',
//            ],
//            [
//                'name' => 'Device 05',
//                'code' => General::generateAccessCode(),
//                'identifier' => General::generateRandomCode(12),
//                'is_active' => false,
//                'api_url' => 'http://localhost:1500/',
//                'api_key' => '****',
//                'trigger_url' => 'http://localhost:3020/',
//            ],
//            [
//                'name' => 'Device 06',
//                'code' => General::generateAccessCode(),
//                'identifier' => General::generateRandomCode(12),
//                'is_active' => true,
//                'api_url' => 'http://localhost:1500/',
//                'api_key' => '****',
//                'trigger_url' => 'http://localhost:3020/',
//            ],
        ];

        DB::table('devices')->insert($devices);
    }
}
