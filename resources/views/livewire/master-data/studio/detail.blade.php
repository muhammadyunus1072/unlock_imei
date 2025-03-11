<div class="row">
    <form wire:submit="store">
        <div class='row'>
            <div class="row">
                <div class="col-md-5 mb-4">
                    <label>Nama Studio</label>
                    <input placeholder="Nama Studio" type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" />
        
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-5 mb-4">
                    <label>Kota / Kabupaten</label>
                    <input placeholder="Kota / Kabupaten" type="text" class="form-control @error('city') is-invalid @enderror" wire:model="city" />
        
                    @error('city')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-md-10 mb-4" wire:ignore>
                <label for="">Lokasi Map</label>
                <div id="map" style="height: 400px;"></div>
            </div>

            <div class="col-md-10 mb-4">
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea rows="5" class="form-control" placeholder="Alamat" name="address" wire:model="address"></textarea>
                </div>
            </div>

            <div class="col-md-10 mb-4">
                <div class="form-group" wire:ignore>
                    <label>Deskripsi</label>
                    <textarea class="form-control ckeditor"  id="description" name="description">{{ $description }}</textarea>
                </div>
            </div>
        <button type="submit" class="btn btn-success mt-3">
            Simpan
        </button>
    
    </form>
</div>

@push('css')
    <style>
        input[type=checkbox] {
            /* Double-sized Checkboxes */
            -ms-transform: scale(1.2);
            /* IE */
            -moz-transform: scale(1.2);
            /* FF */
            -webkit-transform: scale(1.2);
            /* Safari and Chrome */
            -o-transform: scale(1.2);
            /* Opera */
            padding: 10px;
        }
        
        input[type=radio] {
            /* Double-sized Checkboxes */
            -ms-transform: scale(1.2);
            /* IE */
            -moz-transform: scale(1.2);
            /* FF */
            -webkit-transform: scale(1.2);
            /* Safari and Chrome */
            -o-transform: scale(1.2);
            /* Opera */
            padding: 10px;
        }

        .hero-banner {
            position: relative;
            background: url('{{ asset("storage/public/cropped_images/cropped_6788d79c39e238.87096344.png") }}') no-repeat center center;
            background-size: cover;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
 
        #map {
            height: 500px;  /* Set a fixed height to test */
            width: 100%;    /* Full width */
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('vendor/geoSearch/geosearch.css') }}"/>
    <style>
        .nospace-x{
            margin-left: 0;
            margin-right: 0;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
@endpush

@include('js.ckeditor' )

@push('js')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('vendor/geoSearch/geosearch.bundle.min.js') }}"></script>
    <script>
        $(() => {
            // MAP
            const initLat = @json($latitude ?? null);
            const initLng = @json($longitude ?? null);
            const initZoom = @json($map_zoom ?? 5);

            const map = L.map('map').setView([initLat ?? -2.4833826, initLng ?? 117.8902853], initZoom);

            L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const search = new GeoSearch.GeoSearchControl({
                provider: new GeoSearch.OpenStreetMapProvider(),
                showMarker: false, 
                showPopup: false, 
                style: 'bar', 
                marker: {
                    
                    icon: new L.Icon.Default(),
                    draggable: false,
                },
                popupFormat: ({ query, result }) => result.label, 
                resultFormat: ({ result }) => result.label, 
                maxMarkers: 1, 
                retainZoomLevel: false, 
                animateZoom: true, 
                autoClose: false, 
                searchLabel: 'Cari Tempat Event', 
                keepResult: false, 
                updateMap: true, 
            });

            map.addControl(search);
            
            let marker = null;

            function updateMarker(lat, lng, zoom, label = null) {
                
                if (marker) {
                    map.removeLayer(marker);
                }

                
                marker = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(`Latitude: ${lat}<br>Longitude: ${lng}<br>Zoom: ${zoom}`)
                    .openPopup();
                // console.log(lat);
                // console.log(lng);
                // console.log(zoom);
                // console.log(label);
                @this.call('setLocation', lat, lng, zoom, label)
            }
            if (initLat && initLng) {
                updateMarker(initLat, initLng, initZoom);
            }
            
            map.on('click', function (event) {
                const { lat, lng } = event.latlng; 
                const zoom = map.getZoom(); 
                updateMarker(lat, lng, zoom);
            })

            map.on('geosearch/showlocation', (e) => {
                
                const lat = e.location.y;   
                const lng = e.location.x;   
                const label = e.location.label;   

                const zoom = map.getZoom(); 
                updateMarker(lat, lng, zoom, label);
            })
        });
    </script>
@endpush
