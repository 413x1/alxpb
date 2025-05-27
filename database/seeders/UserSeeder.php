<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make(config('app.default_user_password')),
                'email_verified_at' => now(),
                'is_active' => true,
            ],
            [
                'name' => 'admin1',
                'email' => 'admin1@mail.com',
                'password' => Hash::make(config('app.default_user_password')),
                'email_verified_at' => now(),
                'is_active' => false,
            ],
        ];

        DB::table('users')->insert($users);

    }
}
