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
            $table->unsignedBigInteger('transaction_detail_id')->comment('ID TransactionDetail');
            $table->index('transaction_detail_id', 'booking_details_transaction_detail_id_idx');
        });
        Schema::table('_history_booking_details', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_detail_id')->comment('ID TransactionDetail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_details', function (Blueprint $table) {
            $table->dropColumn('transaction_detail_id');
        });
        Schema::table('_history_booking_details', function (Blueprint $table) {
            $table->dropColumn('transaction_detail_id');
        });
    }
};
