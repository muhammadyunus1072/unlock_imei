<?php

namespace Database\Seeders\Transaction;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\MasterData\Product;
use App\Models\Transaction\Transaction;
use App\Models\MasterData\PaymentMethod;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionStatus;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $products = Product::all();

        for($day = 0; $day < 30; $day++) {
            $imei = $faker->uuid();
            $bookingDate = Carbon::now()->subDays($day);

            $randTransactionCount = rand(5, 10);
            for ($t=0; $t < $randTransactionCount; $t++) { 
                // Pilih produk acak
                $product = $faker->randomElement($products); 
                $price = $product->price;
                $qty = rand(1, 4);
                $subtotal = $price * $qty;
                $admin_fee = 0;
                $grandTotal = 0;
                $grandTotal = $subtotal;
                $transaction = Transaction::create([
                    'customer_name' => $faker->userName(),
                    'customer_email' => $faker->email(),
                    'customer_phone' => $faker->phoneNumber(),
                    'customer_lat' => $faker->latitude(),
                    'customer_long' => $faker->longitude(),
                    'customer_ip_lat' => $faker->latitude(),
                    'customer_ip_long' => $faker->longitude(),
                    'customer_ktp' => $faker->word(),
                    'customer_social_media' => json_encode([
                        'instagram' => '',
                        'facebook' => '',
                    ]),
                    'voucher_id' => null,
                    'created_at' => $bookingDate,
                    'total_amount' => $grandTotal,
                    'amount_due' => $grandTotal,
                    'subtotal' => $subtotal,
                    'discount' => 0,
                    // 'cached_ip_location_id' => 1,
                ]);

                $message = "Seeder Transaction 1: " . number_format($day + 1, 0, ",", ".") . " / 30 ";
                $this->command->info($message);
                for ($d = 0; $d < $qty; $d++) {
                  
                    $transactionDetail = TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'imei' => $imei,
                    ]);

                $message = "Seeder Transaction 2 ke " . $d + 1 . ": " . number_format($day + 1, 0, ",", ".") . " / 30 ";
                $this->command->info($message);
                } 
                $stepStatuses = [
                    // TransactionStatus::STATUS_NOT_VERIFIED,
                    TransactionStatus::STATUS_VERIFIED,
                    TransactionStatus::STATUS_ACTIVED,
                    TransactionStatus::STATUS_AWAITING_PAYMENT,
                    TransactionStatus::STATUS_PAID,
                    // TransactionStatus::STATUS_CANCELLED,
                    // TransactionStatus::STATUS_COMPLETED,
                ];

                $endIndex = rand(1, count($stepStatuses) - 1); // skip index 0 to ensure we go at least 1 step
                $statusesToInsert = array_slice($stepStatuses, 0, $endIndex + 1);

                foreach ($statusesToInsert as $key => $status) {
                    TransactionStatus::create([
                        'transaction_id' => $transaction->id,
                        'name' => $status,
                        'description' => 'Seeder auto-step',
                        'remarks_id' => $transaction->id,
                        'remarks_type' => Transaction::class,
                    ]);

                $message = "Seeder Transaction 3 ke " . $key + 1 . ": " . number_format($day + 1, 0, ",", ".") . " / 30 ";
                $this->command->info($message);
                }
            }

            $message = "Seeder Transaction: " . number_format($day + 1, 0, ",", ".") . " / 30 ";
            $this->command->info($message);
        }
    }
}
