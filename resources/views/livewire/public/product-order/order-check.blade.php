<div class="row" id="searchDiv">
    <form wire:submit="store">
        <div class='row'>
            <div class="col-md-6 mb-4">
                <label>Cari Dengan Invoice</label>
                <input placeholder="Cari Dengan Invoice" type="text" class="form-control @error('invoice') is-invalid @enderror" wire:model="invoice" />
    
                @error('invoice')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6 mb-4">
                <label>Cari Dengan No Hp</label>
                <div class="input-group" wire:ignore>
                    <span class="input-group-text" id="basic-addon1">+62</span>
                    <input type="text" class="form-control phone @error('customer_phone') is-invalid @enderror" name="customer_phone" model-name="customer_phone" min="1" placeholder="8XX-XXXX-XXXX" aria-label="customer_phone" aria-describedby="basic-addon1">
                </div>
                <div class="form-text" id="basic-addon4">Contoh +62 8XX-XXXX-XXXX</div>
            </div>
            <div class="col-md-12 mb-4">
                <button type="submit" class="btn btn-success mt-3 w-100">
                    Cari
                </button>
            </div>
        </div>
    </form>
</div>

@include('js.imask')

