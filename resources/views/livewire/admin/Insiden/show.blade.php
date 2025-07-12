<div>
    <div class="card card-outline card-info">
        <div class="card-header">
            <div class="card-title">
                <h1 id="title">Detail Insiden {{ $insiden->nama_insiden }}</h1>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <h3>KETERANGAN</h3>
                <p>{{ $insiden->keterangan }}</p>
            </div>
            <hr>

            <div class="row">
                <h3>PETUGAS INSIDEN</h3>
            </div>
            <hr>

            <div class="row px-2">
                <x-table.table>
                    <thead>
                        <tr>
                            <th width="50px">No</th>
                            <th>Nama Petugas</th>
                            <th>No Seri Perangkat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($insiden->petugasInsiden as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->petugas->nama ?? '-' }}</td>
                                <td>{{ $data->perangkat->no_seri ?? '-' }}</td>
                                <td>{{ $data->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table.table>
            </div>

            <hr>
            <div class="row">
                <h3>LOKASI INSIDEN</h3>
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            .leaflet-control-custom {
                background: white;
                border-radius: 4px;
                box-shadow: 0 1px 5px rgba(0,0,0,0.65);
                font-family: Arial, sans-serif;
            }
            .leaflet-control-custom .toggle-btn {
                display: flex;
                align-items: center;
                padding: 8px;
                cursor: pointer;
                font-size: 18px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            .leaflet-control-custom .layer-list {
                display: none;
                flex-direction: column;
                padding: 5px;
            }
            .leaflet-control-custom .layer-list.active {
                display: flex;
            }
            .leaflet-control-custom .layer-item {
                display: flex;
                align-items: center;
                padding: 5px 10px;
                margin: 2px 0;
                border: 1px solid #ccc;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
            }
            .leaflet-control-custom .layer-item.active {
                background: #e0e0e0;
            }
            .leaflet-control-custom .layer-item:hover {
                background: #f4f4f4;
            }
            .leaflet-control-custom .icon {
                margin-right: 8px;
                font-size: 16px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var lat = {{ $insiden->latitude ?? 0 }};
                var lng = {{ $insiden->longitude ?? 0 }};

                var map = L.map('map').setView([lat, lng], 15);

                // Base layers
                var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                var esriLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 18,
                    attribution: '© Esri, Maxar, Earthstar Geographics'
                });

                // Heatmap suhu
                var suhuHeat = [
                    @foreach($logData as $log)
                        [{{ $log->latitude }}, {{ $log->longitude }}, {{ $log->suhu / 50 }}],
                    @endforeach
                ];
                var suhuLayer = L.heatLayer(suhuHeat, {
                    radius: 25,
                    blur: 15,
                    maxZoom: 17,
                    gradient: {
                        0.2: 'blue',
                        0.4: 'lime',
                        0.6: 'orange',
                        0.9: 'red'
                    }
                }).addTo(map);

                // Heatmap kualitas udara
                var udaraHeat = [
                    @foreach($logData as $log)
                        [{{ $log->latitude }}, {{ $log->longitude }}, {{ $log->kualitas_udara / 100 }}],
                    @endforeach
                ];
                var udaraLayer = L.heatLayer(udaraHeat, {
                    radius: 25,
                    blur: 15,
                    maxZoom: 17,
                    gradient: {
                        0.2: 'green',
                        0.4: 'yellow',
                        0.6: 'orange',
                        0.9: 'black'
                    }
                }).addTo(map);

                // Custom base layer control
                let BaseLayerControl = L.Control.extend({
                    options: { position: 'topright' },
                    onAdd: function (map) {
                        var container = L.DomUtil.create('div', 'leaflet-control-custom base-layer-control');
                        container.innerHTML = `
                            <div class="toggle-btn" id="baseToggle"><span class="icon">🗺️</span></div>
                            <div class="layer-list" id="baseList">
                                <div class="layer-item active" id="osmItem"><span class="icon">🗺️</span>OpenStreetMap</div>
                                <div class="layer-item" id="esriItem"><span class="icon">🛰️</span>Satellite</div>
                            </div>
                        `;
                        L.DomEvent.on(container, 'click', L.DomEvent.stopPropagation);
                        return container;
                    }
                });

                map.addControl(new BaseLayerControl());

                // Custom overlay layer control
                var OverlayLayerControl = L.Control.extend({
                    options: { position: 'topright' },
                    onAdd: function (map) {
                        var container = L.DomUtil.create('div', 'leaflet-control-custom overlay-layer-control');
                        container.innerHTML = `
                            <div class="toggle-btn" id="overlayToggle"><span class="icon">📊</span></div>
                            <div class="layer-list" id="overlayList">
                                <div class="layer-item active" id="suhuItem"><span class="icon">🌡️</span>Suhu</div>
                                <div class="layer-item active" id="udaraItem"><span class="icon">💨</span>Kualitas Udara</div>
                            </div>
                        `;
                        L.DomEvent.on(container, 'click', L.DomEvent.stopPropagation);
                        return container;
                    }
                });

                map.addControl(new OverlayLayerControl());

                // Base layer toggle logic
                var currentBaseLayer = osmLayer;
                var baseList = document.getElementById('baseList');
                var baseToggle = document.getElementById('baseToggle');

                baseToggle.addEventListener('click', function () {
                    baseList.classList.toggle('active');
                });

                document.getElementById('osmItem').addEventListener('click', function () {
                    if (currentBaseLayer !== osmLayer) {
                        map.removeLayer(currentBaseLayer);
                        map.addLayer(osmLayer);
                        currentBaseLayer = osmLayer;
                        document.getElementById('osmItem').classList.add('active');
                        document.getElementById('esriItem').classList.remove('active');
                    }
                    baseList.classList.remove('active');
                });

                document.getElementById('esriItem').addEventListener('click', function () {
                    if (currentBaseLayer !== esriLayer) {
                        map.removeLayer(currentBaseLayer);
                        map.addLayer(esriLayer);
                        currentBaseLayer = esriLayer;
                        document.getElementById('esriItem').classList.add('active');
                        document.getElementById('osmItem').classList.remove('active');
                    }
                    baseList.classList.remove('active');
                });

                // Overlay layer toggle logic
                var overlayList = document.getElementById('overlayList');
                var overlayToggle = document.getElementById('overlayToggle');

                overlayToggle.addEventListener('click', function () {
                    overlayList.classList.toggle('active');
                });

                document.getElementById('suhuItem').addEventListener('click', function () {
                    if (map.hasLayer(suhuLayer)) {
                        map.removeLayer(suhuLayer);
                        this.classList.remove('active');
                    } else {
                        map.addLayer(suhuLayer);
                        this.classList.add('active');
                    }
                    overlayList.classList.remove('active');
                });

                document.getElementById('udaraItem').addEventListener('click', function () {
                    if (map.hasLayer(udaraLayer)) {
                        map.removeLayer(udaraLayer);
                        this.classList.remove('active');
                    } else {
                        map.addLayer(udaraLayer);
                        this.classList.add('active');
                    }
                    overlayList.classList.remove('active');
                });

                // Marker
                L.marker([lat, lng]).addTo(map)
                    .bindPopup("<b>{{ $insiden->nama_insiden }}</b><br>{{ $insiden->keterangan }}")
                    .openPopup();
            });
        </script>
    @endpush
</div>
