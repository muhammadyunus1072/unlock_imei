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
            $table->string('payment_method_code')->comment('Payment Method Code');
        });
        Schema::table('_history_transactions', function (Blueprint $table) {
            $table->string('payment_method_code')->comment('Payment Method Code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method_code');
        });
        Schema::table('_history_transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method_code');
        });
    }
};
