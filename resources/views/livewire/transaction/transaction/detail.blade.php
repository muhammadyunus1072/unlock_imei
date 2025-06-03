<div class="container">
    <div class="row d-flex justify-content-start">
        <div class="col-12">
            <div class="col-md-6">
                <h2 class="panel-configure__title">Review Transaksi</h2>
            </div>
        </div>
        <div class="col-md-7 mb-4">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Informasi Kontak
                    </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
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
    
                    </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Ringkasan Pesanan
                    </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div id="order-items" class="order-items cart-overflow">
                                {{-- Main Product --}}
                                @foreach ($transaction_details as $item)
                                    <div class="cart-summary__order-item d-flex justify-content-start gap-5">
                                        <a class="d-inline overlay col-auto" data-fslightbox="{{ rand() }}" href="{{ $item['imei_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 99;">
                                            <!--begin::Image-->
                                            <img class="img-responsive img-detail rounded-3" width="80" height="80" src="{{ $item['imei_url'] }}">
                                            <!--end::Image-->
                                            <!--begin::Action-->
                                            <div class="overlay-layer card-rounded bg-transparent">
                                                    <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                                            </div>
                                            <!--end::Action-->
                                        </a>
                                        <div class="order-item__description col-auto mx-0 px-0">
                                            <p>{{ $item['product_name'] }}</p>
                                            <div class="order-item__description-count"> </div>
                                        </div>
                                        <div class="col-auto mx-0 px-0">Rp. @currency($item['product_price'])
                                            
                                        </div>
                                        @if ($verified_at)
                                            @if ($item['active_at'])
                                                <div class="">
                                                    <p class="text-center fs-sm fw-normal badge bg-primary text-white">
                                                        <i class="bi bi-check me-1 text-white"></i> @dateFull($item['active_at'])
                                                    </p>
                                                    <p class="text-center fs-sm fw-normal badge bg-warning">
                                                        <i class="bi bi-lock-fill me-1 text-dark"></i> @dateFull($item['active_at'])
                                                    </p>
                                                </div>
                                            @else
                                                <div class="">
                                                    <button type="button" class="btn btn-sm btn-primary mb-3" wire:click="showActiveDialog('{{$item['id']}}')">Aktifkan</button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        
                            <div class="row">
                                <!--begin::Table-->
                                <div class="table-responsive border-bottom mb-9">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                        <tbody class="fw-semibold text-gray-600">
                                            <tr>
                                                <td colspan="3" class="py-0 my-0 text-end">Subtotal</td>
                                                <td class="py-0 my-0 text-end"> @currency($subtotal)</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="py-0 my-0 text-end">Diskon</td>
                                                <td class="py-0 my-0 text-end">- @currency($discount)</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="fs-3 py-0 my-0 text-dark fw-bold text-end">Total</td>
                                                <td class="text-dark fs-3 fw-bolder text-end">Rp @currency($grand_total)</td>
                                            </tr>

                                            <tr>
                                                <td colspan="3" class="py-0 my-0 text-end">Terbayar</td>
                                                <td class="py-0 my-0 text-end">- @currency($grand_total - $amount_due)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                            <div class="row">

                                <!--end::Table-->
                                <div class="row d-flex justify-content-end">
                                    <!--begin::Table-->
                                    <div class="col-auto bg-light p-3">
                                        <h3 class="fs-4 fw-normal mb-3">Jumlah yang harus dibayar</h3>
                                        <h3 class="fs-2x fw-bold text-end">Rp @currency($amount_due)</h3>
                                    <!--end::Table-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <h3>Riwayat Status</h3>
            <div id="master-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6"></div>
                    <div class="col-sm-12 col-md-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table id="master-table" class="table table-bordered dataTable no-footer" role="grid" style="width: 552px;">
                        <thead>
                            <tr role="row">
                                <th class="sorting_disabled" tabindex="0"rowspan="1" colspan="1" style="width: 109.2px;">Tanggal</th>
                                <th class="sorting_disabled" tabindex="0"rowspan="1" colspan="1" style="width: 101.2px;">Status</th>
                                <th class="sorting_disabled" tabindex="0"rowspan="1" colspan="1" style="width: 39.2px;">Oleh</th>
                                <th class="sorting_disabled" tabindex="0"rowspan="1" colspan="1" style="width: 102.2px;">Keterangan</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 45.4px;" aria-label="Aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->transactionStatuses as $item)
                                <tr class="odd">
                                    <td class="sorting_1">{{ Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y, H:i')}}</td>
                                    <td>
                                        <p class="badge bg-primary text-white">{{$item->name}}</p>
                                    </td>
                                    <td>-</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        <div class="btn-group">
                                            @if ($item->isDeletable())
                                                <button type="button" class="btn btn_delete_status" wire:click="deleteStatus('{{$item->id}}')"><i class="fas fa-trash mr-2 text-danger"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                        <div id="master-table_processing" class="dataTables_processing card" style="display: none;">Processing...</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5"></div>
                    <div class="col-sm-12 col-md-7"></div>
                </div>
            </div>
            
            @if (!$canUpdateStatus)
                <form id="form-status" action="http://localhost:8000/purchase_request_status/store" method="post">
                    <div class="mb-3">
                        <label for="description" class="form-label">Catatan</label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <button type="button" class="d-block w-100 mt-2 btn btn-primary" wire:click="showVerifyDialog">
                        Verifikasi
                    </button>
                    <button type="button" class="d-block w-100 mt-2 btn btn-danger" wire:click="showCancelDialog">
                        Batalkan
                    </button>
                </form>
            @endif

            <div class="row mt-5">
                <h3>Riwayat Pembayaran</h3>
				<table id="master-table" width="100%" class="table table-bordered" style="border-collapse: collapse; border-spacing: 0;">
					<thead>
						<tr>
							<th class="text-center" style="width: 20px;">Bukti Bayar</th>
							<th class="text-center" style="width: 120px;">Nilai</th>
							<th class="text-center" style="width: 80px;">Metode</th>
							<th class="text-center" style="width: 50px;">Aksi</th>
						</tr>
					</thead>
					<tbody>
                        @foreach ($transaction_payments as $index => $item)
                            <tr>
                                <td>
                                    <a class="d-inline overlay col-auto" data-fslightbox="{{ rand() }}" href="{{ $item['image_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 99;">
                                        <!--begin::Image-->
                                        <img class="img-responsive img-detail rounded-3" width="80" height="80" src="{{ $item['image_url'] }}">
                                        <!--end::Image-->
                                        <!--begin::Action-->
                                        <div class="overlay-layer card-rounded bg-transparent">
                                                <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                                        </div>
                                        <!--end::Action-->
                                    </a>
                                </td>
                                <td>
                                    <input type="text" class="form-control currency" wire:model="transaction_payments.{{$index}}.amount" />
                                </td>
                                <td>
                                    <p class="" >{{$transaction_payments[$index]['payment_method_name']}}</p>
                                </td>
                                <td>
                                    @if ($item['status'] == App\Models\Transaction\TransactionPayment::STATUS_PENDING)
                                        
                                        <button wire:click="showVerifyPaymentDialog('{{$index}}')" type="button" class="btn btn-primary btn-sm my-1"> 
                                            <i class="ki-duotone ki-check">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                        <button wire:click="showDeletePaymentDialog('{{$index}}')" type="button" class="btn btn-danger btn-sm my-1"> 
                                            <i class="ki-duotone ki-time">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                        </button>
                                        
                                    @else
                                        <span class="badge bg-primary text-white">{{$item['status']}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
					</tbody>
				</table>
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