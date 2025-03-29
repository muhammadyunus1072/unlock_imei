<?php

namespace App\Livewire\Public\Product;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\MasterData\Product;
use Illuminate\Support\Facades\Crypt;

class Data extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $length = 12;

    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $loading = false;

    public $studio_id = 'all';

    private function getProducts()
    {
        return Product::when($this->studio_id !== 'all', function ($query) {
            return $query->where('studio_id', Crypt::decrypt($this->studio_id));
        })
        ->orderBy($this->sortBy, $this->sortDirection);
    }

    #[On('datatable-add-filter')]
    public function datatableAddFilter($filter)
    {
        foreach ($filter as $key => $value) {
            $this->$key = $value;
        }
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.public.product.data', [
            'data' => $this->getProducts()->paginate($this->length),
        ]);
    }
}
