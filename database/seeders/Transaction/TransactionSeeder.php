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
        $users = User::all();

        for($day = 0; $day < 100; $day++) {
            $imei = $faker->uuid();
            $bookingDate = Carbon::now()->subDays($day);

            $randTransactionCount = rand(5, 10);
            for ($i=0; $i < $randTransactionCount; $i++) { 
                $user = $faker->randomElement($users);
                
                // Pilih produk acak
                $product = $faker->randomElement($products); // Get full product model
                
                // Pilih product_detail_id yang sesuai dengan product_id
                if ($product->productDetails->isEmpty()) {
                    continue; // Skip if no product details available
                }

                // Cek ketersediaan sebelum booking
                $isNotAvailable = TransactionDetail::isNotAvailable(
                    $imei,
                );

                if ($isNotAvailable) {
                    continue; // Skip if already booked
                }

                $product_details = [];
                foreach ($product->productDetails as $key => $product_detail) {
                    
                    $product_details[] = [           
                        'product_id' => $product_detail->product_id,             
                        'product_detail_id' => $product_detail->id,             
                        'price' => $product_detail->price,             
                    ];
                }

                $paymentMethod = $faker->randomElement($paymentMethods); // Contoh metode pembayaran
                $subtotal = collect($product_details)->sum('price');
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
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $faker->phoneNumber(),
                    'customer_lat' => $faker->latitude(),
                    'customer_long' => $faker->longitude(),
                    'customer_ktp' => $faker->word(),
                    'customer_social_media' => json_encode([]),
                    'payment_method_id' => $paymentMethod->id,
                    'voucher_id' => null,
                    'created_at' => $bookingDate,
                    'status' => $faker->randomElement([
                        Transaction::PAYMENT_STATUS_PENDING,
                        Transaction::PAYMENT_STATUS_PAID,
                        Transaction::PAYMENT_STATUS_EXPIRED,
                    ]),
                    'grand_total' => $grandTotal,
                    'subtotal' => $subtotal,
                    'admin_fee' => $admin_fee,
                    'discount' => 0,
                ]);

                foreach ($product_details as $book) {
                    $transactionDetail = TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $book['product_id'],
                        'product_detail_id' => $book['product_detail_id'],
                        'imei' => $imei,
                    ]);
                } 
            }
        }
    }
}
