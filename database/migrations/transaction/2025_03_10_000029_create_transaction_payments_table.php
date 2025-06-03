<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_payments', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_transaction_payments', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_payments');
        Schema::dropIfExists('_history_transaction_payments');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {

            $table->index('transaction_id', 'transaction_payments_transaction_id_idx');
            $table->index('status', 'transaction_payments_status_idx');
            $table->index('payment_method_name', 'transaction_payments_payment_method_name_idx');
        }
        
        $table->unsignedBigInteger('transaction_id')->comment('ID Transaction');
        $table->string('image')->nullable()->default(null)->comment('Gambar Bukti');
        $table->text('note')->nullable()->default(null)->comment('Note');
        $table->double('amount')->comment('Amount Paid');
        $table->string('status')->comment('Payment Status');
        
        // Payment Method Information
        $table->unsignedBigInteger('payment_method_id')->comment('ID Payment Method');
        $table->string('payment_method_name')->comment('Payment Method Name');
        $table->string('payment_method_fee_type')->comment('Payment Method Fee Type');
        $table->double('payment_method_fee_amount')->comment('Payment Method Fee Amount');
        $table->string('payment_method_code')->comment('Payment Method Code');
        $table->string('payment_method_is_xendit')->comment('Payment Method Is Xendit');

        $table->string('external_id')->nullable()->default(null);
        $table->string('checkout_link')->nullable()->default(null);
        $table->json('payment_callback')->nullable()->comment('Payment Xendit Callback');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
