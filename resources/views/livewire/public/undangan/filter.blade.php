<div class="row mb-3">
    <div class="row p-0 m-0">
        {{-- SELECT STUDIO --}}
        <div class="col-md-6 mb-3 mx-auto">
            <select class="form-select w-100" wire:model.live='studio_id'>
                <option value="all">Seluruh Studio</option>

                @foreach ($studios as $studio)
                    <option value="{{ $studio['id'] }}">{{ $studio['name'] ." - ". $studio['city'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>