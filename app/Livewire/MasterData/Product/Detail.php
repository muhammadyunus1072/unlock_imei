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
use App\Repositories\MasterData\Studio\StudioRepository;
use App\Repositories\MasterData\Product\ProductRepository;

class Detail extends Component
{
    use WithFileUploads;
    
    public $objId;

    #[Validate('required', message: 'Studio Harus Diisi', onUpdate: false)]
    public $studio_id;
    public $studio_text;
    public $studios = [];

    #[Validate('required', message: 'Nama Produk Harus Diisi', onUpdate: false)]
    public $name;

    #[Validate('required', message: 'Harga Harus Diisi', onUpdate: false)]
    public $price;

    public $image_url;
    public $image;
    public $description;
    public $note = "ABC";

    public function mount()
    {
        $this->studios = auth()->user()->studios->map(function ($studio) {
            return [
                'id' => Crypt::encrypt($studio->id),
                'name' => $studio->name,
                'city' => $studio->city,
            ];
        });
        
        if ($this->objId) {
            $id = Crypt::decrypt($this->objId);
            $product = ProductRepository::find($id);
            $this->studio_id = Crypt::encrypt($product->studio_id);
            $this->studio_text = $product->studio->name ."-". $product->studio->city;
            $this->name = $product->name;
            $this->price = valueToImask($product->price);
            $this->description = $product->description;
            $this->note = $product->note;
            $this->image_url = $product->image_url();
        }else{
            $this->studio_id = $this->studios[0]['id'];
        }
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
                'studio_id' => Crypt::decrypt($this->studio_id),
                'name' => $this->name,
                'description' => $this->description,
                'price' => imaskToValue($this->price),
                'note' => $this->note,
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
