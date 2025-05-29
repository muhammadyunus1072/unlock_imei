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
            ["name" => "Kartu Kredit", "code" => "CREDIT_CARD", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "BCA", "code" => "BCA", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => false],
            ["name" => "BNI", "code" => "BNI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => false],
            ["name" => "BSI", "code" => "BSI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => false],
            ["name" => "BRI", "code" => "BRI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => false],
            ["name" => "Mandiri", "code" => "MANDIRI", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => false],
            ["name" => "Permata", "code" => "PERMATA", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => false],
            ["name" => "Sahabat Sampoerna", "code" => "SAHABAT_SAMPOERNA", "type" => PaymentMethod::TYPE_FIXED, 'amount' => 4000, "is_active" => false],
            ["name" => "BNC", "code" => "BNC", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "Alfamart", "code" => "ALFAMART", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "Indomaret", "code" => "INDOMARET", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "OVO", "code" => "OVO", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "DANA", "code" => "DANA", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "ShopeePay", "code" => "SHOPEEPAY", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "LinkAja", "code" => "LINKAJA", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "JeniusPay", "code" => "JENIUSPAY", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "Direct Debit BRI", "code" => "DD_BRI", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "BCA KlikPay", "code" => "DD_BCA_KLIKPAY", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "Kredivo", "code" => "KREDIVO", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "Akulaku", "code" => "AKULAKU", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "Atome", "code" => "ATOME", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
            ["name" => "QRIS", "code" => "QRIS", "type" => PaymentMethod::TYPE_PERCENTAGE, 'amount' => 2, "is_active" => false],
        ];

        foreach ($paymentMethods as $payment) {
            PaymentMethodRepository::create([
                'name' => $payment['name'],
                'fee_type' => $payment['type'],
                'fee_amount' => $payment['amount'],
                'code' => $payment['code'],
                'is_active' => $payment['is_active'],
                'is_xendit' => true,
            ]);
        }
        PaymentMethodRepository::create([
            'name' => 'Transfer BCA',
            'fee_type' => PaymentMethod::TYPE_FIXED,
            'fee_amount' => 0,
            'code' => 'BCA TRANSFER',
            'is_active' => true,
            'is_xendit' => false,
        ]);
    }
}
