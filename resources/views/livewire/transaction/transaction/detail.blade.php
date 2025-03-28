<div class="container row d-flex justify-content-start">
    <!-- Cart -->
    <div class="mb-4 col-md-6">
        <h2 class="panel-configure__title-mobile">Review orders</h2>
        <div class="cart-summary">
            <div class="card-header">
                <div class="col-auto">
                    <!--begin::Alert-->
                    <div class="alert alert-{{$status_badge}} d-flex align-items-center p-3">

                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column">
                            <!--begin::Content-->
                            <h2 class="text-{{$status_badge}}">STATUS {{ $transaction['status'] }}</h2>
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
                        <a class="d-inline overlay" data-fslightbox="{{ rand() }}" href="{{ $item['product_detail_image_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 99;">
                            <!--begin::Image-->
                            <img class="img-responsive img-detail rounded-3" width="80" height="80" src="{{ $item['product_detail_image_url'] }}">
                            <!--end::Image-->
                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-transparent">
                                    <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                        <div class="order-item__description">
                            <div class="order-item__description-name">{{ $item['studio_name'] }}<br>{{ $item['product_name'] }}</div>
                            <div class="order-item__description-count"> @date($item['booking_date']) {{ \Carbon\Carbon::parse($item['product_booking_time_time'])->format('H:i') }}</div>
                        </div>
                        <div class="order-item__price">Rp. @currency($item['product_price'])
                            
                        </div>
                    </div>
                @endforeach

                {{-- Background Product --}}
                @foreach ($transaction_details as $item)
                    <div class="cart-summary__order-item">
                        <a class="d-inline overlay" data-fslightbox="{{ rand() }}" href="{{ $item['product_detail_image_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 99;">
                            <!--begin::Image-->
                            <img class="img-responsive img-detail rounded-3" width="80" height="80" src="{{ $item['product_detail_image_url'] }}">
                            <!--end::Image-->
                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-transparent">
                                    <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                        <div class="order-item__description">
                            <div class="order-item__description-name">{{ $item['product_detail_name'] }}</div>
                            <div class="order-item__description-count"> @date($item['booking_date']) {{ \Carbon\Carbon::parse($item['product_booking_time_time'])->format('H:i') }}</div>
                        </div>
                        <div class="order-item__price">{{ $item['product_detail_price'] ? numberFormat($item['product_detail_price']) : 'Gratis' }}
                            
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
               <div class="cart-subtotal__order-item mb-0">
                  <div class="order-item__label my-auto">Kode Voucher</div>
                  <div class="order-item__price ms-5">
                     <p class="form-control" id="voucher">{{ $transaction['voucher_id'] ? $transaction['voucher_code']." Nilai ". (\App\Models\MasterData\Voucher::TYPE_PERCENTAGE ? numberFormat(calculatedAdminFee($subtotal, $transaction['voucher_amount'])) : $transaction['voucher_amount'] ) : "TANPA VOUCHER" }}</p>
                  </div>
               </div>
               <div class="cart-subtotal__order-item"></div>
            </div>
            <div class="cart-subtotal__order-item"></div>
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
    <!-- Configuration -->
    <div class="col-md-6" style="border-left: 1px solid #ececec">
        <h2 class="panel-configure__title">Review orders</h2>
        <div class="form-group">
            <h5 class="fw-bold">Contact Information:</h5>
        </div>
        <div class="pe-xl-10 pe-md-10 mb-5">
            <div class="form-group mt-5">
                <label for="full_name" class="form-label">Nama Lengkap</label>
                <p class="form-control" id="full_name">{{ $transaction['customer_name'] }}</p>
            </div>
            <div class="form-group mt-5">
                <label for="email" class="form-label">E-mail</label>
                <p class="form-control" id="email">{{ $transaction['customer_email'] }}</p>
            </div>
            <div class="form-group mt-5">
                <label for="email" class="form-label">No Whatsapp</label>
                <p class="form-control" id="email">+62 {{ $transaction['customer_phone'] }}</p>
            </div>
            <div class="form-group mt-5">
                <label for="email" class="form-label">Metode Pembayaran</label>
                <p class="form-control" id="email">{{ $transaction['payment_method_name'] }} - Admin Fee @currency($admin_fee)</p>
            </div>
            

        </div>
            <!-- <div class="form-group">
                <div class="form-check form-check-custom form-check-solid form-check-sm">
                    <input class="form-check-input get-accessories" type="checkbox" id="acepted_use">
                    <label class="form-check-label" for="acepted_use">Saya setuju bahwa hasil foto yang diambil di Kuy Studio dapat digunakan untuk promosi dan konten internal Kuy Studio selama tidak untuk tujuan komersial</label>
                </div>
            </div> -->
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
@endpush

@include('js.imask')

@push('js')
   <script src="{{ asset('assets/plugins/custom/fslightbox-basic-3.5.1/fslightbox.js') }}"></script>
@endPush