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
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('code')->comment('Payment Method Code');
            $table->boolean('is_active')->comment('Is Active')->default(false);
        });
        Schema::table('_history_payment_methods', function (Blueprint $table) {
            $table->string('code')->comment('Payment Method Code');
            $table->boolean('is_active')->comment('Is Active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('is_active');
        });
        Schema::table('_history_payment_methods', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('is_active');
        });
    }
};
