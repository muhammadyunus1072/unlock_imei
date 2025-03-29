<?php

namespace Database\Seeders\MasterData;

use Illuminate\Database\Seeder;
use App\Repositories\MasterData\Product\ProductBookingTimeRepository;

class ProductBookingTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = \App\Models\MasterData\Product::pluck('id')->toArray();
        
        foreach ($products as $product) { 
            for ($hour= 9; $hour < 19; $hour++) { 
                for ($minute=0; $minute < 2; $minute++) { 
                    ProductBookingTimeRepository::create([
                        'product_id' => $product,
                        'time' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minute * 20, 2, '0', STR_PAD_LEFT) . ':00',
                    ]);
                }
            }
        }
    }
}
