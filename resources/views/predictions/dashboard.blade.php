<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PulsePoint Command - Wildfire Risk Analysis</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Scripts that need to load in the <head> -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @include('partials.styles')
    <style>
        .main-content { flex-grow: 1; }
        .risk-dashboard-container { height: 100%; }
        #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); cursor: crosshair !important; }
        .map-wrapper { position: relative; height: 100%; overflow: hidden; }
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
        .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
        
        .legend-item { display: flex; align-items: center; margin-bottom: 5px; font-size: 0.9rem; }
        .legend-color-box { width: 20px; height: 20px; margin-right: 10px; border: 1px solid #555; }

        @media (max-width: 991.98px) {
            .risk-dashboard-container { height: auto; }
            #map { min-height: 65vh; height: 65vh; }
            #right-sidebar-column { height: auto; border-top: 1px solid var(--bs-border-color) !important; }
            .sidebar-wrapper { height: auto; }
        }
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
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="layers-tab-btn" data-bs-toggle="pill" data-bs-target="#layers-content" type="button" role="tab" aria-controls="layers-content" aria-selected="true" title="Data Layers"><i class="fas fa-layer-group me-1"></i> Layers</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="analysis-tab-btn" data-bs-toggle="pill" data-bs-target="#analysis-content" type="button" role="tab" aria-controls="analysis-content" aria-selected="false" title="Point Analysis"><i class="fas fa-map-pin me-1"></i> Analysis</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="legend-tab-btn" data-bs-toggle="pill" data-bs-target="#legend-content" type="button" role="tab" aria-controls="legend-content" aria-selected="false" title="Map Legend"><i class="fas fa-list me-1"></i> Legend</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="settings-tab-btn" data-bs-toggle="pill" data-bs-target="#settings-content" type="button" role="tab" aria-controls="settings-content" aria-selected="false" title="Map Settings"><i class="fas fa-cogs me-1"></i> Settings</button></li>
                    </ul>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="tab-content h-100">
                        
                        <!-- DATA LAYERS TAB -->
                        <div class="tab-pane fade show active p-3" id="layers-content" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-fire-danger me-2"></i>Wildfire Risk Layers</h5>
                            <p class="text-muted small">Data provided by <a href="https://wildfirerisk.org/" target="_blank">wildfirerisk.org</a>. Layers are most visible when zoomed into a regional level.</p>
                            
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="whp-layer-toggle" checked>
                                <label class="form-check-label" for="whp-layer-toggle">Wildfire Hazard Potential (2023)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="wui-layer-toggle">
                                <label class="form-check-label" for="wui-layer-toggle">Wildland-Urban Interface (2020)</label>
                            </div>
                             <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="exp-layer-toggle">
                                <label class="form-check-label" for="exp-layer-toggle">Exposure of Homes (2023)</label>
                            </div>
                        </div>

                        <!-- POINT ANALYSIS TAB -->
                        <div class="tab-pane fade p-3" id="analysis-content" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-search-location me-2"></i>Point Risk Analysis</h5>
                             <div class="alert alert-info small">
                                <h6 class="alert-heading">Instructions</h6>
                                <p class="mb-0">Click any point on the map to query the Wildfire Risk API for data on the nearest community.</p>
                            </div>
                            <div id="risk-results-placeholder" class="text-center text-muted mt-4">
                                <i class="fas fa-mouse-pointer fa-2x mb-2"></i>
                                <p>Click the map to see risk data.</p>
                            </div>
                        </div>

                        <!-- LEGEND TAB -->
                        <div class="tab-pane fade p-3" id="legend-content" role="tabpanel">
                           <h5 class="mb-3">Wildfire Hazard Potential (WHP)</h5>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #358235;"></div>Very Low</div>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #a6c307;"></div>Low</div>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #fdbe25;"></div>Moderate</div>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #fd7b22;"></div>High</div>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #f7390f;"></div>Very High</div>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #c40008;"></div>Extreme</div>
                           <hr>
                           <h5 class="mb-3 mt-4">Wildland-Urban Interface (WUI)</h5>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #fecc5c;"></div>Intermix</div>
                           <div class="legend-item"><div class="legend-color-box" style="background-color: #fd8d3c;"></div>Interface</div>
                           <hr>
                           <p class="small text-muted">Legends are simplified. For official details, visit wildfirerisk.org.</p>
                        </div>

                        <!-- SETTINGS TAB -->
                        <div class="tab-pane fade p-3" id="settings-content" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-map me-2"></i>Base Maps</h5>
                            <div id="basemap-selector-container">
                                <!-- Populated by JavaScript -->
                            </div>
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
    let analysisMarker = null;

    // **FIX:** WMS Layer Definitions updated for better compatibility
    const wmsBaseUrl = "https://apps.fs.usda.gov/fsgisx08/services/ENFIA/RiskToCommunities/MapServer/WmsServer?";
    const wmsOptions = (layerName) => ({
        layers: layerName,
        format: 'image/png',
        transparent: true,
        version: '1.3.0',
        crs: L.CRS.EPSG3857,
        attribution: 'USDA Forest Service',
        zIndex: 1000 // Ensure WMS layers are on top of the base map
    });

    const whpLayer = L.tileLayer.wms(wmsBaseUrl, wmsOptions('whp2023_wui_whp'));
    const wuiLayer = L.tileLayer.wms(wmsBaseUrl, wmsOptions('wui_2020'));
    const exposureLayer = L.tileLayer.wms(wmsBaseUrl, wmsOptions('whp2023_wui_exp'));
    
    // --- MAP INITIALIZATION ---
    const initMap = () => {
        const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
        const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
        const darkMode = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
        
        baseMaps = { "Streets": streets, "Dark Mode": darkMode, "Satellite": satellite };
        
        map = L.map('map', { 
            center: [37.8, -120.5], 
            zoom: 7, 
            layers: [darkMode] 
        });

        const basemapContainer = document.getElementById('basemap-selector-container');
        Object.keys(baseMaps).forEach((name) => {
            const id = `basemap-radio-${name.replace(/\s+/g, '-')}`;
            const isChecked = (name === "Dark Mode") ? 'checked' : '';
            basemapContainer.innerHTML += `<div class="form-check"><input class="form-check-input" type="radio" name="basemap-selector" id="${id}" value="${name}" ${isChecked}><label class="form-check-label" for="${id}">${name}</label></div>`;
        });
        basemapContainer.addEventListener('change', (e) => {
            Object.values(baseMaps).forEach(layer => map.removeLayer(layer));
            map.addLayer(baseMaps[e.target.value]);
        });

        map.addLayer(whpLayer); // Add default WMS layer to map

        map.on('click', handleMapClick);
    };
    
    // --- API and UI LOGIC ---
    const handleMapClick = async (e) => {
        const lat = e.latlng.lat;
        const lon = e.latlng.lng;
        const resultsPlaceholder = document.getElementById('risk-results-placeholder');

        resultsPlaceholder.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Querying API...</p>';
        new bootstrap.Tab(document.getElementById('analysis-tab-btn')).show();
        
        if (analysisMarker) map.removeLayer(analysisMarker);
        analysisMarker = L.marker([lat, lon]).addTo(map);

        // **CHANGE:** Point fetch to our own backend proxy API
        const apiUrl = `/api/wildfire-risk/point-data?lat=${lat}&lon=${lon}`;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json(); // Always try to parse JSON

            if (!response.ok) {
                // Use error message from our API if available, otherwise use default
                throw new Error(data.error || `Request failed with status: ${response.status}`);
            }

            if (data.results && data.results.community) {
                displayRiskData(data.results.community.properties);
            } else {
                resultsPlaceholder.innerHTML = '<div class="alert alert-warning">No community data found at this location.</div>';
            }
        } catch (error) {
            console.error("API Fetch Error:", error);
            resultsPlaceholder.innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${error.message}</div>`;
        }
    };

    const displayRiskData = (props) => {
        const resultsPlaceholder = document.getElementById('risk-results-placeholder');
        
        const formatPercent = (val) => val ? `${(val * 100).toFixed(1)}%` : 'N/A';
        const formatName = (name) => name ? name.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ') : 'N/A';

        const html = `
            <div class="card bg-body-tertiary">
                <div class="card-header fw-bold">${formatName(props.name)}, ${props.state_abbreviation}</div>
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">${formatName(props.county_name)} County</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            Risk to Homes
                            <span class="badge text-bg-danger">${props.risk_to_homes_text || 'N/A'}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            Hazard Potential
                            <span class="badge text-bg-warning">${props.whp_text || 'N/A'}</span>
                        </li>
                         <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            Homes Exposed
                            <span>${formatPercent(props.exposure)}</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            Population
                            <span>${props.population ? props.population.toLocaleString() : 'N/A'}</span>
                        </li>
                    </ul>
                </div>
            </div>
        `;
        resultsPlaceholder.innerHTML = html;
    };

    // --- LAYER TOGGLE EVENT LISTENERS ---
    document.getElementById('whp-layer-toggle').addEventListener('change', (e) => {
        e.target.checked ? map.addLayer(whpLayer) : map.removeLayer(whpLayer);
    });

    document.getElementById('wui-layer-toggle').addEventListener('change', (e) => {
        e.target.checked ? map.addLayer(wuiLayer) : map.removeLayer(wuiLayer);
    });
    
    document.getElementById('exp-layer-toggle').addEventListener('change', (e) => {
        e.target.checked ? map.addLayer(exposureLayer) : map.removeLayer(exposureLayer);
    });
    
    // --- INITIALIZE ---
    initMap();
});
</script>
</body>
</html>