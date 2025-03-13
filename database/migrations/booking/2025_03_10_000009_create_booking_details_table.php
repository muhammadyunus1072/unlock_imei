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
        Schema::create('booking_details', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_booking_details', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_details');
        Schema::dropIfExists('_history_booking_details');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
        }
        
        // Booking Information
        $table->unsignedBigInteger('booking_id')->comment('ID Booking');

        // Product Information
        $table->unsignedBigInteger('product_id')->comment('ID Product');
        $table->unsignedBigInteger('product_studio_id')->comment('Product ID Studio');
        $table->dateTime('booking_date')->comment('Transaction Booking Date');
        $table->string('product_name')->comment('Product Name');
        $table->text('product_description')->nullable()->comment('Product Description');
        $table->double('product_price')->comment('Product Price');
        $table->string('product_image')->comment('Product Image');
        $table->string('product_note')->nullable()->comment('Product Note');

        // Product Detail Information
        $table->unsignedBigInteger('product_detail_id')->comment('ID Product Detail');  
        $table->string('product_detail_name')->comment('Product Detail Name');
        $table->text('product_detail_description')->nullable()->comment('Product Detail Description');
        $table->double('product_detail_price')->comment('Product Detail Price');
        $table->string('product_detail_image')->comment('Product Detail Image');

        // Product Booking Time Information
        $table->unsignedBigInteger('product_booking_time_id')->comment('ID Product Booking Time');
        $table->time('product_booking_time_time')->comment('Product Booking Time');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
