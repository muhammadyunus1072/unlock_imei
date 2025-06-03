<div class="row">
    <form wire:submit="store">
        <div class='row'>
            <div class="row p-0 m-0">
                <div class="col-md-10 mb-4">
                    <label>Nama Produk</label>
                    <input placeholder="Nama Produk" type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
        
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row p-0 m-0">
                <div class="col-md-10 mb-4">
                    <label>Lama Garansi (Hari)</label>
                    <input placeholder="Lama Garansi (Hari)" type="text" class="form-control currency @error('warranty_days') is-invalid @enderror" wire:model="warranty_days" />
        
                    <div class="form-text" id="basic-addon4">0 = Tanpa Garansi, Kosong = Garansi Selamanya</div>
                    @error('warranty_days')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row p-0 m-0">
                <div class="col-md-10 mb-4">
                    <label>Harga</label>
                    <input placeholder="Harga" type="text" class="form-control currency @error('price') is-invalid @enderror" wire:model="price" />
        
                    @error('price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row p-0 m-0">
                <div class="col-md-10">
                    <label> Gambar Contoh</label>
                    <div class="card shadow p-3 mb-5 bg-body-tertiary rounded position-relative">
                        <img id="imagePreview" src="{{ $image_url ? $image_url : asset("media/404.png") }}" class="img-thumbnail rounded" alt="...">
                        <div id="cobaPrev"></div>
                        <div class="card-body position-absolute bottom-0 end-0">
                        <button type="button" class="btn p-3 btn-secondary btn-icon-primary btn-text-primary me-2" data-bs-toggle="modal" data-bs-target="#ImageModal">
                            <i class="ki-duotone ki-brush fs-2qx text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </button>
                        <button type="button" class="btn p-3 btn-secondary btn-icon-danger btn-text-danger" wire:click="deleteImage">
                            <i class="ki-duotone ki-trash fs-2qx text-danger">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-10 mb-4">
                <div class="form-group" wire:ignore>
                    <label>Deskripsi</label>
                    <textarea class="form-control ckeditor"  id="description" name="description">{{ $description }}</textarea>
                </div>
            </div>

            <div class="col-md-10 mb-4">
                <button type="submit" class="btn btn-success mt-3 w-100">
                    Simpan
                </button>
            </div>

        <!-- Modal -->
        <div class="modal fade" id="ImageModal" tabindex="-1" aria-labelledby="imageLabelModal" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="imageLabelModal">Unggah Gambar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                {{-- FILE --}}
                <div class="form-group">
                    <label class="form-label"
                        for="image">Unggah Gambar :</label>
                    <div class="custom-file">
                        <input type="file" wire:model.blur="image" id="inputImage" class="form-control mb-2 d-none" accept="image/*">
      
                        <div wire:ignore>
                            <img id="ImageEdit" class="img-thumbnail rounded" src="{{ $image_url ? $image_url : asset("media/404.png") }}" style="display:block; width:100%;" class="mb-2"/>
              
                        </div>
                        
                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                  </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary" id="btnCancelSaveImage">Batal</button>
                <button type="button" id="btnInputImage" class="btn btn-secondary" >Gunakan Gambar Lain</button>
                <button type="button" class="btn btn-primary my-2" wire:click="saveImage" style="display:block;" wire:loading.attr='disabled'>
                    <div wire:loading>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </div>
    
                    <div class='d-flex align-items-center' wire:loading.class="d-none">
                        <i class='ki-duotone ki-check fs-1'></i>
                        Simpan
                    </div>
                </button>
                </div>
            </div>
            </div>
        </div>
    </form>
</div>


@include('js.imask')
@include('js.ckeditor')

@push('js')
    <script>
        $(() => {
            // INPUT IMAGE
            // Trigger the file input on button click
            $('#btnInputImage').on('click', function() {
                $('#inputImage').click();
            });
            document.getElementById('inputImage').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('ImageEdit');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } 
            });
            document.getElementById('btnCancelSaveImage').addEventListener('click', function(event) {
                const fileInput = document.getElementById('inputImage'); 
                fileInput.value = '';
                const imageOld = $("#imagePreview").attr('src');
                $('#ImageEdit').attr('src', imageOld);
                $('#ImageModal').modal('hide');
            });

            Livewire.on('resetCroppedImage', (res) => {
                $('#'+res[0]+'Modal').modal('hide');
                $("#"+res[0]+"Edit").attr('src', res[1]);
                setTimeout(() => {
                    const fileInput = document.getElementById('inputImage'); 
                    fileInput.value = '';
                }, 500);
            });
        });
    </script>
@endpush
