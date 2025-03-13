<div class="card-body pb-lg-0">
    <a href="#" class="text-dark text-hover-primary fs-2 fw-bold">{{ $product_name }}</a>
    <a target="_blank" href="{{ $product_studio_location}}">
       <p class="card-meta text-muted mb-5 mb-xl-0 mb-md-0"><i class="fa-solid fa-location-dot text-muted"></i>{{ $product_studio_name }}</p>
    </a>
    <div class="d-flex flex-column flex-xl-row">
       <div class="flex-lg-row-fluid me-xl-15">
          <div class="mb-xl-17">
             <div class="mb-8">
                <div class="overlay mt-5 d-flex justify-content-center align-items-center">
                   <!-- <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-350px" style="background-image:url('https://app.kuystudio.id/assets/uploads/product/2025-01/0fdf190248f2c384b5b6e7ac3f3885f32d6ce439.JPG')"></div> -->
                   <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded" style="background-image:url('{{ $product_image_url }}'); width: 300px; height: 300px;"></div>
                </div>
             </div>
             {!! $product_description !!}
          </div>
       </div>
       <div class="flex-column flex-lg-row-auto w-100 w-xl-350px">
          <div class="mb-10 mt-0 mt-xl-4 d-flex justify-content-center" wire:ignore>
             <input class="d-none" wire:model.live="booking_date" id="booking_date" placeholder="Select a date" type="text" readonly="readonly">
          </div>
          <!-- <div>
             <select class="form-select form-select-lg mb-3" name="product_type" aria-label=".form-select-lg example">
                 <option value="basic" selected>Basic</option>
                 <option value="pas_foto">Pasfoto</option>
                 <option value="visa">Visa</option>
                 <option value="pas_foto_nikah">Pasfoto Nikah</option>
             </select>
             </div> -->
          <div class="d-flex justify-content-center">
             <div class="loader loader-times position-absolute mt-5pr d-none" style="z-index: 9999 !important"></div>
             <div class="box-time">
                <div class="nav nav-pills nav-pills-custom mb-3" role="tablist">
                  @foreach ($product_booking_times as $index => $item)
                     @if (!$item['booking_detail_id'] && $item['is_checked'])
                        {{-- Chosen --}}
                        <div wire:click="handleBookingTime('{{ Crypt::encrypt($index) }}')" class="card mb-2 shadow-none border-dashed-f4 rounded-3 mh-0px mx-1 bg-blocked-30" style="width: 78px;"> <label class="select-time p-2 select-booking-time-30 times-checkbox-checked" data-time="19:00:00" data-key="30"> <label class="mx-3 align-middle cursor-pointer"> <span class="font-size-12px fw-bold cursor-pointer"></span>{{ \Carbon\Carbon::parse($item['time'])->format('H:i') }} </label> <input class="booking-times" type="checkbox" name="times[]" value="19:00:00"></label> </div>
                     @elseif(!$item['booking_detail_id'] && !$item['is_checked'])
                        {{-- Availabel --}}
                        <div wire:click="handleBookingTime('{{ Crypt::encrypt($index) }}')" class="card mb-2 shadow-none border-dashed-f4 rounded-3 mh-0px mx-1 bg-blocked-25" style="width: 78px;"> <label class="select-time p-2 select-booking-time-25" data-time="17:20:00" data-key="25"> <label class="mx-3 align-middle cursor-pointer"> <span class="font-size-12px fw-bold cursor-pointer"></span>{{ \Carbon\Carbon::parse($item['time'])->format('H:i') }} </label> <input class="booking-times" type="checkbox" name="times[]" value="17:20:00"></label> </div>
                     @else
                        {{-- Booked --}}
                        <div class="card mb-2 shadow-none border-dashed-f4 rounded-3 mh-0px mx-1 bg-blocked-0" style="background: #e3e3e3; width: 78px;" disabled="true"> <label class="blocking-time p-2 select-booking-time-0" data-time="09:00:00" data-key="0"> <label class="mx-3 align-middle cursor-default"> <span class="font-size-12px fw-bold cursor-default"></span>{{ \Carbon\Carbon::parse($item['time'])->format('H:i') }} </label> <input class="booking-times" type="checkbox" name="times[]" value="09:00:00"></label> </div>
                     @endif
                  @endforeach
                </div>
             </div>
          </div>
          
          <div id="product-additional" class="mt-10">
            @foreach ($product_booking_details as $bookingIndex => $product_booking)
            <div id="booking-key-30" class="container mb-5 booking-key">
               <lable>
                  <h4>Background for {{ \Carbon\Carbon::parse($product_booking['time'])->format('H:i') }} :</h4>
               </lable>
               <div class="box-accessories">
                  @foreach ($product_booking['product_details'] as $detailIndex => $product_detail)
                        @if ($product_detail['is_checked'])
                           {{-- Chosen --}}
                           <div wire:click="handleProductDetail('{{$bookingIndex}}', '{{$detailIndex}}')" class="card cursor-pointer mb-2 shadow-none border rounded-3 mh-0px">
                              <label class="addon-checkbox p-2 class-key-30 addon-checkbox-checked" data-key="key-30" addon-id="3a6e5bbf-61ef-43b0-b389-771651e741c3" addon-value-price="0">
                              <a class="preview-image vbox-item" href="{{ $product_detail['image_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 9999;">
                              <img class="img-responsive rounded-3" width="80" height="80" src="{{ $product_detail['image_url'] }}">
                              </a>
                              <label for="addon-0-30" class="ms-3 align-middle" style="width: 180px">
                              <span class="fs-5 fw-bold">{{ $product_detail['name'] }}</span><br>
                              <small>{{ $product_detail['description'] }}</small>
                              </label>
                              <i class="fa fa-check d-none m-1"></i>
                              </label>
                           </div>
                        @else
                           {{-- Available --}}
                           <div wire:click="handleProductDetail('{{$bookingIndex}}', '{{$detailIndex}}')" class="card cursor-pointer mb-2 shadow-none border rounded-3 mh-0px">
                              <label class="addon-checkbox p-2 class-key-30 " data-key="key-30" addon-id="3a6e5bbf-61ef-43b0-b389-771651e741c3" addon-value-price="0">
                              <a class="preview-image" href="{{ $product_detail['image_url'] }}" style="height: 80px !important; width: 80px !important; z-index: 9999;">
                              <img class="img-responsive rounded-3" width="80" height="80" src="{{ $product_detail['image_url'] }}">
                              </a>
                              <label for="addon-0-30" class="ms-3 align-middle" style="width: 180px">
                              <span class="fs-5 fw-bold">{{ $product_detail['name'] }}</span><br>
                              <small>{{ $product_detail['description'] }}</small>
                              </label>
                              </label>
                           </div>
                            
                        @endif

                     @endforeach
               </div>
            </div>
            @endforeach
         </div>
          <!-- <div class="container mb-5" id="term-confirmation" hidden>
             <div class="form-check form-check-custom form-check-solid form-check-sm my-3">
                 <input class="form-check-input terms" type="checkbox" id="terms">
                 <label class="form-check-label" for="terms">term and conditions</label>
             </div>
             </div> -->
          <div class="container">
             {!! $product_note !!}
          </div>
          <div class="container mt-5">
             <button type="button" id="btn-booking" class="btn btn-bg-dark text-white w-100">
             <span class="indicator-label">Booking</span>
             <span class="indicator-progress">Please wait...
             <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
             </span>
             </button>
          </div>
       </div>
    </div>
 </div>

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ asset('assets/css/custom-homepage.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            $("#booking_date").flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: "today",
                inline: true,
                defaultDate: "today"
            });
        });
    </script>
@endpush