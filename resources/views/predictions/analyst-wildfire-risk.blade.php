<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PulsePoint Command - Live Fire Analysis (Ambee)</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @include('partials.styles')
    <style>
        .main-content { flex-grow: 1; }
        .risk-dashboard-container { height: 100%; }
        #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); cursor: crosshair !important; }
        .map-wrapper { position: relative; height: 100%; overflow: hidden; }
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
        .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
        .fire-card { border-left-width: 4px; }
    </style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
<div class="main-content d-flex flex-column">
    
    @include('partials.header')

    <div class="risk-dashboard-container row g-0 flex-grow-1">
        <div class="col-lg-8 col-md-12" id="map-column">
            <div class="map-wrapper">
                <div id="map"></div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 border-start" id="right-sidebar-column">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-fill flex-nowrap" id="sidebar-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="instructions-tab-btn" data-bs-toggle="pill" data-bs-target="#instructions-content" type="button" role="tab" aria-controls="instructions-content" aria-selected="true" title="Instructions"><i class="fas fa-info-circle me-1"></i> Info</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="report-tab-btn" data-bs-toggle="pill" data-bs-target="#report-content" type="button" role="tab" aria-controls="report-content" aria-selected="false" title="Fire Report"><i class="fas fa-fire me-1"></i> Fire Report</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="settings-tab-btn" data-bs-toggle="pill" data-bs-target="#settings-content" type="button" role="tab" aria-controls="settings-content" aria-selected="false" title="Map Settings"><i class="fas fa-cogs me-1"></i> Settings</button></li>
                    </ul>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="tab-content h-100">
                        
                        <div class="tab-pane fade show active p-3" id="instructions-content" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-bullseye me-2"></i>Live Fire Data</h5>
                            <div class="alert alert-info">
                                <h6 class="alert-heading">How to Use</h6>
                                <p>Click anywhere on the map to query the Ambee API for the latest fire incidents near that point.</p>
                                <p class="mb-0">The map will update with markers for each fire, and details will appear in the "Fire Report" tab.</p>
                            </div>
                        </div>

                        <div class="tab-pane fade p-3" id="report-content" role="tabpanel">
                             <div id="report-placeholder" class="text-center text-muted mt-4">
                                <i class="fas fa-mouse-pointer fa-2x mb-2"></i>
                                <p>Click the map to get the latest fire report.</p>
                            </div>
                            <div id="report-container" class="d-flex flex-column gap-3"></div>
                        </div>

                        <div class="tab-pane fade p-3" id="settings-content" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-map me-2"></i>Base Maps</h5>
                            <div id="basemap-selector-container"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</div>
</div>

@include('partials.theme_settings')
@include('partials.scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    let map;
    let baseMaps;
    let clickMarker = null;
    let fireMarkersLayer = L.layerGroup();

    const initMap = () => {
        const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
        const darkMode = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
        
        baseMaps = { "Streets": streets, "Dark Mode": darkMode, "Satellite": satellite };
        
        map = L.map('map', { center: [34.05, -118.25], zoom: 9, layers: [darkMode] });

        const basemapContainer = document.getElementById('basemap-selector-container');
        Object.keys(baseMaps).forEach((name) => {
            const id = `basemap-radio-${name.replace(/\s+/g, '-')}`;
            const isChecked = (name === "Dark Mode") ? 'checked' : '';
            basemapContainer.innerHTML += `<div class="form-check"><input class="form-check-input" type="radio" name="basemap-selector" id="${id}" value="${name}" ${isChecked}><label class="form-check-label" for="${id}">${name}</label></div>`;
        });
        basemapContainer.addEventListener('change', (e) => map.eachLayer(layer => { if (baseMaps[e.target.value] !== layer && Object.values(baseMaps).includes(layer)) map.removeLayer(layer); map.addLayer(baseMaps[e.target.value]); }));

        map.addLayer(fireMarkersLayer);
        map.on('click', handleMapClick);
    };
    
    const handleMapClick = async (e) => {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        const reportPlaceholder = document.getElementById('report-placeholder');
        const reportContainer = document.getElementById('report-container');

        new bootstrap.Tab(document.getElementById('report-tab-btn')).show();
        reportPlaceholder.innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Fetching fire data...</p>';
        reportContainer.innerHTML = '';
        
        if (clickMarker) map.removeLayer(clickMarker);
        clickMarker = L.marker([lat, lng], { opacity: 0.7 }).addTo(map).bindPopup('Querying from here...').openPopup();

        const apiUrl = `/api/ambee/fire-data?lat=${lat}&lng=${lng}`;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (!response.ok) throw new Error(data.error || 'Request failed');
            
            if (data.message === 'success' && data.data.length > 0) {
                displayFireData(data.data);
            } else {
                reportPlaceholder.innerHTML = '<div class="alert alert-success">No active fires found near this location.</div>';
            }
        } catch (error) {
            console.error("API Fetch Error:", error);
            reportPlaceholder.innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${error.message}</div>`;
        }
    };

    const displayFireData = (fires) => {
        const reportPlaceholder = document.getElementById('report-placeholder');
        const reportContainer = document.getElementById('report-container');
        reportPlaceholder.innerHTML = '';
        fireMarkersLayer.clearLayers();

        fires.forEach(fire => {
            // Add marker to map
            const fireIcon = L.divIcon({ className: 'text-danger', html: '<i class="fas fa-fire-alt fa-2x"></i>', iconSize: [24, 24] });
            L.marker([fire.lat, fire.lng], { icon: fireIcon })
                .addTo(fireMarkersLayer)
                .bindPopup(`<b>${fire.fireName || 'Unnamed Fire'}</b><br>Category: ${fire.fireCategory}`);

            // Create card in sidebar
            const fwi = fire.fwi ? parseFloat(fire.fwi).toFixed(1) : 'N/A';
            let fwiColor = 'secondary';
            if (fwi >= 40) fwiColor = 'danger';
            else if (fwi >= 20) fwiColor = 'warning';
            else if (fwi > 0) fwiColor = 'success';

            const detectedDate = new Date(fire.detectedAt).toLocaleString();

            const cardHtml = `
                <div class="card bg-body-tertiary fire-card border-start border-${fwiColor}">
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1">${fire.fireName || 'Unnamed Fire'}</h6>
                        <ul class="list-unstyled small mb-0">
                            <li><strong>Detected:</strong> ${detectedDate}</li>
                            <li><strong>Category:</strong> ${fire.fireCategory || 'N/A'} <span class="badge text-bg-${fwiColor} float-end">FWI: ${fwi}</span></li>
                            <li><strong>Type:</strong> ${fire.fireType || 'N/A'}</li>
                        </ul>
                    </div>
                </div>`;
            reportContainer.innerHTML += cardHtml;
        });
    };
    
    initMap();
});
</script>
</body>
</html>