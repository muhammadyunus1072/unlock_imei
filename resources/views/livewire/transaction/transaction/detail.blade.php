<div class="container row d-flex justify-content-start">
    
    <!-- Configuration -->
    <div class="col-md-6" style="border-right: 1px solid #ececec">
        <h2 class="panel-configure__title">Review orders</h2>
        <div class="form-group">
            <h5 class="fw-bold">Contact Information:</h5>
        </div>
        <div class="pe-xl-10 pe-md-10">
            <div class="form-group">
                <label for="full_name" class="form-label">Nama Lengkap</label>
                <p class="form-control" id="full_name">{{ $transaction['customer_name'] }}</p>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">E-mail</label>
                <p class="form-control" id="email">{{ $transaction['customer_email'] }}</p>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">No Whatsapp</label>
                <div class="row d-flex justify-content-between">
                    <div class="col-md-6">
                        <p class="form-control" id="email">+62 {{ $transaction['customer_phone'] }}</p>
                    </div>
                    <div class="col-md-6">
                        <a target="_blank" href="https://wa.me/62{{ $transaction['customer_phone'] }}" class="btn btn-success w-100" id="phone"><i class="fab fa-whatsapp fs-4"></i> +62 {{ $transaction['customer_phone'] }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Instagram</label>
                <div class="row d-flex justify-content-between">
                    <div class="col-md-6">
                        <p class="form-control" id="email">{{ $customer_ig }}</p>
                    </div>
                    <div class="col-md-6">
                        <a target="_blank" href="https://www.instagram.com/{{ $customer_ig }}" class="btn btn-instagram w-100" id="instagram"><i class="fab fa-instagram fs-4"></i>{{ $customer_ig }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Facebook</label>
                <div class="row d-flex justify-content-between">
                    <div class="col-md-6">
                        <p class="form-control" id="email">{{ $customer_fb }}</p>
                    </div>
                    <div class="col-md-6">
                        <a target="_blank" href="https://www.facebook.com/{{ $customer_fb }}" class="btn btn-facebook w-100" id="facebook"><i class="fab fa-facebook fs-4"></i>{{ $customer_fb }}</a>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="email" class="form-label">KTP / SIM</label>
                <a class="d-inline overlay" data-fslightbox="{{ rand() }}" href="{{ $customer_ktp_url }}" style="z-index: 99;">
                    <!--begin::Image-->
                    <img class="img-responsive img-detail rounded-3 w-100" src="{{ $customer_ktp_url }}">
                    <!--end::Image-->
                    <!--begin::Action-->
                    <div class="overlay-layer card-rounded bg-transparent">
                            <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                    </div>
                    <!--end::Action-->
                </a>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 mb-4" wire:ignore>
                    <label for="" class="form-label">Lokasi Map</label>
                    <div id="map" style="height: 400px;"></div>
                </div>
                <button
                class="btn btn-primary"
                {{-- onclick="window.open(`https://www.google.com/maps?q={{ $customer_lat }},{{ $customer_lng }}`, '_blank')"> --}}
                onclick="window.open(`https://www.google.com/maps?q=-7.7012339,112.7904209`, '_blank')">
                Buka Di Google Maps
            </button>

            </div>
            

        </div>
            <!-- <div class="form-group">
                <div class="form-check form-check-custom form-check-solid form-check-sm">
                    <input class="form-check-input get-accessories" type="checkbox" id="acepted_use">
                    <label class="form-check-label" for="acepted_use">Saya setuju bahwa hasil foto yang diambil di Kuy Studio dapat digunakan untuk promosi dan konten internal Kuy Studio selama tidak untuk tujuan komersial</label>
                </div>
            </div> -->
    </div>
    <!-- Cart -->
    <div class="mb-4 col-md-6">
        <h2 class="panel-configure__title-mobile">Review orders</h2>
        <div class="cart-summary">
            <div class="card-header">
                <div class="col-auto">
                    <!--begin::Alert-->
                    <div class="alert alert-{{$transaction_status_badge}} d-flex align-items-center p-3">

                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column">
                            <!--begin::Content-->
                            <h2 class="text-{{$transaction_status_badge}}">STATUS {{ $transaction['transaction_status'] }}</h2>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Alert-->
                </div>
            </div>
        </div>
        <hr class="ms-2">
        <input class="cart-summary__checkbox--mobile" type="checkbox" id="cart-summary--mobile">
        <label for="cart-summary--mobile" class="cart-summary--mobile">
            <div class="cart-summary__title-toggle">
                <span>Show Orders</span>
            </div>
            <div class="cart-summary__icon-toggle"></div>
            <div class="cart-summary__total"></div>
        </label>
        <div class="cart-summary">
            <h2 class="cart-summary__title">Order Summary</h2>
            <div id="order-items" class="order-items cart-overflow">
                {{-- Main Product --}}
                @foreach ($transaction_details as $item)
                    <div class="cart-summary__order-item">
                        <a class="d-inline overlay" data-fslightbox="{{ rand() }}" href="{{ $item['imei_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 99;">
                            <!--begin::Image-->
                            <img class="img-responsive img-detail rounded-3" width="80" height="80" src="{{ $item['imei_url'] }}">
                            <!--end::Image-->
                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-transparent">
                                    <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                        <div class="order-item__description">
                            <div class="order-item__description-name">{{ $item['product_name'] }}</div>
                            <div class="order-item__description-count"> </div>
                        </div>
                        <div class="order-item__price">Rp. @currency($item['product_price'])
                            
                        </div>
                    </div>
                @endforeach
             </div>
        </div>
        <div class="subtotal-wrapper">
            <div id="subtotal" class="cart-subtotal">
               <div class="cart-subtotal__order-item mb-5">
                  <div class="order-item__label">Subtotal</div>
                  <div class="order-item__price ms-5">Rp. @currency($subtotal)</div>
               </div>

        <div class="form-group">
            <label for="email" class="form-label">Metode Pembayaran</label>
            <p class="form-control" id="email">{{ $transaction['payment_method_name'] }} - Admin Fee @currency($admin_fee)</p>
        </div>
               <div class="cart-subtotal__order-item mb-0">
                  <div class="order-item__label my-auto">Kode Voucher</div>
                  <div class="order-item__price ms-5">
                     <p class="form-control" id="voucher">{{ $transaction['voucher_id'] ? $transaction['voucher_code']." Nilai ". (\App\Models\MasterData\Voucher::TYPE_PERCENTAGE ? numberFormat(calculatedAdminFee($subtotal, $transaction['voucher_amount'])) : $transaction['voucher_amount'] ) : "TANPA VOUCHER" }}</p>
                  </div>
               </div>
            </div>
        </div>
        <div class="total-wrapper">
        <div id="total" class="cart-total pt-0">
            <div class="cart-total__order-item">
                <div class="order-item__label">Total</div>
                <div class="order-item__price ms-5">
                    Rp. @currency($transaction['grand_total'])
                </div>
            </div>
        </div>
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
   <script src="{{ asset('assets/plugins/custom/fslightbox-basic-3.5.1/fslightbox.js') }}"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('vendor/geoSearch/geosearch.bundle.min.js') }}"></script>
    <script>
        $(() => {
            // MAP
            var lat;
            var lng;

            const initLat = @json($customer_lat ?? null);
            const initLng = @json($customer_lng ?? null);

            let marker = null;
            const map = L.map('map').setView([ initLat, initLng], 14);
            L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            initMap();

            function initMap(){

                updateMarker(initLat, initLng, 18);
                
            }
        

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
@endPush