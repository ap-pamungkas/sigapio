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

<div wire:poll>
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
                
                // Safely get coordinates with fallback
                const latitude = {{ $latitude ?? -0.0263 }};
                const longitude = {{ $longitude ?? 109.3425 }};
                
                map = L.map('map').setView([latitude, longitude], 15);
    
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
    
                L.marker([latitude, longitude], {
                        icon: pontianakIcon
                    })
                    .addTo(map)
                    .bindPopup("Pusat komando");
    
                isMapInitialized = true;
                updateStatusIndicator('updated', 'Peta berhasil dimuat');
                
                // Initial data load
                setTimeout(() => {
                    try {
                        const initialData = {!! json_encode($petugasInsidenData ?? []) !!};
                        updateMarkers(initialData);
                    } catch (e) {
                        console.warn('Error loading initial data:', e);
                        updateMarkers([]);
                    }
                }, 500);
            }
        }
    
        function validatePetugasData(data) {
            if (!Array.isArray(data)) {
                console.warn('Petugas data is not an array:', data);
                return [];
            }
    
            return data.filter(p => {
                const isValid = p && 
                               typeof p.latitude !== 'undefined' && 
                               typeof p.longitude !== 'undefined' && 
                               !isNaN(parseFloat(p.latitude)) && 
                               !isNaN(parseFloat(p.longitude)) &&
                               p.nama_petugas;
                
                if (!isValid) {
                    console.warn('Invalid petugas data:', p);
                }
                
                return isValid;
            });
        }
    
        function updateMarkers(petugasData) {
            if (!isMapInitialized) {
                console.log('Map not initialized yet');
                return;
            }
    
            console.log('Raw petugas data received:', petugasData);
            
            // Validate and clean the data
            const validatedData = validatePetugasData(petugasData);
            console.log('Validated petugas data:', validatedData);
    
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
    
            // Safely get coordinates with fallback
            const komandoLat = {{ $latitude ?? -0.0263 }};
            const komandoLng = {{ $longitude ?? 109.3425 }};
            const komandoLatLng = L.latLng(komandoLat, komandoLng);
    
            if (validatedData.length > 0) {
                console.log('👮 Processing', validatedData.length, 'valid petugas records');
                
                validatedData.forEach((p, index) => {
                    console.log(`👮 Petugas #${index + 1} - ${p.nama_petugas}:`, p.latitude, p.longitude);
                    
                    const lat = parseFloat(p.latitude);
                    const lng = parseFloat(p.longitude);
                    const petugasLatLng = L.latLng(lat, lng);
    
                    // Create custom marker with officer photo
                    const petugasIcon = L.divIcon({
                        html: `<div class="rounded-full border-2 border-white shadow-lg overflow-hidden" style="width: 40px; height: 40px;">
                            <img src="${p.foto ? `/storage/${p.foto}` : '{{ url('public/komando/assets/img/user/petugas.jpg') }}'}"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
                            onerror="this.src='{{ url('public/komando/assets/img/user/petugas.jpg') }}';">
                        </div>`,
                        iconSize: [40, 40],
                        iconAnchor: [20, 40],
                        popupAnchor: [0, -40],
                        className: `petugas-marker-${index}`
                    });
    
                    // Create detailed popup content
                    const now = new Date();
                    const popupContent = `
                        <div class="font-sans text-sm p-2 min-w-[200px]">
                            <div class="flex items-center mb-2">
                                <img src="${p.foto ? `/storage/${p.foto}` : '{{ url('public/komando/assets/img/user/petugas.jpg') }}'}"
                                    alt="Foto Petugas"
                                    class="w-16 h-16 rounded-full object-cover mr-3 border-2 border-gray-300"
                                    onerror="this.src='{{ url('public/komando/assets/img/user/petugas.jpg') }}';">
                                <div class="flex-1">
                                    <strong class="block text-base text-gray-800">${p.nama_petugas}</strong>
                                    <span class="text-xs text-gray-600">Seri: ${p.no_seri}</span>
                                </div>
                            </div>
                            <hr class="my-2 border-gray-200">
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Suhu:</span> 
                                    <span class="font-medium">${p.suhu || 'N/A'}&deg;C</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Udara:</span> 
                                    <span class="font-medium">${p.kualitas_udara || 'N/A'}</span>
                                </div>
                                <div class="col-span-2 flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="${p.status_color || 'text-gray-500'} font-bold">${p.status_text || 'N/A'}</span>
                                </div>
                                <div class="col-span-2 text-xs text-gray-400 text-center mt-2">
                                    <span>⏰ ${now.toLocaleTimeString('id-ID')}</span>
                                </div>
                            </div>
                        </div>
                    `;
    
                    // Add marker to map
                    const marker = L.marker(petugasLatLng, { icon: petugasIcon })
                        .addTo(map)
                        .bindPopup(popupContent, {
                            maxWidth: 250,
                            className: 'custom-popup'
                        });
                    markers.push(marker);
    
                    // Line from command center to petugas
                    const line = L.polyline([komandoLatLng, petugasLatLng], {
                        color: '#fbbf24', // Tailwind yellow-400
                        weight: 3,
                        opacity: 0.8,
                        dashArray: '5, 5',
                    }).addTo(map);
    
                    // Distance calculation and tooltip
                    const distance = komandoLatLng.distanceTo(petugasLatLng);
                    const midpointLat = (komandoLat + lat) / 2;
                    const midpointLng = (komandoLng + lng) / 2;
    
                    const tooltip = L.tooltip({
                        permanent: true,
                        direction: 'center',
                        className: 'leaflet-tooltip-distance bg-yellow-100 border border-yellow-300 text-yellow-800 px-2 py-1 rounded shadow-sm',
                        offset: [0, 0],
                    })
                        .setLatLng([midpointLat, midpointLng])
                        .setContent(`${(distance / 1000).toFixed(2)} km`)
                        .addTo(map);
    
                    lines.push(line);
                    lines.push(tooltip);
                });
    
                updateStatusIndicator('updated', `${validatedData.length} petugas aktif - ${new Date().toLocaleTimeString('id-ID')}`);
            } else {
                updateStatusIndicator('updated', 'Tidak ada petugas aktif');
            }
    
            // Store current data
            currentPetugasData = validatedData;
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
                
                let dataToUpdate = null;
                
                // Handle different event structures
                if (event && event[0] && event[0].data) {
                    // Event format: [{ data: [...], timestamp: ... }]
                    dataToUpdate = event[0].data;
                } else if (event && event.data) {
                    // Event format: { data: [...], timestamp: ... }
                    dataToUpdate = event.data;
                } else if (Array.isArray(event)) {
                    // Event format: [data...]
                    dataToUpdate = event;
                } else if (Array.isArray(event[0])) {
                    // Event format: [[data...]]
                    dataToUpdate = event[0];
                }
                
                if (dataToUpdate) {
                    console.log('Updating markers with event data:', dataToUpdate);
                    updateMarkers(dataToUpdate);
                } else {
                    console.warn('No valid data found in event:', event);
                }
            });
        });
    
        // Listen for Livewire updates (when wire:poll triggers)
        document.addEventListener('livewire:updated', function() {
            console.log('Livewire component updated');
            
            // Try to get updated data from the Livewire component
            try {
                if (window.Livewire && window.Livewire.all && window.Livewire.all().length > 0) {
                    // Try to find the Map component
                    const mapComponent = window.Livewire.all().find(component => 
                        component.el && component.el.closest('[wire\\:id]') && 
                        component.__instance && component.__instance.effects && 
                        component.__instance.effects.petugasInsidenData
                    );
                    
                    if (mapComponent && mapComponent.__instance.effects.petugasInsidenData) {
                        const newData = mapComponent.__instance.effects.petugasInsidenData;
                        if (Array.isArray(newData)) {
                            console.log('Updating from livewire:updated event');
                            updateMarkers(newData);
                        }
                    }
                }
            } catch (error) {
                console.warn('Could not access Livewire data:', error);
            }
        });
    
        // Backup: Periodic check for data changes
        setInterval(() => {
            try {
                if (window.Livewire && window.Livewire.all && window.Livewire.all().length > 0) {
                    // Try to find the Map component
                    const mapComponent = window.Livewire.all().find(component => 
                        component.el && component.el.closest('[wire\\:id]') && 
                        component.__instance && component.__instance.effects
                    );
                    
                    if (mapComponent && mapComponent.__instance.effects.petugasInsidenData && mapComponent.__instance.effects.lastUpdated) {
                        const currentData = mapComponent.__instance.effects.petugasInsidenData;
                        const currentTimestamp = mapComponent.__instance.effects.lastUpdated;
                        
                        if (currentTimestamp > lastUpdateTimestamp && Array.isArray(currentData)) {
                            console.log('Data changed detected via polling, updating markers...');
                            updateMarkers(currentData);
                            lastUpdateTimestamp = currentTimestamp;
                        }
                    }
                }
            } catch (error) {
                // Silently handle errors in polling
            }
        }, 2000); // Check every 2 seconds
    </script>
    
    <style>
        .leaflet-tooltip-distance {
            background-color: rgba(251, 191, 36, 0.9) !important;
            border: 1px solid #f59e0b !important;
            color: #92400e !important;
            font-weight: 600;
            font-size: 11px;
            border-radius: 4px;
            padding: 2px 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .custom-popup .leaflet-popup-content {
            margin: 8px !important;
        }
        
        .status-indicator {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .status-indicator.updating {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        .status-indicator.updated {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
    </style>
    @endpush
</div>