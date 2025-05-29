<?php

namespace Database\Seeders\MasterData;

use Illuminate\Database\Seeder;
use App\Repositories\MasterData\Product\ProductRepository;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $description = "";

        for ($i=0; $i < 3; $i++) { 
            ProductRepository::create([
                'product_warranty_id' => $i+1,
                'name' => "PRODUCT IMEI ".$faker->word(),
                'description' => $description,
                'image' => $faker->randomElement([
                    'bg-3.jpg',
                    'bg-black.jpg',
                    'bg-blue.jpg',
                    'bg-pink.jpg',
                ]),
            ]);
        }
    }
}
