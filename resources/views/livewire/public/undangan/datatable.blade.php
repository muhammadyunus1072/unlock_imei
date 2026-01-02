<div>
        <h3 class="fs-2hx text-dark text-center">Total Undangan = @currency($total) Orang</h3>
    {{-- EXPORT DATA --}}
    @if (isset($showExport) && $showExport)
        <div class="row align-items-center">
            <div class="col-auto">
                <label>Export Data:</label>
            </div>
            <div class="col-auto">
                <button class="btn btn-light-success btn-sm"
                    wire:click="datatableExport('{{ App\Exports\LivewireDatatableExport::EXPORT_EXCEL }}')">
                    <i class="fa fa-file-excel"></i>
                    Export Excel
                </button>
            </div>
            <div class="col-auto">
                <button class="btn btn-light-danger btn-sm"
                    wire:click="datatableExport('{{ App\Exports\LivewireDatatableExport::EXPORT_PDF }}')">
                    <i class="fa fa-file-pdf"></i>
                    Export PDF
                </button>
            </div>
        </div>
        <hr>
    @endif
    <div class="row justify-content-between mb-3">
        <div class="col-auto mb-2 {{ !isset($show_filter) || $show_filter == true ? '' : 'd-none' }}">
            <label>Show</label>
            <select wire:model.change="length" class="form-select">
                @foreach ($lengthOptions as $item)
                    <option value="{{ $item }}">{{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 mb-2 {{ !isset($keyword_filter) || $keyword_filter == true ? '' : 'd-none' }}">
            <label>Nama</label>
            <input wire:model.live.debounce.300ms="name" type="text" class="form-control">
        </div>
        <div class="col-sm-3 mb-2 {{ !isset($keyword_filter) || $keyword_filter == true ? '' : 'd-none' }}">
            <label>Deskripsi</label>
            <input wire:model.live.debounce.300ms="description" type="text" class="form-control">
        </div>
        <div class="row">
            <div class="col-6 col-md-12 mb-2 {{ !isset($keyword_filter) || $keyword_filter == true ? '' : 'd-none' }}">
                <label>Jumlah</label>
                <input type="text" class="form-control currency" placeholder="Jumlah"  wire:model.live.debounce.300ms="quantity"/>
            </div>
            <div class="col-6 col-md-12 d-flex mb-2 align-items-end{{ !isset($show_filter) || $show_filter == true ? '' : 'd-none' }}">
                
                    <button class="btn btn-light-danger"
                        wire:click="addUndangan()">
                        <i class="fa fa-plus"></i>
                        Tambahkan
                    </button>
        </div>
        </div>
    </div>

    <div class="position-relative">
        <div wire:loading>
            <div class="position-absolute w-100 h-100">
                <div class="w-100 h-100" style="background-color: grey; opacity:0.2"></div>
            </div>
            <h5 class="position-absolute shadow bg-white p-2 rounded"
                style="top: 50%;left: 50%;transform: translate(-50%, -50%);">Loading...</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-nowrap w-100 h-100">
                <thead>
                    <tr>
                        @foreach ($columns as $index => $col)
                            <th wire:key='datatable_header_{{ $index }}'>
                                @if (!isset($col['sortable']) || $col['sortable'])
                                    @php $isSortAscending = $col['key'] == $sortBy && $sortDirection == 'asc'@endphp
                                    <button type="button" class='btn p-0 m-0'
                                        wire:click="datatableSort('{{ $col['key'] }}')">
                                        <div class="fw-bold align-items-center d-flex">
                                            <div class='pe-2'>
                                                {{ $col['name'] }}
                                            </div>
                                            <div class="d-flex flex-column">
                                                <i
                                                    class="ki-duotone ki-up fs-4 m-0 p-0
                                {{ $isSortAscending ? 'text-dark' : 'text-secondary' }}"></i>
                                                <i
                                                    class="ki-duotone ki-down fs-4 m-0 p-0
                                {{ $isSortAscending ? 'text-secondary' : 'text-dark' }}"></i>
                                            </div>
                                        </div>
                                    </button>
                                @else
                                    <div class="fs-6 p-2">
                                        {{ $col['name'] }}
                                    </div>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr wire:key='datatable_row_{{ $index }}'>
                            @foreach ($columns as $col)
                                @php
                                    $cell_style = '';
                                    if (isset($col['style'])) {
                                        $cell_style = is_callable($col['style'])
                                            ? call_user_func($col['style'], $item, $index)
                                            : $col['style'];
                                        $cell_style = "style='{$cell_class}'";
                                    }

                                    $cell_class = '';
                                    if (isset($col['class'])) {
                                        $cell_class = is_callable($col['class'])
                                            ? call_user_func($col['class'], $item, $index)
                                            : $col['class'];
                                        $cell_class = "class='{$cell_class}'";
                                    }
                                @endphp

                                @if (isset($col['render']) && is_callable($col['render']))
                                    <td {!! $cell_class !!} {!! $cell_style !!}>
                                        {!! call_user_func($col['render'], $item) !!}
                                    </td>
                                @elseif (isset($col['key']))
                                    <td {!! $cell_class !!} {!! $cell_style !!}>
                                        {{ $item->{$col['key']} }}
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-end mt-3">
        <div class="col">
            <em>Total Data: {{ $data->total() }}</em>
        </div>
        <div class="col-auto">
            {{ $data->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="editUndanganModal" tabindex="-1" aria-labelledby="editUndanganModal" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editUndanganModal">Ubah Undangan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-4 mb-4">
                <label>Nama Undangan</label>
                <input placeholder="Nama Undangan" type="text" class="form-control @error('name_edit') is-invalid @enderror" wire:model="name_edit" />
    
                @error('name_edit')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4 mb-4">
                <label>Deskipris Undangan</label>
                <input placeholder="Deskripsi Undangan" type="text" class="form-control @error('description_edit') is-invalid @enderror" wire:model="description_edit" />
    
                @error('description_edit')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-4 mb-4">
                <label>Jumlah</label>
                <input placeholder="Jumlah" type="text" class="form-control currency @error('quantity_edit') is-invalid @enderror" wire:model="quantity_edit" />
    
                @error('quantity_edit')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
        <button type="button" class="btn btn-primary" wire:click="saveEditUndangan">Simpan</button>
      </div>
    </div>
  </div>
</div>
</div>

@include('js.imask')

@push('js') 
    <script>
        Livewire.on('onSuccessStore', function() {
                $('#editUndanganModal').modal('hide');
            });
    </script>
@endpush
