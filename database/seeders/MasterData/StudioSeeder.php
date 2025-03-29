<?php

namespace Database\Seeders\MasterData;

use Illuminate\Database\Seeder;
use App\Models\MasterData\Studio;
use App\Repositories\MasterData\Studio\StudioRepository;

class StudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $description = "
        <h4><strong>Capacity Basic Studio</strong></h4>
        <ul>
            <li>Recommended maximum capacity Studio 1: 15 people.</li>
            <li>Recommended maximum capacity Studio 2: 12 people.</li>
        </ul>

        <h4><strong>Additional Person</strong></h4>

        <ul>
            <li>Free extra charge per person</li>
        </ul>

        <h4><strong>Additional Print</strong></h4>

        <ul>
            <li>1 Printed Photo OR MORE @10K</li>
        </ul>

        <h4><strong>Digital Soft Copy</strong></h4>

        <ul>
            <li>All Color @Free (Tag IGS @KuyStudio + Follow + Google Review)</li>
            <li>All Color @25K (No Terms and Conditions)</li>
        </ul>

        ";
        for ($i=0; $i < 5; $i++) { 
            StudioRepository::create([
                'name' => $faker->company(),
                'city' => $faker->city(),
                'address' => $faker->address(),
                'description' => $description,
                'latitude' => $faker->randomFloat(6, -11.0, 6.0), // Lat in Indonesia
                'longitude' => $faker->randomFloat(6, 95.0, 141.0), // Lon in Indonesia
                'map_zoom' => $faker->numberBetween(1, 20),
            ]);
        }
    }
}
