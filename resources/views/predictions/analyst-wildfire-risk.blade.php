<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PulsePoint Command - Live Fire & Evacuation Route</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>

    @include('partials.styles')
    <style>
        .main-content { flex-grow: 1; }
        .risk-dashboard-container { height: 100%; }
        #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); cursor: grab; }
        #map.selection-active { cursor: crosshair !important; }
        .map-wrapper { position: relative; height: 100%; overflow: hidden; }
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
        .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
        .fire-card { border-left-width: 4px; }
        .leaflet-routing-container { display: none !important; }
        .accordion-button { padding: 0.75rem 1rem; }
        .accordion-button:not(.collapsed) { background-color: rgba(var(--bs-primary-rgb), 0.1); }
        .accordion-body { padding: 1rem; background-color: var(--bs-tertiary-bg); }
        .raw-data-table { font-size: 0.8rem; }
        .raw-data-table td { word-break: break-all; }
    </style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
<div class="main-content d-flex flex-column">
    @include('partials.header')
    <div class="risk-dashboard-container row g-0 flex-grow-1">
        <div class="col-lg-8 col-md-12" id="map-column">
            <div class="map-wrapper"><div id="map"></div></div>
        </div>
        <div class="col-lg-4 col-md-12 border-start" id="right-sidebar-column">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-fill flex-nowrap" id="sidebar-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="report-tab-btn" data-bs-toggle="pill" data-bs-target="#report-content" type="button" role="tab" aria-controls="report-content" aria-selected="true" title="Live Fire Report"><i class="fas fa-fire-alt me-1"></i> Live Fire</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="forecast-tab-btn" data-bs-toggle="pill" data-bs-target="#forecast-content" type="button" role="tab" aria-controls="forecast-content" aria-selected="false" title="Risk Forecast"><i class="fas fa-chart-line me-1"></i> Risk Forecast</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="routing-tab-btn" data-bs-toggle="pill" data-bs-target="#routing-content" type="button" role="tab" aria-controls="routing-content" aria-selected="false" title="Evacuation Route"><i class="fas fa-route me-1"></i> Routing</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="settings-tab-btn" data-bs-toggle="pill" data-bs-target="#settings-content" type="button" role="tab" aria-controls="settings-content" aria-selected="false" title="Map Settings"><i class="fas fa-cogs me-1"></i> Settings</button></li>
                    </ul>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="tab-content h-100">
                        <!-- LIVE FIRE TAB -->
                        <div class="tab-pane fade show active p-3" id="report-content" role="tabpanel">
                            <div class="d-flex gap-2 mb-3">
                                <button class="btn btn-primary w-100" id="enable-live-selection-btn"><i class="fas fa-crosshairs me-2"></i>Select Location</button>
                                <button class="btn btn-outline-secondary" id="clear-fires-btn" title="Clear Live Fires"><i class="fas fa-times"></i></button>
                            </div>
                            <div id="report-placeholder" class="text-center text-muted mt-4"><i class="fas fa-map-marked-alt fa-2x mb-2"></i><p>Use 'Select Location' to get a live fire report.</p></div>
                            <div id="report-container" class="accordion"></div>
                        </div>
                        <!-- RISK FORECAST TAB -->
                        <div class="tab-pane fade p-3" id="forecast-content" role="tabpanel">
                            <div class="d-grid mb-3">
                                <button class="btn btn-primary" id="enable-forecast-selection-btn"><i class="fas fa-crosshairs me-2"></i>Select Location</button>
                            </div>
                            <div id="forecast-placeholder" class="text-center text-muted mt-4"><i class="fas fa-map-marked-alt fa-2x mb-2"></i><p>Use 'Select Location' to get a risk forecast.</p></div>
                            <div id="forecast-container" class="accordion"></div>
                        </div>
                        <!-- ROUTING TAB -->
                        <div class="tab-pane fade p-3" id="routing-content" role="tabpanel">
                            <h5 class="mb-3">Evacuation Route Planner</h5>
                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-outline-success" id="set-start-btn"><i class="fas fa-flag-checkered me-2"></i>Set Start Point</button>
                                <button class="btn btn-outline-danger" id="set-end-btn"><i class="fas fa-bullseye me-2"></i>Set Safe Destination</button>
                            </div>
                            <ul class="list-group mb-3">
                                <li class="list-group-item"><strong>Start:</strong> <span id="start-point-text" class="text-muted">Not set</span></li>
                                <li class="list-group-item"><strong>End:</strong> <span id="end-point-text" class="text-muted">Not set</span></li>
                            </ul>
                            <div class="d-grid"><button class="btn btn-primary" id="calculate-route-btn" disabled><i class="fas fa-calculator me-2"></i>Calculate Route</button><button class="btn btn-secondary mt-2" id="clear-route-btn" style="display: none;"><i class="fas fa-times me-2"></i>Clear All Routes</button></div>
                            <hr><div id="route-info-container"></div>
                        </div>
                        <!-- SETTINGS TAB -->
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

