<div class="row d-flex align-items-stretch my-4">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap w-100 h-100">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Transaksi</th>
                        <th>Nilai Transaksi (Rp)</th>
                        <th>Biaya Web</th>
                        <th>Biaya Notifikasi</th>
                        <th>Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr>
                           <td>{{$item['month_name'] }}</td> 
                           <td>@currency($item['total_transaction'])</td> 
                           <td>@currency($item['total_amount'])</td> 
                           <td>@currency($item['total_transaction'] * $web)</td> 
                           <td>@currency($item['notification_price']) + @currency($notification)</td> 
                           <td>@currency($item['total_amount'] - ($item['total_transaction'] * $web) - ($item['notification_price'] + $notification))</td> 
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
