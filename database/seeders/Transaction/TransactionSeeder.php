<?php

namespace Database\Seeders\Transaction;

use App\Models\MasterData\PaymentMethod;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\MasterData\Product;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        // Ambil semua produk dan booking time
        $paymentMethods = PaymentMethodRepository::getBy([
            ['is_active', true],
        ]);
        $products = Product::all();

        for($day = 0; $day < 100; $day++) {
            $imei = $faker->uuid();
            $bookingDate = Carbon::now()->subDays($day);

            $randTransactionCount = rand(5, 10);
            for ($i=0; $i < $randTransactionCount; $i++) { 
                // Pilih produk acak
                $product = $faker->randomElement($products); // Get full product model

                // Cek ketersediaan sebelum booking
                $isNotAvailable = TransactionDetail::isNotAvailable(
                    $imei,
                );

                if ($isNotAvailable) {
                    continue; // Skip if already booked
                }

                $paymentMethod = $faker->randomElement($paymentMethods); // Contoh metode pembayaran
                $price = $product->price;
                $qty = rand(1, 4);
                $subtotal = $price * $qty;
                $admin_fee = 0;
                $grandTotal = 0;
                if($paymentMethod->fee_type === PaymentMethod::TYPE_PERCENTAGE)
                {
                    $admin_fee = calculatedAdminFee($subtotal, $paymentMethod->fee_amount);
                    $grandTotal = $subtotal + $admin_fee;
                }else{
                    $admin_fee = $paymentMethod->fee_amount;
                    $grandTotal = $subtotal + $admin_fee;
                }
                $transaction = Transaction::create([
                    'customer_name' => $faker->userName(),
                    'customer_email' => $faker->email(),
                    'customer_phone' => $faker->phoneNumber(),
                    'customer_lat' => $faker->latitude(),
                    'customer_long' => $faker->longitude(),
                    'customer_ktp' => $faker->word(),
                    'customer_social_media' => json_encode([]),
                    'payment_method_id' => $paymentMethod->id,
                    'voucher_id' => null,
                    'created_at' => $bookingDate,
                    'transaction_status' => Transaction::TRANSACTION_STATUS_COMPLETED,
                    'payment_status' => Transaction::PAYMENT_STATUS_PAID,
                    'grand_total' => $grandTotal,
                    'subtotal' => $subtotal,
                    'admin_fee' => $admin_fee,
                    'discount' => 0,
                    // 'cached_ip_location_id' => 1,
                ]);

                for ($i = 0; $i < $qty; $i++) {
                    $transactionDetail = TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $$product->id,
                        'imei' => $imei,
                        'active_at' => Carbon::parse($bookingDate)->addMinutes(rand(3, 60)),
                    ]);
                } 
            }
        }
    }
}