@include('partials.theme_settings')
@include('partials.scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map;
    let baseMaps;
    let clickMarker = null;
    let fireMarkersLayer = L.layerGroup();
    let routeLayers = L.layerGroup();
    let settingPoint = null, startPoint = null, endPoint = null;
    let currentFires = [];
    let selectionMode = null;

    const initMap = () => {
        const darkMode = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
        baseMaps = { "Streets": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }), "Satellite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' }), "Dark Mode": darkMode };
        map = L.map('map', { center: [34.05, -118.25], zoom: 9, layers: [darkMode] });
        map.addLayer(fireMarkersLayer);
        map.addLayer(routeLayers);
        map.on('click', handleMapClick);
        setupUI();
    };

    const setupUI = () => {
        const basemapContainer = document.getElementById('basemap-selector-container');
        Object.keys(baseMaps).forEach(name => {
            const id = `basemap-radio-${name.replace(/\s+/g, '-')}`;
            const isChecked = name === "Dark Mode" ? 'checked' : '';
            basemapContainer.innerHTML += `<div class="form-check"><input class="form-check-input" type="radio" name="basemap-selector" id="${id}" value="${name}" ${isChecked}><label class="form-check-label" for="${id}">${name}</label></div>`;
        });
        basemapContainer.addEventListener('change', (e) => { Object.values(baseMaps).forEach(layer => map.removeLayer(layer)); map.addLayer(baseMaps[e.target.value]); });
        
        document.getElementById('enable-live-selection-btn').addEventListener('click', () => setSelectionMode('live'));
        document.getElementById('enable-forecast-selection-btn').addEventListener('click', () => setSelectionMode('forecast'));
        document.getElementById('clear-fires-btn').addEventListener('click', clearLiveFireData);

        document.getElementById('set-start-btn').addEventListener('click', () => setSelectionMode('routing-start'));
        document.getElementById('set-end-btn').addEventListener('click', () => setSelectionMode('routing-end'));
        document.getElementById('calculate-route-btn').addEventListener('click', calculateAndCheckRoute);
        document.getElementById('clear-route-btn').addEventListener('click', () => clearAllRoutes(false));
    };

    const setSelectionMode = (mode) => {
        selectionMode = mode;
        document.getElementById('map').classList.add('selection-active');
    };

    const handleMapClick = async (e) => {
        if (!selectionMode) return;
        const mode = selectionMode; // Capture mode before resetting
        selectionMode = null; // Deactivate selection mode immediately
        document.getElementById('map').classList.remove('selection-active');

        if (mode.startsWith('routing')) {
            handleRoutingPointPlacement(e.latlng, mode);
        } else {
            if (clickMarker) map.removeLayer(clickMarker);
            clickMarker = L.marker(e.latlng, { opacity: 0.7 }).addTo(map);

            if (mode === 'live') {
                document.getElementById('report-placeholder').innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Fetching live fires...</p>';
                fetchAndDisplayFires(e.latlng);
            } else if (mode === 'forecast') {
                document.getElementById('forecast-placeholder').innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Fetching risk forecast...</p>';
                fetchAndDisplayRisk(e.latlng);
            }
        }
    };
    
    // --- Data Fetching and Display ---
    const fetchAndDisplayFires = async (latlng) => {
        try {
            const response = await fetch(`/api/ambee/fire-data?lat=${latlng.lat}&lng=${latlng.lng}`);
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Request failed');
            const placeholder = document.getElementById('report-placeholder');
            const container = document.getElementById('report-container');
            container.innerHTML = '';
            
            if (data.message === 'success' && data.data.length > 0) {
                placeholder.innerHTML = '';
                currentFires = data.data;
                fireMarkersLayer.clearLayers();
                data.data.forEach((fire, index) => {
                    const fireIcon = L.divIcon({ className: 'text-danger', html: '<i class="fas fa-fire-alt fa-2x"></i>', iconSize: [24, 24] });
                    L.marker([fire.lat, fire.lng], { icon: fireIcon }).addTo(fireMarkersLayer).bindPopup(`<b>${fire.fireName || 'Unnamed Fire'}</b>`);
                    const fwi = fire.fwi ? parseFloat(fire.fwi).toFixed(1) : 'N/A';
                    const fwiColor = fwi >= 40 ? 'danger' : fwi >= 20 ? 'warning' : fwi > 0 ? 'success' : 'secondary';
                    container.innerHTML += createAccordionItem(`live-fire-${index}`, fire.fireName || 'Unnamed Fire', new Date(fire.detectedAt).toLocaleString(), `FWI: ${fwi}`, fwiColor, fire);
                });
            } else {
                placeholder.innerHTML = '<div class="alert alert-success">No active fires found near this location.</div>';
                clearLiveFireData();
            }
        } catch (error) { document.getElementById('report-placeholder').innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${error.message}</div>`; }
    };

    const fetchAndDisplayRisk = async (latlng) => {
        try {
            const response = await fetch(`/api/ambee/fire-risk?lat=${latlng.lat}&lng=${latlng.lng}`);
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Request failed');
            const placeholder = document.getElementById('forecast-placeholder');
            const container = document.getElementById('forecast-container');
            container.innerHTML = '';
            
            if (data.message === 'success' && data.data.length > 0) {
                placeholder.innerHTML = '';
                data.data.slice(0, 4).forEach((risk, index) => {
                    const riskColor = risk.predicted_risk_category === 'high' ? 'danger' : risk.predicted_risk_category === 'moderate' ? 'warning' : 'success';
                    container.innerHTML += createAccordionItem(`forecast-${index}`, `Week ${risk.week} Forecast`, risk.predicted_risk_category, `Avg Temp: ${risk.temperature.toFixed(1)}°C`, riskColor, risk);
                });
            } else {
                placeholder.innerHTML = '<div class="alert alert-secondary">No risk forecast data available for this location.</div>';
            }
        } catch (error) { document.getElementById('forecast-placeholder').innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${error.message}</div>`; }
    };

    const formatLabel = (key) => {
        if (!key) return '';
        const result = key.replace(/_/g, ' ').replace(/([A-Z])/g, ' $1');
        return result.charAt(0).toUpperCase() + result.slice(1);
    };

    const createAccordionItem = (id, title, subtitle, badgeText, badgeColor, rawData) => {
        let rawDataHtml = '<table class="table table-bordered table-sm raw-data-table"><tbody>';
        for (const key in rawData) {
            let value = rawData[key];
            if (typeof value === 'object' && value !== null) { value = JSON.stringify(value, null, 2); }
             // NEW: Use the formatLabel function here
            rawDataHtml += `<tr><td><strong>${formatLabel(key)}</strong></td><td>${value}</td></tr>`;
        }
        rawDataHtml += '</tbody></table>';

        return `<div class="accordion-item bg-transparent border-bottom"><h2 class="accordion-header" id="heading-${id}"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${id}" aria-expanded="false" aria-controls="collapse-${id}"><div class="w-100 d-flex justify-content-between align-items-center"><div class="me-2"><strong>${title}</strong><br><small class="text-muted">${subtitle}</small></div><span class="badge text-bg-${badgeColor} flex-shrink-0">${badgeText}</span></div></button></h2><div id="collapse-${id}" class="accordion-collapse collapse" aria-labelledby="heading-${id}"><div class="accordion-body">${rawDataHtml}</div></div></div>`;
    };

    const clearLiveFireData = () => {
        currentFires = [];
        fireMarkersLayer.clearLayers();
        document.getElementById('report-container').innerHTML = '';
        document.getElementById('report-placeholder').innerHTML = '<i class="fas fa-map-marked-alt fa-2x mb-2"></i><p>Use \'Select Location\' to get a live fire report.</p>';
    };

    // --- Routing Logic ---
    const handleRoutingPointPlacement = (latlng, mode) => {
        const type = mode.split('-')[1];
        const pointText = `${latlng.lat.toFixed(4)}, ${latlng.lng.toFixed(4)}`;
        const icon = L.divIcon({ className: `text-${type === 'start' ? 'success' : 'danger'}`, html: `<i class="fas fa-map-marker-alt fa-3x"></i>`, iconSize: [30, 42], iconAnchor: [15, 42] });
        const pointConfig = { latlng, marker: L.marker(latlng, { icon }).addTo(map) };

        if (type === 'start') {
            if (startPoint) map.removeLayer(startPoint.marker);
            startPoint = pointConfig;
            document.getElementById('start-point-text').textContent = pointText;
        } else {
            if (endPoint) map.removeLayer(endPoint.marker);
            endPoint = pointConfig;
            document.getElementById('end-point-text').textContent = pointText;
        }
        document.getElementById('calculate-route-btn').disabled = !(startPoint && endPoint);
    };
    
    const calculateAndCheckRoute = () => {
        if (!startPoint || !endPoint) return;
        clearAllRoutes(true);
        const infoContainer = document.getElementById('route-info-container');
        infoContainer.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Calculating direct route...';
        
        const directRouter = L.Routing.control({ waypoints: [startPoint.latlng, endPoint.latlng], createMarker: () => null }).on('routesfound', (e) => {
            const route = e.routes[0];
            routeLayers.addLayer(L.Routing.line(route, { styles: [{color: 'red', opacity: 0.8, weight: 6}] }));
            const closestFire = findClosestFireToRoute(route.coordinates);
            if (closestFire.distance < 2000) {
                infoContainer.innerHTML = `<div class="alert alert-danger"><strong>DANGER:</strong> Direct route is unsafe. Finding an alternative...</div>`;
                calculateAlternativeRoute(closestFire.fire);
            } else {
                infoContainer.innerHTML = `<div class="alert alert-success"><strong>Route Safe:</strong> The direct route appears clear of known fire zones.</div>`;
            }
        }).addTo(map);
        document.getElementById('clear-route-btn').style.display = 'block';
    };
    
    const calculateAlternativeRoute = (problemFire) => {
        const fireLatLng = L.latlng(problemFire.lat, problemFire.lng);
        const bearing = map.options.crs.bearing(startPoint.latlng, fireLatLng);
        const detourPoint = map.options.crs.destination(fireLatLng, bearing + 90, 3000);

        const altRouter = L.Routing.control({ waypoints: [startPoint.latlng, detourPoint, endPoint.latlng], createMarker: () => null }).on('routesfound', (e) => {
            routeLayers.addLayer(L.Routing.line(e.routes[0], { styles: [{color: 'blue', opacity: 0.8, weight: 6}] }));
            document.getElementById('route-info-container').innerHTML += `<div class="alert alert-success"><strong>Alternative Route Found (Blue):</strong> Review carefully.</div>`;
        }).on('routingerror', () => {
             document.getElementById('route-info-container').innerHTML += `<div class="alert alert-warning">Could not calculate an alternative.</div>`;
        }).addTo(map);
    };

    const findClosestFireToRoute = (routeCoords) => {
        let closestDistance = Infinity, closestFire = null;
        if (currentFires.length === 0) return { distance: Infinity, fire: null };
        routeCoords.forEach(coord => {
            const routePoint = L.latLng(coord.lat, coord.lng);
            currentFires.forEach(fire => {
                const distance = routePoint.distanceTo(L.latLng(fire.lat, fire.lng));
                if (distance < closestDistance) { closestDistance = distance; closestFire = fire; }
            });
        });
        return { distance: closestDistance, fire: closestFire };
    };
    
    const clearAllRoutes = (keepPoints = false) => {
        routeLayers.clearLayers();
        if (!keepPoints) {
            if (startPoint) map.removeLayer(startPoint.marker);
            if (endPoint) map.removeLayer(endPoint.marker);
            startPoint = null;
            endPoint = null;
            document.getElementById('start-point-text').textContent = 'Not set';
            document.getElementById('end-point-text').textContent = 'Not set';
            document.getElementById('calculate-route-btn').disabled = true;
        }
        document.getElementById('route-info-container').innerHTML = '';
        document.getElementById('clear-route-btn').style.display = 'none';
    };

    initMap();
});
</script>
</body>
</html>