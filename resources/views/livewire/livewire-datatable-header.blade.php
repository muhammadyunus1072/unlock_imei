<div class="row d-flex align-items-stretch my-4">
    @foreach ($data as $item)
        <div class="col-md-{{ $item['col'] }} mb-2" onclick="{{ isset($item['url']) ? "clickHeaderHandler('".$item['url']."')" : "" }}"">
            <div class="card">
                <div class="card-header d-flex justify-content-center">
                    <h2 class="text-center pt-5">{{ $item['name'] }}</h2>
                </div>
                <div class="card-body text-white text-center">
                    <h2>@currency($item['value'])</h2>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('js')
    <script>
        function clickHeaderHandler(url)
        {
            window.open(url, '_blank');
        }
    </script>
@endpush
