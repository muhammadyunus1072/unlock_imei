<div class="row">
    @if ($objId)
        <div class="card">
            <!-- begin::Body-->
            <div class="card-body">
                <!-- begin::Wrapper-->
                <div class="mw-lg-950px mx-auto w-100">
                    <!-- begin::Header-->
                    <div class="d-flex justify-content-between mb-12 row">
                        
                        <!--end::Logo-->
                        <div class="col-6 d-flex justify-content-start row">
                            <!--begin::Logo-->
                            <a href="#" class="mw-150px col-auto">
                                <img alt="Logo" src="{{ asset(config('template.logo_auth'))}}" class="w-100">
                            </a>
                            <!--end::Logo-->
                        </div>
                        <div class="col-6">
                            <h4 class="fw-bolder text-gray-800 fs-2x pe-5 text-end">Invoice</h4>
                            <h4 class="fw-normal text-gray-800 fs-2 pe-5 pb-7 text-end">Unlock Imei</h4>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="pb-7">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column gap-7 gap-md-10">

                            <!--begin:Order summary-->
                            <div class="d-flex justify-content-evenly bg-light row px-3 pt-3 pb-0">
                                <!--begin::Table-->
                                <div class="col-6">
                                    <h3 class="fs-2x fw-bold mb-3">Kepada</h3>
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
                                                @if ($amount_due)
                                                    <tr class="fs-6">
                                                        <td class="my-0 py-0 fw-bolder text-end">Garansi</td>
                                                        <td class="my-0 py-0 text-end">{{ Carbon\Carbon::now()->addDays($model->product->warranty_days)->translatedFormat('d M Y') }}</td>
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
                                                <th class="min-w-175px pb-2"><p class="ms-5 py-0 my-0">Barang</p></th>
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
                                                            <div class="fw-bold">{{ $transaction->transactionDetails[0]->product_name }}</div>
                                                        </div>
                                                        <!--end::Title-->
                                                    </div>
                                                </td>
                                                <td class="text-end">@currency($transaction->transactionDetails->count())</td>
                                                <td class="text-end">Rp @currency($transaction->transactionDetails[0]->product_price)</td>
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
                                @if ($amount_due)
                                    <!--begin::Table-->
                                    <div class="col-auto bg-light p-3">
                                        <h3 class="fs-4 fw-normal mb-3">Jumlah yang harus dibayar</h3>
                                        <h3 class="fs-2x fw-bold text-end">Rp @currency($amount_due)</h3>
                                    <!--end::Table-->
                                @else
                                    <!--begin::Table-->
                                    <div class="col-auto bg-success rounded p-3">
                                        <h3 class="fs-2x fw-bold text-center text-white">Lunas</h3>
                                    <!--end::Table-->
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
                    <!-- begin::Footer-->
                    <div class="d-flex flex-stack flex-wrap mt-lg-10" id="actionDiv">
                        <!-- begin::Actions-->
                        <div class="my-1 me-5">
                            <!-- begin::Pint-->
                            <button type="button" class="btn btn-primary my-1 me-12" onclick="printHandler()"> 
                                <i class="ki-duotone ki-printer fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                 Print Invoice
                            </button>
                            <!-- end::Pint--> 
                        </div>
                        <!-- end::Actions-->
                        @if ($amount_due)
                        <!-- begin::Actions-->
                        <div class="my-1 me-5">
                            <!-- begin::Pint-->
                            <a href="{{ route('public.order_payment', [ 'id' => $objId ]) }}" target="_BLANK" class="btn btn-success my-1 me-12"> 
                                <i class="ki-duotone ki-dollar fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                 Bayar Sekarang
                            </a>
                            <!-- end::Pint--> 
                        </div>
                        <!-- end::Actions-->
                        @endif
                    </div>
                    <!-- end::Footer-->
                </div>
                <!-- end::Wrapper-->
            </div>
            <!-- end::Body-->
        </div>
    @endif
</div>

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