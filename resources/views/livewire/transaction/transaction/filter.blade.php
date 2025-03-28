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
    <div class="col-md-3 mb-4 row align-items-end">
        <button class='btn btn-danger btn-sm m-0 col-auto' wire:click="showDeleteDialog">
            <i class='ki-duotone ki-trash fs-1'>
                <span class='path1'></span>
                <span class='path2'></span>
                <span class='path3'></span>
                <span class='path4'></span>
                <span class='path5'></span>
            </i>
            Hapus Data Expired
        </button>
    </div>
</div>
