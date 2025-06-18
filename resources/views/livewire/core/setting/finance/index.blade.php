<form wire:submit="store">
    <div class='row border rounded p-4 mb-4'>
        <div class="col-md-12 mb-4">
            <h4>Pengaturan Keuangan</h4>
            <hr>
        </div>
        <div class="col-md-8 mb-4">
            <label>Adsmedia</label>
            <input type="text" class="form-control currency" wire:model="setting.adsmedia" />
        </div>
        <div class="col-md-8 mb-4">
            <label>Website</label>
            <input type="text" class="form-control currency" wire:model="setting.web" />
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-3">
        <i class='ki-duotone ki-check fs-1'></i>
        Simpan
    </button>
</form>

@include('js.imask')
