<div class="row">
    <form wire:submit="store">
        <div class="row">
            <h1 class="fw-bold">{{$product_name}}</h1>
        </div>
        <div class='row'>
            <div class="row p-0 m-0">
                <div class="col-md-auto mb-2">
                    <button type="button" class="btn btn-success" wire:click="addProductBookingTime">
                        <i class="ki-duotone ki-plus fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        Tambah Waktu Booking
                    </button>
                </div>
                <div class="col-md-auto">
                    <div class="input-group">
                        <input type="time" class="form-control" wire:model="time">
                    </div>                    
                </div>
            </div>
            
            <div class="row d-flex justify-content-start mt-4 gap-4">
                @foreach ($product_booking_times as $index => $item)
                    <div class="col-lg-3 col-md-4 mb-2 row">
                        <div class="col">
                            <div class="input-group">
                                <span class="form-control">{{ \Carbon\Carbon::parse($item['time'])->format('H:i') }}</span>
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger btn-sm" wire:click="removeProductBookingTime('{{ $index }}')">
                                <i class="ki-duotone ki-trash fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-md-10 mb-4 mx-auto">
                <button type="submit" class="btn btn-success mt-3 w-100">
                    Simpan
                </button>
            </div>
    </form>
</div>

@include('js.imask')
@include('js.ckeditor')

@push('js')

    <script>
        $(() => {

        });
    </script>
@endpush
