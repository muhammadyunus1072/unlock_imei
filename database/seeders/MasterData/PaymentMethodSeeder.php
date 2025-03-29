<?php

namespace Database\Seeders\MasterData;

use Illuminate\Database\Seeder;
use App\Models\MasterData\PaymentMethod;
use App\Repositories\MasterData\PaymentMethod\PaymentMethodRepository;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            ["name" => "Kartu Kredit", "code" => "CREDIT_CARD", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "BCA", "code" => "BCA", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => true],
            ["name" => "BNI", "code" => "BNI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => true],
            ["name" => "BSI", "code" => "BSI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => true],
            ["name" => "BRI", "code" => "BRI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => true],
            ["name" => "Mandiri", "code" => "MANDIRI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => true],
            ["name" => "Permata", "code" => "PERMATA", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => true],
            ["name" => "Sahabat Sampoerna", "code" => "SAHABAT_SAMPOERNA", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => true],
            ["name" => "BNC", "code" => "BNC", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "Alfamart", "code" => "ALFAMART", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "Indomaret", "code" => "INDOMARET", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "OVO", "code" => "OVO", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "DANA", "code" => "DANA", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "ShopeePay", "code" => "SHOPEEPAY", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "LinkAja", "code" => "LINKAJA", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "JeniusPay", "code" => "JENIUSPAY", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "Direct Debit BRI", "code" => "DD_BRI", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "BCA KlikPay", "code" => "DD_BCA_KLIKPAY", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "Kredivo", "code" => "KREDIVO", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "Akulaku", "code" => "AKULAKU", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "Atome", "code" => "ATOME", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
            ["name" => "QRIS", "code" => "QRIS", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => true],
        ];

        foreach ($paymentMethods as $payment) {
            PaymentMethodRepository::create([
                'name' => $payment['name'],
                'type' => $payment['type'],
                'amount' => $payment['amount'],
                'code' => $payment['code'],
                'is_active' => $payment['is_active'],
            ]);
        }
    }
}
