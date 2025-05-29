<div class="row">
    <form wire:submit="store">
        <div class='row'>
            <div class="col-md-10 mb-4">
                <label>Nama Garansi</label>
                <input placeholder="Nama Garansi" type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
    
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-10 mb-4">
                <label>Lama Garansi (Hari)</label>
                <input placeholder="Lama Garansi (Hari)" type="text" class="form-control currency @error('days') is-invalid @enderror" wire:model="days" />
                <div class="form-text" id="basic-addon4">Kosongkan untuk lama garansi selamanya</div>
                @error('days')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-10 mb-4">
                <button type="submit" class="btn btn-success mt-3 w-100">
                    Simpan
                </button>
            </div>

    
    </form>
</div>

@include('js.imask')

