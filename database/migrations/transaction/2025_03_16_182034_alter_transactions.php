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
            $table->string('external_id');
            $table->string('checkout_link')->nullable()->default(null);
        });
        Schema::table('_history_transactions', function (Blueprint $table) {
            $table->string('external_id');
            $table->string('checkout_link')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('external_id');
            $table->dropColumn('checkout_link');
        });
        Schema::table('_history_transactions', function (Blueprint $table) {
            $table->dropColumn('external_id');
            $table->dropColumn('checkout_link');
        });
    }
};
