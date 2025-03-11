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
        Schema::create('vouchers', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_vouchers', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('_history_vouchers');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('type', 'vouchers_type_idx');
            $table->index('code', 'vouchers_code_idx');
            $table->index('is_active', 'vouchers_is_active_idx');
        }

        $table->string('type')->comment('Voucher Type');
        $table->double('amount')->comment('Voucher Amount');
        $table->string('code')->comment('Voucher Code');
        $table->dateTime('start_date')->comment('Voucher Start Date');
        $table->dateTime('end_date')->comment('Voucher End Date');
        $table->boolean('is_active')->comment('Voucher Active');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
