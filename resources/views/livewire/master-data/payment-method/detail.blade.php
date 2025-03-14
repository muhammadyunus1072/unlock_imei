<div class="row">
    <form wire:submit="store">
        <div class='row'>
            <div class="col-md-10 mb-4">
                <label>Nama Metode Pembayaran</label>
                <input placeholder="Nama Metode Pembayaran" type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
    
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-10 mb-4">
                <label>Jenis Biaya Admin</label>
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
                <label>Nilai Biaya Admin</label>
                <input placeholder="Nilai Biaya Admin" type="text" class="form-control currency @error('amount') is-invalid @enderror" wire:model="amount" />
    
                @error('amount')
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

