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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

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

        .leaflet-draw-toolbar a,
        .leaflet-draw-actions a {
            background-color: white !important;
            color: #333 !important;
            border-color: #bbb !important;
            box-shadow: 0 1px 5px rgba(0,0,0,0.2);
        }
        .leaflet-draw-toolbar a:hover,
        .leaflet-draw-actions a:hover {
            background-color: #f4f4f4 !important;
        }
        .leaflet-popup-content-wrapper, .leaflet-popup-tip {
            background: var(--bs-tertiary-bg) !important;
            color: var(--bs-body-color) !important;
            box-shadow: 0 3px 14px rgba(0,0,0,0.4);
        }
        .coord-tooltip {
            background-color: rgba(var(--bs-dark-rgb), 0.8) !important;
            border: 1px solid rgba(var(--bs-light-rgb), 0.5) !important;
            color: var(--bs-light) !important;
        }
        #vision-ai-result-image {
            max-width: 100%;
            height: auto;
            border-radius: var(--bs-border-radius);
            border: 1px solid var(--bs-border-color);
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
            <div class="map-wrapper"><div id="map"></div></div>
        </div>
        <div class="col-lg-4 col-md-12 border-start" id="right-sidebar-column">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-fill flex-nowrap" id="sidebar-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="report-tab-btn" data-bs-toggle="pill" data-bs-target="#report-content" type="button" role="tab" aria-controls="report-content" aria-selected="true" title="Live Fire Report"><i class="fas fa-fire-alt me-1"></i> Live Fire</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="vision-ai-tab-btn" data-bs-toggle="pill" data-bs-target="#vision-ai-content" type="button" role="tab" aria-controls="vision-ai-content" aria-selected="false" title="Vision AI Analysis"><i class="fas fa-eye me-1"></i> Vision AI</button></li>
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
                            <div id="report-location-display" class="mb-2"></div>
                            <div id="report-placeholder" class="text-center text-muted mt-4"><i class="fas fa-map-marked-alt fa-2x mb-2"></i><p>Use 'Select Location' to get a live fire report.</p></div>
                            <div id="report-container" class="accordion"></div>
                        </div>
                        <!-- VISION AI TAB CONTENT -->
                        <div class="tab-pane fade p-3" id="vision-ai-content" role="tabpanel">
                             <div class="d-grid mb-3">
                                <button class="btn btn-info" id="enable-vision-ai-selection-btn"><i class="fas fa-camera-retro me-2"></i>Analyze Area with AI</button>
                            </div>
                            <div id="vision-ai-placeholder" class="text-center text-muted mt-4">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p>Use 'Analyze Area' to get an AI-powered wildfire risk assessment from a satellite image.</p>
                            </div>
                            <div id="vision-ai-result-container" class="d-none"></div>
                        </div>
                        <!-- RISK FORECAST TAB -->
                        <div class="tab-pane fade p-3" id="forecast-content" role="tabpanel">
                            <div class="d-grid mb-3">
                                <button class="btn btn-primary" id="enable-forecast-selection-btn"><i class="fas fa-crosshairs me-2"></i>Select Location</button>
                            </div>
                            <div id="forecast-location-display" class="mb-2"></div>
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
    let drawnItems = L.featureGroup();
    let settingPoint = null, startPoint = null, endPoint = null;
    let currentFires = [];
    let selectionMode = null;

    const fireCategoryMap = {
        d: "Drought", ffmc: "Fine Fuel Moisture Code", dmc: "Duff Moisture Code", dc: "Drought Code", 
        bui: "Buildup Index", fwi: "Fire Weather Index", dsr: "Daily Severity Rating", W: "Wind", 
        T: "Temperature", H: "Humidity", P: "Precipitation"
    };

    const initMap = () => {
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
        const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri', crossOrigin: true });
        baseMaps = { 
            "Streets": streetMap, 
            "Satellite": satelliteMap, 
            "Dark Mode": L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' })
        };
        
        map = L.map('map', { center: [34.05, -118.25], zoom: 9, layers: [streetMap] });
        map.addLayer(fireMarkersLayer);
        map.addLayer(routeLayers);
        map.addLayer(drawnItems);
        map.on('click', handleMapClick);

        map.addControl(new L.Control.Draw({
            edit: { featureGroup: drawnItems },
            draw: {
                polygon: false, marker: false, circlemarker: false,
                rectangle: { shapeOptions: { color: '#0dcaf0' } },
                circle: { shapeOptions: { color: '#0dcaf0' } },
                polyline: { shapeOptions: { color: '#0dcaf0' } }
            }
        }));
        map.on(L.Draw.Event.CREATED, handleDrawingCreated);
        setupUI();
    };

    const setupUI = () => {
        const basemapContainer = document.getElementById('basemap-selector-container');
        Object.keys(baseMaps).forEach(name => {
            const id = `basemap-radio-${name.replace(/\s+/g, '-')}`;
            basemapContainer.innerHTML += `<div class="form-check"><input class="form-check-input" type="radio" name="basemap-selector" id="${id}" value="${name}" ${name === "Streets" ? 'checked' : ''}><label class="form-check-label" for="${id}">${name}</label></div>`;
        });
        basemapContainer.addEventListener('change', (e) => { 
            Object.values(baseMaps).forEach(layer => map.removeLayer(layer)); 
            map.addLayer(baseMaps[e.target.value]); 
        });
        
        document.getElementById('enable-live-selection-btn').addEventListener('click', () => setSelectionMode('live'));
        document.getElementById('enable-forecast-selection-btn').addEventListener('click', () => setSelectionMode('forecast'));
        document.getElementById('enable-vision-ai-selection-btn').addEventListener('click', () => setSelectionMode('vision'));
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
        if (clickMarker) map.removeLayer(clickMarker);
        
        document.getElementById('report-location-display').innerHTML = '';
        document.getElementById('forecast-location-display').innerHTML = '';

        if (selectionMode.startsWith('routing')) {
            await handleRoutingPointPlacement(e.latlng);
        } else if (selectionMode === 'vision') {
            await analyzeImageWithAI(e.latlng);
        } else {
            clickMarker = L.marker(e.latlng, { opacity: 0.7 }).addTo(map);
            const address = await getAddressFromLatLng(e.latlng);
            const addressHtml = `<div class="alert alert-info py-2"><strong>Selected Location:</strong><br>${address}</div>`;
            if (selectionMode === 'live') {
                document.getElementById('report-location-display').innerHTML = addressHtml;
                document.getElementById('report-placeholder').innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Fetching live fires...</p>';
                fetchAndDisplayFires(e.latlng);
            } else if (selectionMode === 'forecast') {
                document.getElementById('forecast-location-display').innerHTML = addressHtml;
                document.getElementById('forecast-placeholder').innerHTML = '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Fetching risk forecast...</p>';
                fetchAndDisplayRisk(e.latlng);
            }
        }
        
        selectionMode = null;
        document.getElementById('map').classList.remove('selection-active');
    };

    const getAddressFromLatLng = async (latlng) => {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`);
            const data = await response.json();
            return data.display_name || `${latlng.lat.toFixed(4)}, ${latlng.lng.toFixed(4)}`;
        } catch (error) { return `${latlng.lat.toFixed(4)}, ${latlng.lng.toFixed(4)}`; }
    };

    const formatFieldName = (key) => {
        const mappedName = fireCategoryMap[key];
        if (mappedName) return `${key.toUpperCase()} (${mappedName})`;
        const result = key.replace(/_/g, ' ').replace(/([a-z])([A-Z])/g, '$1 $2');
        return result.charAt(0).toUpperCase() + result.slice(1);
    };
    
    const fetchAndDisplayFires = async (latlng) => {
        const placeholder = document.getElementById('report-placeholder');
        try {
            const response = await fetch(`/api/ambee/fire-data?lat=${latlng.lat}&lng=${latlng.lng}`);
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Request failed');
            
            placeholder.innerHTML = '';
            document.getElementById('report-container').innerHTML = '';
            
            if (data.message === 'success' && data.data.length > 0) {
                currentFires = data.data;
                fireMarkersLayer.clearLayers();
                data.data.forEach((fire, index) => {
                    L.marker([fire.lat, fire.lng], { icon: L.divIcon({ className: 'text-danger', html: '<i class="fas fa-fire-alt fa-2x"></i>', iconSize: [24, 24] }) }).addTo(fireMarkersLayer).bindPopup(`<b>${fire.fireName || 'Unnamed Fire'}</b>`);
                    const fwi = fire.fwi ? parseFloat(fire.fwi).toFixed(1) : 'N/A';
                    document.getElementById('report-container').innerHTML += createAccordionItem(`live-fire-${index}`, fire.fireName || 'Unnamed Fire', new Date(fire.detectedAt).toLocaleString(), `FWI: ${fwi}`, fwi >= 40 ? 'danger' : fwi >= 20 ? 'warning' : 'success', fire);
                });
            } else {
                placeholder.innerHTML = '<div class="alert alert-success">No active fires found near this location.</div>';
            }
        } catch (error) { placeholder.innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${error.message}</div>`; }
    };

    const fetchAndDisplayRisk = async (latlng) => {
        const placeholder = document.getElementById('forecast-placeholder');
        try {
            const response = await fetch(`/api/ambee/fire-risk?lat=${latlng.lat}&lng=${latlng.lng}`);
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Request failed');

            placeholder.innerHTML = '';
            document.getElementById('forecast-container').innerHTML = '';
            
            if (data.message === 'success' && data.data.length > 0) {
                data.data.slice(0, 4).forEach((risk, index) => {
                    const riskColor = risk.predicted_risk_category === 'high' ? 'danger' : risk.predicted_risk_category === 'moderate' ? 'warning' : 'success';
                    document.getElementById('forecast-container').innerHTML += createAccordionItem(`forecast-${index}`, `Week ${risk.week} Forecast`, risk.predicted_risk_category, `Avg Temp: ${risk.temperature.toFixed(1)}°C`, riskColor, risk);
                });
            } else {
                placeholder.innerHTML = '<div class="alert alert-secondary">No risk forecast data available for this location.</div>';
            }
        } catch (error) { placeholder.innerHTML = `<div class="alert alert-danger"><strong>Error:</strong> ${error.message}</div>`; }
    };

    const createAccordionItem = (id, title, subtitle, badgeText, badgeColor, rawData) => {
        let rawDataHtml = '<table class="table table-bordered table-sm raw-data-table"><tbody>';
        for (const key in rawData) {
            let value = rawData[key];
            if (typeof value === 'object' && value !== null) value = `<pre>${JSON.stringify(value, null, 2)}</pre>`;
            rawDataHtml += `<tr><td><strong>${formatFieldName(key)}</strong></td><td>${value}</td></tr>`;
        }
        rawDataHtml += '</tbody></table>';
        return `<div class="accordion-item bg-transparent border-bottom"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${id}"><div class="w-100 d-flex justify-content-between align-items-center"><div><strong class="d-block">${title}</strong><small class="text-muted">${subtitle}</small></div><span class="badge text-bg-${badgeColor} me-2">${badgeText}</span></div></button></h2><div id="collapse-${id}" class="accordion-collapse collapse"><div class="accordion-body">${rawDataHtml}</div></div></div>`;
    };

    const clearLiveFireData = () => {
        currentFires = [];
        fireMarkersLayer.clearLayers();
        document.getElementById('report-container').innerHTML = '';
        document.getElementById('report-location-display').innerHTML = '';
        document.getElementById('report-placeholder').innerHTML = '<i class="fas fa-map-marked-alt fa-2x mb-2"></i><p>Use \'Select Location\' to get a live fire report.</p>';
    };

    // --- Vision AI Analysis Logic ---
    const analyzeImageWithAI = async (latlng) => {
        const placeholder = document.getElementById('vision-ai-placeholder');
        const resultContainer = document.getElementById('vision-ai-result-container');
        placeholder.innerHTML = '<div class="spinner-border text-info" role="status"></div><p class="mt-2">Analyzing satellite imagery...</p>';
        resultContainer.classList.add('d-none');

        const zoom = 15; 
        const tile = latLngToTile(latlng, zoom);
        const tileBounds = tileToBounds(tile.x, tile.y, zoom);
        const imageUrl = `https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/${zoom}/${tile.y}/${tile.x}`;

        try {
            // Fetch the image and convert it to Base64
            const imageB64 = await imageUrlToBase64(imageUrl);

            const response = await fetch('/api/ambee/classify-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ image_b64: imageB64 })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || `Server returned status ${response.status}`);
            }
            
            const result = await response.json();
            displayAIResults(result, imageB64, tileBounds); // Use base64 data for local display

        } catch (error) {
            placeholder.innerHTML = `<div class="alert alert-danger"><strong>AI Analysis Failed:</strong> ${error.message}</div>`;
        }
    };
    
    const imageUrlToBase64 = async (url) => {
        const response = await fetch(url);
        const blob = await response.blob();
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(blob);
        });
    };

    const displayAIResults = (result, imageB64, tileBounds) => {
        const placeholder = document.getElementById('vision-ai-placeholder');
        const resultContainer = document.getElementById('vision-ai-result-container');
        placeholder.innerHTML = '';
        
        const isRisk = result.predicted_class === 1;
        const confidence = result.confidence_score * 100;
        const resultClass = isRisk ? 'danger' : 'success';
        const resultText = isRisk ? 'High Wildfire Risk Detected' : 'No Immediate Wildfire Risk Detected';

        resultContainer.innerHTML = `
            <div class="alert alert-${resultClass}">
                <h5 class="alert-heading">${resultText}</h5>
                <p>The AI model is <strong>${confidence.toFixed(1)}%</strong> confident in this assessment.</p>
            </div>
            <p class="text-muted small">Analyzed Image:</p>
            <img src="${imageB64}" id="vision-ai-result-image" alt="Analyzed satellite image">
        `;
        resultContainer.classList.remove('d-none');

        L.rectangle(tileBounds, { color: '#0dcaf0', weight: 2, fillOpacity: 0.1 }).addTo(drawnItems)
            .bindPopup(`<b>AI Analysis Area</b><br>Prediction: ${resultText}<br>Confidence: ${confidence.toFixed(1)}%`).openPopup();
    };

    const latLngToTile = (latlng, zoom) => {
        const latRad = latlng.lat * Math.PI / 180;
        const n = Math.pow(2, zoom);
        const xtile = Math.floor(n * ((latlng.lng + 180) / 360));
        const ytile = Math.floor(n * (1 - (Math.log(Math.tan(latRad) + 1/Math.cos(latRad)) / Math.PI)) / 2);
        return { x: xtile, y: ytile };
    };

    const tileToBounds = (x, y, zoom) => {
        const n = Math.pow(2, zoom);
        const lon1 = x / n * 360 - 180;
        const lat1Rad = Math.atan(Math.sinh(Math.PI * (1 - 2 * y / n)));
        const lat1 = lat1Rad * 180 / Math.PI;
        const lon2 = (x + 1) / n * 360 - 180;
        const lat2Rad = Math.atan(Math.sinh(Math.PI * (1 - 2 * (y + 1) / n)));
        const lat2 = lat2Rad * 180 / Math.PI;
        return L.latLngBounds(L.latLng(lat1, lon1), L.latLng(lat2, lon2));
    };

    const handleDrawingCreated = (e) => {
        const layer = e.layer; const type = e.layerType; const formatDistance = (m) => m > 1000 ? `${(m / 1000).toFixed(2)} km` : `${m.toFixed(2)} m`; const formatArea = (sqM) => sqM > 1000000 ? `${(sqM / 1000000).toFixed(2)} km²` : `${sqM.toFixed(2)} m²`;
        if (type === 'rectangle') {
            const bounds = layer.getBounds(); const topRight = bounds.getNorthEast(); const topLeft = bounds.getNorthWest(); const bottomRight = bounds.getSouthEast(); const width = topLeft.distanceTo(topRight); const height = topRight.distanceTo(bottomRight);
            layer.bindPopup(`<strong>Rectangle Details:</strong><br>Area: ${formatArea(width * height)}<br>Perimeter: ${formatDistance(2 * (width + height))}<br>Width: ${formatDistance(width)}<br>Height: ${formatDistance(height)}<br>Top-Right: ${topRight.lat.toFixed(4)}, ${topRight.lng.toFixed(4)}`);
            drawnItems.addLayer(layer);
        } else if (type === 'circle') {
            const center = layer.getLatLng(); const radius = layer.getRadius();
            layer.bindPopup(`<strong>Circle Details:</strong><br>Area: ${formatArea(Math.PI * Math.pow(radius, 2))}<br>Radius: ${formatDistance(radius)}<br>Center: ${center.lat.toFixed(4)}, ${center.lng.toFixed(4)}`);
            drawnItems.addLayer(layer);
        } else if (type === 'polyline') {
            const latlngs = layer.getLatLngs(); let distance = 0; for (let i = 0; i < latlngs.length - 1; i++) { distance += latlngs[i].distanceTo(latlngs[i + 1]); }
            const lineGroup = L.featureGroup().addLayer(layer);
            latlngs.forEach(latlng => { L.circleMarker(latlng, { radius: 1, opacity: 0 }).bindTooltip(`${latlng.lat.toFixed(4)}, ${latlng.lng.toFixed(4)}`, { permanent: true, direction: 'top', offset: [0, -10], className: 'coord-tooltip' }).addTo(lineGroup); });
            lineGroup.bindPopup(`<strong>Total Line Distance:</strong><br>${formatDistance(distance)}`);
            drawnItems.addLayer(lineGroup);
        }
    };
    
    const handleRoutingPointPlacement = async (latlng) => {
        const type = selectionMode.split('-')[1]; const pointTextSpan = document.getElementById(`${type}-point-text`); pointTextSpan.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        const address = await getAddressFromLatLng(latlng); const icon = L.divIcon({ className: `text-${type === 'start' ? 'success' : 'danger'}`, html: `<i class="fas fa-map-marker-alt fa-3x"></i>`, iconSize: [30, 42], iconAnchor: [15, 42] }); const pointConfig = { latlng, marker: L.marker(latlng, { icon }).addTo(map) };
        if (type === 'start') { if (startPoint) map.removeLayer(startPoint.marker); startPoint = pointConfig; } else { if (endPoint) map.removeLayer(endPoint.marker); endPoint = pointConfig; }
        pointTextSpan.textContent = address; document.getElementById('calculate-route-btn').disabled = !(startPoint && endPoint);
    };

    const calculateAndCheckRoute = () => {
        if (!startPoint || !endPoint) return; clearAllRoutes(true); const info = document.getElementById('route-info-container'); info.innerHTML = '<div class="spinner-border spinner-border-sm"></div> Calculating...';
        L.Routing.control({ waypoints: [startPoint.latlng, endPoint.latlng], createMarker: () => null }).on('routesfound', (e) => {
            const route = e.routes[0]; routeLayers.addLayer(L.Routing.line(route, { styles: [{color: 'red', opacity: 0.8, weight: 6}] })); const closest = findClosestFireToRoute(route.coordinates);
            if (closest.distance < 2000) { info.innerHTML = `<div class="alert alert-danger"><strong>DANGER:</strong> Route is unsafe. Finding alternative...</div>`; calculateAlternativeRoute(closest.fire); } else { info.innerHTML = `<div class="alert alert-success"><strong>Route Safe.</strong></div>`; }
        }).addTo(map); document.getElementById('clear-route-btn').style.display = 'block';
    };

    const calculateAlternativeRoute = (fire) => {
        const detour = map.options.crs.destination(L.latLng(fire.lat, fire.lng), map.options.crs.bearing(startPoint.latlng, L.latLng(fire.lat, fire.lng)) + 90, 3000);
        L.Routing.control({ waypoints: [startPoint.latlng, detour, endPoint.latlng], createMarker: () => null }).on('routesfound', (e) => {
            routeLayers.addLayer(L.Routing.line(e.routes[0], { styles: [{color: 'blue', opacity: 0.8, weight: 6}] })); document.getElementById('route-info-container').innerHTML += `<div class="alert alert-success"><strong>Alternative Route Found (Blue).</strong></div>`;
        }).on('routingerror', () => { document.getElementById('route-info-container').innerHTML += `<div class="alert alert-warning">Could not calculate an alternative.</div>`; }).addTo(map);
    };

    const findClosestFireToRoute = (coords) => {
        let closest = { distance: Infinity, fire: null }; if (currentFires.length === 0) return closest;
        coords.forEach(c => { currentFires.forEach(f => { const d = L.latLng(c.lat, c.lng).distanceTo(L.latLng(f.lat, f.lng)); if (d < closest.distance) { closest = { distance: d, fire: f }; } }); });
        return closest;
    };
    
    const clearAllRoutes = (keepPoints = false) => {
        routeLayers.clearLayers();
        if (!keepPoints) { if (startPoint) map.removeLayer(startPoint.marker); if (endPoint) map.removeLayer(endPoint.marker); startPoint = endPoint = null; document.getElementById('start-point-text').textContent = 'Not set'; document.getElementById('end-point-text').textContent = 'Not set'; document.getElementById('calculate-route-btn').disabled = true; }
        document.getElementById('route-info-container').innerHTML = ''; document.getElementById('clear-route-btn').style.display = 'none';
    };

    initMap();
});
</script>
</body>
</html>