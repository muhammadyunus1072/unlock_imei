<div class='row w-100 mt-3'>
    <div class="row">
        <div class="col-md-3 mb-2">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" wire:model.live="dateStart" />
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" class="form-control" wire:model.live="dateEnd" />
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <label>Status</label>
        <select class="form-select d-block" wire:model.live="status">
            <option>Seluruh</option>
            @foreach ($statuses as $status)
                <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>
