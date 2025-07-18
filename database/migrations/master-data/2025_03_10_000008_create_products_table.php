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
        Schema::create('products', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_products', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('_history_products');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('name', 'products_name_idx');
        }
        
        $table->string('name')->comment('Product Name');
        $table->text('description')->nullable()->comment('Product Description');
        $table->string('image')->nullable()->comment('Product Image');
        $table->double('price')->comment('Product Price');
        $table->double('warranty_days')->nullable()->default(0)->comment('Product Warranty Days');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
