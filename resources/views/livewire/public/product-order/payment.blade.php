<div class="row">
   @if ($objId)
   <div class="card">
      <!-- begin::Body-->
      <div class="card-body">
         <!-- begin::Wrapper-->
         <div class="mw-lg-950px mx-auto w-100">
            <!--begin::Body-->
            <div class="pb-7">
               <!--begin::Wrapper-->
               <div class="d-flex flex-column gap-7 gap-md-10">
                  <!--begin:Order summary-->
                  <div class="d-flex justify-content-evenly bg-light row px-3 pt-3 pb-0">
                     <!--begin::Table-->
                     <div class="col-6">
                        <h3 class="fs-3 fw-normal my-0 py-0">{{$transaction->customer_name}}</h3>
                        <h3 class="fs-3 fw-normal my-0 py-0">{{$transaction->customer_email}}</h3>
                        <h3 class="fs-3 fw-normal my-0 py-0">+62 {{$transaction->customer_phone}}</h3>
                        @php
                        $social_media = json_decode($transaction->customer_social_media);
                        $ig = $social_media->instagram;
                        $fb = $social_media->facebook;
                        @endphp
                        <p class="text-danger w-100 my-0 py-0" id="instagram"><i class="fab text-danger fa-instagram fs-4"></i> {{ $ig }}</p>
                        <p class="text-primary w-100 my-0 py-0" id="facebook"><i class="fab text-primary fa-facebook fs-4"></i> {{ $fb }}</p>
                     </div>
                     <!--end::Table-->
                     <div class="col-6">
                        <!--begin::Table-->
                        <div class="table-responsive mb-9">
                           <table class="table align-middle gy-5 mb-0 py-0 my-0">
                              <tbody>
                                 <tr class="fs-6">
                                    <td class="my-0 py-0 fw-bolder text-end">Invoice</td>
                                    <td class="my-0 py-0 text-end">{{ $transaction->number }}</td>
                                 </tr>
                                 <tr class="fs-6">
                                    <td class="my-0 py-0 fw-bolder text-end">Tanggal Transaksi</td>
                                    <td class="my-0 py-0 text-end">@date($transaction->created_at)</td>
                                 </tr>
                                 <tr class="fs-6">
                                    <td class="my-0 py-0 fw-bolder text-end">Status</td>
                                    <td class="my-0 py-0 text-end"><span class="fs-5 badge badge-{{$transaction->lastStatus->getStatusStyle()}}"> {{$transaction->lastStatus->name}} </span></td>
                                 </tr>
                                 @if ($transaction->transactionStatuses->contains('name', \App\Models\Transaction\TransactionStatus::STATUS_COMPLETED))
                                 <tr class="fs-6">
                                    <td class="my-0 py-0 fw-bolder text-end">Garansi</td>
                                    <td class="my-0 py-0 text-end">{{ Carbon\Carbon::now()->addDays($transaction->transactionDetails[0]->product->warranty_days)->translatedFormat('d F Y') }}</td>
                                 </tr>
                                 @endif
                              </tbody>
                           </table>
                        </div>
                        <!--end::Table-->
                     </div>
                  </div>
                  <!--end:Order summary-->
                  <!--begin:Order summary-->
                  <div class="row flex-column mb-5">
                     <!--begin::Table-->
                     <div class="table-responsive mb-3">
                        <table class="table table-borderless align-middle fs-6 mb-0">
                           <thead>
                              <tr class="border-bottom fs-6 fw-bold text-muted">
                                 <th class="min-w-175px pb-2">
                                    <p class="ms-5 py-0 my-0">Barang</p>
                                 </th>
                                 <th class="min-w-100px text-end pb-2">Kuantitas</th>
                                 <th class="min-w-100px text-end pb-2">Harga</th>
                                 <th class="min-w-100px text-end pb-2">Total</th>
                              </tr>
                           </thead>
                           <tbody class="fw-semibold text-gray-600">
                              <tr>
                                 <td>
                                    <div class="d-flex align-items-center">
                                       <!--begin::Title-->
                                       <div class="ms-5">
                                          <div class="fw-bold">{{ $transaction->transactionDetails[0]->product_name }} | Garansi @currency($transaction->transactionDetails[0]->product_warranty_days) Hari</div>
                                       </div>
                                       <!--end::Title-->
                                    </div>
                                 </td>
                                 <td class="text-end">@currency($transaction->transactionDetails->count())</td>
                                 <td class="text-end">@currency($transaction->transactionDetails[0]->product_price)</td>
                                 <td class="text-end">Rp @currency($transaction->transactionDetails->sum('product_price'))</td>
                              </tr>
                              <tr>
                                 <td colspan="3" class="py-0 my-0 text-end">Subtotal</td>
                                 <td class="py-0 my-0 text-end"> @currency($subtotal)</td>
                              </tr>
                              <tr>
                                 <td colspan="3" class="py-0 my-0 text-end">Diskon</td>
                                 <td class="py-0 my-0 text-end">- @currency($discount)</td>
                              </tr>
                              <tr>
                                 <td colspan="2" class="py-0 my-0"></td>
                                 <td class="fs-3 py-0 my-0 text-dark fw-bold text-end">Total</td>
                                 <td class="text-dark fs-3 fw-bolder text-end">Rp @currency($grand_total)</td>
                              </tr>
                              <tr>
                                 <td colspan="3" class="py-0 my-0 text-end">Terbayar</td>
                                 <td class="py-0 my-0 text-end">@currency($grand_total - $amount_due)</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <!--end::Table-->
                     <div class="row d-flex justify-content-end">
                        @if (!$transaction->transactionStatuses->contains('name', \App\Models\Transaction\TransactionStatus::STATUS_PAID))
                        <!--begin::Table-->
                        <div class="col-auto bg-light p-3">
                           <h3 class="fs-4 fw-normal mb-3">Jumlah yang harus dibayar</h3>
                           <h3 class="fs-2x fw-bold text-end">Rp @currency($amount_due)</h3>
                           <!--end::Table-->
                           @else
                           <!--begin::Table-->
                           <div class="col-4 bg-success rounded p-3">
                              <h3 class="fs-2x fw-bold text-center text-white">Lunas</h3>
                              <!--end::Table-->
                              @endif
                           </div>
                        </div>
                        <!--begin::Table-->
                        <h3>Riwayat Pembayaran</h3>
                        <div class="table-responsive mb-3">
                           <table class="table table-borderless align-middle fs-6 mb-0">
                              <thead>
                                 <tr class="border-bottom fs-6 fw-bold text-muted">
                                    <th class="min-w-175px pb-2">
                                       <p class="ms-5 py-0 my-0">Tanggal</p>
                                    </th>
                                    <th class="min-w-100px text-end pb-2">Metode</th>
                                    <th class="min-w-100px text-end pb-2">Nilai</th>
                                    <th class="min-w-100px text-end pb-2">Status</th>
                                 </tr>
                              </thead>
                              <tbody class="fw-semibold text-gray-600">
                                 @foreach ($transaction->transactionPayments as $payment)
                                 <tr>
                                    <td>
                                       <div class="d-flex align-items-center">
                                          <!--begin::Title-->
                                          <div class="ms-5">
                                             <div class="fw-bold">@dateFull($payment->created_at)</div>
                                          </div>
                                          <!--end::Title-->
                                       </div>
                                    </td>
                                    <td class="text-end">{{$payment->payment_method_name}}</td>
                                    <td class="text-end">Rp @currency($payment->amount)</td>
                                    <td class="text-end">
                                       <p class="badge bg-{{$payment->getStatusStyle()}} text-white">{{$payment->status}}</p>
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                        @if (!$transaction->transactionStatuses->contains('name', \App\Models\Transaction\TransactionStatus::STATUS_PAID))
                        <!--end::Table-->
                        <div class="row d-flex justify-content-center flex-column align-items-center">
                           <div class="col-md-6 mb-2">
                              <div class="form-group mt-5" id="container-payment-method">
                                 <label for="payment-method" class="form-label">Metode Pembayaran</label>
                                 <select id="payment-method" class="form-select" aria-label="Default Select Payment Methods" wire:model.live="payment_method">
                                    <option value="">Pilih Metode Pembayaran</option>
                                    @foreach ($payment_method_choices as $payment_method)
                                    <option value="{{$payment_method['id']}}">{{$payment_method['name']}} - Admin Fee Rp. 
                                       @if ($payment_method['fee_type'] === \App\Models\MasterData\PaymentMethod::TYPE_PERCENTAGE)
                                       {{ numberFormat(calculateAdminFee($subtotal, $payment_method['fee_amount'])) }}
                                       @else
                                       @currency($payment_method['fee_amount'])
                                       @endif
                                    </option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-6">
                              @if (isset($selected_payment_method['is_xendit']) && !$selected_payment_method['is_xendit'])
                              <div class="my-4">
                                 {!! $selected_payment_method['code'] !!}
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
                                class="form-group mt-0"
                              >
                                <label
                                    for="upload_image"
                                    class="upload_dropZone text-center mb-3 p-4 w-100 border border-dashed rounded"
                                    :class="isDragging ? 'bg-light border-primary border-3' : 'border-secondary'"
                                    >
                                    <legend class="visually-hidden">Image uploader</legend>
                                    <svg class="upload_svg" width="60" height="60" aria-hidden="true">
                                        <use href="#icon-imageUpload"></use>
                                    </svg>
                                    <p class="small my-2">
                                        Drag & Drop screenshot Bukti Bayar di dalam wilayah putus-putus<br><i>atau</i>
                                    </p>
                                    <input
                                        x-ref="input"
                                        id="upload_image"
                                        type="file"
                                        wire:model="image"
                                        @change="handleFiles"
                                        accept="image/jpeg, image/png, image/svg+xml"
                                        class="position-absolute invisible"
                                        />
                                <label class="btn btn-upload mb-3" for="upload_image">Choose file(s)</label>
                                <!-- Optional preview -->
                                <div class="upload_gallery d-flex flex-wrap justify-content-center gap-3 mb-0">
                                    @if ($image)
                                    <div class="text-center">
                                        <img src="{{ $image->temporaryUrl() }}" alt="preview" class="img-thumbnail">
                                    </div>
                                    @endif
                                </div>
                                </label>
                                @error('image') 
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
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
                           <div class="col-auto mb-4">
                              <label>Jumlah Dibayar</label>
                              <input placeholder="Jumlah Dibayar" type="text" class="form-control currency @error('amount') is-invalid @enderror" wire:model="amount" />
                              @error('amount')
                              <div class="invalid-feedback">
                                 {{ $message }}
                              </div>
                              @enderror
                           </div>
                           @endif
                        </div>
                        <div class="col-md-6">
                           <form id="" class="w-100 " autocomplete="off" wire:submit="store">
                              <button type="submit" class="btn btn-success my-1 me-12 w-100"> 
                              <i class="ki-duotone ki-dollar fs-2x">
                              <span class="path1"></span>
                              <span class="path2"></span>
                              <span class="path3"></span>
                              <span class="path4"></span>
                              <span class="path5"></span>
                              </i>
                              Bayar
                              </button>
                           </form>
                        </div>
                     </div>
                     @endif
                  </div>
                  <div class="row d-flex justify-content-center">
                     <hr>
                     <h1 class="fs-4x fw-bolder text-center" style="color: rgb(117, 219, 200)">TERIMA KASIH!</h1>
                     <p class="text-center fs-4">Hubungi Kami : Wa/Tlp +62 85807139331 | Pasuruan, Jawa Timur</p>
                  </div>
                  <!--end:Order summary-->
               </div>
               <!--end::Wrapper-->
            </div>
            <!--end::Body-->
         </div>
         <!-- end::Wrapper-->
      </div>
      <!-- end::Body-->
   </div>
   @endif
</div>

@push('css')
     <style>
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
    <script>
        function printHandler(){
            console.log('print')
            $("#navbar").addClass('d-none');
            $("#searchDiv").addClass('d-none');
            $("#footer").addClass('d-none');
            $("#kt_scrolltop").addClass('d-none');
            $(".container").addClass('container-fluid');
            $(".container").removeClass('container');
            $("#actionDiv").addClass('d-none');
            
            const print = setTimeout(() => {
                window.print();
            }, 500);
            const afterPrint = setTimeout(() => {
                $("#navbar").removeClass('d-none');
                $("#footer").removeClass('d-none');
                $("#searchDiv").removeClass('d-none');
                $("#kt_scrolltop").removeClass('d-none');
                $(".container-fluid").addClass('container');
                $(".container-fluid").removeClass('container-fluid');
                $("#actionDiv").removeClass('d-none');
            }, 500);
        }
    </script>
@endpush