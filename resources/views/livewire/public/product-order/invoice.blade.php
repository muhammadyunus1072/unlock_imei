
<div class="card">
    <!-- begin::Body-->
    <div class="card-body py-20">
        <!-- begin::Wrapper-->
        <div class="mw-lg-950px mx-auto w-100">
            <!-- begin::Header-->
            <div class="d-flex justify-content-between flex-column flex-sm-row mb-19">
                <h4 class="fw-bolder text-gray-800 fs-2qx pe-5 pb-7">INVOICE</h4>
                <!--end::Logo-->
                <div class="text-sm-end">
                    <!--begin::Logo-->
                    <a href="#" class="d-block mw-150px ms-sm-auto">
                        <img alt="Logo" src="{{ asset(config('template.logo_auth'))}}" class="w-100">
                    </a>
                    <!--end::Logo-->
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="pb-12">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column gap-7 gap-md-10">
                    <!--begin::Message-->
                    <div class="fw-bold fs-2">Dear {{$transaction->customer_name}}
                    <span class="fs-6">({{$transaction->customer_email}})</span>,
                    <br>
                    <span class="text-muted fs-5">Berikut adalah detail pesanan Anda. Terima kasih atas pembelian Anda.</span></div>
                    <!--begin::Message-->
                    <!--begin::Separator-->
                    <div class="separator"></div>
                    <!--begin::Separator-->
                    <!--begin::Order details-->
                    <div class="d-flex flex-column flex-sm-row gap-7 gap-md-10 fw-bold">
                        <div class="flex-root d-flex flex-column">
                            <span class="text-muted">Tanggal</span>
                            <span class="fs-5">@date($transaction->created_at)</span>
                        </div>
                        <div class="flex-root d-flex flex-column">
                            <span class="text-muted">Invoice</span>
                            <span class="fs-5">{{ $transaction->number }}</span>
                        </div>
                        <div class="flex-root d-flex flex-column">
                            <span class="text-muted">Status Transaksi</span>
                            <span class="fs-5">{{ $transaction->getTransactionStatusBadge }}</span>
                        </div>
                    </div>
                    <!--end::Order details-->
                    <!--begin:Order summary-->
                    <div class="d-flex justify-content-between flex-column">
                        <!--begin::Table-->
                        <div class="table-responsive border-bottom mb-9">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                                <thead>
                                    <tr class="border-bottom fs-6 fw-bold text-muted">
                                        <th class="min-w-175px pb-2">Produk</th>
                                        <th class="min-w-80px text-end pb-2">QTY</th>
                                        <th class="min-w-100px text-end pb-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($transaction->transactionDetails as $transactionDetail)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!--begin::Title-->
                                                    <div class="ms-5">
                                                        <div class="fw-bold">{{ $transactionDetail->product_name }}</div>
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                            </td>
                                            <td class="text-end">1</td>
                                            <td class="text-end">Rp @currency($transactionDetail->price)</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-end">Subtotal</td>
                                        <td class="text-end">$264.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">VAT (0%)</td>
                                        <td class="text-end">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Shipping Rate</td>
                                        <td class="text-end">$5.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="fs-3 text-dark fw-bold text-end">Grand Total</td>
                                        <td class="text-dark fs-3 fw-bolder text-end">$269.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end:Order summary-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->
            <!-- begin::Footer-->
            <div class="d-flex flex-stack flex-wrap mt-lg-20 pt-13">
                <!-- begin::Actions-->
                <div class="my-1 me-5">
                    <!-- begin::Pint-->
                    <button type="button" class="btn btn-success my-1 me-12" onclick="window.print();">Print Invoice</button>
                    <!-- end::Pint-->
                    <!-- begin::Download-->
                    <button type="button" class="btn btn-light-success my-1">Download</button>
                    <!-- end::Download-->
                </div>
                <!-- end::Actions-->
                <!-- begin::Action-->
                <a href="../../demo1/dist/apps/invoices/create.html" class="btn btn-primary my-1">Create Invoice</a>
                <!-- end::Action-->
            </div>
            <!-- end::Footer-->
        </div>
        <!-- end::Wrapper-->
    </div>
    <!-- end::Body-->
</div>