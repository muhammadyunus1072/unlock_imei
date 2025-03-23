@extends('app.layouts.public')

@section('content')

    <div class="row d-flex justify-content-center mt-5 align-items-center" style="height: 80vh;">
        <!--begin::Aside-->
        <img class="theme-light-show" style="width: 100vw; left: 0; top: 0;"
        src="{{ asset('files/images/header.jpeg') }}" alt="" id="img-tiket"/>
        <img class="theme-light-show d-none img-barcode" style="width: 100vw; left: 0;"
        src="{{ asset('files/images/barcode_atas.png') }}" alt=""/>

        <div class="col-10 col-md-4 row d-flex justify-content-center mt-4" id="content-qr"><!--end::Image-->

            <h1 class="text-gray-800 fs-2qx fw-bold text-center" id="peringatan">
                Kode Booking
            </h1>
            <div class="d-flex justify-content-center">
                {{$qrCode}}
            </div>
            <h1 class="text-gray-800 display-3 fw-bold text-center" id="peringatan">
                {{$code}}
            </h1>
            
            <div class="row d-flex justify-content-center" style="margin-top: 40px;" id="action">
                <div class="col-md-10 d-grid">
                    <button type="button" onclick="printQR()" class="btn mt-3 btn-block text-white" style="background-color: #00cacd">
                        DOWNLOAD
                    </button>
                </div>
            </div>
        </div>

        <img class="theme-light-show d-none img-barcode mt-4" style="width: 100vw; left: 0;"
        src="{{ asset('files/images/barcode_bawah.png') }}" alt=""/>
        <!--begin::Aside-->
</div>
@endsection

@push('js')
    <script>
        function printQR(){
            $('#navbar').addClass('d-none');
            $('#footer').addClass('d-none');
            $('#action').addClass('d-none');

            window.print();
            const afterPrint = setTimeout(() => {
                window.close()
            }, 500);
        }
    </script>
@endpush
