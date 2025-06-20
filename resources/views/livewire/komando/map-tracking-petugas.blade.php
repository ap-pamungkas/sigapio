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

    .leaflet-popup-content .text-green-500 { color: #85e085 !important; }
    .leaflet-popup-content .text-red-500 { color: #ff8080 !important; }
    .leaflet-popup-content .text-yellow-500 { color: #ffe680 !important; }

</style>
@endpush

<div>


    <div id="map" style="height: 80vh; border-radius: 12px;"></div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            let map;
            let markers = [];
            let isMapInitialized = false;

            function initMap() {
                if (!map) {
                    map = L.map('map').setView([{{ $latitude }}, {{ $longitude }}], 6);

                     L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap, © CartoDB'
    }).addTo(map);


                    console.log(L.tileLayer('{{ url("public/tiles/Mapnik/{z}/{x}/{y}.png") }}') );

                    // Add a marker at the setView location
                    const pontianakIcon = L.icon({
                        iconUrl: '{{ url("public/komando/icon/zona.svg") }}', // Replace with your desired icon URL
                        iconSize: [46, 46],
                        iconAnchor: [16, 32],
                        popupAnchor: [0, -32]
                    });

                    L.marker([{{ $latitude }}, {{ $longitude }}], { icon: pontianakIcon })
                        .addTo(map)
                        .bindPopup("Pusat komando"); // Popup content

                    isMapInitialized = true;
                }
            }

            function updateMarkers(petugasData) {
                if (!isMapInitialized) return;

                markers.forEach(marker => map.removeLayer(marker));
                markers = [];

                petugasData.forEach(p => {
                    const lat = parseFloat(p.latitude);
                    const lng = parseFloat(p.longitude);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const petugasIcon = L.divIcon({
                            html: `<div class="rounded-full border-2 border-white shadow-lg overflow-hidden" style="width: 40px; height: 40px;">
                                <img src="${p.foto ? `/storage/${p.foto}` : '{{ url('public/komando/assets/img/user/petugas.jpg') }}'}"
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>`,
                            iconSize: [40, 40],
                            iconAnchor: [20, 40],
                            popupAnchor: [0, -40],
                            className: ''
                        });

                        const popupContent = `
                            <div class="font-sans text-sm p-2">
                                <div class="flex items-center mb-2">
                                    <img src="${p.foto ? `/storage/${p.foto}` : '{{ url('public/komando/assets/img/user/petugas.jpg') }}'}"
                                        alt="Foto Petugas"
                                        width="90%"
                                        class=" text-theme py-2 px-2  shadow-md" style="border-radius: 50%;">
                                    <div>
                                        <hr>
                                        <strong class="block text-base">${p.nama_petugas}</strong>
                                        <span>No Seri: ${p.no_seri}</span>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span>Suhu:</span> ${p.suhu}&deg;C
                                    </div>
                                    <div>
                                        <span>Kualitas Udara:</span> ${p.kualitas_udara}
                                    </div>
                                    <div class="col-span-2">
                                        <span>Status:</span>
                                        <span class="${p.status_color || ''} font-bold">${p.status_text}</span>
                                    </div>
                                </div>
                            </div>
                        `;

                        const marker = L.marker([lat, lng], {
                                icon: petugasIcon
                            })
                            .addTo(map)
                            .bindPopup(popupContent);
                        markers.push(marker);
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    initMap();
                    updateMarkers(@json($petugasInsidenData));
                }, 1000);

                Livewire.on('petugasDataUpdated', (data) => {
                    updateMarkers(data);
                });
            });

            window.addEventListener('livewire:load', function() {
                setInterval(() => {
                    @this.call('refreshData');
                }, 5000);

                @this.on('refreshMap', () => {
                    updateMarkers(@json($petugasInsidenData));
                });
            });
        </script>
    @endpush
</div>
