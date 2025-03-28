<div class='row w-100 mt-3'>
    <div class="col-md-3 mb-4">
        <label>Jenis Laporan</label>
        <select class="form-select d-block" wire:model.live="dateType">
            @foreach ($dateChoice as $value => $name)
                <option value="{{ $value }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>
