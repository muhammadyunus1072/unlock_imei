<?php

namespace App\Livewire\MasterData\ProductDetail;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Helpers\FilePathHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MasterData\Product\ProductRepository;
use App\Repositories\MasterData\Product\ProductDetailRepository;

class Detail extends Component
{
    use WithFileUploads;
    
    public $objId;
    public $editedIndex;
    public $image;

    public $modal_image_url = '';
    public $product_details = [];
    public $product_detail_removes = [];

    public function mount()
    {
        $product_details = ProductRepository::find(Crypt::decrypt($this->objId))->productDetails;
        if($product_details->count() > 0){
            foreach ($product_details as $key => $value) {
                $this->product_details[] = [
                    'id' => Crypt::encrypt($value->id),
                    'key' => Str::random(30),
                    'name' => $value->name,
                    'price' => ValueToImask($value->price),
                    'description' => $value->description,
                    'image' => $value->image,
                    'image_url' => $value->image ? Storage::url(FilePathHelper::FILE_PRODUCT_DETAIL_IMEI . $value->image) : asset("media/404.png"),
                ];
            }
        }else{
            $this->addProductDetail();
        }
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('product_detail.edit', $this->objId);
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('product.edit', $this->objId);
    }

    public function addProductDetail()
    {
        $this->product_details[] = [
            'id' => null,
            'key' => Str::random(30),
            'name' => null,
            'price' => 0,
            'catatan' => null,
            'image' => null,
            'image_url' => asset("media/404.png"),
        ];
    }

    public function removeProductDetail($index)
    {
        if ($this->product_details[$index]['id']) {
            $this->product_detail_removes[] = $this->product_details[$index]['id'];
        }

        unset($this->product_details[$index]);
    }

    public function editImage($index)
    {
        $this->modal_image_url = $this->product_details[$index]['image_url'];
        $this->editedIndex = $index;
        $this->dispatch('openModal');
    }

    public function saveImage()
    {
        try {
            DB::beginTransaction(); 
            // Validate the uploaded image file
            $this->validate([
                'image' => 'required|image',
            ],[
                'image.required' => 'Pilih gambar terlebih dahulu',
                'image.image' => 'File yang diupload harus berupa gambar',
            ]);
            
            $file = $this->image;      
            $filePath = $file->store(FilePathHelper::FILE_PRODUCT_DETAIL_IMAGE, 'public');
            $product_detail = $this->product_details[$this->editedIndex];
            if ($product_detail['id']) {
                $validatedData = [
                    'image' => basename($filePath),
                ];
                $objId = Crypt::decrypt($product_detail['id']);
                ProductDetailRepository::update($objId, $validatedData);
            }else{
                $image_url = Storage::url(FilePathHelper::FILE_PRODUCT_DETAIL_IMAGE . basename($filePath));
                $this->product_details[$this->editedIndex]['image_url'] = $image_url;
                $this->product_details[$this->editedIndex]['image'] = basename($filePath);
            }
            // Commit the database transaction
            $this->modal_image_url = asset("media/404.png");
            $this->dispatch('closeModal');
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function store()
    {
        try {       
            DB::beginTransaction();
            foreach($this->product_details as $product_detail){
                if(!$product_detail['name']){
                    throw new Exception("Nama Produk tidak boleh kosong");
                }
                
                if(!$product_detail['image']){
                    throw new Exception("Gambar Produk tidak boleh kosong");
                }
                $validatedData = [
                    'product_id' => Crypt::decrypt($this->objId),
                    'name' => $product_detail['name'],
                    'price' => imaskToValue($product_detail['price']),
                    'description' => $product_detail['description'],
                    'image' => $product_detail['image'],
                ];
                if ($product_detail['id']) {
                    $objId = Crypt::decrypt($product_detail['id']);
                    ProductDetailRepository::update($objId, $validatedData);
                } else {
                    $obj = ProductDetailRepository::create($validatedData);
                    $objId = $obj->id;
                }
            }

            foreach($this->product_detail_removes as $product_detail_id)
            {
                ProductDetailRepository::delete(Crypt::decrypt($product_detail_id));
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
        return view('livewire.master-data.product-detail.detail');
    }
}
