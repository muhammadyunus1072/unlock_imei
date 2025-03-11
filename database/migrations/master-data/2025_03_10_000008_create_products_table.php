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
            $table->index('studio_id', 'products_studio_id_idx');
            $table->index('name', 'products_name_idx');
        }

        $table->unsignedBigInteger('studio_id')->comment('ID Studio');
        $table->string('name')->comment('Product Name');
        $table->text('description')->nullable()->comment('Product Description');
        $table->double('price')->comment('Product Price');
        $table->string('image')->comment('Product Image');
        $table->string('note')->nullable()->comment('Product Note');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
