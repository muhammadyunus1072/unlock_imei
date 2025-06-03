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
        Schema::create('transactions', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_transactions', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('_history_transactions');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('customer_name', 'transactions_customer_name_idx');
            $table->index('customer_email', 'transactions_customer_email_idx');
            $table->index('customer_phone', 'transactions_customer_phone_idx');
            $table->index('number', 'transactions_number_idx');
            $table->index('transaction_status', 'transactions_transaction_status_idx');
            $table->index('payment_status', 'transactions_payment_status_idx');
            $table->index('verified_at', 'transactions_verified_at_idx');
            $table->index('voucher_id', 'transactions_voucher_id_idx');
        }

        $table->double('subtotal')->comment('Subtotal');
        $table->double('admin_fee')->default(0)->comment('Admin Fee');
        $table->double('discount')->default(0)->comment('Discount');
        $table->double('grand_total')->comment('Transaction Grand Total');

        $table->string('number')->comment('Transaction Number');
        // $table->unsignedBigInteger('cached_ip_location_id')->comment('ID Cached IP Location');

        $table->string('customer_name')->comment('Customer Name');
        $table->string('customer_email')->comment('Customer Email');
        $table->string('customer_phone')->comment('Customer Phone');
        $table->string('customer_lat')->comment('Customer Latitude');
        $table->string('customer_long')->comment('Customer Longitude');
        $table->string('customer_ip_lat')->comment('Customer IP Latitude');
        $table->string('customer_ip_long')->comment('Customer IP Longitude');
        $table->string('customer_ktp')->comment('Customer KTP');
        $table->json('customer_social_media')->comment('Customer Social Media');
        
        $table->text('cancellation_reason')->nullable()->comment('Transaction Caancellation Reason');
        $table->dateTime('verified_at')->nullable()->default(null)->comment('Transaction Verified At');
        $table->string('transaction_status')->comment('Transaction Status');
        $table->string('payment_status')->comment('Transaction Payment Status');
        
        // Voucher Information
        $table->unsignedBigInteger('voucher_id')->nullable()->default(null)->comment('ID Voucher');
        $table->string('voucher_type')->nullable()->default(null)->comment('Voucher Type');
        $table->double('voucher_amount')->nullable()->default(null)->comment('Voucher Amount');
        $table->string('voucher_code')->nullable()->default(null)->comment('Voucher Code');
        $table->dateTime('voucher_start_date')->nullable()->default(null)->comment('Voucher Start Date');
        $table->dateTime('voucher_end_date')->nullable()->default(null)->comment('Voucher End Date');

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
