<?php

namespace Database\Seeders\MasterData;

use Illuminate\Database\Seeder;
use App\Repositories\MasterData\Product\ProductDetailRepository;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $products = \App\Models\MasterData\Product::pluck('id')->toArray();
        
        foreach ($products as $product) { 
            for($d=0; $d < rand(1, 2); $d++)
            {
                ProductDetailRepository::create([
                    'product_id' => $product,
                    'description' => "NOTE ".$faker->word(),
                    'price' => $faker->randomElement([
                        30_000,
                        50_000,
                        100_000,
                    ]),
                ]);
            }
        }
    }
}
