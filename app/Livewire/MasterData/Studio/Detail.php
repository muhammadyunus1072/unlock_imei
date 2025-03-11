<?php

namespace App\Livewire\MasterData\Studio;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\MasterData\Studio\StudioRepository;

class Detail extends Component
{
    public $objId;

    #[Validate('required', message: 'Nama Studio Harus Diisi', onUpdate: false)]
    public $name;

    #[Validate('required', message: 'Kota Harus Diisi', onUpdate: false)]
    public $city;

    #[Validate('required', message: 'Alamat Harus Diisi', onUpdate: false)]
    public $address;
    
    public $description;

    public $latitude;
    public $longitude;
    public $map_zoom;

    public function mount()
    {
        if ($this->objId) {
            $id = Crypt::decrypt($this->objId);
            $studio = StudioRepository::find($id);

            $this->name = $studio->name;
            $this->city = $studio->city;
            $this->address = $studio->address;
            $this->description = $studio->description;
            $this->latitude = $studio->latitude;
            $this->longitude = $studio->longitude;
            $this->map_zoom = $studio->map_zoom;
        } 
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('studio.edit', $this->objId);
        } else {
            $this->redirectRoute('studio.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('studio.index');
    }

    public function setLocation($lat, $lng, $zoom, $label)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->map_zoom = $zoom;   
    }

    public function store()
    {
        $this->validate();

        $validatedData = [
            'name' => $this->name,
            'city' => $this->city,
            'address' => $this->address,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'map_zoom' => $this->map_zoom,
        ];

        try {
            DB::beginTransaction();
            if ($this->objId) {
                $objId = Crypt::decrypt($this->objId);
                StudioRepository::update($objId, $validatedData);
            } else {
                $obj = StudioRepository::create($validatedData);
                $objId = $obj->id;
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
        return view('livewire.master-data.studio.detail');
    }
}
