<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);
        for ($i = 1; $i <= 1000; $i++) {

            $code = strtoupper(Str::random(4));
            while (DB::table('vouchers')->where('code', $code)->exists()) {
                $code = Str::random(4);
            }

            $is_used = mt_rand(0,1);

            DB::table('vouchers')->insert([
                'code' => $code,
                'description' => 'Random voucher code '. $code,
                'created_by' => $user->id,
                'is_used' => $is_used,
                'used_at' => $is_used ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
