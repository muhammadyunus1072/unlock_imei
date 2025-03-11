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
        Schema::create('studios', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_studios', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('studios');
        Schema::dropIfExists('_history_studios');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('name', 'studios_name_idx');
            $table->index('city', 'studios_city_idx');
        }

        $table->string('name')->comment('Studio Name');
        $table->text('description')->nullable()->comment('Studio Description');
        $table->string('latitude')->nullable()->comment('Studio Latitude');
        $table->string('longitude')->nullable()->comment('Studio Longitude');
        $table->string('map_zoom')->nullable()->comment('Studio Map Zoom');
        $table->string('city')->nullable()->comment('Studio City Name');
        $table->text('address')->nullable()->comment('Studio Address');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
