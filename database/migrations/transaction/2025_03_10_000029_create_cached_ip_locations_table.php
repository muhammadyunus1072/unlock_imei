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
        Schema::create('cached_ip_locations', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_cached_ip_locations', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cached_ip_locations');
        Schema::dropIfExists('_history_cached_ip_locations');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {

            $table->index('ip', 'cached_ip_locations_ip_idx');
        }
        
        $table->string('ip')->comment('IP Address');
        $table->string('latitude')->comment('Latitude');
        $table->string('longitude')->comment('Longitude');
        $table->string('city')->comment('City');
        $table->string('country')->comment('Country');
        $table->json('raw')->comment('Raw');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
