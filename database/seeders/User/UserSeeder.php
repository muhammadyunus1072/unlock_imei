<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => "Admin",
            'username' => "admin",
            'email' => "admin@gmail.com",
            'password' => Hash::make("123"),
        ]);

        $user->assignRole('Admin');

        // $faker = \Faker\Factory::create('id_ID');

        // for($i = 0; $i < 50; $i ++)
        // {
        //     $user = User::create([
        //         'name' => $faker->name(),
        //         'username' => $faker->userName(),
        //         'email' => $faker->email(),
        //         'password' => Hash::make("123"),
        //     ]);
    
        //     $user->assignRole(config('template.registration_default_role'));
        // }
    }
}
