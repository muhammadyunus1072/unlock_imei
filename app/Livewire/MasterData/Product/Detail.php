<?php

namespace App\Livewire\MasterData\Product;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Helpers\FilePathHelper;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MasterData\Product\ProductRepository;
use App\Repositories\MasterData\Product\ProductWarrantyRepository;

class Detail extends Component
{
    use WithFileUploads;
    
    public $objId;

    #[Validate('required', message: 'Nama Produk Harus Diisi', onUpdate: false)]
    public $name;

    public $image_url;
    public $image;
    public $description;
    public $product_warranty_id;
    public $product_warranty_choices = [];

    public function mount()
    {   
        if ($this->objId) {
            $id = Crypt::decrypt($this->objId);
            $product = ProductRepository::find($id);
            $this->name = $product->name;
            $this->description = $product->description;
            $this->product_warranty_id = simple_encrypt($product->product_warranty_id);
            $this->image_url = $product->image_url();
        }

        $this->product_warranty_choices = ProductWarrantyRepository::all()->map(function ($product_warranty) {
                return [
                    'id' => simple_encrypt($product_warranty->id),
                    'name' => $product_warranty->name,
                ];
            });
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('product.edit', $this->objId);
        } else {
            $this->redirectRoute('product.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('product.index');
    }

    public function saveImage()
    {
        try {
            DB::beginTransaction(); 
            // Validate the uploaded image file
            $this->validate([
                'image' => 'required|image',
            ]);
            
            $file = $this->image;      
            $filePath = $file->store(FilePathHelper::FILE_PRODUCT_IMAGE, 'public');
            if ($this->objId) {
                $validatedData = [
                    'image' => basename($filePath),
                ];
                $objId = Crypt::decrypt($this->objId);
                ProductRepository::update($objId, $validatedData);
            }else{
                session([
                    'uploaded_image' => $filePath,
                ]);
            }
            // Commit the database transaction
            $this->image_url = Storage::url(FilePathHelper::FILE_PRODUCT_IMAGE . basename($filePath));
            $this->dispatch('resetCroppedImage', 'Image', $this->image_url);
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function deleteImage()
    {
        if (session()->has('uploaded_image')) {
            $filePath = session('uploaded_image');
            Storage::disk('public')->delete($filePath);
            session()->forget('uploaded_image');
            $this->image_url = null;
            $this->dispatch('resetCroppedImage', 'Image', asset("media/404.png"));
        }
    }
    public function store()
    {
        $this->validate();
        try {
            $validatedData = [
                'name' => $this->name,
                'description' => $this->description,
                'product_warranty_id' => $this->product_warranty_id ? simple_decrypt($this->product_warranty_id) : null,
            ];
            $filePath = session('uploaded_image', null);
            if ($filePath) {
                $validatedData['image'] = basename($filePath);
            }
            
            DB::beginTransaction();
            if ($this->objId) {

                $objId = Crypt::decrypt($this->objId);
                ProductRepository::update($objId, $validatedData);
            } else {
                $obj = ProductRepository::create($validatedData);
                $objId = $obj->id;
            }

            DB::commit();
            if (session()->has('uploaded_image')) {
                session()->forget('uploaded_image');
            }
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
        return view('livewire.master-data.product.detail');
    }
}
