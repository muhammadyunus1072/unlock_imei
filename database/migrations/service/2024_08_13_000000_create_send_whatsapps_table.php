<?php

use App\Models\Service\SendWhatsapp;
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
            $table->index('phone', 'send_whatsapps_phone_idx');
            $table->index('message_id', 'send_whatsapps_message_id_idx');
            $table->index('status', 'send_whatsapps_status_idx');
            $table->index('status_text', 'send_whatsapps_status_text_idx');
        }

        $table->string("phone")->comment('Target Phone');
        $table->text("message")->comment('Message');
        $table->string("message_id")->nullable()->default(null)->comment('ADSMEDIA Message ID');
        $table->boolean("status")->nullable()->default(null)->comment('ADSMEDIA Status');
        $table->string("status_text")->nullable()->default(SendWhatsapp::STATUS_CREATED)->comment('ADSMEDIA Status Text');
        $table->string("status_text_message")->nullable()->default(null)->comment('ADSMEDIA Status Text Message');
        $table->double("price")->nullable()->default(null)->comment('ADSMEDIA price');
        $table->json("data")->nullable()->default(null)->comment('Adsmedia Data');

        $table->bigInteger("created_by")->unsigned()->nullable();
        $table->bigInteger("updated_by")->unsigned()->nullable();
        $table->bigInteger("deleted_by")->unsigned()->nullable()->default(null);
        $table->softDeletes();
        $table->timestamps();
    }
};
