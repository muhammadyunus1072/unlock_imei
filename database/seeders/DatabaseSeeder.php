<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\MasterData\PaymentMethodSeeder;
use Database\Seeders\MasterData\ProductBookingTimeSeeder;
use Database\Seeders\MasterData\ProductDetailSeeder;
use Database\Seeders\MasterData\ProductSeeder;
use Database\Seeders\MasterData\StudioSeeder;
use Database\Seeders\Transaction\TransactionSeeder;
use Database\Seeders\User\PermissionSeeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // DEMO
        $this->call([
            PaymentMethodSeeder::class,
            StudioSeeder::class,
            ProductSeeder::class,
            ProductDetailSeeder::class,
            ProductBookingTimeSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
