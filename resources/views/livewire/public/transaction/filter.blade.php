<div class='row w-100 mt-3'>
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
