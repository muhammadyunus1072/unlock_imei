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
        $warranties = [
            null,
            0,
            7,
            30,
            60,
            90
        ];

        for ($i=0; $i < count($warranties); $i++) { 
            
            ProductRepository::create([
                'name' => "IMEI All Operator",
                'description' => $description,
                'image' => $faker->randomElement([
                    'bg-3.jpg',
                    'bg-black.jpg',
                    'bg-blue.jpg',
                    'bg-pink.jpg',
                ]),
                'price' => $faker->randomElement([
                    30_000,
                    50_000,
                    100_000,
                ]),
                'warranty_days' => $warranties[$i]
            ]);
        }
    }
}
