<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\User\RoleSeeder;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\User\PermissionSeeder;
use Database\Seeders\MasterData\ProductSeeder;
use Database\Seeders\Transaction\TransactionSeeder;
use Database\Seeders\MasterData\PaymentMethodSeeder;
use Database\Seeders\MasterData\ProductDetailSeeder;
use Database\Seeders\MasterData\ProductWarrantySeeder;

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
            ProductWarrantySeeder::class,
            ProductSeeder::class,
            ProductDetailSeeder::class,
            // TransactionSeeder::class,
        ]);
    }
}
