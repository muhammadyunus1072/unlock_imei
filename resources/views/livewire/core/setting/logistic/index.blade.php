<form wire:submit="store">
    <div class='row border rounded p-4 mb-4'>
        <div class="col-md-12 mb-4">
            <h4>Pengaturan Notifikasi Whatsapp</h4>
            <hr>
        </div>
        <div class="col-md-4 mb-4">
            <label>Adsmedia URL</label>
            <input type="text" class="form-control" wire:model="setting.{{ SettingSendWhatsapp::ADSMEDIA_URL }}" />
        </div>
        <div class="col-md-4 mb-4">
            <label>Adsmedia API KEY</label>
            <input type="text" class="form-control" wire:model="setting.{{ SettingSendWhatsapp::ADSMEDIA_API_KEY }}" />
        </div>
        <div class="col-md-4 mb-4">
            <label>Adsmedia DEVICE ID</label>
            <input type="text" class="form-control" wire:model="setting.{{ SettingSendWhatsapp::ADSMEDIA_DEVICE_ID }}" />
        </div>
        <div class="col-md-4 mb-4">
            <label>WA Admin</label>
            <input type="text" class="form-control" wire:model="setting.{{ SettingSendWhatsapp::ADMIN_PHONE }}" />
        </div>
    </div>

    <button type="submit" class="btn btn-success mt-3">
        <i class='ki-duotone ki-check fs-1'></i>
        Simpan
    </button>
</form>
