<div class="container checkout__wrapper">
    <!-- Configuration -->
    <div class="configure">
        <h2 class="panel-configure__title">Review your orders</h2>
        <div class="form-group">
            <h5 class="fw-bold">Contact Information:</h5>
        </div>
        <div class="pe-xl-10 pe-md-10 mb-5">
            <div class="form-group mt-5">
                <label for="full_name" class="form-label">Nama Lengkap</label>
                <p class="form-control" id="full_name">{{ auth()->user()->name }}</p>
            </div>
            <div class="form-group mt-5">
                <label for="email" class="form-label">E-mail</label>
                <p class="form-control" id="email">{{ auth()->user()->email }}</p>
            </div>
            <div class="form-group mt-5">
                <label>No Whatsapp</label>
                <div class="input-group" wire:ignore>
                    <span class="input-group-text" id="basic-addon1">+62</span>
                    <input type="text" class="form-control phone @error('phone') is-invalid @enderror" name="phone" model-name="phone" min="1" placeholder="85X-XXXX-XXXX" aria-label="phone" aria-describedby="basic-addon1">
                </div>
                <div class="form-text" id="basic-addon4">Contoh +62 8XX-XXX-XXX</div>
            </div>

            <div class="form-group mt-5" id="container-payment-method">
                <label for="payment-method" class="form-label">Metode Pembayaran</label>
                <select id="payment-method" class="form-select" aria-label="Default Select Payment Methods" wire:model.live="payment_method">
                    <option value="">Pilih Metode Pembayaran</option>
                    @foreach ($payment_method_choices as $payment_method)
                        <option value="{{$payment_method['id']}}">{{$payment_method['name']}} - Admin Fee Rp. 
                            @if ($payment_method['type'] === \App\Models\MasterData\PaymentMethod::TYPE_PERCENTAGE)
                                {{ numberFormat(calculatedAdminFee($subtotal, $payment_method['amount'])) }}
                            @else
                                @currency($payment_method['amount'])
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            

        </div>
        <div class="subtotal-wrapper">
            <div id="subtotal" class="cart-subtotal"><div class="cart-subtotal__order-item mb-5"><div class="order-item__label">Subtotal</div>
            <div class="order-item__price ms-5">Rp. @currency($subtotal)</div></div><div class="cart-subtotal__order-item mb-0"><div class="order-item__label my-auto">Coupon code</div><div class="order-item__price ms-5"><input id="coupon-code" type="text" class="form-control form-control-lg form-control text-end" value=""></div></div><div class="cart-subtotal__order-item"></div></div>
            <div class="cart-subtotal__order-item"></div>
        </div>
        <div class="total-wrapper">
            <div id="total" class="cart-total pt-0"><div class="cart-total__order-item"><div class="order-item__label">Total</div>
            <div class="order-item__price ms-5">Rp. @currency($grand_total)</div></div><div class="cart-total__soft-indicator">
                    <span id="soft-indicator">*Soft File ini disediakan dengan ketentuan dan syarat yang harus diperhatikan.</span>
                </div></div>
        </div>
        <!-- <div class="form-group">
            <div class="form-check form-check-custom form-check-solid form-check-sm">
                <input class="form-check-input get-accessories" type="checkbox" id="acepted_use">
                <label class="form-check-label" for="acepted_use">Saya setuju bahwa hasil foto yang diambil di Kuy Studio dapat digunakan untuk promosi dan konten internal Kuy Studio selama tidak untuk tujuan komersial</label>
            </div>
        </div> -->
        <form id="form-configure" class="form-configure w-100 pe-10 mb-5 mt-10 btn-confirm-order-wrapper" autocomplete="off" wire:submit="store">
            <button type="submit" id="btn-confirm-order" class="btn btn-bg-success text-white w-100">
                <span class="indicator-label">Confirm Order</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </form>
        <h5 id="change-booking" class="text-end pe-xl-10 text-hover-primary cursor-pointer">
            <i class="fa-regular fa-arrow-left font-size-16px"></i> Ganti Jam Booking
        </h5>
    </div>
    <!-- Cart -->
    <div class="cart mb-4">
        <h2 class="panel-configure__title-mobile">Review your orders</h2>
        <div class="cart-summary">
            <div class="card-header">
                <h2>
                    Customer Agreement
                </h2>
            </div>
            <div id="freeFile" class="card-body p-2 rounded d-flex align-items-center justify-content-center" style="min-height: 120px; background-color:#4CC9FE;">
                <blockquote class="blockquote mb-0 text-center">
                    <h4 class="text-white">
                        Dengan ini anda menyatakan kesediaan untuk melaksanakan ketentuan untuk mendapatkan soft file secara
                        <strong>GRATIS</strong>
                    </h4>
                </blockquote>
            </div>
            <div id="fileBerbayar" class="d-none card-body p-2 rounded d-flex align-items-center justify-content-center" style="min-height: 120px; background-color:#ffa8a8;">
                <blockquote class="blockquote mb-0 text-center">
                    <h4 class="text-black">
                        Okay, kakak setuju untuk dapetin softfile nya berbayar <strong>Rp.25.000</strong> yaaa
                        <br>
                        <strong style="filter: brightness(1.2) contrast(1.2);">🥰🫶</strong>
                    </h4>
                </blockquote>
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
                @foreach ($booking_details as $item)
                    <div class="cart-summary__order-item">
                        <a class="d-inline overlay" data-fslightbox="{{ rand() }}" href="{{ $item['product_details']['image_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 99;">
                            <!--begin::Image-->
                            <img class="img-responsive img-detail rounded-3" width="80" height="80" src="{{ $item['product_details']['image_url'] }}">
                            <!--end::Image-->
                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-transparent">
                                    <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                        <div class="order-item__description">
                            <div class="order-item__description-name">{{ $product_studio_name }}<br>{{ $product_name }}</div>
                            <div class="order-item__description-count"> @date($booking_date) {{ \Carbon\Carbon::parse($item['time'])->format('H:i') }}</div>
                        </div>
                        <div class="order-item__price">Rp. @currency($product_price)
                            
                        </div>
                    </div>
                @endforeach

                {{-- Background Product --}}
                @foreach ($booking_details as $item)
                    <div class="cart-summary__order-item">
                        <a class="d-inline overlay" data-fslightbox="{{ rand() }}" href="{{ $item['product_details']['image_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 99;">
                            <!--begin::Image-->
                            <img class="img-responsive img-detail rounded-3" width="80" height="80" src="{{ $item['product_details']['image_url'] }}">
                            <!--end::Image-->
                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-transparent">
                                    <i class="bi bi-eye-fill text-white fs-5 eye-button"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                        <div class="order-item__description">
                            <div class="order-item__description-name">{{ $item['product_details']['name'] }}</div>
                            <div class="order-item__description-count"> @date($booking_date) {{ \Carbon\Carbon::parse($item['time'])->format('H:i') }}</div>
                        </div>
                        <div class="order-item__price">{{ $item['product_details']['price'] ? numberFormat($item['product_details']['price']) : 'Gratis' }}
                            
                        </div>
                    </div>
                @endforeach
             </div>
        </div>
    </div>
    <!-- Popup Modal -->
    <div class="modal-background"></div>
    <div class="modal-popup">
        <div class="modal-popup__icon-close"></div>
        <iframe id="iframe-invoice" class="iframe-invoice" title="Invoice"></iframe>
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