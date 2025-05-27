<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Fancy Photo',
                'description' => "<p>Ketentuan :</p>
                                    <ul>
                                    <li>Maksimal 2 Orang</li>
                                    <li>30 Menit sesi foto</li>
                                    <li>Digital Copy</li>
                                    <li>1 lembar foto print (bisa di tambah)</li>
                                    </ul>",
                'price' => 50000,
                'is_active' => true
            ]
        ];

        DB::table('products')->insert($products);
    }
}
