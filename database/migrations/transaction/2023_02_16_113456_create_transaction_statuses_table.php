<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('purchase_request_statuses', function (Blueprint $table) {
            $this->scheme($table);
        });

        Schema::create('_history_purchase_request_statuses', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_request_statuses');
        Schema::dropIfExists('_history_purchase_request_statuses');
    }

    private function scheme(Blueprint $table, $isHistory = false)
    {
        $table->id();
        if ($isHistory) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        $table->bigInteger('purchase_request_id')->unsigned();
        $table->string('name');
        $table->text('description')->nullable()->default(null);
        $table->bigInteger('remarks_id')->unsigned()->nullable()->default(null);
        $table->string('remarks_type')->nullable()->default(null);

        $table->bigInteger("created_by")->unsigned();
        $table->bigInteger("updated_by")->unsigned();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
