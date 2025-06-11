<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ArtemisShield - Wildfire Protection Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link href="https://cesium.com/downloads/cesiumjs/releases/1.117/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="{{ asset('css/leaflet-velocity.css') }}" />

    @include('partials.styles')
    <style>
        :root { --legend-width: 280px; --right-sidebar-width: 25%; }
        html, body { height: 100%; overflow: hidden; }
        .main-content { height: calc(100vh - 58px); transition: height 0.3s ease-in-out; }
        .wildfire-dashboard-container { height: 100%; }
        .map-column { height: 100%; position: relative; transition: width 0.3s ease-in-out; }
        .map-column.fullscreen { width: 100% !important; }
        .right-sidebar-column { width: var(--right-sidebar-width); max-width: 400px; }
        .right-sidebar-column.d-none { display: none !important; }
        #map-wrapper, #map, #cesium-container { position: absolute; top:0; left:0; height: 100%; width: 100%; margin: 0; padding: 0; }
        #cesium-container { z-index: 0; }
        #map { z-index: 1; background: transparent; }
        #layers-sidebar { position: absolute; top: 15px; left: 15px; width: var(--legend-width); min-width: 220px; max-width: 500px; z-index: 1001; background-color: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: .5rem; max-height: calc(100vh - 100px); display: flex; flex-direction: column; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); resize: both; overflow: auto; }
        #layers-sidebar-header { cursor: move; flex-shrink: 0; }
        #layers-sidebar-content { overflow-y: auto; flex-grow: 1; transition: all 0.2s ease-out; max-height: 500px; }
        #layers-sidebar.collapsed #layers-sidebar-content { max-height: 0; padding-top: 0 !important; padding-bottom: 0 !important; opacity: 0; }
        .legend-item { display: flex; align-items: center; font-size: 0.9rem; }
        .legend-icon { width: 30px; margin-right: 10px; text-align: center; }
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
        .sidebar-wrapper .tab-content, .chat-messages, #routes-content { flex-grow: 1; overflow-y: auto; }
        .cesium-viewer-bottom { display: none !important; }
        #map-controls { position: absolute; top: 80px; right: 10px; z-index: 1001; display: flex; flex-direction: column; gap: 5px; }
        .leaflet-routing-container { display: none !important; }
        #recent-fires .card:hover { background-color: var(--bs-body-tertiary); cursor: pointer; }
        .frp-legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; border: 1px solid #666;}
        .leaflet-control-geocoder-form input { color: var(--bs-body-color); }
        .leaflet-control-geocoder-result-name { display: flex; align-items: center; }
        .weather-popup .card-body { padding: 0.75rem; }
        .weather-popup .weather-main { display: flex; align-items: center; justify-content: space-between; }
        .weather-popup .weather-main h4 { margin: 0; }
        .weather-popup .weather-details { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.9rem; }
        #goes-preview { position: fixed; z-index: 1002; background: rgba(0,0,0,0.7); border: 2px solid #fff; border-radius: 5px; pointer-events: none; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 5px; }
        #goes-preview img { width: 300px; height: 300px; }
        #goes-preview p { color: white; margin: 5px 0 0 0; font-size: 0.8em; text-align: center; }
        #goes-fire-temp-btn.active, #toggle-contained-btn.active { background-color: var(--bs-primary); border-color: var(--bs-primary); }
        .map-container.goes-preview-active { cursor: crosshair; }
        #zoomed-goes-modal .modal-dialog { max-width: 90vw; }
        #zoomed-goes-modal .modal-content { background-color: rgba(10, 10, 10, 0.85); backdrop-filter: blur(5px); border: 1px solid #555; }
        #zoomed-goes-image-container { position: relative; cursor: crosshair; }
        #zoomed-goes-image { width: 100%; height: auto; max-height: 80vh; object-fit: contain; }
        #magnifier-loupe { width: 200px; height: 200px; position: absolute; border: 3px solid #fff; border-radius: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.5); pointer-events: none; display: none; background-repeat: no-repeat; }
        #timeline-container { position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); width: 70%; max-width: 800px; z-index: 1001; background: rgba(var(--bs-body-bg-rgb), 0.85); backdrop-filter: blur(4px); border: 1px solid var(--bs-border-color); border-radius: .5rem; padding: 10px 20px; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.2); display: flex; align-items: center; gap: 15px; }
        #timeline-label { font-size: 0.9em; font-weight: bold; white-space: nowrap; min-width: 100px; text-align: center; color: var(--bs-body-color); }
        #timeline-slider { flex-grow: 1; }
    </style>
</head>

