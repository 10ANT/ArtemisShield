<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PulsePoint Command - Historical Fire Map</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>

    <!-- MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    
    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    @include('partials.styles')
    <style>
        .main-content { flex-grow: 1; }
        .map-container { height: 100%; }
        #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); }
        .map-wrapper { position: relative; height: 100%; overflow: hidden; }
        
        /* Loading overlay styles */
        #map-loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001; /* Above map tiles */
            background: rgba(var(--bs-dark-rgb), 0.8);
            color: white;
            padding: 20px 30px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.2rem;
        }

        .leaflet-popup-content-wrapper, .leaflet-popup-tip {
            background: var(--bs-tertiary-bg) !important;
            color: var(--bs-body-color) !important;
            box-shadow: 0 3px 14px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
<div class="main-content d-flex flex-column">
    @include('partials.header')
    <div class="map-container row g-0 flex-grow-1">
        <div class="col-12">
            <div class="map-wrapper">
                <div id="map"></div>
                <div id="map-loader">
                    <div class="spinner-border text-light mb-3" role="status"></div>
                    <p>Loading historical fire data...</p>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</div>

@include('partials.theme_settings')
@include('partials.scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map;
    let baseMaps;
    let drawnItems = L.featureGroup();
    const loader = document.getElementById('map-loader');

    const initMap = () => {
        // Define Base Maps
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
        const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
        const darkMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
        
        baseMaps = { 
            "Streets": streetMap, 
            "Satellite": satelliteMap, 
            "Dark Mode": darkMap
        };
        
        // Initialize Map
        map = L.map('map', { 
            center: [39.82, -98.57], // Center of the USA
            zoom: 4, 
            layers: [darkMap] // Default layer
        });

        map.addLayer(drawnItems);
        
        // Add Controls
        L.control.layers(baseMaps).addTo(map);
        map.addControl(new L.Control.Draw({
            edit: { featureGroup: drawnItems },
            draw: {
                polygon: true, marker: true, circlemarker: true,
                rectangle: true, circle: true, polyline: true
            }
        }));
        map.on(L.Draw.Event.CREATED, (e) => drawnItems.addLayer(e.layer));

        // Fetch and plot the historical fire data
        fetchHistoricalFires();
    };

    const fetchHistoricalFires = async () => {
        try {
            const response = await fetch("{{ route('api.historical.fires') }}");
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            const fires = await response.json();
            
            plotFires(fires);

        } catch (error) {
            console.error('Failed to fetch historical fire data:', error);
            loader.innerHTML = `<p class="text-danger">Error loading fire data.<br>Please check the console and try again.</p>`;
        }
    };

    const plotFires = (fires) => {
        // Use MarkerCluster for performance with thousands of points
        const fireMarkers = L.markerClusterGroup({
            chunkedLoading: true, // Optimizes adding lots of markers
            maxClusterRadius: 60,
            iconCreateFunction: function(cluster) {
                return L.divIcon({
                    html: `<b>${cluster.getChildCount()}</b>`,
                    className: 'marker-cluster marker-cluster-small',
                    iconSize: new L.Point(40, 40)
                });
            }
        });

        fires.forEach(fire => {
            // Format time for readability (e.g., 0333 -> 03:33)
            const time = fire.acq_time.padStart(4, '0');
            const formattedTime = time.substring(0, 2) + ':' + time.substring(2, 4);

            // Create the popup content
            const popupContent = `
                <h5>Historical Fire</h5>
                <b>Date:</b> ${fire.acq_date}<br>
                <b>Time:</b> ${formattedTime} UTC<br>
                <b>Confidence:</b> ${fire.confidence}%<br>
                <hr>
                <b>Latitude:</b> ${fire.latitude}<br>
                <b>Longitude:</b> ${fire.longitude}<br>
                <b>Brightness (K):</b> ${fire.brightness}<br>
                <b>FRP (MW):</b> ${fire.frp}<br>
                <b>Satellite:</b> ${fire.satellite}
            `;

            // CircleMarkers are better than Markers for performance with dense data
            const marker = L.circleMarker([fire.latitude, fire.longitude], {
                radius: 4,
                color: 'orange',
                weight: 1,
                fillColor: '#FF4500', // Firebrick color
                fillOpacity: 0.7
            }).bindPopup(popupContent);

            fireMarkers.addLayer(marker);
        });

        // Add the cluster layer to the map
        map.addLayer(fireMarkers);
        
        // Hide the loader once data is plotted
        loader.style.display = 'none';
    };

    // Initialize the map on page load
    initMap();
});
</script>
</body>
</html>