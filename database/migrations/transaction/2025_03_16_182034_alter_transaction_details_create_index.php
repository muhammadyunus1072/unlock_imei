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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->index('transaction_id', 'transaction_details_transaction_id_idx');
            $table->index('booking_date', 'transaction_details_booking_date_idx');
            $table->index('product_id', 'transaction_details_product_id_idx');
            $table->index('product_detail_id', 'transaction_details_product_detail_id_idx');
            $table->index('product_booking_time_id', 'transaction_details_product_booking_time_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndexes([
                'transaction_details_transaction_id_idx',
                'transaction_details_booking_date_idx',
                'transaction_details_product_id_idx',
                'transaction_details_product_detail_id_idx',
                'transaction_details_product_booking_time_id_idx',
            ]);
        });
    }
};
