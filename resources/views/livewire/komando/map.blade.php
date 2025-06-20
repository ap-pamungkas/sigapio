@push('styles')
    <link rel="stylesheet" href="{{ url('public/leaflet/leaflet.css') }}">
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

        .leaflet-tooltip.distance-label {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            border: none;
        }
    </style>
@endpush

<div wire:ignore>
    <div id="map" wire:ignore style="height:500px; width:100%;"></div>
@push('scripts')
@once
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let map = L.map('map', {
        center: [{{ $latitude }}, {{ $longitude }}],
        zoom: 10
    });

    const openStreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    });

    const darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap, © CartoDB'
    });

    darkLayer.addTo(map);

    const baseLayers = {
        "🗺️ OpenStreet": openStreet,
        "🌙 Dark Mode": darkLayer
    };

    L.control.layers(baseLayers).addTo(map);

    const markers = [];
    const komandoLatLng = [{{ $latitude }}, {{ $longitude }}];

    const komandoIcon = L.icon({
        iconUrl: '{{ url("public/komando/icon/zona.svg") }}',
        iconSize: [46, 46],
        iconAnchor: [23, 46],
        popupAnchor: [0, -46]
    });

    const komandoMarker = L.marker(komandoLatLng, { icon: komandoIcon })
        .addTo(map)
        .bindPopup("📍 <strong>Pusat Komando</strong>");

    markers.push(komandoMarker);
    window.petugasLines = [];

    function updateMarkers(data) {
        markers.slice(1).forEach(marker => map.removeLayer(marker));
        markers.length = 1;

        window.petugasLines.forEach(item => {
            map.removeLayer(item.line);
            map.removeLayer(item.label);
        });
        window.petugasLines = [];

        data.forEach(p => {
            if (!p.latitude || !p.longitude) return;

            const petugasLatLng = [p.latitude, p.longitude];

            const petugasIcon = L.divIcon({
                html: `<div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                    <img src="${p.foto ? `/storage/${p.foto}` : '{{ url('public/komando/assets/img/user/petugas.jpg') }}'}"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40],
                className: ''
            });

            const distance = map.distance(komandoLatLng, petugasLatLng).toFixed(1);

            const popupContent = `
                <strong>${p.nama_petugas}</strong><br>
                Suhu: ${p.suhu}°C<br>
                Kualitas Udara: ${p.kualitas_udara}<br>
                Jarak ke Pusat Komando: ${distance} meter<br>
                Status: <span class="${p.status_color}">${p.status_text}</span>
            `;

            const marker = L.marker(petugasLatLng, { icon: petugasIcon })
                .addTo(map)
                .bindPopup(popupContent);

            markers.push(marker);

            // Tambahkan garis
            const line = L.polyline([komandoLatLng, petugasLatLng], {
                color: 'red',
                weight: 2,
                dashArray: '4,6'
            }).addTo(map);

            // Hitung posisi tengah garis
            const midLat = (komandoLatLng[0] + petugasLatLng[0]) / 2;
            const midLng = (komandoLatLng[1] + petugasLatLng[1]) / 2;

            const label = L.tooltip({
                permanent: true,
                direction: 'center',
                className: 'distance-label',
                offset: [0, 0]
            })
            .setContent(`${distance} meter`)
            .setLatLng([midLat, midLng])
            .addTo(map);

            window.petugasLines.push({ line, label });
        });
    }

    updateMarkers(@json($petugasInsidenData));

    Livewire.on('petugasDataUpdated', (data) => {
        updateMarkers(data);
    });
});
</script>


@endonce
@endpush

</div>
