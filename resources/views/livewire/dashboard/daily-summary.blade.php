<div class="row d-flex align-items-stretch my-4">
    <h3 class="fw-bold">{{ Carbon\Carbon::now()->translatedFormat('l, d F Y')}}</h3>
    @if ($todayAmount)
        <div class="col-md-6 mb-2">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2 class="text-center pt-5">Jumlah Transaksi IMEI</h2>
                </div>
                <div class="card-body text-white text-center">
                    <h2>@currency($todayAmount)</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2 class="text-center pt-5">Nilai Transaksi IMEI</h2>
                </div>
                <div class="card-body text-white text-center">
                    <h2>@currency($todayValue)</h2>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-2">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2 class="text-center pt-5">Belum Ada Transaksi Hari Ini</h2>
                </div>
            </div>
        </div>
    @endif
    <h3 class="fw-bold mt-4">Transaksi Sebelumnya</h3>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap w-100 h-100">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah Transaksi</th>
                        <th>Nilai Transaksi (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr>
                           <td>{{$item['transaction_date'] }}</td> 
                           <td>@currency($item['transaction_amount'])</td> 
                           <td>@currency($item['transaction_value'])</td> 
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-auto">
            {{ $data->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>

@push('css')
    <style>
        p.small.text-muted{
            display: none;
        }
    </style>
@endpush
