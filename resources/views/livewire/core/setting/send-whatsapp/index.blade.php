<form wire:submit="store">
    <div class='row border rounded p-4 mb-4'>
        <div class="col-md-12 mb-4">
            <h4>Pengaturan Notifikasi Whatsapp</h4>
            <hr>
        </div>
        <div class="col-md-8 mb-4">
            <label>Adsmedia URL</label>
            <input type="text" class="form-control" wire:model="setting.adsmedia_url" />
        </div>
        <div class="col-md-8 mb-4">
            <label>Adsmedia API KEY</label>
            <input type="text" class="form-control" wire:model="setting.adsmedia_api_key" />
        </div>
        <div class="col-md-8 mb-4">
            <label>Adsmedia DEVICE ID</label>
            <input type="text" class="form-control" wire:model="setting.adsmedia_device_id" />
        </div>

        <div class="col-md-8 mb-4">
            <label>Whatsapp Admin</label>
            <div class="input-group" wire:ignore>
                <span class="input-group-text" id="basic-addon1">+62</span>
                <input type="text" wire:model="setting.admin_phone" class="form-control phone @error('setting.admin_phone') is-invalid @enderror" name="setting.admin_phone" model-name="setting.admin_phone" min="1" placeholder="8XX-XXXX-XXXX" aria-label="customer_phone" aria-describedby="basic-addon1">
            </div>
            <div class="form-text" id="basic-addon4">Contoh +62 8XX-XXXX-XXXX</div>
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-3">
        <i class='ki-duotone ki-check fs-1'></i>
        Simpan
    </button>
</form>

@include('js.imask')
