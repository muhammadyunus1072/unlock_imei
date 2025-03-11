<div class="{!! $containerClass !!}" style="{!! $containerStyle !!}" wire:ignore>
    @yield('content-before')
    <canvas id="{{ $canvasId }}"></canvas>
    @yield('content-after')
</div>

@push('js')
    <script>
        const config = @json($config);
        const chart = new Chart(document.getElementById('{{ $canvasId }}'), config);

        Livewire.on('js-chart-update', (data) => {
            chart.data.datasets = data.datasets;
            chart.data.labels = data.labels;
            chart.update();
        });
    </script>
@endpush
