<?php

namespace App\Livewire\Core\Setting\SendWhatsapp;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Repositories\Core\Setting\SettingRepository;
use App\Settings\SettingSendWhatsapp;

class Index extends Component
{
    public $objId;

    public $name;
    public $setting = [];

    public function mount()
    {
        $this->name = SettingSendWhatsapp::NAME;

        // Init
        foreach (SettingSendWhatsapp::ALL as $key => $value) {
            $this->setting[$key] = $value;
        }

        // Set Variables
        $setting = SettingRepository::findBy(whereClause: [['name', $this->name]]);
        if ($setting) {
            $this->objId = Crypt::encrypt($setting->id);
            $settings = json_decode($setting->setting);

            foreach ($this->setting as $key => $value) {
                $this->setting[$key] = (isset($settings->{$key})) ? $settings->{$key} : "";
            }
        }
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        $this->redirectRoute('setting_send_whatsapp.index');
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('setting_send_whatsapp.index');
    }

    public function store()
    {
        try {
            $phone = preg_replace('/[^\d]/', '', $this->setting[SettingSendWhatsapp::ADMIN_PHONE]);
            if (!preg_match("/^8[0-9]{9,11}$/", $phone) || (strlen($phone) < 9 || strlen($phone) > 11)) {
                throw new \Exception("Format No Telp tidak sesuai,<br>Contoh: +62 8XX-XXXX-XXXX");
            }
            $this->setting[SettingSendWhatsapp::ADMIN_PHONE] = $phone;
            DB::beginTransaction();
            if ($this->objId) {
                SettingRepository::update(Crypt::decrypt($this->objId), [
                    'name' => $this->name,
                    'setting' => json_encode($this->setting),
                ]);
            } else {
                SettingRepository::create([
                    'name' => $this->name,
                    'setting' => json_encode($this->setting),
                ]);
            }

            DB::commit();

            Alert::confirmation(
                $this,
                Alert::ICON_SUCCESS,
                "Berhasil",
                "Data Berhasil Diperbarui",
                "on-dialog-confirm",
                "on-dialog-cancel",
                "Oke",
                "Tutup",
            );
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.core.setting.send-whatsapp.index');
    }
}
