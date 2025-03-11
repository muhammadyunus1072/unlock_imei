<div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importExcelModal">
        Import Excel
    </button>

    <!-- Modal -->
    <div class="modal fade" id="importExcelModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="importExcelModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importExcelModalLabel">Import Excel</h5>
                    <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($import_excel as $index => $excel)
                        <div class="mb-4">
                            <label for="import_excel_{{ $index }}" class="form-label">{{ $excel['name'] }}</label>
                            <input type="file" wire:model="import_excel.{{ $index }}.data" id="import_excel_{{ $index }}" class="form-control">
                            
                            @error('import_excel.' . $index . '.data')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" wire:click="storeImport" class="btn btn-primary px-5">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
