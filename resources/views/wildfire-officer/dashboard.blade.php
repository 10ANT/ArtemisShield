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
    
    <!-- Leaflet.draw Plugin for drawing tools -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

    @include('partials.styles')
    <style>
        :root {
            --legend-width: 280px;
        }
        html, body {
            height: 100%;
            overflow: hidden;
        }
        .main-content {
            height: calc(100vh - 58px);
        }
        .wildfire-dashboard-container {
            height: 100%;
        }
        #map-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }
        #map {
            width: 100%;
            height: 100%;
            background-color: var(--bs-tertiary-bg);
        }

        /* Draggable & Collapsible Legend/Layers Panel */
        #layers-sidebar {
            position: absolute;
            top: 75px; /* Position below draw tools */
            left: 15px;
            width: var(--legend-width);
            z-index: 1001; /* High z-index to render over other elements */
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: .5rem;
            max-height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        #layers-sidebar-header {
            cursor: move;
        }
        #layers-sidebar-content {
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.2s ease-out;
            max-height: 500px;
        }
        #layers-sidebar.collapsed #layers-sidebar-content {
            max-height: 0;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            opacity: 0;
        }

        .legend-item { display: flex; align-items: center; font-size: 0.9rem; }
        .legend-icon { width: 30px; height: 20px; margin-right: 10px; text-align: center; }
        .legend-icon i, .legend-icon div { vertical-align: middle; }

        /* Leaflet control styling */
        .leaflet-control-container .leaflet-top.leaflet-right { top: 15px; right: 15px; }
        .leaflet-control-container .leaflet-top.leaflet-left { top: 15px; left: 15px; }

        /* Right sidebar (Chat/Controls) */
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; background-color: var(--bs-body-bg); }
        .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
        .chat-container { height: 100%; display: flex; flex-direction: column; }
        .chat-messages { flex-grow: 1; overflow-y: auto; }

        .loading-spinner { display: none; }
        .loading .loading-spinner { display: inline-block; }

        /* Modal styling */
        .modal-header, .modal-footer { border-color: var(--bs-border-color-translucent); }
        .weather-card { background-color: var(--bs-body-tertiary); border: 1px solid var(--bs-border-color); }
    </style>
</head>

