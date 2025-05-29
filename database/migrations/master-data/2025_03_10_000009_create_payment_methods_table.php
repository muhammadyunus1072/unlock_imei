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
        Schema::create('payment_methods', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_payment_methods', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('_history_payment_methods');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('name', 'payment_methods_name_idx');
            $table->index('code', 'payment_methods_code_idx');
            $table->index('fee_type', 'payment_methods_fee_type_idx');
        }

        $table->string('name')->comment('Payment Method Name');
        $table->string('fee_type')->comment('Payment Method Fee Type'); // Percentage / Fixed
        $table->double('fee_amount')->comment('Payment Method Fee Amount');
        $table->string('code')->comment('Payment Method Code');
        $table->boolean('is_xendit')->comment('Is Xendit')->default(false);
        $table->boolean('is_active')->comment('Is Active')->default(false);

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
