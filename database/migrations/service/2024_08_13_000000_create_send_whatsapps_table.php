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
        Schema::create('send_whatsapps', function (Blueprint $table) {
            $this->scheme($table, false);
        });

        Schema::create('_history_send_whatsapps', function (Blueprint $table) {
            $this->scheme($table, true);
        });
    }

    public function down()
    {
        Schema::dropIfExists('send_whatsapps');
        Schema::dropIfExists('_history_send_whatsapps');
    }

    private function scheme(Blueprint $table, $is_history = false)
    {
        $table->id();

        if ($is_history) {
            $table->bigInteger('obj_id')->unsigned();
        } else {
            $table->index('name', 'send_whatsapps_name_idx');
        }

        $table->string("message_id")->nullable()->default(null)->comment('ADSMEDIA Message ID');
        $table->boolean("status")->default(false)->comment('ADSMEDIA Status');
        $table->string("status_text_message")->nullable()->default(null)->comment('ADSMEDIA Status Text Message');
        $table->string("status_text")->nullable()->default(null)->comment('ADSMEDIA Status Text');
        $table->double("price")->nullable()->default(null)->comment('ADSMEDIA price');
        $table->json("data")->comment('Adsmedia Data');
        $table->string("phone")->comment('Target Phone');
        $table->text("message")->comment('Message');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
