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
            for ($i=0; $i < 4; $i++) { 
                ProductDetailRepository::create([
                    'product_id' => $product,
                    'name' => "BACKGROUND ".$faker->word(),
                    'price' => $faker->randomElement([
                        0,
                        5_000,
                        10_000,
                    ]),
                    'description' => "BACKGROUND ".$faker->word(),
                    'image' => $faker->randomElement([
                            'bg-black.jpg',
                            'bg-blue.jpg',
                            'bg-gray.jpg',
                            'bg-pink.jpg',
                            'bg-white.jpg',
                        ]),
                ]);
            }
        }
    }
}
