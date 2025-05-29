<?php

namespace App\Livewire\Public\Product;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\MasterData\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\MasterData\ProductDetail;

class Data extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $length = 12;

    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $loading = false;

    private function getProducts()
    {
        $query = ProductDetail::select(
            'product_id',
            DB::raw('SUM(price) as price'),
        )
        ->groupBy('product_id');
        
        return Product::select(
            'products.*',
            'product_details.price',
        )
        ->joinSub($query, 'product_details', function ($join) {
            $join->on('products.id', '=', 'product_details.product_id');
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
