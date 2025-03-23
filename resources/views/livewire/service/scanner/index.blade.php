<div class="w-75 row d-flex justify-content-center">
    <div id="cameraPreview" class="col-12 mb-3 d-flex justify-content-center">
        <video id="preview" wire:ignore></video>
    </div>
    <div id="inputManual" class="col-12 mb-3 d-none">
        <label>Kode Booking</label>
        <input placeholder="Kode Booking" id="input-code" type="text" class="form-control @error('code') is-invalid @enderror" wire:model="code" />
    </div>
    <div class="col-auto">
        <button class="btn btn-success" id="showPreview">Manual</button>
        {{-- <button class="btn btn-success" id="startScan" disabled>Start Scan</button>
        <button class="btn btn-success" id="stopScan">Stop Scan</button> --}}
    </div>
</div>



@push('css')
    <script src="{{ asset('assets/js/instascan.min.js') }}"></script>
    <style>
        #preview {
            width: 100%;
            max-width: 600px;
            height: auto;
            aspect-ratio: 16 / 9; /* Maintain aspect ratio */
            border: 2px solid #50cd89;
            border-radius: 10px;
            box-shadow: 2px 2px 10px #50cd89;
        }
    </style>
@endpush

@push('js')
    <script type="text/javascript">
        let scanner;
        let selectedCamera;
        let isPreview = true;

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length === 0) {
                console.error('No cameras found.');
                return;
            }

            // Find the back camera, if available
            let backCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
            selectedCamera = backCamera || cameras[0];

            scanner = new Instascan.Scanner({ 
                continuous: true,
                video: document.getElementById('preview'),
                refractoryPeriod: 10000,
                mirror: !backCamera, // Mirror only for front camera
            });

            scanner.start(selectedCamera);
            scanner.addListener('scan', function (content) {
                Livewire.dispatch('on-scanned', { data: content });
            });

        }).catch(function (e) {
            console.error('Error accessing cameras:', e);
        });

        // document.getElementById('startScan').addEventListener('click', function () {
        //     if (scanner && selectedCamera) {
        //         scanner.start(selectedCamera);
        //         $("#preview").toggleClass('d-none');
        //         this.disabled = true;
        //         document.getElementById('stopScan').disabled = false;
        //     }
        // });

        // document.getElementById('stopScan').addEventListener('click', function () {
        //     if (scanner) {
        //         scanner.stop();
        //         $("#preview").toggleClass('d-none');
        //         document.getElementById('startScan').disabled = false;
        //         this.disabled = true;
        //     }
        // });

        document.getElementById('showPreview').addEventListener('click', function (e) {
            if(isPreview)
            {
                if (scanner) {
                    scanner.stop();
                    $("#preview").toggleClass('d-none');
                    // document.getElementById('startScan').disabled = false;
                    // this.disabled = true;
                    $("#cameraPreview").toggleClass('d-none');
                    $("#inputManual").toggleClass('d-none');
                    $("#input-code").focus();
                    $(e.target).text('Scanner');
                    isPreview = false;

                }
            }else{
                if (scanner && selectedCamera) {
                    scanner.start(selectedCamera);
                    $("#preview").toggleClass('d-none');
                    // this.disabled = true;
                    // document.getElementById('stopScan').disabled = false;
                    $("#cameraPreview").toggleClass('d-none');
                    $("#inputManual").toggleClass('d-none');
                    $(e.target).text('Manual');
                    isPreview = true;
                }
            }
        });

    </script>
@endpush