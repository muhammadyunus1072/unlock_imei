<?php

namespace App\Console\Commands;

use App\Helpers\ServiceHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Service\SendWhatsapp;
use App\Settings\SettingSendWhatsapp;
use App\Repositories\Core\Setting\SettingRepository;
use App\Repositories\Service\SendWhatsapp\SendWhatsappRepository;

class SendWhatsappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-whatsapp-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            Log::channel('notification')->info('COMMAND EXECUTED');
            $setting = SettingRepository::findBy(whereClause: [['name', SettingSendWhatsapp::NAME]]);
            
            if ($setting) {
                $settings = json_decode($setting->setting);
            }
            // Example query and logic
            $messages = SendWhatsappRepository::getBy([
                ['status_text', SendWhatsapp::STATUS_CREATED]
            ]);

            foreach ($messages as $data) {
                // Call your WhatsApp sending logic here
                ServiceHelper::send($data, $settings);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('notification')->error("Gagal Mengirim Notifikasi Whatsapp Otomatis", [
                $e->getMessage()
            ]);
        }
    }

}
