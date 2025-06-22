@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* Custom styles for Leaflet pop-up */
        .leaflet-popup-content-wrapper {
            background: rgba(30, 30, 30, 0.9);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .leaflet-popup-content {
            padding: 15px;
        }

        .leaflet-popup-tip {
            background: rgb(11, 35, 43);
        }

        .leaflet-popup-close-button {
            color: #ffffff;
            font-size: 24px;
            top: 8px;
            right: 8px;
        }

        .leaflet-popup-close-button:hover {
            color: #cccccc;
        }

        .leaflet-popup-content strong,
        .leaflet-popup-content span,
        .leaflet-popup-content div {
            color: #ffffff;
        }

        .leaflet-popup-content hr {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .leaflet-popup-content .text-green-500 {
            color: #85e085 !important;
        }

        .leaflet-popup-content .text-red-500 {
            color: #ff8080 !important;
        }

        .leaflet-popup-content .text-yellow-500 {
            color: #ffe680 !important;
        }

        .leaflet-tooltip-distance {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #fff;
        }

        /* Status indicator */
        .status-indicator {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            z-index: 1000;
            border: 1px solid #333;
        }

        .status-indicator.updating {
            background: rgba(255, 193, 7, 0.9);
            color: #000;
            animation: pulse 1s infinite;
        }

        .status-indicator.updated {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
@endpush

<div>
    <div wire:ignore id="map" style="height: 80vh; border-radius: 12px; position: relative;">
        <div id="status-indicator" class="status-indicator">
            Memuat peta...
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            let map;
            let markers = [];
            let isMapInitialized = false;
            let lines = [];
            let currentPetugasData = [];
            let lastUpdateTimestamp = 0;

            function updateStatusIndicator(status, message) {
                const indicator = document.getElementById('status-indicator');
                if (indicator) {
                    indicator.className = `status-indicator ${status}`;
                    indicator.textContent = message;
                    
                    if (status === 'updated') {
                        setTimeout(() => {
                            indicator.style.display = 'none';
                        }, 2000);
                    } else {
                        indicator.style.display = 'block';
                    }
                }
            }

            function initMap() {
                if (!map) {
                    console.log('Initializing map...');
                    map = L.map('map').setView([{{ $latitude }}, {{ $longitude }}], 15);

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        maxZoom: 18,
                        attribution: '© OpenStreetMap, © CartoDB'
                    }).addTo(map);

                    // Add command center marker
                    const pontianakIcon = L.icon({
                        iconUrl: '{{ url('public/komando/icon/zona.svg') }}', 
                        iconSize: [46, 46],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    L.marker([{{ $latitude }}, {{ $longitude }}], {
                            icon: pontianakIcon
                        })
                        .addTo(map)
                        .bindPopup("Pusat komando");

                    isMapInitialized = true;
                    updateStatusIndicator('updated', 'Peta berhasil dimuat');
                    
                    // Initial data load
                    setTimeout(() => {
                        updateMarkers(@json($petugasInsidenData));
                    }, 500);
                }
            }

            function updateMarkers(petugasData) {
                if (!isMapInitialized) {
                    console.log('Map not initialized yet');
                    return;
                }

                console.log('Updating markers with data:', petugasData);
                updateStatusIndicator('updating', 'Memperbarui posisi petugas...');

                // Clear existing markers and lines
                markers.forEach(marker => {
                    map.removeLayer(marker);
                });
                markers = [];

                lines.forEach(line => {
                    map.removeLayer(line);
                });
                lines = [];

                const komandoLat = {{ $latitude }};
                const komandoLng = {{ $longitude }};
                const komandoLatLng = L.latLng(komandoLat, komandoLng);

                if (Array.isArray(petugasData) && petugasData.length > 0) {
                    petugasData.forEach((p, index) => {
                        const lat = parseFloat(p.latitude);
                        const lng = parseFloat(p.longitude);

                        if (!isNaN(lat) && !isNaN(lng)) {
                            const petugasLatLng = L.latLng(lat, lng);

                            const petugasIcon = L.divIcon({
                                html: `<div class="rounded-full border-2 border-white shadow-lg overflow-hidden" style="width: 40px; height: 40px;">
                                    <img src="${p.foto ? `/storage/${p.foto}` : '{{ url('public/komando/assets/img/user/petugas.jpg') }}'}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>`,
                                iconSize: [40, 40],
                                iconAnchor: [20, 40],
                                popupAnchor: [0, -40],
                                className: `petugas-marker-${index}`
                            });

                            const now = new Date();
                            const popupContent = `
                                <div class="font-sans text-sm p-2">
                                    <div class="flex items-center mb-2">
                                        <img src="${p.foto ? `/storage/${p.foto}` : '{{ url('public/komando/assets/img/user/petugas.jpg') }}'}"
                                            alt="Foto Petugas"
                                            width="90%"
                                            class="text-theme py-2 px-2 shadow-md" style="border-radius: 50%;">
                                        <div>
                                            <hr>
                                            <strong class="block text-base">${p.nama_petugas}</strong>
                                            <span>No Seri: ${p.no_seri}</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span>Suhu:</span> ${p.suhu || 'N/A'}&deg;C
                                        </div>
                                        <div>
                                            <span>Kualitas Udara:</span> ${p.kualitas_udara || 'N/A'}
                                        </div>
                                        <div class="col-span-2">
                                            <span>Status:</span>
                                            <span class="${p.status_color || ''} font-bold">${p.status_text || 'N/A'}</span>
                                        </div>
                                        <div class="col-span-2 text-xs text-gray-300">
                                            <span>⏰ Diperbarui: ${now.toLocaleTimeString()}</span>
                                        </div>
                                    </div>
                                </div>
                            `;

                            const marker = L.marker(petugasLatLng, { icon: petugasIcon })
                                .addTo(map)
                                .bindPopup(popupContent);
                            markers.push(marker);

                            // Line from command center to petugas
                            const line = L.polyline([komandoLatLng, petugasLatLng], {
                                color: 'yellow',
                                weight: 3,
                                opacity: 0.9,
                                dashArray: '',
                            }).addTo(map);

                            // Distance calculation and tooltip
                            const distance = komandoLatLng.distanceTo(petugasLatLng);
                            const midpointLat = (komandoLat + lat) / 2;
                            const midpointLng = (komandoLng + lng) / 2;

                            const tooltip = L.tooltip({
                                permanent: true,
                                direction: 'center',
                                className: 'leaflet-tooltip-distance',
                                offset: [0, 0],
                            })
                                .setLatLng([midpointLat, midpointLng])
                                .setContent(`${(distance / 1000).toFixed(2)} km`)
                                .addTo(map);

                            lines.push(line);
                            lines.push(tooltip);
                        }
                    });

                    updateStatusIndicator('updated', `${petugasData.length} petugas aktif - ${new Date().toLocaleTimeString()}`);
                } else {
                    updateStatusIndicator('updated', 'Tidak ada petugas aktif');
                }

                // Store current data
                currentPetugasData = petugasData;
            }

            // Initialize when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, initializing map...');
                setTimeout(() => {
                    initMap();
                }, 1000);
            });

            // Livewire event listeners
            document.addEventListener('livewire:initialized', () => {
                console.log('Livewire initialized');
                
                // Listen for data updates from Livewire
                Livewire.on('petugasDataUpdated', (event) => {
                    console.log('Petugas data updated event received:', event);
                    
                    if (event && event.data) {
                        updateMarkers(event.data);
                    } else if (Array.isArray(event)) {
                        // Sometimes Livewire sends the array directly
                        updateMarkers(event);
                    }
                });
            });

            // Listen for Livewire updates (when wire:poll triggers)
            document.addEventListener('livewire:updated', function() {
                console.log('Livewire component updated');
                
                // Force update markers with current data
                const newData = @this.petugasInsidenData;
                if (newData && Array.isArray(newData)) {
                    updateMarkers(newData);
                }
            });

            // Backup: Watch for changes in the Livewire component data
            setInterval(() => {
                if (window.Livewire && @this) {
                    const currentData = @this.petugasInsidenData;
                    const currentTimestamp = @this.lastUpdated;
                    
                    if (currentTimestamp > lastUpdateTimestamp) {
                        console.log('Data changed detected via polling, updating markers...');
                        updateMarkers(currentData);
                        lastUpdateTimestamp = currentTimestamp;
                    }
                }
            }, 1000);
        </script>
    @endpush
</div>