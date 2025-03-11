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
        }

        $table->string('customer_name')->comment('Transaction Customer Name');
        $table->string('customer_email')->comment('Transaction Customer Email');
        $table->string('customer_phone')->comment('Transaction Customer Phone');
        $table->string('customer_quantity')->default(0)->comment('Transaction Customer Quantity');
        $table->string('customer_label')->comment('Transaction Customer Label');
        $table->double('grand_total')->comment('Transaction Grand Total');
        $table->string('status')->comment('Transaction Status');
        $table->text('cancellation_reason')->nullable()->comment('Transaction Caancellation Reason');
        $table->text('snap_token')->nullable()->default(null)->comment('Snap Token');
        $table->string('booking_code')->nullable()->default(null)->comment('Booking Code');
        $table->dateTime('checkin_at')->nullable()->default(null)->comment('Checkin At');

        // Payment Method Information
        $table->unsignedBigInteger('payment_method_id')->comment('ID Payment Method');
        $table->string('payment_method_name')->comment('Payment Method Name');
        $table->string('payment_method_type')->comment('Payment Method Type');
        $table->double('payment_method_amount')->comment('Payment Method amount');

        // Voucher Information
        $table->unsignedBigInteger('voucher_id')->nullable()->default(null)->comment('ID Voucher');
        $table->string('voucher_type')->nullable()->default(null)->comment('Voucher Type');
        $table->double('voucher_amount')->nullable()->default(null)->comment('Voucher Amount');
        $table->string('voucher_code')->nullable()->default(null)->comment('Voucher Code');
        $table->dateTime('voucher_start_date')->nullable()->default(null)->comment('Voucher Start Date');
        $table->dateTime('voucher_end_date')->nullable()->default(null)->comment('Voucher End Date');
        $table->boolean('voucher_is_active')->nullable()->default(null)->comment('Voucher Active');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
