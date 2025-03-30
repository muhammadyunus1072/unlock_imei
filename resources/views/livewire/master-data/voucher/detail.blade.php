<div class="row">
    <form wire:submit="store">
        <div class='row'>
            <div class="col-md-10 mb-4">
                <label>Kode</label>
                <input placeholder="Kode" type="text" class="form-control @error('code') is-invalid @enderror" wire:model="code" />
    
                @error('code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-10 mb-4">
                <label>Jenis Voucher</label>
                <select class="form-select w-100" wire:model='type'>
                    @php $isFound = false; @endphp

                    @foreach ($type_choices as $type_value => $type_name)
                        @php $isFound = $isFound || $type_value == $type; @endphp
                        <option value="{{ $type_value }}" {{$isFound ? 'selected' : ''}}>{{ $type_name }}</option>
                    @endforeach
                </select>
    
                @error('type')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-10 mb-4">
                <label>Nilai Voucher</label>
                <input placeholder="Nilai Voucher" type="text" class="form-control currency @error('amount') is-invalid @enderror" wire:model="amount" />
    
                @error('amount')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" wire:model="start_date" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" wire:model="end_date" />
                </div>
            </div>
            <!-- Active Option -->
            
            <div class="col-md-4 mb-4 row align-items-end">
                <div class="form-check m-2">
                    <input class="form-check-input" type="checkbox" wire:model="is_active">
                    <label class="form-label ms-2 mb-2">
                        Penanda Aktif
                    </label>
                </div>
            </div>
            <div class="col-md-10 mb-4">
                <button type="submit" class="btn btn-success mt-3 w-100">
                    Simpan
                </button>
            </div>

    
    </form>
</div>

@include('js.imask')

