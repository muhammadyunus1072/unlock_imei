<?php

namespace App\Livewire\Public\Product;

use Livewire\Component;
use App\Models\MasterData\Studio;
use Illuminate\Support\Facades\Crypt;

class Filter extends Component
{
    public $dispatchEvent = 'datatable-add-filter';

    public $studio_id;
    public $studios = [];

    public function mount()
    {
        $this->studios = Studio::get()->map(function ($studio) {
            return [
            'id' => Crypt::encrypt($studio->id),
            'name' => $studio->name,
            'city' => $studio->city,
            ];
        });
    }

    public function updated()
    {
        $this->dispatchFilter();
    }

    private function dispatchFilter()
    {
        $this->dispatch($this->dispatchEvent, [
            'studio_id' => $this->studio_id,
        ]);
    }

    public function render()
    {
        return view('livewire.public.product.filter');
    }
}
