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
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('id', 'transactions_id_idx');
            $table->index('number', 'transactions_number_idx');
            $table->index('user_id', 'transactions_user_id_idx');
            $table->index('status', 'transactions_status_idx');
            $table->index('voucher_id', 'transactions_voucher_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndexes([
                'transactions_id_idx',
                'transactions_number_idx',
                'transactions_user_id_idx',
                'transactions_status_idx',
                'transactions_voucher_id_idx',
            ]);
        });
    }
};
