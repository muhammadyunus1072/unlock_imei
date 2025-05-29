<?php

namespace Database\Seeders\MasterData;

use Illuminate\Database\Seeder;
use App\Repositories\MasterData\Product\ProductWarrantyRepository;

class ProductWarrantySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warranty = [
            'Garansi Seminggu' => 7,
            'Garansi Sebulan' => 30,
            'Garansi Tiga Bulan' => 90,
        ];

        foreach($warranty as $name => $days) {
            ProductWarrantyRepository::create([
                'name' => $name,
                'days' => $days,
            ]);
        }
    }
}