<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            @include('partials.header')
            <div class="wildfire-dashboard-container row g-0 flex-grow-1">
                <div class="col map-column">
                    <div id="map-wrapper">
                        <div id="cesium-container" class="d-none"></div>
                        <div id="map"></div>
                        <div id="map-controls" style="margin-top: 23px">
                            <button id="toggle-3d-btn" class="btn btn-secondary" title="Toggle 3D View"><i class="fas fa-cube"></i> 3D</button>
                            <button id="expand-map-btn" class="btn btn-secondary" title="Toggle Fullscreen Map"><i class="fas fa-expand-arrows-alt"></i></button>
                            <button id="get-weather-btn" class="btn btn-secondary" title="Get Weather for a Point"><i class="fas fa-cloud-sun-rain"></i></button>
                            <button id="goes-fire-temp-btn" class="btn btn-secondary" title="Toggle GOES Fire Temp Preview"><i class="fas fa-fire-alt"></i></button>
                            <!-- MODIFIED: Changed button ID, title, icon, and text -->
                            <button id="toggle-contained-btn" class="btn btn-secondary" title="Hide Contained & Out Fires"><i class="fas fa-shield-alt"></i></button>
                        </div>
                        <div id="layers-sidebar">
                            <div id="layers-sidebar-header" class="card-header d-flex justify-content-between align-items-center p-2">
                                <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>Layers & Legend</h6>
                                <div><div id="main-loader" class="spinner-border spinner-border-sm me-2 d-none" role="status"></div><button id="sidebar-toggle" class="btn btn-sm btn-secondary py-0 px-1"><i class="fas fa-chevron-up"></i></button></div>
                            </div>
                            <div id="layers-sidebar-content" class="p-3">
                                <div class="mb-3">
                                    <h6>Official Incidents</h6>
                                    <div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="official-perimeters" checked><label class="form-check-label" for="official-perimeters">Perimeters & Points</label></div>
                                    <div class="ps-2"><div class="legend-item"><span class="legend-icon"><i class="fas fa-circle text-danger"></i></span>< 24h (by Size)</div><div class="legend-item"><span class="legend-icon"><i class="fas fa-circle text-warning"></i></span>< 3d (by Size)</div><div class="legend-item"><span class="legend-icon"><i class="fas fa-circle text-info"></i></span>> 3d (by Size)</div></div>
                                    <div class="mt-2">
                                        <label for="discovery-date-filter" class="form-label small">Show fires discovered on or after</label>
                                        <div class="input-group input-group-sm"><input type="date" id="discovery-date-filter" class="form-control form-control-sm"><button id="apply-date-filter" class="btn btn-outline-secondary" type="button" title="Apply Filter"><i class="fas fa-check"></i></button><button id="clear-date-filter" class="btn btn-outline-secondary" type="button" title="Clear Filter"><i class="fas fa-times"></i></button></div>
                                    </div>
                                </div>
                                <hr class="my-2"><div class="mb-3"><h6>Weather Overlays</h6><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="weather-precipitation"><label class="form-check-label legend-item" for="weather-precipitation"><span class="legend-icon"><i class="fas fa-cloud-showers-heavy"></i></span>Precipitation</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="weather-temp"><label class="form-check-label legend-item" for="weather-temp"><span class="legend-icon"><i class="fas fa-temperature-high"></i></span>Temperature</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="weather-wind"><label class="form-check-label legend-item" for="weather-wind"><span class="legend-icon"><i class="fas fa-compass"></i></span>Static Wind</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="animated-wind"><label class="form-check-label legend-item" for="animated-wind"><span class="legend-icon"><i class="fas fa-wind"></i></span>Animated Wind</label></div></div>
                                <hr class="my-2"><div class="mb-3"><h6>Map Overlays</h6><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="state-boundaries"><label class="form-check-label legend-item" for="state-boundaries"><span class="legend-icon"><i class="fas fa-border-all"></i></span>State Boundaries</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="drought-layer"><label class="form-check-label legend-item" for="drought-layer"><span class="legend-icon"><div style="width: 15px; height: 15px; background: rgba(255, 255, 0, 0.5); border: 1px solid #ccc;"></div></span>Abnormally Dry</label></div></div>
                                <hr class="my-2"><div class="mb-3"><h6>Live Imagery</h6><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="goes-imagery"><label class="form-check-label legend-item" for="goes-imagery"><span class="legend-icon"><i class="fas fa-satellite"></i></span>GOES Satellite</label></div></div>
                                <hr class="my-2">
                                <div class="mb-3">
                                    <h6>Satellite Hotspots (by FRP)</h6>
                                    <div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="viirs-hotspots" data-source="VIIRS" checked><label class="form-check-label legend-item" for="viirs-hotspots"><span class="legend-icon"><i class="fas fa-satellite-dish text-primary"></i></span>VIIRS Hotspots</label></div>
                                    <div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="modis-hotspots" data-source="MODIS" checked><label class="form-check-label legend-item" for="modis-hotspots"><span class="legend-icon"><i class="fas fa-satellite-dish text-success"></i></span>MODIS Hotspots</label></div>
                                    <div class="d-flex align-items-center justify-content-around small text-muted mt-1 px-2"><span>Low</span><span class="frp-legend-dot" style="background-color: #ffff00;"></span><span class="frp-legend-dot" style="background-color: #ffaa00;"></span><span class="frp-legend-dot" style="background-color: #ff4500;"></span><span class="frp-legend-dot" style="background-color: #d40202;"></span><span>High</span></div>
                                </div>
                            </div>
                        </div>
                        <div id="timeline-container"><label for="timeline-slider" id="timeline-label">Current</label><input type="range" min="0" max="90" value="0" class="form-range" id="timeline-slider"></div>
                    </div>
                </div>
                <!-- Right Sidebar Column -->
                <div class="col-lg-3 col-md-4 border-start right-sidebar-column">
                    <div class="sidebar-wrapper">
                        <div class="card-header p-2"><ul class="nav nav-pills nav-fill" id="sidebar-tabs" role="tablist"><li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#chat-content" type="button" role="tab"><i class="fas fa-comments me-1"></i> Ask</button></li><li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#routes-content" type="button" role="tab"><i class="fas fa-route me-1"></i> Routes</button></li><li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#control-content" type="button" role="tab"><i class="fas fa-cogs me-1"></i> Data</button></li></ul></div>
                        <div class="card-body d-flex flex-column p-0"><div class="tab-content h-100"><div class="tab-pane fade p-3 h-100" id="chat-content" role="tabpanel"><div class="chat-container"><div class="chat-messages mb-3" id="chat-messages"></div><div class="chat-input-group d-flex gap-2"><input type="text" class="form-control" placeholder="Ask a question..." id="chat-input"><button class="btn btn-primary" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button></div></div></div>
                        <div class="tab-pane fade show active p-3 d-flex flex-column" id="routes-content" role="tabpanel">
                            <div>
                                <h6 class="text-body-secondary">Route Planner</h6>
                                <p class="small text-muted">Use the marker tool (<i class="fas fa-map-marker-alt"></i>) on the map to place a start and end point.</p>
                                <div class="mb-3"><label for="route-name" class="form-label small">Route Name</label><input type="text" class="form-control" id="route-name" placeholder="e.g., Evacuation Route A"></div>
                                <div class="d-grid gap-2"><button class="btn btn-primary" id="calculate-route-btn" disabled><i class="fas fa-calculator me-2"></i>Calculate & Save Route</button><button class="btn btn-secondary" id="clear-markers-btn"><i class="fas fa-times me-2"></i>Clear Markers</button></div>
                                <hr>
                            </div>
                            <div class="flex-grow-1" style="overflow-y: auto;">
                                <h6 class="text-body-secondary">Saved Routes</h6>
                                <div class="input-group input-group-sm mb-2"><span class="input-group-text" id="route-search-addon"><i class="fas fa-search"></i></span><input type="text" id="route-search-input" class="form-control" placeholder="Search saved routes..." aria-label="Search saved routes" aria-describedby="route-search-addon"></div>
                                <div id="saved-routes-list-container"><ul class="list-group list-group-flush" id="saved-routes-list"></ul></div>
                            </div>
                        </div>
                        <div class="tab-pane fade p-3 d-flex flex-column" id="control-content" role="tabpanel">
                             <h6 class="text-body-secondary">Live Data</h6>
                             <ul class="list-group mb-3"><li class="list-group-item d-flex justify-content-between align-items-center">Satellite Detections <span class="badge text-bg-danger" id="active-fires-count">--</span></li><li class="list-group-item d-flex justify-content-between align-items-center">High Confidence <span class="badge text-bg-warning" id="high-confidence-count">--</span></li></ul>
                             <h6 class="text-body-secondary">Recent Detections (< 3 hours)</h6>
                             <div id="recent-fires" class="flex-grow-1" style="overflow-y: auto;"></div>
                        </div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="fire-details-modal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="fire-details-modal-title"></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="fire-details-modal-body"></div></div></div></div>
    <div id="goes-preview"><img id="goes-preview-img" src="" alt="GOES Preview"><p id="goes-preview-label">Move mouse over map</p></div>
    <div class="modal fade" id="zoomed-goes-modal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-xl"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="zoomed-goes-modal-title">Fire Temperature</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-center" id="zoomed-goes-image-container"><img id="zoomed-goes-image" src="" alt="Zoomed GOES Fire Temperature Image"><div id="magnifier-loupe"></div></div></div></div></div>

    @include('partials.scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://cesium.com/downloads/cesiumjs/releases/1.117/Build/Cesium/Cesium.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.min.js"></script>
    <script src="{{ asset('js/leaflet-velocity.js') }}"></script>
    
    <script>
        const OWM_API_KEY = "{{ config('services.openweather.api_key', 'YOUR_FALLBACK_KEY') }}";
        Cesium.Ion.defaultAccessToken = "{{ config('services.cesium.ion_access_token', 'YOUR_FALLBACK_KEY') }}";
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let map, fireDetailsModal, weatherMarkerDrawer, drawnItems;
        const fireLayerGroups = { 'VIIRS': L.layerGroup(), 'MODIS': L.layerGroup() };
        const fireDataCache = { 'VIIRS': [], 'MODIS': [] };
        let officialPerimetersLayer, stateBoundariesLayer, droughtLayer, weatherPrecipLayer, weatherTempLayer, staticWeatherWindLayer, animatedWindLayer, goesLayer, weatherPointMarker;
        let cesiumViewer, is3D = false;
        let startMarker = null, endMarker = null, currentRouteControl = null, savedRoutesLayer = null;
        let isGoesPreviewActive = false;
        let goesPreviewContainer, goesPreviewImg, goesPreviewLabel, zoomedGoesModal, zoomedGoesImage, zoomedGoesTitle, magnifierLoupe, zoomedGoesContainer;
        let goesImageRequestTimer = null;
        let lastValidGoesUrl = '';
        let timelineSlider, timelineLabel, selectedDate = null;
        let hideContainedFires = false; // State for the new "Hide Contained" button
        const NOAA_SECTORS = { 'conus':{ satellite: 'GOES19', name: 'CONUS', bounds: L.latLngBounds([[24, -125], [50, -67]]) }, 'sp':   { satellite: 'GOES19', name: 'Southern Plains', bounds: L.latLngBounds([[25, -107], [40, -92]]) }, 'se':   { satellite: 'GOES19', name: 'Southeast', bounds: L.latLngBounds([[24, -92], [37, -75]]) }, 'sr':   { satellite: 'GOES19', name: 'Southern Rockies', bounds: L.latLngBounds([[31, -114], [42, -102]]) }, 'nr':   { satellite: 'GOES19', name: 'Northern Rockies', bounds: L.latLngBounds([[41, -117], [50, -103]]) }, 'umv':  { satellite: 'GOES19', name: 'Upper Mississippi Valley', bounds: L.latLngBounds([[39, -98], [48, -86]]) }, 'gl':   { satellite: 'GOES19', name: 'Great Lakes', bounds: L.latLngBounds([[41, -92], [49, -76]]) }, 'ne':   { satellite: 'GOES19', name: 'Northeast', bounds: L.latLngBounds([[39, -83], [48, -67]]) }, 'pr':   { satellite: 'GOES19', name: 'Puerto Rico', bounds: L.latLngBounds([[17, -68], [19, -65]]) }, 'wus':  { satellite: 'GOES18', name: 'West US', bounds: L.latLngBounds([[31, -125], [49, -102]]) }, 'psw':  { satellite: 'GOES18', name: 'Pacific Southwest', bounds: L.latLngBounds([[32, -124], [43, -114]]) }, 'pnw':  { satellite: 'GOES18', name: 'Pacific Northwest', bounds: L.latLngBounds([[42, -125], [49, -116]]) }, 'ak':   { satellite: 'GOES18', name: 'Alaska', bounds: L.latLngBounds([[51, -179], [72, -129]]) }, 'hi':   { satellite: 'GOES18', name: 'Hawaii', bounds: L.latLngBounds([[18, -161], [23, -154]]) }, };

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                initializeMap();
                fireDetailsModal = new bootstrap.Modal(document.getElementById('fire-details-modal'));
                goesPreviewContainer = document.getElementById('goes-preview'); goesPreviewImg = document.getElementById('goes-preview-img'); goesPreviewLabel = document.getElementById('goes-preview-label');
                zoomedGoesModal = new bootstrap.Modal(document.getElementById('zoomed-goes-modal')); zoomedGoesContainer = document.getElementById('zoomed-goes-image-container'); zoomedGoesImage = document.getElementById('zoomed-goes-image'); zoomedGoesTitle = document.getElementById('zoomed-goes-modal-title'); magnifierLoupe = document.getElementById('magnifier-loupe');
                timelineSlider = document.getElementById('timeline-slider'); timelineLabel = document.getElementById('timeline-label');
                initializeTimeline();
                setupEventListeners();
                initializeMagnifier();
                loadInitialData();
                fetchAndDisplaySavedRoutes();
            }, 250);
        });

        function initializeMap() {
            const streets = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
            map = L.map('map', { center: [39.8283, -98.5795], zoom: 5, layers: [streets] });
            map.createPane('goesPreviewPane').style.zIndex = 450; map.createPane('goesLayerPane').style.zIndex = 250;
            L.control.layers({ "Streets": streets, "Satellite": satellite }, null, { position: 'topright' }).addTo(map);
            L.control.scale({ imperial: false }).addTo(map);
            drawnItems = new L.FeatureGroup().addTo(map);
            new L.Control.Draw({ position: 'topleft', edit: { featureGroup: drawnItems }, draw: { polyline: true, polygon: true, circle: true, rectangle: true, marker: { tooltip: { start: 'Click map to place start/end point for routing.' } } } }).addTo(map);
            officialPerimetersLayer = L.layerGroup(); stateBoundariesLayer = L.layerGroup(); droughtLayer = L.layerGroup(); savedRoutesLayer = L.layerGroup().addTo(map);
            weatherPrecipLayer = L.tileLayer(`https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid=${OWM_API_KEY}`);
            weatherTempLayer = L.tileLayer(`https://tile.openweathermap.org/map/temp_new/{z}/{x}/{y}.png?appid=${OWM_API_KEY}`);
            staticWeatherWindLayer = L.tileLayer(`https://tile.openweathermap.org/map/wind_new/{z}/{x}/{y}.png?appid=${OWM_API_KEY}`);
            weatherMarkerDrawer = new L.Draw.Marker(map, { icon: L.divIcon({ className: 'leaflet-draw-icon', html: '<i class="fas fa-cloud-sun-rain fa-2x text-info"></i>', iconSize: [32,32] }) });
            initializeFireSearch();
        }
        
        function initializeTimeline() { const today = new Date(); today.setUTCHours(0, 0, 0, 0); selectedDate = today.toISOString().split('T')[0]; timelineSlider.max = 90; timelineSlider.value = 0; timelineLabel.textContent = 'Current'; timelineSlider.addEventListener('input', handleTimelineInput); timelineSlider.addEventListener('change', handleTimelineChange); }
        function handleTimelineInput() { const daysAgo = parseInt(timelineSlider.value, 10); const targetDate = new Date(); targetDate.setUTCDate(targetDate.getUTCDate() - daysAgo); selectedDate = targetDate.toISOString().split('T')[0]; if (daysAgo === 0) { timelineLabel.textContent = 'Current'; } else if (daysAgo === 1) { timelineLabel.textContent = 'Yesterday'; } else { timelineLabel.textContent = targetDate.toLocaleDateString('en-CA', { year: 'numeric', month: 'short', day: 'numeric' }); } }
        function handleTimelineChange() { if (document.getElementById('viirs-hotspots').checked) { loadFireData('VIIRS'); } if (document.getElementById('modis-hotspots').checked) { loadFireData('MODIS'); } }

        function setupEventListeners() {
            makeDraggable(document.getElementById('layers-sidebar'), document.getElementById('layers-sidebar-header'));
            document.getElementById('sidebar-toggle').addEventListener('click', (e) => { document.getElementById('layers-sidebar').classList.toggle('collapsed'); e.currentTarget.querySelector('i').className = document.getElementById('layers-sidebar').classList.contains('collapsed') ? 'fas fa-chevron-down' : 'fas fa-chevron-up'; });
            document.querySelectorAll('.layer-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    if (this.dataset.source === 'VIIRS' || this.dataset.source === 'MODIS') { const source = this.dataset.source; if (this.checked) { map.addLayer(fireLayerGroups[source]); loadFireData(source); } else { map.removeLayer(fireLayerGroups[source]); fireDataCache[source] = []; updateFireLayer(source, []); updateAllFireStats(); if (is3D) synchronizeLayersToCesium(); }
                    } else {
                        switch(this.id) {
                            case 'official-perimeters': this.checked ? (map.addLayer(officialPerimetersLayer), (officialPerimetersLayer.getLayers().length === 0 && loadOfficialPerimeters())) : map.removeLayer(officialPerimetersLayer); break;
                            case 'state-boundaries': this.checked ? (map.addLayer(stateBoundariesLayer), (stateBoundariesLayer.getLayers().length === 0 && loadStateBoundaries())) : map.removeLayer(stateBoundariesLayer); break;
                            case 'drought-layer': toggleDroughtLayer(this.checked); break;
                            case 'animated-wind': toggleAnimatedWindLayer(this.checked); break;
                            case 'goes-imagery': toggleGoesLayer(this.checked); break;
                            case 'weather-precipitation': this.checked ? map.addLayer(weatherPrecipLayer) : map.removeLayer(weatherPrecipLayer); break;
                            case 'weather-temp': this.checked ? map.addLayer(weatherTempLayer) : map.removeLayer(weatherTempLayer); break;
                            case 'weather-wind': this.checked ? map.addLayer(staticWeatherWindLayer) : map.removeLayer(staticWeatherWindLayer); break;
                        }
                    }
                    if (is3D && this.dataset.source !== 'VIIRS' && this.dataset.source !== 'MODIS') { synchronizeLayersToCesium(); }
                });
            });
            document.getElementById('toggle-3d-btn').addEventListener('click', toggle3DView);
            document.getElementById('expand-map-btn').addEventListener('click', () => { const mapColumn = document.querySelector('.map-column'); const rightSidebar = document.querySelector('.right-sidebar-column'); const header = document.querySelector('.main-content > .header'); const mainContent = document.querySelector('.main-content'); mapColumn.classList.toggle('fullscreen'); rightSidebar.classList.toggle('d-none'); if (header) { header.classList.toggle('d-none'); } mainContent.style.height = mapColumn.classList.contains('fullscreen') ? '100vh' : 'calc(100vh - 58px)'; setTimeout(() => { map.invalidateSize({ pan: true }); }, 310); });
            document.getElementById('get-weather-btn').addEventListener('click', () => weatherMarkerDrawer.enable());
            document.getElementById('goes-fire-temp-btn').addEventListener('click', toggleGoesPreview);
            map.on(L.Draw.Event.CREATED, (event) => {
                if (weatherMarkerDrawer.enabled()) { getAndShowWeatherForPoint(event.layer.getLatLng()); weatherMarkerDrawer.disable(); return; }
                if (event.layerType === 'marker') {
                    if (!startMarker) { startMarker = event.layer.addTo(map).setIcon(L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] })).bindPopup('Start Point').openPopup(); }
                    else if (!endMarker) { endMarker = event.layer.addTo(map).setIcon(L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] })).bindPopup('End Point').openPopup(); }
                    updateCalculateButtonState();
                } else { drawnItems.addLayer(event.layer); }
            });
            document.getElementById('toggle-contained-btn').addEventListener('click', function() { hideContainedFires = !hideContainedFires; this.classList.toggle('active', hideContainedFires); loadOfficialPerimeters(); });
            document.getElementById('apply-date-filter').addEventListener('click', loadOfficialPerimeters);
            document.getElementById('clear-date-filter').addEventListener('click', () => { document.getElementById('discovery-date-filter').value = ''; loadOfficialPerimeters(); });
            document.getElementById('route-search-input').addEventListener('input', filterSavedRoutes);
            document.getElementById('calculate-route-btn').addEventListener('click', calculateAndSaveRoute);
            document.getElementById('clear-markers-btn').addEventListener('click', clearRouteMarkers);
            document.getElementById('saved-routes-list').addEventListener('click', handleSavedRouteClick);
        }

        function loadInitialData() { document.querySelectorAll('.layer-toggle:checked').forEach(toggle => { toggle.dispatchEvent(new Event('change')); }); }
        
        async function loadFireData(source) {
            document.getElementById('main-loader').classList.remove('d-none');
            const apiSource = source === 'VIIRS' ? 'VIIRS_SNPP_NRT' : 'MODIS_NRT';
            let urlParams = (selectedDate && parseInt(timelineSlider.value, 10) > 0) ? `&acq_date=${selectedDate}` : '&day_range=1';
            try { const response = await axios.get(`/api/v1/fire-data?source=${apiSource}&area=world${urlParams}`); const fires = response.data.success ? response.data.data : []; fireDataCache[source] = fires; updateFireLayer(source, fires); updateAllFireStats(); const allCurrentFires = (fireDataCache['VIIRS'] || []).concat(fireDataCache['MODIS'] || []); if (parseInt(timelineSlider.value, 10) === 0) { updateRecentFires(allCurrentFires); } else { document.getElementById('recent-fires').innerHTML = `<p class="text-muted small p-2">Showing historical data for ${timelineLabel.textContent}.</p>`; } if (is3D) { synchronizeLayersToCesium(); }
            } catch (error) { console.error(`Failed to load ${source} fire data for ${selectedDate}:`, error); fireDataCache[source] = []; updateFireLayer(source, []); updateAllFireStats(); } 
            finally { document.getElementById('main-loader').classList.add('d-none'); }
        }

        async function loadOfficialPerimeters() {
            if (!document.getElementById('official-perimeters').checked) return;
            document.getElementById('main-loader').classList.remove('d-none');
            officialPerimetersLayer.clearLayers();
            const params = new URLSearchParams();
            const dateValue = document.getElementById('discovery-date-filter').value;
            if (dateValue) { params.append('discovery_date', dateValue); }
            if (hideContainedFires) { params.append('hide_contained', 'true'); }
            const queryString = params.toString();
            const url = `/api/wildfire-perimeters${queryString ? '?' + queryString : ''}`;
            console.log('Requesting Official Fires URL:', url);
            try {
                const response = await axios.get(url); 
                if (!response.data || !response.data.features || response.data.features.length === 0) { console.log("No features found in response for the current filter."); return; }
                const now = Date.now(), oneDay = 86400000, threeDays = 3 * oneDay;
                L.geoJSON(response.data, { 
                    onEachFeature: (feature, layer) => {
                        const props = feature.properties; const discoveryTs = props.attr_FireDiscoveryDateTime; const age = now - discoveryTs;
                        let color = '#0dcaf0'; if (age < oneDay) color = '#dc3545'; else if (age < threeDays) color = '#fd7e14';
                        let radius = Math.max(5, Math.min(20, 5 + Math.log((props.poly_GISAcres || 0) + 1))); 
                        const bounds = layer.getBounds();
                        if (bounds.isValid()) {
                            const center = bounds.getCenter();
                            const dot = L.circleMarker(center, { radius, fillColor: color, color: '#fff', weight: 1.5, opacity: 1, fillOpacity: 0.8, fireProperties: props }).on('click', e => showOfficialFireModal(e.target.options.fireProperties));
                            officialPerimetersLayer.addLayer(dot);
                            layer.setStyle({ color, weight: 2, opacity: 0.6, fillOpacity: 0.15 }).on('click', e => showOfficialFireModal(e.target.feature.properties));
                            officialPerimetersLayer.addLayer(layer);
                        }
                    }
                });
                if (is3D) synchronizeLayersToCesium();
            } catch (error) { console.error("Failed to load official perimeters:", error); } 
              finally { document.getElementById('main-loader').classList.add('d-none'); }
        }
        
        const formatDate = (ts) => { if (!ts || ts <= 0) return 'N/A'; const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }; return new Date(ts).toLocaleString('en-US', options); };
        function toggleAnimatedWindLayer(show) { if (show) { if (!animatedWindLayer) { document.getElementById('main-loader').classList.remove('d-none'); axios.get('https://onaci.github.io/leaflet-velocity/wind-global.json').then(res => { animatedWindLayer = L.velocityLayer({ displayValues: true, displayOptions: { velocityType: 'Wind', position: 'bottomleft', emptyString: 'No wind data', angleConvention: 'bearingCCW', speedUnit: 'm/s' }, data: res.data, maxVelocity: 15, velocityScale: 0.005, particleMultiplier: 1 / 400, lineWidth: 1.5, colorScale: ["#2196F3", "#1976D2", "#0D47A1"] }); if (document.getElementById('animated-wind').checked) { map.addLayer(animatedWindLayer); } }).catch(err => { console.error("Failed to load or process animated wind data:", err); alert("Could not load animated wind data."); document.getElementById('animated-wind').checked = false; }).finally(() => { document.getElementById('main-loader').classList.add('d-none'); }); } else { map.addLayer(animatedWindLayer); } } else { if (animatedWindLayer) { map.removeLayer(animatedWindLayer); } } }
        function toggleGoesLayer(show) { if (show) { if (!goesLayer) { goesLayer = L.tileLayer.wms("https://mesonet.agron.iastate.edu/cgi-bin/wms/goes/conus_ir.cgi", { layers: 'goes_conus_ir', format: 'image/png', transparent: true, attribution: 'GOES Imagery Courtesy of Iowa Environmental Mesonet', opacity: 0.5, pane: 'goesLayerPane' }); } map.addLayer(goesLayer); } else { if (goesLayer) { map.removeLayer(goesLayer); } } }
        function toggleGoesPreview() { const btn = document.getElementById('goes-fire-temp-btn'); isGoesPreviewActive = !isGoesPreviewActive; btn.classList.toggle('active', isGoesPreviewActive); document.getElementById('map').classList.toggle('goes-preview-active', isGoesPreviewActive); if (isGoesPreviewActive) { map.on('mousemove', handleGoesMouseMove); map.on('click', handleGoesMapClick); map.on('mouseout', handleGoesMouseLeave); } else { map.off('mousemove', handleGoesMouseMove); map.off('click', handleGoesMapClick); map.off('mouseout', handleGoesMouseLeave); handleGoesMouseLeave(); } }
        function handleGoesMouseMove(e) { clearTimeout(goesImageRequestTimer); goesImageRequestTimer = setTimeout(() => updateGoesPreviewImage(e.latlng), 100); goesPreviewContainer.style.left = (e.originalEvent.clientX + 20) + 'px'; goesPreviewContainer.style.top = (e.originalEvent.clientY - 150) + 'px'; goesPreviewContainer.style.display = 'flex'; }
        function handleGoesMapClick(e) { if (lastValidGoesUrl) { const sector = getNoaaSector(e.latlng); zoomedGoesTitle.textContent = `Fire Temperature - ${sector ? sector.name : 'Image'}`; zoomedGoesImage.src = lastValidGoesUrl; zoomedGoesModal.show(); } }
        function handleGoesMouseLeave() { goesPreviewContainer.style.display = 'none'; }
        function getNoaaSector(latlng) { let bestFit = null; let smallestArea = Infinity; for (const code in NOAA_SECTORS) { const sector = NOAA_SECTORS[code]; if (sector.bounds.contains(latlng)) { const area = sector.bounds.getNorthEast().distanceTo(sector.bounds.getSouthWest()); if (area < smallestArea) { smallestArea = area; bestFit = { code: code.toUpperCase(), ...sector }; } } } return bestFit; }
        function updateGoesPreviewImage(latlng) { const sector = getNoaaSector(latlng); if (!sector) { goesPreviewLabel.textContent = "Outside GOES coverage"; goesPreviewImg.style.display = 'none'; lastValidGoesUrl = ''; return; } const imageUrl = `https://cdn.star.nesdis.noaa.gov/${sector.satellite}/ABI/SECTOR/${sector.code}/FireTemperature/latest.jpg`; const cacheBusterUrl = `${imageUrl}?t=${new Date().getTime()}`; goesPreviewLabel.textContent = `Loading ${sector.name}...`; goesPreviewImg.style.display = 'none'; goesPreviewImg.src = cacheBusterUrl; goesPreviewImg.onerror = () => { goesPreviewLabel.textContent = `Image unavailable for ${sector.name}`; goesPreviewImg.style.display = 'none'; lastValidGoesUrl = ''; }; goesPreviewImg.onload = () => { goesPreviewLabel.textContent = `${sector.name} - Click to Pin/Zoom`; goesPreviewImg.style.display = 'block'; lastValidGoesUrl = cacheBusterUrl; }; }
        function initializeMagnifier() { const img = zoomedGoesImage; const container = zoomedGoesContainer; const loupe = magnifierLoupe; const zoom = 2.5; const showLoupe = () => { loupe.style.display = 'block'; loupe.style.backgroundImage = `url('${img.src}')`; }; const hideLoupe = () => { loupe.style.display = 'none'; }; const moveLoupe = (e) => { const rect = img.getBoundingClientRect(); let x = e.clientX - rect.left; let y = e.clientY - rect.top; x = Math.max(0, Math.min(x, rect.width)); y = Math.max(0, Math.min(y, rect.height)); const bgX = -(x * zoom - loupe.offsetWidth / 2); const bgY = -(y * zoom - loupe.offsetHeight / 2); loupe.style.left = (x - loupe.offsetWidth / 2) + 'px'; loupe.style.top = (y - loupe.offsetHeight / 2) + 'px'; loupe.style.backgroundPosition = `${bgX}px ${bgY}px`; loupe.style.backgroundSize = `${img.width * zoom}px ${img.height * zoom}px`; }; container.addEventListener('mouseenter', showLoupe); container.addEventListener('mouseleave', hideLoupe); container.addEventListener('mousemove', moveLoupe); }
        function updateRecentFires(fires) { const container = document.getElementById('recent-fires'); container.innerHTML = ''; const threeHoursAgo = new Date(Date.now() - 3 * 3600 * 1000); const recentFires = fires.filter(fire => new Date(`${fire.acq_date}T${fire.acq_time.slice(0,2)}:${fire.acq_time.slice(2)}:00Z`) > threeHoursAgo).sort((a, b) => b.frp - a.frp).slice(0, 15); if (recentFires.length === 0) { container.innerHTML = '<p class="text-muted small p-2">No detections in the last 3 hours.</p>'; return; } recentFires.forEach(fire => { const fireCard = document.createElement('div'); fireCard.className = 'card bg-body-tertiary mb-2'; fireCard.innerHTML = `<div class="card-body p-2"><div class="d-flex justify-content-between align-items-center"><div><h6 class="card-title mb-1 small"><i class="fas fa-fire me-1" style="color: ${getColorForFRP(fire.frp)}"></i>${fire.satellite} at ${fire.acq_time.slice(0,2)}:${fire.acq_time.slice(2)}</h6><p class="card-text mb-1 small text-muted">FRP: ${fire.frp} MW</p></div><span class="badge text-bg-${fire.confidence.toLowerCase() === 'high' ? 'success' : 'warning'}">${fire.confidence}</span></div></div>`; fireCard.addEventListener('click', () => { map.setView([fire.latitude, fire.longitude], 12); showSatelliteFireModal(fire); }); container.appendChild(fireCard); }); }
        async function getAndShowWeatherForPoint(latlng) { if (weatherPointMarker) map.removeLayer(weatherPointMarker); weatherPointMarker = L.marker(latlng).addTo(map); const popup = L.popup({className: 'weather-popup', minWidth: 280}); try { const response = await axios.get('/api/weather-for-point', { params: { lat: latlng.lat, lon: latlng.lng } }); const data = response.data; const windSpeedKmh = (data.wind.speed * 3.6).toFixed(1); const popupContent = `<div class="card bg-body-tertiary shadow-sm"><div class="card-body"><div class="weather-main mb-3"><h4 class="d-flex align-items-center"><i class="fas fa-map-marker-alt fa-xs me-2"></i> Local Weather</h4><img src="https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png" alt="weather icon" width="50" height="50"></div><h5 class="mb-1">${data.main.temp.toFixed(1)} °C <small class="text-muted">(${data.weather[0].description})</small></h5><p class="small text-muted mb-3">Feels like ${data.main.feels_like.toFixed(1)} °C</p><div class="weather-details"><div class="d-flex align-items-center" title="Wind Speed & Direction"><i class="fas fa-wind fa-fw me-2 text-info"></i> ${windSpeedKmh} km/h <i class="fas fa-location-arrow ms-2" style="transform: rotate(${data.wind.deg - 45}deg);"></i></div><div class="d-flex align-items-center" title="Humidity"><i class="fas fa-tint fa-fw me-2 text-primary"></i> ${data.main.humidity}%</div><div class="d-flex align-items-center" title="Pressure"><i class="fas fa-tachometer-alt fa-fw me-2 text-warning"></i> ${data.main.pressure} hPa</div><div class="d-flex align-items-center" title="Visibility"><i class="fas fa-eye fa-fw me-2 text-success"></i> ${(data.visibility / 1000).toFixed(1)} km</div></div></div></div>`; popup.setLatLng(latlng).setContent(popupContent).openOn(map); } catch (error) { console.error("Weather fetch failed:", error.response?.data?.error || error.message); popup.setLatLng(latlng).setContent('Could not retrieve weather data.').openOn(map); } }
        function toggle3DView() { is3D = !is3D; const cesiumContainer = document.getElementById('cesium-container'); const mapContainer = document.getElementById('map'); const toggleBtn = document.getElementById('toggle-3d-btn'); if (is3D) { mapContainer.style.visibility = 'hidden'; cesiumContainer.classList.remove('d-none'); toggleBtn.innerHTML = '<i class="fas fa-map"></i> 2D'; if (!cesiumViewer) initializeCesium(); synchronizeCamera(); synchronizeLayersToCesium(); } else { mapContainer.style.visibility = 'visible'; cesiumContainer.classList.add('d-none'); toggleBtn.innerHTML = '<i class="fas fa-cube"></i> 3D'; } }
        function synchronizeLayersToCesium() { if (!cesiumViewer) return; cesiumViewer.entities.removeAll(); const addFirePointsToCesium = (fireCache) => { fireCache.forEach(fire => { cesiumViewer.entities.add({ position: Cesium.Cartesian3.fromDegrees(fire.longitude, fire.latitude), point: { pixelSize: 8, color: Cesium.Color.fromCssColorString(getColorForFRP(fire.frp)), outlineColor: Cesium.Color.BLACK, outlineWidth: 1, disableDepthTestDistance: Number.POSITIVE_INFINITY } }); }); }; if (document.getElementById('viirs-hotspots').checked) { addFirePointsToCesium(fireDataCache['VIIRS']); } if (document.getElementById('modis-hotspots').checked) { addFirePointsToCesium(fireDataCache['MODIS']); } if (document.getElementById('official-perimeters').checked) { officialPerimetersLayer.eachLayer(layer => { const geojson = layer.toGeoJSON ? layer.toGeoJSON() : null; if (!geojson) return; const props = layer.feature ? layer.feature.properties : layer.options.fireProperties; if (!props) return; const age = Date.now() - (props.attr_FireDiscoveryDateTime); let colorCss = '#0dcaf0'; if (age < 86400000) colorCss = '#dc3545'; else if (age < 86400000 * 3) colorCss = '#fd7e14'; if (geojson.geometry.type === "Polygon") { cesiumViewer.entities.add({ name: props.poly_IncidentName || 'Perimeter', polygon: { hierarchy: Cesium.Cartesian3.fromDegreesArray(geojson.geometry.coordinates[0].flat()), extrudedHeightReference: Cesium.HeightReference.RELATIVE_TO_GROUND, heightReference: Cesium.HeightReference.CLAMP_TO_GROUND, extrudedHeight: 500, material: Cesium.Color.fromCssColorString(colorCss).withAlpha(0.4), outline: true, outlineColor: Cesium.Color.BLACK } }); } else if (geojson.geometry.type === "Point") { cesiumViewer.entities.add({ position: Cesium.Cartesian3.fromDegrees(geojson.geometry.coordinates[0], geojson.geometry.coordinates[1]), point: { pixelSize: 12, color: Cesium.Color.fromCssColorString(colorCss), outlineColor: Cesium.Color.WHITE, outlineWidth: 2, disableDepthTestDistance: Number.POSITIVE_INFINITY } }); } }); } }
        function initializeCesium() { try { cesiumViewer = new Cesium.Viewer('cesium-container', { terrain: Cesium.Terrain.fromWorldTerrain({requestVertexNormals: true}), animation: false, timeline: false, geocoder: false, homeButton: false, sceneModePicker: false, baseLayerPicker: false, navigationHelpButton: false }); cesiumViewer.scene.globe.depthTestAgainstTerrain = true; } catch (e) { console.error("Cesium initialization failed:", e); alert("Could not initialize 3D view."); } }
        function synchronizeCamera() { if (!cesiumViewer) return; const center = map.getCenter(); const zoom = map.getZoom(); const height = 40000000 / Math.pow(2, zoom); cesiumViewer.camera.flyTo({ destination: Cesium.Cartesian3.fromDegrees(center.lng, center.lat, height) }); }
        function updateFireLayer(source, fires) { const layerGroup = fireLayerGroups[source]; layerGroup.clearLayers(); fires.forEach(fire => { const marker = L.circleMarker([fire.latitude, fire.longitude], { radius: 5, fillColor: getColorForFRP(fire.frp), color: '#000', weight: 0.5, opacity: 1, fillOpacity: 0.8, fireData: fire }); marker.on('click', e => showSatelliteFireModal(e.target.options.fireData)); layerGroup.addLayer(marker); }); }
        function getColorForFRP(frp) { if (frp > 500) return '#6e0101'; if (frp > 250) return '#d40202'; if (frp > 100) return '#ff4500'; if (frp > 50)  return '#ffaa00'; return '#ffff00'; }
        function updateAllFireStats() { const fires = (fireDataCache['VIIRS'] || []).concat(fireDataCache['MODIS'] || []); const totalFires = fires.length; const highConfidenceFires = fires.filter(f => (f.confidence?.toLowerCase() === 'high' || (typeof f.confidence === 'number' && f.confidence >= 80))).length; document.getElementById('active-fires-count').textContent = totalFires.toLocaleString(); document.getElementById('high-confidence-count').textContent = highConfidenceFires.toLocaleString(); }
        async function loadStateBoundaries() { try { const r = await axios.get('/us-states.json'); L.geoJSON(r.data, { style: () => ({ color: "#fff", weight: 1, opacity: 0.6, fill: false, interactive: false }) }).addTo(stateBoundariesLayer); } catch(e) { console.error("us-states.json not found in /public."); } }
        function toggleDroughtLayer(show) { if (show) { if (droughtLayer.getLayers().length === 0) { L.rectangle([[25, -125], [50, -65]], { color: "#FFC107", weight: 0, fillOpacity: 0.1, interactive: false }).addTo(droughtLayer); L.rectangle([[30, -100], [35, -90]], { color: "#FFC107", weight: 1, fillOpacity: 0.4, interactive: false }).bindPopup("Abnormally Dry Area").addTo(droughtLayer); } map.addLayer(droughtLayer); } else { map.removeLayer(droughtLayer); } }
        function showOfficialFireModal(props) { document.getElementById('fire-details-modal-title').innerHTML = `<i class="fas fa-certificate text-danger me-2"></i> Official Incident`; document.getElementById('fire-details-modal-body').innerHTML = `<div class="mb-3"><h4>${props.poly_IncidentName || 'Unknown'}</h4><p class="text-muted mb-0">${props.UniqueFireIdentifier || ''}</p></div><div class="row g-3"><div class="col-md-6"><div class="card bg-body-tertiary h-100"><div class="card-body"><h6 class="card-title">Details</h6><p class="mb-1 d-flex justify-content-between"><strong>Cause:</strong> <span>${props.attr_FireCause || 'N/A'}</span></p><p class="mb-1 d-flex justify-content-between"><strong>Size:</strong> <span>${props.poly_GISAcres ? props.poly_GISAcres.toFixed(2) + ' acres' : 'N/A'}</span></p></div></div></div><div class="col-md-6"><div class="card bg-body-tertiary h-100"><div class="card-body"><h6 class="card-title">Timeline</h6><p class="mb-1 d-flex justify-content-between"><strong>Discovered:</strong> <span>${formatDate(props.attr_FireDiscoveryDateTime)}</span></p><p class="mb-1 d-flex justify-content-between"><strong>Contained:</strong> <span>${formatDate(props.attr_ContainmentDateTime)}</span></p><p class="mb-1 d-flex justify-content-between"><strong>Fire Out:</strong> <span>${formatDate(props.attr_FireOutDateTime)}</span></p><p class="mb-1 d-flex justify-content-between"><strong>Last Update:</strong> <span>${formatDate(props.poly_PolygonDateTime)}</span></p></div></div></div></div>`; fireDetailsModal.show(); }
        async function showSatelliteFireModal(fire) { document.getElementById('fire-details-modal-title').innerHTML = `<i class="fas fa-satellite-dish text-info me-2"></i> Satellite Detection`; const modalBody = document.getElementById('fire-details-modal-body'); modalBody.innerHTML = `<div>Loading details...</div>`; fireDetailsModal.show(); let weatherHtml = '<p class="text-muted">Weather data unavailable.</p>'; try { const response = await axios.get('/api/weather-for-point', { params: { lat: fire.latitude, lon: fire.longitude } }); const w = response.data; weatherHtml = `<p class="mb-2 d-flex justify-content-between">Temperature: <strong>${w.main.temp.toFixed(1)}°C</strong></p><p class="mb-2 d-flex justify-content-between">Humidity: <strong>${w.main.humidity}%</strong></p><p class="mb-0 d-flex justify-content-between">Winds: <strong>${(w.wind.speed * 3.6).toFixed(1)} km/h</strong></p>`; } catch (error) { console.error('Failed to load weather for modal:', error); } modalBody.innerHTML = `<div class="d-flex justify-content-between align-items-start mb-3"><div><span class="badge text-bg-secondary">${fire.satellite || 'N/A'}</span><h4 class="mt-1">Detection at ${fire.latitude.toFixed(4)}, ${fire.longitude.toFixed(4)}</h4></div></div><div class="row g-2 text-center mb-4"><div class="col"><div class="p-2 bg-body-tertiary rounded"><small class="text-muted">CONFIDENCE</small><div class="fs-5 fw-bold text-warning">${fire.confidence}</div></div></div><div class="col"><div class="p-2 bg-body-tertiary rounded"><small class="text-muted">DETECTED</small><div class="fs-5 fw-bold">${fire.acq_date} ${fire.acq_time.slice(0,2)}:${fire.acq_time.slice(2)}</div></div></div><div class="col"><div class="p-2 bg-body-tertiary rounded"><small class="text-muted">FRP (MW)</small><div class="fs-5 fw-bold" style="color:${getColorForFRP(fire.frp)}">${fire.frp}</div></div></div></div><div class="row g-3"><div class="col-md-6"><div class="card h-100"><div class="card-body"><h6 class="card-title mb-3"><i class="fas fa-cloud-sun me-2"></i>Nearby Weather</h6>${weatherHtml}</div></div></div><div class="col-md-6"><div class="card h-100"><div class="card-body"><h6 class="card-title mb-3"><i class="fas fa-chart-line me-2"></i>Sensor Data</h6><p class="mb-2 d-flex justify-content-between">Brightness: <strong>${fire.bright_ti4 || fire.brightness} K</strong></p><p class="mb-0 d-flex justify-content-between">Scan/Track: <strong>${fire.scan}° / ${fire.track}°</strong></p></div></div></div></div>`; }
        function updateCalculateButtonState() { document.getElementById('calculate-route-btn').disabled = !(startMarker && endMarker); }
        function clearRouteMarkers() { if (startMarker) map.removeLayer(startMarker); if (endMarker) map.removeLayer(endMarker); if (currentRouteControl) map.removeControl(currentRouteControl); startMarker = null; endMarker = null; currentRouteControl = null; updateCalculateButtonState(); }
        function calculateAndSaveRoute() { if (!startMarker || !endMarker) return; const routeName = document.getElementById('route-name').value.trim(); if (!routeName) return alert('Please enter a name for the route.'); if (currentRouteControl) map.removeControl(currentRouteControl); currentRouteControl = L.Routing.control({ waypoints: [startMarker.getLatLng(), endMarker.getLatLng()], routeWhileDragging: false, addWaypoints: false, createMarker: () => null, lineOptions: { styles: [{ color: 'blue', opacity: 0.8, weight: 6 }] } }).on('routesfound', function(e) { const route = e.routes[0]; const geometry = L.polyline(route.coordinates.map(c => [c.lat, c.lng])).toGeoJSON(); const routeData = { name: routeName, start_latitude: startMarker.getLatLng().lat, start_longitude: startMarker.getLatLng().lng, end_latitude: endMarker.getLatLng().lat, end_longitude: endMarker.getLatLng().lng, geometry: JSON.stringify(geometry.geometry) }; axios.post('/api/routes', routeData).then(() => { alert('Route saved successfully!'); clearRouteMarkers(); document.getElementById('route-name').value = ''; fetchAndDisplaySavedRoutes(); }).catch(error => { console.error('Error saving route:', error.response?.data); alert('Failed to save route.'); }); }).addTo(map); }
        async function fetchAndDisplaySavedRoutes() { try { const response = await axios.get('/api/routes'); const routes = response.data; const listContainer = document.getElementById('saved-routes-list'); savedRoutesLayer.clearLayers(); listContainer.innerHTML = routes.length === 0 ? '<li class="list-group-item text-muted small">No routes saved yet.</li>' : ''; routes.forEach(route => { const latlngs = route.geometry.coordinates.map(coord => [coord[1], coord[0]]); const polyline = L.polyline(latlngs, { color: 'green', weight: 5, opacity: 0.7 }).bindPopup(`<b>${route.name}</b>`); savedRoutesLayer.addLayer(polyline); const li = document.createElement('li'); li.className = 'list-group-item d-flex justify-content-between align-items-center list-group-item-action'; li.innerHTML = `<span><i class="fas fa-route me-2"></i>${route.name}</span> <button class="btn btn-sm btn-outline-danger delete-route-btn" data-id="${route.id}"><i class="fas fa-trash"></i></button>`; li.addEventListener('click', (e) => { if (!e.target.closest('.delete-route-btn')) { map.fitBounds(polyline.getBounds()); } }); listContainer.appendChild(li); }); filterSavedRoutes(); } catch (error) { console.error('Failed to fetch saved routes:', error); } }
        async function handleSavedRouteClick(event) { const deleteButton = event.target.closest('.delete-route-btn'); if (deleteButton) { const routeId = deleteButton.dataset.id; if (confirm('Are you sure you want to delete this route?')) { try { await axios.delete(`/api/routes/${routeId}`); fetchAndDisplaySavedRoutes(); } catch (error) { console.error('Failed to delete route:', error); alert('Could not delete the route.'); } } } }
        function filterSavedRoutes() { const searchTerm = document.getElementById('route-search-input').value.toLowerCase(); const routes = document.querySelectorAll('#saved-routes-list li'); routes.forEach(route => { const routeName = route.querySelector('span').textContent.toLowerCase(); route.style.display = routeName.includes(searchTerm) ? '' : 'none'; }); }
        function initializeFireSearch() { if (map.fireSearchControl) { map.removeControl(map.fireSearchControl); } const customGeocoder = { geocode: function(query, cb, context) { const results = []; const lowerCaseQuery = query.toLowerCase(); const addedFireNames = new Set(); if (map.hasLayer(officialPerimetersLayer)) { officialPerimetersLayer.eachLayer(layer => { const props = layer.feature ? layer.feature.properties : layer.options.fireProperties; if (props && props.poly_IncidentName && props.poly_IncidentName.toLowerCase().includes(lowerCaseQuery)) { if (addedFireNames.has(props.poly_IncidentName)) return; const bounds = layer.getBounds(); if (bounds && bounds.isValid()) { addedFireNames.add(props.poly_IncidentName); results.push({ name: `🔥 ${props.poly_IncidentName}`, html: `<span class="leaflet-control-geocoder-result-name">🔥 <b>${props.poly_IncidentName}</b> <small>(${props.poly_GISAcres ? props.poly_GISAcres.toFixed(0) : 'N/A'} acres)</small></span>`, bbox: bounds, center: bounds.getCenter() }); } } }); } if (results.length > 0) { cb.call(context, results); } else { new L.Control.Geocoder.Nominatim().geocode(query, cb, context); } }, reverse: function (location, scale, cb, context) { new L.Control.Geocoder.Nominatim().reverse(location, scale, cb, context); } }; map.fireSearchControl = new L.Control.Geocoder({ defaultMarkGeocode: false, geocoder: customGeocoder, placeholder: 'Search for location or fire name...' }).on('markgeocode', e => { map.fitBounds(e.geocode.bbox); }).addTo(map); }
        function makeDraggable(element, handle) { let isDragging=false,x,y; handle.addEventListener('mousedown',function(e){isDragging=true;x=e.clientX-element.offsetLeft;y=e.clientY-element.offsetTop; e.preventDefault();}); document.addEventListener('mousemove',function(e){if(isDragging===true){element.style.left=Math.max(5, (e.clientX-x))+'px';element.style.top=Math.max(5, (e.clientY-y))+'px';}}); document.addEventListener('mouseup',function(e){isDragging=false;}); }
        function sendMessage() { const input = document.getElementById('chat-input'), messageText = input.value.trim(); if (!messageText) return; const msgContainer = document.getElementById('chat-messages'); msgContainer.innerHTML += `<div class="mb-3 text-end"><div class="p-3 rounded mt-1 bg-primary-subtle d-inline-block">${messageText}</div></div>`; input.value = ''; msgContainer.scrollTop = msgContainer.scrollHeight; setTimeout(() => { const response = `Based on current data, I can provide information about the ${document.getElementById('active-fires-count').textContent} active fire detections. How can I help?`; msgContainer.innerHTML += `<div class="mb-3 text-start"><small class="text-body-secondary">Artemis AI</small><div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">${response}</div></div>`; msgContainer.scrollTop = msgContainer.scrollHeight; }, 800); }
    </script>
</body>
</html>