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
        Schema::create('user_studios', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_user_studios', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_studios');
        Schema::dropIfExists('_history_user_studios');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('user_id', 'user_studios_user_id_idx');
            $table->index('studio_id', 'user_studios_studio_id_idx');
        }

        $table->bigInteger("user_id")->unsigned()->comment('User ID');
        $table->bigInteger("studio_id")->unsigned()->comment('Studio ID');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
