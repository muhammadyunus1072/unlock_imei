<div class="row">
    <form wire:submit="store">
        <div class='row'>
            <div class="row p-0 m-0">
                <div class="col-md-auto">
                    <button type="button" class="btn btn-success" wire:click="addProductDetail">
                        <i class="ki-duotone ki-plus fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Tambah Detail Produk
                    </button>
                </div>
            </div>

            @foreach ($product_details as $index => $item)
                <div class="row p-0 m-0 mt-4">
                    <div class="col-lg-10 col-md-12 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nama Detail Produk</label>
                                <input placeholder="Nama Detail Produk" type="text" class="form-control @error('product_details.{{ $index }}.name') is-invalid @enderror" wire:model="product_details.{{ $index }}.name" />
                    
                                @error('product_details.{{ $index }}.name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                                    
                            <div class="col-md-6">
                                <label class='fw-bold'>Harga</label>
                                <input type="text" class="form-control currency" placeholder="Harga" wire:model="product_details.{{ $index }}.price"/>

                                @error('product_details.{{ $index }}.price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea rows="5" class="form-control" placeholder="Catatan" wire:model="product_details.{{ $index }}.description"></textarea>

                                    @error('product_details.{{ $index }}.description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <input type="hidden">
                                    @error('product_details.{{ $index }}.image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <div class="row p-0 m-0">
                            <div class="col-md-12 p-0 m-0">
                                <div class="card shadow p-1 mb-5 bg-body-tertiary rounded position-relative">
                                    <img id="imagePreview" src="{{ $item['image_url'] ? $item['image_url'] : asset("media/404.png") }}" class="img-thumbnail rounded" alt="...">
                                    <div id="cobaPrev"></div>
                                    <div class="card-body position-absolute bottom-0 end-0">
                                        <button type="button" class="btn p-1 btn-secondary btn-icon-primary btn-text-primary me-1" wire:click="editImage('{{$index}}')">
                                            <i class="ki-duotone ki-brush fs-6 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 p-0 m-0">
                                <button type="button" class="btn btn-danger mt-3 w-100" wire:click="removeProductDetail('{{ $index }}')">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="mt-2">
            @endforeach

            <div class="col-md-10 mb-4 mx-auto">
                <button type="submit" class="btn btn-success mt-3 w-100">
                    Simpan
                </button>
            </div>

        <!-- Modal -->
        <div class="modal fade" id="ImageModal" tabindex="-1" aria-labelledby="imageLabelModal" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="imageLabelModal">Unggah Gambar Seating Layout</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                {{-- FILE --}}
                <div class="form-group">
                    <label class="form-label"
                        for="image">Unggah Gambar :</label>
                    <div class="custom-file">
                        <input type="file" wire:model.blur="image" id="inputImage" class="form-control mb-2 d-none" accept="image/*">
      
                        <div>
                            <img id="ImageEdit" class="img-thumbnail rounded" src="{{ $modal_image_url ? $modal_image_url : asset("media/404.png") }}" style="display:block; width:100%;" class="mb-2"/>
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
            Livewire.on('openModal', (res) => {
                $('#ImageModal').modal('show');
                const fileInput = document.getElementById('inputImage'); 
                fileInput.value = '';
                console.log("OPEN")
            });
            Livewire.on('closeModal', (res) => {
                $('#ImageModal').modal('hide');
                $('#ImageEdit').attr('src', '{{asset("media/404.png")}}');
                const fileInput = document.getElementById('inputImage'); 
                fileInput.value = '';
                console.log("CLOSE")
            });
            // Trigger the file input on button click
            $('#btnInputImage').on('click', function() {
                $('#inputImage').click();
            });
            document.getElementById('inputImage').addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        @this.set('modal_image_url', e.target.result);
                    };
                    reader.readAsDataURL(file);
                } 
            });
            document.getElementById('btnCancelSaveImage').addEventListener('click', function(event) {
                const fileInput = document.getElementById('inputImage'); 
                fileInput.value = '';
                $('#ImageEdit').attr('src', '{{asset("media/404.png")}}');
                $('#ImageModal').modal('hide');
            });

        });
    </script>
@endpush
