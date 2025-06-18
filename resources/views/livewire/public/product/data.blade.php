<div class="row">
    @foreach ($data as $index => $item)
    <div class="col-sm-12 col-md-6 col-lg-4 mb-4" onclick="handleProduct('{{ route('public.product-order', Crypt::encrypt($item->id)) }}')">
        <div class="card text-white card-has-bg click-col" style="background-image: url('{{ $item->image_url()}}');">
            <div class="card-img-overlay d-flex flex-column">
                <div class="card-body">
                <h2 class="card-title mt-0">
                    <a class="text-white" href="#">{{$item->name}}</a>
                    <br>
                    <a class="text-white" href="#">Garansi 
                        @if (is_null($item->warranty_days))
                            Selamanya
                        @else
                            @currency($item->warranty_days) Hari
                        @endif</a>
                </h2>
              </div>
              <div class="card-footer">
                  <div class="media">
                      <small>Start From</small>
                      <h2 class="card-title mt-0" style="color: #01cecb">
                          Rp. @currency($item->price) / IMEI 
                      </h2>
                  </div>
              </div>
            </div>
        </div>
     </div>
    @endforeach

    <div class="row justify-content-center mt-3">
        <div class="col-auto">
            {{ $data->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>

@push('css')
    <link href="{{ asset('assets/css/custom-homepage.css') }}" rel="stylesheet" type="text/css" />
    <style>
        p.small.text-muted{
            display: none;
        }
    </style>
@endpush

@push('js')
    <script>
        function handleProduct(url)
        {
            window.location = url;
            return false;
        }
    </script>
@endpush