<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            @include('partials.header')
            <div class="wildfire-dashboard-container row g-0 flex-grow-1">

                <!-- Map Column -->
                <div class="col">
                    <div id="map-wrapper">
                        <div id="map"></div>
                        
                        <div id="layers-sidebar">
                            <div id="layers-sidebar-header" class="card-header d-flex justify-content-between align-items-center p-2">
                                <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>Legend</h6>
                                <div>
                                    <div id="main-loader" class="loading-spinner spinner-border spinner-border-sm me-2" role="status"></div>
                                    <button id="sidebar-toggle" class="btn btn-sm btn-secondary py-0 px-1"><i class="fas fa-chevron-up"></i></button>
                                </div>
                            </div>
                            <div id="layers-sidebar-content" class="p-3">
                                <div class="mb-3">
                                    <h6>VIIRS Hotspots</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="viirs-snpp" data-source="VIIRS_SNPP_NRT" checked>
                                        <label class="form-check-label legend-item" for="viirs-snpp"><span class="legend-icon"><i class="fas fa-fire" style="color: #ff0000;"></i></span>VIIRS S-NPP</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="viirs-noaa20" data-source="VIIRS_NOAA20_NRT" checked>
                                        <label class="form-check-label legend-item" for="viirs-noaa20"><span class="legend-icon"><i class="fas fa-fire" style="color: #ffa500;"></i></span>VIIRS NOAA-20</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6>MODIS Hotspots</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="modis-nrt" data-source="MODIS_NRT">
                                        <label class="form-check-label legend-item" for="modis-nrt"><span class="legend-icon"><i class="fas fa-fire" style="color: #ff4500;"></i></span>MODIS</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h6>Drought Conditions</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="drought-layer">
                                        <label class="form-check-label legend-item" for="drought-layer"><span class="legend-icon"><div style="width: 15px; height: 15px; background: rgba(255, 255, 0, 0.5); border: 1px solid #ccc;"></div></span>Abnormally Dry</label>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i><span id="fire-count-display">Loading...</span></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar Column -->
                <div class="col-lg-3 col-md-4 border-start">
                    <div class="sidebar-wrapper">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-fill" id="sidebar-tabs" role="tablist">
                                <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#chat-content"><i class="fas fa-comments me-1"></i> Ask Artemis</button></li>
                                <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#control-content"><i class="fas fa-cogs me-1"></i> Control Panel</button></li>
                            </ul>
                        </div>
                        <div class="card-body d-flex flex-column p-0">
                            <div class="tab-content h-100">
                                <div class="tab-pane fade show active p-3 h-100" id="chat-content" role="tabpanel">
                                    <div class="chat-container">
                                        <div class="chat-messages mb-3" id="chat-messages"><!-- Chat messages go here --></div>
                                        <div class="chat-input-group d-flex gap-2">
                                            <input type="text" class="form-control" placeholder="Ask a question..." id="chat-input">
                                            <button class="btn btn-primary" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-3" id="control-content" role="tabpanel">
                                    <h6 class="text-body-secondary">Live Data</h6>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Active Fires <span class="badge text-bg-danger" id="active-fires-count">--</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">High Confidence <span class="badge text-bg-warning" id="high-confidence-count">--</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Last Updated <span class="badge text-bg-info" id="last-updated">--</span></li>
                                    </ul>
                                    <h6 class="text-body-secondary">Recent Detections</h6>
                                    <div id="recent-fires" class=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @include('partials.footer')
        </div>
    </div>
    
    <!-- Fire Details Modal -->
    <div class="modal fade" id="fire-details-modal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="fas fa-fire-alt text-danger"></i> Wildfire Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="fire-details-modal-body"></div></div></div></div>

    @include('partials.theme_settings')
    @include('partials.scripts')
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    
    <script>
        // --- Global Variables ---
        let map, fireDetailsModal, drawnItems, droughtLayer;
        const fireLayerGroups = {}, fireDataCache = {};
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                initializeMap();
                fireDetailsModal = new bootstrap.Modal(document.getElementById('fire-details-modal'));
                loadInitialData();
                setupEventListeners();
                startPeriodicUpdates();
            }, 250);
        });

        function initializeMap() {
            map = L.map('map', { renderer: L.canvas() }).setView([39.8283, -98.5795], 5);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '© OpenStreetMap © CARTO', maxZoom: 19 }).addTo(map);

            drawnItems = new L.FeatureGroup().addTo(map);
            new L.Control.Draw({ position: 'topleft', edit: { featureGroup: drawnItems } }).addTo(map);

            document.querySelectorAll('.layer-toggle[data-source]').forEach(toggle => {
                fireLayerGroups[toggle.dataset.source] = L.layerGroup();
                fireDataCache[toggle.dataset.source] = [];
            });
        }
        
        function setupEventListeners() {
            const sidebar = document.getElementById('layers-sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle');
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                toggleBtn.querySelector('i').className = sidebar.classList.contains('collapsed') ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
            });
            
            makeDraggable(sidebar, document.getElementById('layers-sidebar-header'));
            
            map.on(L.Draw.Event.CREATED, (event) => drawnItems.addLayer(event.layer));

            document.getElementById('chat-input').addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
            
            // ******** FIXED: COMPLETE EVENT LISTENER FOR ALL TOGGLES ********
            document.querySelectorAll('.layer-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const source = this.dataset.source;
                    
                    if (source) { // Handle all fire data layers
                        const layerGroup = fireLayerGroups[source];
                        if (this.checked) {
                            if (!map.hasLayer(layerGroup)) map.addLayer(layerGroup);
                            if (fireDataCache[source].length === 0) loadFireData(source);
                        } else {
                            if (map.hasLayer(layerGroup)) map.removeLayer(layerGroup);
                        }
                    } else if (this.id === 'drought-layer') { // Handle the drought layer specifically
                        toggleDroughtLayer(this.checked);
                    }
                });
            });
        }
        
        // ******** NEW/FIXED: Function to specifically handle the drought layer ********
        function toggleDroughtLayer(show) {
            if (show) {
                if (!droughtLayer) { // Create it if it doesn't exist
                    droughtLayer = L.rectangle([[30, -100], [35, -90]], {
                        color: "#FFC107", weight: 1, fillOpacity: 0.4
                    }).bindPopup("Drought Area: Abnormally Dry");
                }
                if (!map.hasLayer(droughtLayer)) map.addLayer(droughtLayer);
            } else {
                if (droughtLayer && map.hasLayer(droughtLayer)) map.removeLayer(droughtLayer);
            }
        }

        function makeDraggable(element, handle) {
            let isDragging = false, offsetX, offsetY;
            handle.addEventListener('mousedown', (e) => {
                isDragging = true;
                offsetX = e.clientX - element.offsetLeft;
                offsetY = e.clientY - element.offsetTop;
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
                e.preventDefault();
            });
            function onMouseMove(e) {
                if (!isDragging) return;
                let newX = Math.max(5, e.clientX - offsetX);
                let newY = Math.min(Math.max(5, e.clientY - offsetY), window.innerHeight - element.offsetHeight - 5);
                element.style.left = `${newX}px`;
                element.style.top = `${newY}px`;
            }
            function onMouseUp() { isDragging = false; document.removeEventListener('mousemove', onMouseMove); document.removeEventListener('mouseup', onMouseUp); }
        }
        
        function loadInitialData() {
            document.querySelectorAll('.layer-toggle[data-source]:checked').forEach(toggle => {
                map.addLayer(fireLayerGroups[toggle.dataset.source]);
                loadFireData(toggle.dataset.source);
            });
        }

        function startPeriodicUpdates() {
            setInterval(() => document.querySelectorAll('.layer-toggle[data-source]:checked').forEach(toggle => loadFireData(toggle.dataset.source)), 300000);
        }

        async function loadFireData(source) {
            document.getElementById('main-loader').classList.add('loading');
            try {
                const yesterday = new Date(); yesterday.setDate(yesterday.getDate() - 1);
                const response = await axios.get('/api/v1/fire-data', { params: { source, area: 'world', day_range: 1, date: yesterday.toISOString().split('T')[0] } });
                if (response.data.success) {
                    fireDataCache[source] = response.data.data;
                    updateFireLayer(source, response.data.data);
                    updateAllFireStats();
                    if(source === 'VIIRS_SNPP_NRT') updateRecentFires(response.data.data.slice(0, 5));
                }
            } catch (error) { console.error(`Failed to load fire data for ${source}:`, error);
            } finally { document.getElementById('main-loader').classList.remove('loading'); }
        }

        function updateFireLayer(source, fires) {
            const layerGroup = fireLayerGroups[source];
            layerGroup.clearLayers();
            fires.forEach(fire => {
                const marker = L.circleMarker([fire.latitude, fire.longitude], { radius: 5, fillColor: fire.intensity_color || '#ff0000', color: '#fff', weight: 0.5, opacity: 1, fillOpacity: 0.8, fireData: fire });
                marker.on('click', e => showFireDetailsModal(e.target.options.fireData));
                layerGroup.addLayer(marker);
            });
        }
        
        async function showFireDetailsModal(fire) {
            const modalBody = document.getElementById('fire-details-modal-body');
            modalBody.innerHTML = `<div>Loading details...</div>`;
            fireDetailsModal.show();
            let weatherHtml = '<p class="text-muted">Weather data unavailable.</p>';
            try {
                const response = await axios.get('/api/v1/weather-data', { params: { lat: fire.latitude, lon: fire.longitude } });
                if (response.data.success) {
                    const w = response.data.data;
                    weatherHtml = `<p class="mb-2 d-flex justify-content-between">Temperature: <strong>${w.temperature}°C</strong></p><p class="mb-2 d-flex justify-content-between">Humidity: <strong>${w.humidity}%</strong></p><p class="mb-0 d-flex justify-content-between">Winds: <strong>${w.wind_speed} km/h ${w.wind_direction}</strong></p>`;
                }
            } catch (error) { console.error('Failed to load weather for modal:', error); }
            modalBody.innerHTML = `<div class="d-flex justify-content-between align-items-start mb-3"><div><span class="badge text-bg-secondary">${fire.satellite || 'N/A'}</span><h4 class="mt-1">Detection at ${fire.latitude.toFixed(4)}, ${fire.longitude.toFixed(4)}</h4></div></div><div class="row g-2 text-center mb-4"><div class="col"><div class="p-2 bg-body-tertiary rounded"><small class="text-muted">CONFIDENCE</small><div class="fs-5 fw-bold text-warning">${fire.confidence}${typeof fire.confidence === 'number' ? '%' : ''}</div></div></div><div class="col"><div class="p-2 bg-body-tertiary rounded"><small class="text-muted">DETECTED</small><div class="fs-5 fw-bold">${fire.acq_date}</div></div></div><div class="col"><div class="p-2 bg-body-tertiary rounded"><small class="text-muted">SOURCE</small><div class="fs-5 fw-bold">${fire.source}</div></div></div></div><div class="row g-3"><div class="col-md-6"><div class="card weather-card h-100"><div class="card-body"><h6 class="card-title mb-3"><i class="fas fa-cloud-sun me-2"></i>Nearby Weather</h6>${weatherHtml}</div></div></div><div class="col-md-6"><div class="card weather-card h-100"><div class="card-body"><h6 class="card-title mb-3"><i class="fas fa-chart-line me-2"></i>Incident Data</h6><p class="mb-2 d-flex justify-content-between">Brightness (Temp): <strong>${fire.brightness} K</strong></p><p class="mb-0 d-flex justify-content-between">Fire Radiative Power: <strong>${fire.frp} MW</strong></p></div></div></div></div>`;
        }
        
        function updateAllFireStats() {
            let totalFires = 0, highConfidenceFires = 0;
            Object.values(fireDataCache).forEach(arr => { totalFires += arr.length; highConfidenceFires += arr.filter(f => (typeof f.confidence === 'number' && f.confidence >= 80) || (f.confidence?.toLowerCase() === 'high')).length; });
            document.getElementById('fire-count-display').textContent = `${totalFires.toLocaleString()} detections`;
            document.getElementById('active-fires-count').textContent = totalFires.toLocaleString();
            document.getElementById('high-confidence-count').textContent = highConfidenceFires.toLocaleString();
            document.getElementById('active-fires-chat').textContent = `${totalFires.toLocaleString()}`;
            document.getElementById('last-updated').textContent = new Date().toLocaleTimeString();
        }

        function updateRecentFires(recentFires) {
           const container = document.getElementById('recent-fires');
           container.innerHTML = (recentFires.length === 0) ? '<p class="text-muted small">No recent detections.</p>' : '';
           recentFires.forEach(fire => {
               const fireCard = document.createElement('div');
               fireCard.className = 'card bg-body-tertiary mb-2 list-group-item-action';
               fireCard.style.cursor = 'pointer';
               fireCard.innerHTML = `<div class="card-body p-2"><div class="d-flex justify-content-between align-items-start"><div><h6 class="card-title mb-1 small">${fire.satellite} Detection</h6><p class="card-text mb-1 small text-muted"><i class="fas fa-map-marker-alt me-1"></i>${fire.latitude.toFixed(2)}, ${fire.longitude.toFixed(2)}</p></div><span class="badge text-bg-${fire.confidence_level === 'high' ? 'success' : 'warning'}">${fire.confidence_level}</span></div></div>`;
               fireCard.addEventListener('click', () => { map.setView([fire.latitude, fire.longitude], 12); showFireDetailsModal(fire); });
               container.appendChild(fireCard);
           });
       }

       function sendMessage() {
           const input = document.getElementById('chat-input'), messageText = input.value.trim();
           if (!messageText) return;
           const msgContainer = document.getElementById('chat-messages');
           msgContainer.innerHTML += `<div class="mb-3 text-end"><div class="p-3 rounded mt-1 bg-primary-subtle d-inline-block">${messageText}</div></div>`;
           input.value = ''; msgContainer.scrollTop = msgContainer.scrollHeight;
           setTimeout(() => {
               const response = `Based on current data, I can provide information about the ${document.getElementById('active-fires-count').textContent} active fire detections. How can I help?`;
               msgContainer.innerHTML += `<div class="mb-3 text-start"><small class="text-body-secondary">Artemis AI</small><div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">${response}</div></div>`;
               msgContainer.scrollTop = msgContainer.scrollHeight;
           }, 800);
       }
   </script>
</body>
</html>