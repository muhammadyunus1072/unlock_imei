<div>
        <div class="container checkout__wrapper">
            <!-- Configuration -->
            <div class="configure">
                <h2 class="panel-configure__title">Create Orders</h2>
                <div class="form-group">
                    <h5 class="fw-bold">Contact Information:</h5>
                </div>
                <div class="pe-xl-10 pe-md-10 mb-5">
                    <div class="form-group mt-5">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" placeholder="Masukkan Nama Sesuai KTP" wire:model="customer_name" required>
        
                        @error('customer_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mt-5">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" placeholder="Masukkan Email Aktif" wire:model="customer_email" required>
        
                        @error('customer_email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mt-5">
                        <label>No Whatsapp</label>
                        <div class="input-group" wire:ignore>
                            <span class="input-group-text" id="basic-addon1">+62</span>
                            <input type="text" class="form-control phone @error('customer_phone') is-invalid @enderror" name="customer_phone" model-name="customer_phone" min="1" placeholder="8XX-XXXX-XXXX" aria-label="customer_phone" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-text" id="basic-addon4">Contoh +62 8XX-XXXX-XXXX</div>
                    </div>
                    <div class="form-group mt-5">
                        <label>Instagram</label>
                        <div class="input-group">
                            <span class="input-group-text m-0 p-0" style="border: 0;" id="basic-addon1">
                                <a href="#" class="btn btn-icon btn-light-instagram rounded-0"><i class="fab fa-instagram fs-4"></i></a>
                            </span>
                            <input type="text" class="form-control @error('customer_ig') is-invalid @enderror" wire:model="customer_ig" placeholder="Instagram" aria-label="customer_ig" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <label>Facebook</label>
                        <div class="input-group">
                            <span class="input-group-text m-0 p-0" style="border: 0;" id="basic-addon1">
                                <a href="#" class="btn btn-icon btn-light-facebook rounded-0"><i class="fab fa-facebook fs-4"></i></a>
                            </span>
                            <input type="text" class="form-control @error('customer_fb') is-invalid @enderror" wire:model="customer_fb" placeholder="Facebook" aria-label="customer_fb" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div
                        x-data="{
                            isDragging: false,
                            handleDrop(event) {
                                const file = event.dataTransfer.files[0]; // Only take the first file
                                if (file) {
                                    const dataTransfer = new DataTransfer();
                                    dataTransfer.items.add(file);
                                    $refs.input.files = dataTransfer.files;
                                    $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                                this.isDragging = false;
                            },
                            handleFiles(event) {
                                const file = event.target.files[0];
                                // Optional: you can limit or validate here
                            }
                        }"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        :class="isDragging ? 'border-primary bg-light border-3' : 'border-secondary'"
                        class="form-group mt-5"
                    >
                        <label class="form-label">Upload KTP / SIM</label>
        
                        <label
                            for="upload_image_background"
                            class="upload_dropZone text-center mb-3 p-4 w-100 border border-dashed rounded"
                            :class="isDragging ? 'bg-light border-primary border-3' : 'border-secondary'"
                        >
                            <legend class="visually-hidden">Image uploader</legend>
        
                            <svg class="upload_svg" width="60" height="60" aria-hidden="true">
                                <use href="#icon-imageUpload"></use>
                            </svg>
        
                            <p class="small my-2">
                                Drag & Drop KTP / SIM di dalam wilayah putus-putus<br><i>atau</i>
                            </p>
        
                            <input
                                x-ref="input"
                                id="upload_image_background"
                                type="file"
                                wire:model="customer_ktp"
                                @change="handleFiles"
                                accept="image/jpeg, image/png, image/svg+xml"
                                class="position-absolute invisible"
                            />
        
                            <label class="btn btn-upload mb-3" for="upload_image_background">Choose file(s)</label>
        
                            <!-- Optional preview -->
                            <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0">
                                @if ($customer_ktp)
                                    <div class="text-center">
                                        <img src="{{ $customer_ktp->temporaryUrl() }}" alt="preview" class="img-thumbnail">
                                    </div>
                                @endif
                            </div>
                        </label>
        
                        @error('customer_ktp.*') <div class="text-danger">{{ $message }}</div> @enderror
        
                        <!-- SVG icon definition -->
                        <svg style="display:none">
                            <defs>
                                <symbol id="icon-imageUpload" clip-rule="evenodd" viewBox="0 0 96 96">
                                    <path d="M47 6a21 21 0 0 0-12.3 3.8c-2.7 2.1-4.4 5-4.7 7.1-5.8 1.2-10.3 5.6-10.3 10.6 0 6 5.8 11 13 11h12.6V22.7l-7.1 6.8c-.4.3-.9.5-1.4.5-1 0-2-.8-2-1.7 0-.4.3-.9.6-1.2l10.3-8.8c.3-.4.8-.6 1.3-.6.6 0 1 .2 1.4.6l10.2 8.8c.4.3.6.8.6 1.2 0 1-.9 1.7-2 1.7-.5 0-1-.2-1.3-.5l-7.2-6.8v15.6h14.4c6.1 0 11.2-4.1 11.2-9.4 0-5-4-8.8-9.5-9.4C63.8 11.8 56 5.8 47 6Zm-1.7 42.7V38.4h3.4v10.3c0 .8-.7 1.5-1.7 1.5s-1.7-.7-1.7-1.5Z M27 49c-4 0-7 2-7 6v29c0 3 3 6 6 6h42c3 0 6-3 6-6V55c0-4-3-6-7-6H28Zm41 3c1 0 3 1 3 3v19l-13-6a2 2 0 0 0-2 0L44 79l-10-5a2 2 0 0 0-2 0l-9 7V55c0-2 2-3 4-3h41Z M40 62c0 2-2 4-5 4s-5-2-5-4 2-4 5-4 5 2 5 4Z"/>
                                </symbol>
                            </defs>
                        </svg>
                    </div>
                    
                </div>
                <div class="subtotal-wrapper">
                    <div id="subtotal" class="cart-subtotal">
                        <div class="cart-subtotal__order-item mb-5">
                            <div class="order-item__label">Subtotal</div>
                            <div class="order-item__price ms-5">
                                Rp. @currency($subtotal)
                            </div>
                        </div>
                        <div class="cart-subtotal__order-item mb-3">
                            <div class="order-item__label my-auto">Kode Diskon</div>
                            <div class="order-item__price ms-5 row col-lg-auto d-flex justify-content-start gap-1 col-sm-12">
                                <div class="input-group p-0 m-0">
                                    <input type="text" class="form-control mx-0 @error('code') is-invalid @enderror" wire:model="code" oninput="this.value = this.value.toUpperCase();" placeholder="Kode">
                                    <button type="button" class="btn text-white" style="background-color: #5d2fc2; " wire:click="couponHandler">Pakai</button>
                                </div>
                            </div>
                        </div>
                        @if ($voucher_id)
                            <div class="cart-subtotal__order-item mb-2">
                                <div class="order-item__label">
                                    Potongan Discount
                                </div>
                                <div class="order-item__price ms-5">
                                    Rp. @currency($discount)
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="total-wrapper">
                    <div id="total" class="cart-total pt-0">
                        <div class="cart-total__order-item">
                            <div class="order-item__label">Total Rp. @currency($grand_total)</div>
                        </div>
                    </div>
                </div>

                <div class="row sm-d-none">
                    <form id="" class="w-100 " autocomplete="off" wire:submit="store">
                        <button type="submit" id="btn-confirm-order" class="btn text-white w-100" style="background-color: #5d2fc2;">
                            <span class="indicator-label">Confirm Order</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
            <!-- Cart -->
            <div class="cart mb-4 w-100 p-0">
                <div class="p-5">
                    <h2 class="cart-summary__title fs-1 p-0">Order Detail</h2>
                    <div class="form-group mb-0">
                        <table class="table w-auto my-0 py-0">
                            <tr>
                                <td><h5 class="fw-bold my-0 py-0">Produk</h5></td>
                                <td><h5 class="fw-bold my-0 py-0">:</h5></td>
                                <td><h5 class="fw-bold my-0 py-0">{{$product['name']}}</h5></td>
                            </tr>
                            <tr>
                                <td><h5 class="fw-bold my-0 py-0">Deskripsi</h5></td>
                                <td><h5 class="fw-bold my-0 py-0">:</h5></td>
                                <td>{!! $product['description'] !!}</td>
                            </tr>
                            <tr>
                                <td><h5 class="fw-bold my-0 py-0">Harga</h5></td>
                                <td><h5 class="fw-bold my-0 py-0">:</h5></td>
                                <td><h5 class="fw-bold my-0 py-0">Rp. @currency($product['price']) / IMEI</h5></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row mt-4">
                        <button type="button" class="btn btn-success w-75 mx-auto" wire:click="addProduct"><i class='fa fa-plus'></i> Tambah IMEI <i class='fa fa-plus'></i></button>
                    </div>
                    {{-- Main Product --}}
                    @foreach ($product_details as $index => $item)
                        <div class="row p-0 m-0 mt-4">
                            <div class="col-lg-12 col-md-12 mb-2">
                                <div class="row">
                                    <div class="order-item__description-name form-label d-flex justify-content-between">
                                        <span class="fs-3 fw-bold me-3">{{$index+1}}. Upload screenshot IMEI </span>
                                        <button type="button" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger col-auto py-1 px-3 text-center" wire:click="removeProduct({{ $index }})">
                                            <i class='fa fa-trash p-0 m-0'></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div
                                x-data="{
                                        isDragging: false,
                                        handleDrop(event) {
                                            const file = event.dataTransfer.files[0]; // Only take the first file
                                            if (file) {
                                                const dataTransfer = new DataTransfer();
                                                dataTransfer.items.add(file);
                                                $refs.input.files = dataTransfer.files;
                                                $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
                                            }
                                            this.isDragging = false;
                                        },
                                        handleFiles(event) {
                                            const file = event.target.files[0];
                                            // Optional: you can limit or validate here
                                        }
                                    }"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    @drop.prevent="handleDrop($event)"
                                    :class="isDragging ? 'border-primary bg-light border-3' : 'border-secondary'"
                                    class="form-group mt-0"
                                >
                                    <label
                                        for="upload_imei_{{$index}}"
                                        class="upload_dropZone text-center mb-3 p-4 w-100 border border-dashed rounded"
                                        :class="isDragging ? 'bg-light border-primary border-3' : 'border-secondary'"
                                    >
                                        <legend class="visually-hidden">Image uploader</legend>
        
                                        <svg class="upload_svg" width="60" height="60" aria-hidden="true">
                                            <use href="#icon-imageUpload"></use>
                                        </svg>
        
                                        <p class="small my-2">
                                            Drag & Drop screenshot IMEI di dalam wilayah putus-putus<br><i>atau</i>
                                        </p>
        
                                        <input
                                            x-ref="input"
                                            id="upload_imei_{{$index}}"
                                            type="file"
                                            wire:model="product_details.{{ $index }}.imei"
                                            @change="handleFiles"
                                            accept="image/jpeg, image/png, image/svg+xml"
                                            class="position-absolute invisible"
                                        />
        
                                        <label class="btn btn-upload mb-3" for="upload_imei_{{$index}}">Choose file(s)</label>
        
                                        <!-- Optional preview -->
                                        <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0">
                                            @if ($product_details[$index]['imei'])
                                                <div class="text-center">
                                                    <img src="{{ $product_details[$index]['imei']->temporaryUrl() }}" alt="preview" class="img-thumbnail">
                                                </div>
                                            @endif
                                        </div>
                                    </label>
        
                                    @error('product_details.{{$index}}.imei') <div class="text-danger">{{ $message }}</div> @enderror
        
                                    <!-- SVG icon definition -->
                                    <svg style="display:none">
                                        <defs>
                                            <symbol id="icon-imageUpload" clip-rule="evenodd" viewBox="0 0 96 96">
                                                <path d="M47 6a21 21 0 0 0-12.3 3.8c-2.7 2.1-4.4 5-4.7 7.1-5.8 1.2-10.3 5.6-10.3 10.6 0 6 5.8 11 13 11h12.6V22.7l-7.1 6.8c-.4.3-.9.5-1.4.5-1 0-2-.8-2-1.7 0-.4.3-.9.6-1.2l10.3-8.8c.3-.4.8-.6 1.3-.6.6 0 1 .2 1.4.6l10.2 8.8c.4.3.6.8.6 1.2 0 1-.9 1.7-2 1.7-.5 0-1-.2-1.3-.5l-7.2-6.8v15.6h14.4c6.1 0 11.2-4.1 11.2-9.4 0-5-4-8.8-9.5-9.4C63.8 11.8 56 5.8 47 6Zm-1.7 42.7V38.4h3.4v10.3c0 .8-.7 1.5-1.7 1.5s-1.7-.7-1.7-1.5Z M27 49c-4 0-7 2-7 6v29c0 3 3 6 6 6h42c3 0 6-3 6-6V55c0-4-3-6-7-6H28Zm41 3c1 0 3 1 3 3v19l-13-6a2 2 0 0 0-2 0L44 79l-10-5a2 2 0 0 0-2 0l-9 7V55c0-2 2-3 4-3h41Z M40 62c0 2-2 4-5 4s-5-2-5-4 2-4 5-4 5 2 5 4Z"/>
                                            </symbol>
                                        </defs>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-2">
                    @endforeach
                </div>
            </div>

                <div class="row lg-d-none">
                    <form id="" class="w-100 " autocomplete="off" wire:submit="store">
                        <button type="submit" id="btn-confirm-order" class="btn text-white w-100" style="background-color: #5d2fc2;">
                            <span class="indicator-label">Confirm Order</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </form>
                </div>
        </div>
</div>

@push('css')
    <link href="{{ asset('assets/css/custom-homepage.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .sm-d-none {
            display: none !important;
        }
        .lg-d-none {
            display: none !important;
        }

        @media (min-width: 575.98px) {
            .sm-d-none {
                display: block !important;
            }
        }

        @media (max-width: 575.98px) {
            .lg-d-none {
                display: block !important;
            }
        }

       .overlay:hover .img-detail {
          filter: brightness(0.7);
       }
 
       .overlay:hover .eye-button {
          filter: none !important;
       }
       /* Bootstrap 5 CSS and icons included */
        :root {
        --colorPrimaryNormal: #00b3bb;
        --colorPrimaryDark: #00979f;
        --colorPrimaryGlare: #00cdd7;
        --colorPrimaryHalf: #80d9dd;
        --colorPrimaryQuarter: #bfecee;
        --colorPrimaryEighth: #dff5f7;
        --colorPrimaryPale: #f3f5f7;
        --colorPrimarySeparator: #f3f5f7;
        --colorPrimaryOutline: #dff5f7;
        --colorButtonNormal: #00b3bb;
        --colorButtonHover: #00cdd7;
        --colorLinkNormal: #00979f;
        --colorLinkHover: #00cdd7;
        }

        .upload_dropZone {
        color: #0f3c4b;
        background-color: var(--colorPrimaryPale, #c8dadf);
        outline: 2px dashed var(--colorPrimaryHalf, #c1ddef);
        outline-offset: -12px;
        transition:
            outline-offset 0.2s ease-out,
            outline-color 0.3s ease-in-out,
            background-color 0.2s ease-out;
        }
        .upload_dropZone.highlight {
        outline-offset: -4px;
        outline-color: var(--colorPrimaryNormal, #0576bd);
        background-color: var(--colorPrimaryEighth, #c8dadf);
        }
        .upload_svg {
        fill: var(--colorPrimaryNormal, #0576bd);
        }
        .btn-upload {
        color: #fff;
        background-color: var(--colorPrimaryNormal);
        }
        .btn-upload:hover,
        .btn-upload:focus {
        color: #fff;
        background-color: var(--colorPrimaryGlare);
        }
        .upload_img {
        width: calc(33.333% - (2rem / 3));
        object-fit: contain;
        }

    </style>
@endpush

@include('js.imask')

@push('js')
   <script src="{{ asset('assets/plugins/custom/fslightbox-basic-3.5.1/fslightbox.js') }}"></script>

    <script>
        navigator.geolocation.getCurrentPosition(
            function (position) {
                lat = position.coords.latitude;
                lng = position.coords.longitude;

                @this.call('setLocation', lat, lng);
                initMap();
            },
            function (error) {
                window.location.href = "{{ route('public.index')}}";

            },
            {
                enableHighAccuracy: true, // use GPS if available
                timeout: 10000,           // wait up to 10 seconds
                maximumAge: 0             // do not use cached location
            }
        );
    </script>
@endPush