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
        Schema::table('booking_details', function (Blueprint $table) {
            $table->index('booking_id', 'booking_details_booking_id_idx');
            $table->index('booking_date', 'booking_details_booking_date_idx');
            $table->index('product_booking_time_id', 'booking_details_product_booking_time_id_idx');
            $table->index('product_id', 'booking_details_product_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_details', function (Blueprint $table) {
            $table->dropIndexes([
                'booking_details_booking_id_idx',
                'booking_details_booking_date_idx',
                'booking_details_product_booking_time_id_idx',
                'booking_details_product_id_idx',
            ]);
        });
    }
};
