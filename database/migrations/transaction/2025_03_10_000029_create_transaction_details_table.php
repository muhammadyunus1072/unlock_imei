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
        Schema::create('transaction_details', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_transaction_details', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('_history_transaction_details');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {

            $table->index('transaction_id', 'transaction_details_transaction_id_idx');
            $table->index('product_id', 'transaction_details_product_id_idx');
        }

        $table->unsignedBigInteger('transaction_id')->comment('ID Transaction');
        
        $table->string('imei')->comment('Transaction Detail IMEI');

        // Product Information
        $table->unsignedBigInteger('product_id')->comment('ID Product');
        $table->string('product_name')->comment('Product Name');
        $table->text('product_description')->nullable()->comment('Product Description');
        $table->string('product_image')->comment('Product Image');
        $table->integer('product_warranty_days')->comment('Product Warranty Days');
        $table->double('product_price')->comment('Product Price');
        $table->dateTime('warranty_expired_at')->nullable()->default(now())->comment('Product Warranty Expired At');
        
        $table->dateTime('active_at')->nullable()->default(null)->comment('Product IMEI Active At');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
