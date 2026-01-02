<?php

namespace App\Livewire\Public\Undangan;

use Livewire\Component;
use App\Models\MasterData\Studio;
use Illuminate\Support\Facades\Crypt;

class Filter extends Component
{
    public $dispatchEvent = 'datatable-add-filter';


    public function mount() {}

    public function updated()
    {
        $this->dispatchFilter();
    }

    private function dispatchFilter() {}

    public function render()
    {
        return view('livewire.public.undangan.filter');
    }
}
