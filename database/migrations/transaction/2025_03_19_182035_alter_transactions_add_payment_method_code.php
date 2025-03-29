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
            $table->double('subtotal')->comment('Subtotal');
            $table->double('admin_fee')->default(0)->comment('Admin Fee');
            $table->double('discount')->default(0)->comment('Discount');
        });
        Schema::table('_history_transactions', function (Blueprint $table) {
            $table->string('payment_method_code')->comment('Payment Method Code');
            $table->double('subtotal')->comment('Subtotal');
            $table->double('admin_fee')->default(0)->comment('Admin Fee');
            $table->double('discount')->default(0)->comment('Discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method_code');
            $table->dropColumn('subtotal');
            $table->dropColumn('admin_fee');
            $table->dropColumn('discount');
        });
        Schema::table('_history_transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method_code');
            $table->dropColumn('subtotal');
            $table->dropColumn('admin_fee');
            $table->dropColumn('discount');
        });
    }
};
