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
        });
        Schema::table('_history_payment_methods', function (Blueprint $table) {
            $table->string('code')->comment('Payment Method Code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('_history_payment_methods', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
