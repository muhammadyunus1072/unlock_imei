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
                <input type="text" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" placeholder="Masukkan Email Aktif" wire:model="customer_email" required>

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
                    <input type="text" class="form-control phone @error('phone') is-invalid @enderror" name="phone" model-name="phone" min="1" placeholder="8XX-XXXX-XXXX" aria-label="phone" aria-describedby="basic-addon1">
                </div>
                <div class="form-text" id="basic-addon4">Contoh +62 8XX-XXXX-XXXX</div>
            </div>
            <div class="form-group mt-5">
                <label>Instagram</label>
                <div class="input-group" wire:ignore>
                    <span class="input-group-text" id="basic-addon1">
                        <i class="px-2 font-size-20px fa-brands fa-instagram text-dark"></i>
                    </span>
                    <input type="text" class="form-control @error('customer_ig') is-invalid @enderror" wire.model="customer_ig" min="1" placeholder="Instagram" aria-label="customer_ig" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="form-group mt-5">
                <label>Facebook</label>
                <div class="input-group" wire:ignore>
                    <span class="input-group-text" id="basic-addon1">
                        <i class="px-2 font-size-20px fa-brands fa-facebook text-dark"></i>
                    </span>
                    <input type="text" class="form-control @error('customer_fb') is-invalid @enderror" wire.model="customer_fb" min="1" placeholder="Facebook" aria-label="customer_fb" aria-describedby="basic-addon1">
                </div>
            </div>
            <div
                x-data="{
                    isDragging: false,
                    handleDrop(event) {
                        const files = event.dataTransfer.files;
                        if (files.length > 0) {
                            const dataTransfer = new DataTransfer();
                            for (let i = 0; i < files.length; i++) {
                                dataTransfer.items.add(files[i]);
                            }
                            $refs.input.files = dataTransfer.files;
                            $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                        this.isDragging = false;
                    },
                    handleFiles(event) {
                        const files = event.target.files;
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
                        multiple
                        wire:model="customer_ktp"
                        @change="handleFiles"
                        accept="image/jpeg, image/png, image/svg+xml"
                        class="position-absolute invisible"
                    />

                    <label class="btn btn-upload mb-3" for="upload_image_background">Choose file(s)</label>

                    <!-- Optional preview -->
                    <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0">
                        @if ($customer_ktp)
                            @foreach ($customer_ktp as $file)
                                <div class="text-center">
                                    <img src="{{ $file->temporaryUrl() }}" alt="preview" class="img-thumbnail">
                                </div>
                            @endforeach
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


            <div class="form-group mt-5" id="container-payment-method">
                <label for="payment-method" class="form-label">Metode Pembayaran</label>
                <select id="payment-method" class="form-select" aria-label="Default Select Payment Methods" wire:model.live="payment_method">
                    <option value="">Pilih Metode Pembayaran</option>
                    @foreach ($payment_method_choices as $payment_method)
                        <option value="{{$payment_method['id']}}">{{$payment_method['name']}} - Admin Fee Rp. 
                            @if ($payment_method['fee_type'] === \App\Models\MasterData\PaymentMethod::TYPE_PERCENTAGE)
                                {{ numberFormat(calculatedAdminFee($subtotal, $payment_method['fee_amount'])) }}
                            @else
                                @currency($payment_method['fee_amount'])
                            @endif
                        </option>
                    @endforeach
                </select>
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
                    <div class="order-item__label my-auto">Coupon code</div>
                    <div class="order-item__price ms-5 row col-lg-auto d-flex justify-content-start gap-1 col-sm-12">
                        <div class="col-auto">
                            <input type="text" class="form-control form-control-lg" wire:model="code" oninput="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn text-white" style="background-color: #5d2fc2; " wire:click="couponHandler">Gunakan Coupon</button>
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
                    <div class="order-item__label">Total</div>
                </div>
            <div class="order-item__price ms-5">Rp. @currency($grand_total)
                </div>
            </div>
        </div>
        <form id="form-configure" class="form-configure w-100 pe-10 mb-5 mt-10 btn-confirm-order-wrapper" autocomplete="off" wire:submit="store">
            <button type="submit" id="btn-confirm-order" class="btn text-white w-100" style="background-color: #5d2fc2; ">
                <span class="indicator-label">Confirm Order</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </form>

    <div class="row">
        <div class="col-md-10 mb-4" wire:ignore>
            <label for="">Lokasi Map</label>
            <div id="map" style="height: 400px;"></div>
        </div>
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
                        <td><h5 class="fw-bold my-0 py-0">Garansi</h5></td>
                        <td><h5 class="fw-bold my-0 py-0">:</h5></td>
                        <td><h5 class="fw-bold my-0 py-0">{{$product['warranty_text']}}</h5></td>
                    </tr>
                    <tr>
                        <td><h5 class="fw-bold my-0 py-0">Deskripsi</h5></td>
                        <td><h5 class="fw-bold my-0 py-0">:</h5></td>
                        <td>{!! $product['description'] !!}</td>
                    </tr>
                </table>
            </div>
            {{-- Main Product --}}

        @foreach ($product_details as $index => $item)
            <div class="row p-0 m-0 mt-4">
                <div class="col-lg-10 col-md-12 mb-4">
                    <div class="row">
                        <div class="order-item__description-name form-label"><span class="fs-3 fw-bold me-3">{{$index+1}}.</span> {{ $item['description'] }} @ Rp. @currency($item['price'])</div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div
                        x-data="{
                            isDragging: false,
                            handleDrop(event) {
                                const files = event.dataTransfer.files;
                                if (files.length > 0) {
                                    const dataTransfer = new DataTransfer();
                                    for (let i = 0; i < files.length; i++) {
                                        dataTransfer.items.add(files[i]);
                                    }
                                    $refs.input.files = dataTransfer.files;
                                    $refs.input.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                                this.isDragging = false;
                            },
                            handleFiles(event) {
                                const files = event.target.files;
                            }
                        }"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        :class="isDragging ? 'border-primary bg-light border-3' : 'border-secondary'"
                        class="form-group mt-0"
                    >
                        <label class="form-label">Upload screenshot IMEI</label>

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
                                multiple
                                wire:model="product_details.{{ $index }}.imei"
                                @change="handleFiles"
                                accept="image/jpeg, image/png, image/svg+xml"
                                class="position-absolute invisible"
                            />

                            <label class="btn btn-upload mb-3" for="upload_imei_{{$index}}">Choose file(s)</label>

                            <!-- Optional preview -->
                            <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0">
                                @if ($product_details[$index]['imei'])
                                    @foreach ($product_details[$index]['imei'] as $file)
                                        <div class="text-center">
                                            <img src="{{ $file->temporaryUrl() }}" alt="preview" class="img-thumbnail">
                                        </div>
                                    @endforeach
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
</div>

@push('css')
    <link href="{{ asset('assets/css/custom-homepage.css') }}" rel="stylesheet" type="text/css" />
    <style>
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

        body {
        margin: 24px;
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

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('vendor/geoSearch/geosearch.css') }}"/>
    <style>
        .nospace-x{
            margin-left: 0;
            margin-right: 0;
            padding-left: 0;
            padding-right: 0;
        }
        .card.shadow{
            min-height:50px;
        }
    </style>

@endpush

@include('js.imask')

@push('js')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('vendor/geoSearch/geosearch.bundle.min.js') }}"></script>
    <script>
        $(() => {
            // MAP
            var lat;
            var lng;

            const map = L.map('map');

            L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;
                    initMap();
                    
                },
                function (error) {
                    console.warn("GPS failed, fallback to IP:", error);

                    // Fall back to IP-based location
                    getIpLocation();
                },
                {
                    enableHighAccuracy: true, // use GPS if available
                    timeout: 10000,           // wait up to 10 seconds
                    maximumAge: 0             // do not use cached location
                }
            );

            function initMap(){
                map.setView([ lat, lng], 18);
                const search = new GeoSearch.GeoSearchControl({
                    provider: new GeoSearch.OpenStreetMapProvider(),
                    showMarker: false, 
                    showPopup: false, 
                    style: 'bar', 
                    marker: {
                        
                        icon: new L.Icon.Default(),
                        draggable: false,
                    },
                    popupFormat: ({ query, result }) => result.label, 
                    resultFormat: ({ result }) => result.label, 
                    maxMarkers: 1, 
                    retainZoomLevel: false, 
                    animateZoom: true, 
                    autoClose: false, 
                    searchLabel: 'Cari Tempat Event', 
                    keepResult: false, 
                    updateMap: true, 
                });
    
                map.addControl(search);
                updateMarker(lat, lng, 18);
                map.on('click', function (event) {
                    const { lat, lng } = event.latlng; 
                    const zoom = map.getZoom(); 
                    updateMarker(lat, lng, zoom);
                })

                map.on('geosearch/showlocation', (e) => {
                    
                    const lat = e.location.y;   
                    const lng = e.location.x;   
                    const label = e.location.label;   

                    const zoom = map.getZoom(); 
                    updateMarker(lat, lng, zoom, label);
                })
            }
            


            let marker = null;

            function updateMarker(lat, lng, zoom, label = null) {
                console.log(lat);
                console.log(lat);
                console.log(zoom);
                if (marker) {
                    map.removeLayer(marker);
                }

                
                marker = L.marker([lat, lng], zoom)
                    .addTo(map)
                    .bindPopup(`Latitude: ${lat}<br>Longitude: ${lng}<br>Zoom: ${zoom}`)
                    .openPopup();
                // console.log(lat);
                // console.log(lng);
                // console.log(zoom);
                // console.log(label);
                // @this.call('setLocation', lat, lng, zoom, label)
            }
        });
    </script>
   <script src="{{ asset('assets/plugins/custom/fslightbox-basic-3.5.1/fslightbox.js') }}"></script>
@endPush