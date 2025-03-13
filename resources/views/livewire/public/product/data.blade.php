<div class="row">
    @foreach ($data as $index => $item)
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4 open-product" style="min-height: 350px;" href="{{ route('public.product-booking', Crypt::encrypt($item->id)) }}">
            <div class="card text-white card-has-bg click-col" style="background-image: url('{{ $item->image_url()}}');">
                <div class="card-img-overlay h-100 d-flex flex-column" style="min-height:350px;">
                    <div class="card-body">
                        <a target="_blank" href="https://www.google.com/maps?q={{ $item->studio->latitude.','.$item->studio->longitude }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Jl Soekarno Hatta Ruko Griya Shanta Eksekutif, Blok MP-57, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141" data-kt-initialized="1">
                            <p class="card-meta mb-1 text-success"><i class="fa-solid fa-location-dot text-success"></i> {{$item->studio->name}} - {{$item->studio->city}}</p>
                        </a>
                        <h2 class="card-title mt-0">
                            <a class="text-white" href="https://app.kuystudio.id/products/detail/c958ef8d-1a69-4f8c-a13c-225e2e2ba0d8">{{$item->name}}</a>
                        </h2>
                    </div>
                    <div class="card-footer">
                        <div class="media">
                            <small>Start From</small>
                            <h2 class="card-title mt-0 text-white">
                                Rp. @currency($item->price) / Sesi 
                            </h2>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    @endforeach
</div>

@push('css')
    <link href="{{ asset('assets/css/custom-homepage.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            $('.open-product').click(function() {
                window.location = $(this).attr('href');
                return false;
            });
        });
    </script>
@endpush