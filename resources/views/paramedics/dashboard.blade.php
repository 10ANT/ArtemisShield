<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PulsePoint Command - Emergency Medical Response Dashboard</title>
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<!-- Scripts that need to load in the <head> -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


@include('partials.styles')
<style>
    /* Main layout styles */
    .main-content { flex-grow: 1; }
    .ems-dashboard-container { height: 100%; }
    #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); }
    .map-wrapper { position: relative; height: 100%; overflow: hidden; }
    .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
    .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
    
    /* Check-in tab styles */
    #victim-check-in-content { display: flex; flex-direction: column; height: 100%; }
    #alert-accordion-container { overflow-y: auto; flex-grow: 1; }
    .alert-accordion .accordion-header { display: flex; align-items: center; }
    .alert-accordion .accordion-button { font-size: 0.9rem; font-weight: 600; flex-grow: 1; }
    .alert-accordion .accordion-body { padding: 0.5rem; background-color: var(--bs-tertiary-bg); }
    .alert-accordion .nav-tabs .nav-link { font-size: 0.85rem; padding: 0.5rem 0.75rem; }
    .user-status-list { max-height: 300px; overflow-y: auto; }
    .user-status-item .status-badge { font-size: 0.75rem; }

    /* Map marker styles */
    .user-status-dot { height: 12px; width: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.7); }
    .user-status-dot-ok { background-color: var(--bs-success); }
    .user-status-dot-help { background-color: var(--bs-danger); }
    .user-status-dot-threat { background-color: var(--bs-warning); }
    .user-status-dot-awaiting { background-color: var(--bs-secondary); }

    /* NEW: Route info box styles */
    #route-info-box {
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        background: rgba(43, 48, 53, 0.9);
        color: #fff;
        padding: 8px 15px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.2);
        display: none; /* Hidden by default */
    }
    #route-info-box .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Responsive styles */
    #map-column, #right-sidebar-column { transition: flex 0.3s ease-in-out, width 0.3s ease-in-out, padding 0.3s ease-in-out, border 0.3s ease-in-out; }
    #right-sidebar-toggle { position: absolute; top: 1rem; right: 1rem; z-index: 1001; display: none; }
    .ems-dashboard-container.right-sidebar-collapsed #right-sidebar-column { flex: 0 0 0; width: 0; overflow: hidden; border: none !important; padding: 0 !important; }
    .ems-dashboard-container.right-sidebar-collapsed #map-column { flex: 0 0 100%; max-width: 100%; }
    
    @media (max-width: 991.98px) { .ems-dashboard-container { height: auto; } #map { min-height: 65vh; height: 65vh; } #right-sidebar-column { height: auto; border-top: 1px solid var(--bs-border-color) !important; } .sidebar-wrapper { height: auto; } }
    @media (min-width: 992px) { #right-sidebar-toggle { display: block; } }
</style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
<div class="main-content d-flex flex-column">
    
    @include('partials.header')

    <div class="ems-dashboard-container row g-0 flex-grow-1">
        <div class="col-lg-8 col-md-12" id="map-column">
            <div class="map-wrapper">
                <div id="map"></div>
                <!-- NEW: Route Info Box -->
                <div id="route-info-box" class="d-flex align-items-center gap-3">
                    <div id="route-info-text"></div>
                    <button type="button" class="btn-close btn-sm" id="clear-route-btn" aria-label="Close"></button>
                </div>
                <button id="right-sidebar-toggle" class="btn btn-dark" type="button" title="Hide Sidebar">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 border-start" id="right-sidebar-column">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-fill flex-nowrap" id="sidebar-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="checkin-tab-btn" data-bs-toggle="pill" data-bs-target="#victim-check-in-content" type="button" role="tab"><i class="fas fa-user-check me-1"></i> Check-in</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="config-tab-btn" data-bs-toggle="pill" data-bs-target="#config-content" type="button" role="tab"><i class="fas fa-cogs me-1"></i> Settings</button></li>
                    </ul>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="tab-content h-100">
                        <div class="tab-pane fade show active" id="victim-check-in-content" role="tabpanel">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-broadcast-tower me-2"></i> Active Alert Zones</h5>
                                <button class="btn btn-sm btn-secondary" id="refresh-alerts-btn" title="Refresh Alert List">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="p-3 border-bottom">
                                <input type="text" id="alert-search-input" class="form-control" placeholder="Search alerts by message...">
                            </div>
                            <div id="alert-accordion-container" class="alert-accordion">
                                <div class="text-center p-5 text-muted">
                                    <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
                                    <p class="mt-2">Fetching relevant alerts...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- UPDATED SETTINGS TAB -->
                        <div class="tab-pane fade p-3" id="config-content" role="tabpanel">
                            <h5 class="mb-3"><i class="fas fa-map me-2"></i> Base Map View</h5>
                            <div id="basemap-selector-container" class="btn-group w-100" role="group">
                                <!-- Buttons will be injected by JavaScript -->
                            </div>
                            <hr class="my-4">
                            <h5 class="mb-3"><i class="fas fa-layer-group me-2"></i> Map Layers</h5>
                            <div class="text-muted small">
                                <p>Additional map layers (e.g., AEDs, Hospitals) can be configured by the system administrator.</p>
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

<script src="https://unpkg.com/draggabilly@3/dist/draggabilly.pkgd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let map;
        let affectedUserLayers = {};
        let userMarkers = {};
        let currentRouteLayer = null; // NEW: To keep track of the active route layer
        let commandUserLocation = null; // NEW: To store command user's location

        const initMap = () => {
            console.log('Map initialization started.');
            const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
            const darkMode = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
            
            // Store base layers for the switcher
            const baseLayers = {
                'Street': streets,
                'Dark': darkMode,
                'Satellite': satellite
            };
            
            map = L.map('map', { center: [41.8781, -87.6298], zoom: 6, layers: [streets] }); // Default to streets
            
            // **NEW: Base Map Switcher Logic**
            const basemapContainer = document.getElementById('basemap-selector-container');
            Object.keys(baseLayers).forEach((layerName, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `btn ${index === 0 ? 'btn-primary' : 'btn-outline-primary'}`;
                button.textContent = layerName;
                button.dataset.layer = layerName;
                basemapContainer.appendChild(button);

                button.addEventListener('click', () => {
                    // Remove all other base layers
                    Object.values(baseLayers).forEach(layer => {
                        if (map.hasLayer(layer)) {
                            map.removeLayer(layer);
                        }
                    });
                    // Add the selected one
                    map.addLayer(baseLayers[layerName]);

                    // Update button styles
                    basemapContainer.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    });
                    button.classList.add('btn-primary');
                    button.classList.remove('btn-outline-primary');
                });
            });

            console.log('Map initialization finished.');
        };

        const initSidebarToggles = () => { const rightSidebarToggle = document.getElementById('right-sidebar-toggle'); const dashboardContainer = document.querySelector('.ems-dashboard-container'); if(rightSidebarToggle && dashboardContainer) { rightSidebarToggle.addEventListener('click', () => { dashboardContainer.classList.toggle('right-sidebar-collapsed'); const isCollapsed = dashboardContainer.classList.contains('right-sidebar-collapsed'); const icon = rightSidebarToggle.querySelector('i'); if(isCollapsed) { icon.className = 'fas fa-chevron-left'; rightSidebarToggle.title = 'Show Sidebar'; } else { icon.className = 'fas fa-chevron-right'; rightSidebarToggle.title = 'Hide Sidebar'; } setTimeout(() => { if(map) map.invalidateSize(); }, 300); }); } };
        
        const initVictimCheckIn = () => {
            const accordionContainer = document.getElementById('alert-accordion-container');
            const refreshBtn = document.getElementById('refresh-alerts-btn');
            const searchInput = document.getElementById('alert-search-input');
            const clearRouteBtn = document.getElementById('clear-route-btn');

            const fetchAndRenderAlerts = async () => {
                accordionContainer.innerHTML = `<div class="text-center p-5 text-muted"><div class="spinner-border" role="status"></div><p class="mt-2">Fetching relevant alerts...</p></div>`;
                Object.values(affectedUserLayers).forEach(layer => map.removeLayer(layer));
                affectedUserLayers = {}; userMarkers = {};

                try {
                    const response = await axios.get("{{ route('command.relevant-alerts') }}");
                    const data = response.data;
                    commandUserLocation = data.command_user_location; // **NEW: Store command location**

                    if (!data.alerts || data.alerts.length === 0) {
                        accordionContainer.innerHTML = `<div class="text-center p-5 text-muted"><i class="fas fa-check-circle fa-2x mb-2"></i><p>No active alert zones found at your location.</p></div>`;
                        return;
                    }
                    renderAlertsAccordion(data.alerts);
                } catch (error) {
                    console.error("Failed to fetch relevant alerts:", error);
                    accordionContainer.innerHTML = `<div class="alert alert-danger m-3">Failed to load alert data. Please try again.</div>`;
                }
            };
            
            const renderAlertsAccordion = (alerts) => {
                let accordionHtml = '<div class="accordion" id="alerts-master-accordion">';
                alerts.forEach(alert => {
                    accordionHtml += `
                        <div class="accordion-item" data-alert-message="${alert.message.toLowerCase()}">
                            <h2 class="accordion-header" id="heading-alert-${alert.id}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-alert-${alert.id}"><i class="fas fa-exclamation-triangle me-2 text-warning"></i> ${alert.message}</button>
                                <button class="btn btn-sm btn-outline-info ms-auto me-2 my-auto flex-shrink-0 zoom-to-alert-btn" title="View Alert Zone" data-lat="${alert.latitude}" data-lng="${alert.longitude}" data-radius="${alert.radius}"><i class="fas fa-search-location"></i></button>
                            </h2>
                            <div id="collapse-alert-${alert.id}" class="accordion-collapse collapse" data-alert-id="${alert.id}" data-loaded="false">
                                <div class="accordion-body"><div class="text-center p-3"><div class="spinner-border spinner-border-sm"></div></div></div>
                            </div>
                        </div>`;
                });
                accordionHtml += '</div>';
                accordionContainer.innerHTML = accordionHtml;

                accordionContainer.addEventListener('click', handleActionButtons);
                const accordionElement = document.getElementById('alerts-master-accordion');
                accordionElement.addEventListener('show.bs.collapse', handleAccordionOpen);
                accordionElement.addEventListener('hide.bs.collapse', handleAccordionClose);
            };

            const handleActionButtons = (event) => {
                const zoomAlertBtn = event.target.closest('.zoom-to-alert-btn');
                const locateUserBtn = event.target.closest('.locate-user-btn');
                const routeUserBtn = event.target.closest('.route-user-btn'); // **NEW**

                if (zoomAlertBtn) { event.stopPropagation(); const lat = parseFloat(zoomAlertBtn.dataset.lat); const lng = parseFloat(zoomAlertBtn.dataset.lng); const radius = parseFloat(zoomAlertBtn.dataset.radius); if (map && !isNaN(lat) && !isNaN(lng) && !isNaN(radius)) { const alertCircle = L.circle([lat, lng], { radius: radius }); map.fitBounds(alertCircle.getBounds()); } }
                if (locateUserBtn) { const lat = parseFloat(locateUserBtn.dataset.lat); const lng = parseFloat(locateUserBtn.dataset.lng); const userId = locateUserBtn.dataset.userId; if (map && !isNaN(lat) && !isNaN(lng)) { map.flyTo([lat, lng], 16); if (userMarkers[userId]) { userMarkers[userId].openPopup(); } } }
                if (routeUserBtn) { const lat = parseFloat(routeUserBtn.dataset.lat); const lng = parseFloat(routeUserBtn.dataset.lng); if (commandUserLocation && !isNaN(lat) && !isNaN(lng)) { calculateAndShowRoute(commandUserLocation, { latitude: lat, longitude: lng }); } else { alert('Cannot calculate route. Your location or the target location is unknown.'); } }
            };
            
            const handleAccordionOpen = async (event) => {
                const accordionBody = event.target.querySelector('.accordion-body');
                const alertId = event.target.dataset.alertId;
                if (!alertId || event.target.dataset.loaded === 'true') return;
                
                try {
                    const response = await axios.get(`/alerts/${alertId}/affected-users`);
                    event.target.dataset.loaded = 'true';
                    renderUserTabsAndList(alertId, response.data);
                    plotUsersOnMap(alertId, response.data);
                } catch(error) { console.error(`Failed to fetch users for alert ${alertId}:`, error); accordionBody.innerHTML = `<div class="alert alert-danger m-2 small">Error loading user data.</div>`; }
            };

            const handleAccordionClose = (event) => { const alertId = event.target.dataset.alertId; if (affectedUserLayers[alertId]) { map.removeLayer(affectedUserLayers[alertId]); delete affectedUserLayers[alertId]; } if(userMarkers) { userMarkers = {}; } };

            const renderUserTabsAndList = (alertId, users) => { /* This function remains the same as before */
                const targetAccordionBody = document.querySelector(`#collapse-alert-${alertId} .accordion-body`);
                if (!targetAccordionBody) return;
                const assistanceUsers = users.filter(u => u.status === 'needs_help' || u.status === 'threat_report');
                const clearedUsers = users.filter(u => u.status === 'im_safe');
                const tabsHtml = `<ul class="nav nav-tabs nav-fill" role="tablist"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-all-${alertId}">All (${users.length})</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-help-${alertId}">Assistance (${assistanceUsers.length})</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-cleared-${alertId}">Cleared (${clearedUsers.length})</button></li></ul><div class="tab-content pt-2"><div class="tab-pane fade show active" id="pane-all-${alertId}">${generateUserListHtml(users)}</div><div class="tab-pane fade" id="pane-help-${alertId}">${generateUserListHtml(assistanceUsers)}</div><div class="tab-pane fade" id="pane-cleared-${alertId}">${generateUserListHtml(clearedUsers)}</div></div>`;
                targetAccordionBody.innerHTML = tabsHtml;
            };

            const generateUserListHtml = (filteredUsers) => { /* Modified to add route button */
                const statusMap = { im_safe: { text: "I'm Safe", badge: "text-bg-success" }, needs_help: { text: "Needs Help", badge: "text-bg-danger" }, threat_report: { text: "Threat Reported", badge: "text-bg-warning" }, awaiting_response: { text: "Awaiting", badge: "text-bg-secondary" } };
                if (filteredUsers.length === 0) return `<div class="text-center text-muted p-3 small">No users in this category.</div>`;

                let listHtml = '<div class="list-group list-group-flush user-status-list">';
                filteredUsers.forEach(user => {
                    const statusInfo = statusMap[user.status] || statusMap.awaiting_response;
                    const lastUpdate = user.last_update ? new Date(user.last_update).toLocaleString() : 'N/A';
                    const hasCoords = user.latitude && user.longitude;
                    
                    listHtml += `<div class="list-group-item bg-transparent user-status-item py-2"><div class="d-flex w-100 justify-content-between"><h6 class="mb-1 small fw-bold">${user.name}</h6><span class="badge ${statusInfo.badge} rounded-pill status-badge">${statusInfo.text}</span></div><p class="mb-1 small text-muted fst-italic">${user.message || 'No message.'}</p><div class="d-flex justify-content-between align-items-center mt-1"><small class="text-white-50">Updated: ${lastUpdate}</small>${hasCoords ? `<div class="btn-group btn-group-sm"><button class="btn btn-outline-primary locate-user-btn" data-lat="${user.latitude}" data-lng="${user.longitude}" data-user-id="${user.id}" title="Locate"><i class="fas fa-map-marker-alt"></i></button><button class="btn btn-outline-info route-user-btn" data-lat="${user.latitude}" data-lng="${user.longitude}" title="Route"><i class="fas fa-route"></i></button></div>` : ''}</div></div>`;
                });
                listHtml += '</div>';
                return listHtml;
            };

            const plotUsersOnMap = (alertId, users) => { /* This function remains the same */
                if (affectedUserLayers[alertId]) map.removeLayer(affectedUserLayers[alertId]);
                const layerGroup = L.layerGroup();
                const statusDotMap = { im_safe: 'user-status-dot-ok', needs_help: 'user-status-dot-help', threat_report: 'user-status-dot-threat', awaiting_response: 'user-status-dot-awaiting' };
                users.forEach(user => { if(user.latitude && user.longitude) { const dotClass = statusDotMap[user.status] || statusDotMap.awaiting_response; const marker = L.marker([user.latitude, user.longitude], { icon: L.divIcon({ className: `user-status-dot ${dotClass}`, iconSize: [12, 12] }) }).bindPopup(`<b>${user.name}</b><br>Status: ${user.status.replace(/_/g,' ')}`); userMarkers[user.id] = marker; layerGroup.addLayer(marker); } });
                affectedUserLayers[alertId] = layerGroup;
                map.addLayer(layerGroup);
            };

            // **NEW: Routing Functionality**
            const calculateAndShowRoute = async (startCoords, endCoords) => {
                const routeInfoBox = document.getElementById('route-info-box');
                const routeInfoText = document.getElementById('route-info-text');
                const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${startCoords.longitude},${startCoords.latitude};${endCoords.longitude},${endCoords.latitude}?overview=full&geometries=geojson`;

                routeInfoText.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Calculating route...';
                routeInfoBox.style.display = 'flex';

                try {
                    const response = await fetch(osrmUrl);
                    if (!response.ok) throw new Error('Routing service failed');
                    const routeData = await response.json();
                    if (!routeData.routes || routeData.routes.length === 0) throw new Error('No route found');

                    const route = routeData.routes[0];
                    if (currentRouteLayer) map.removeLayer(currentRouteLayer);
                    currentRouteLayer = L.geoJson(route.geometry, { style: { color: '#0dcaf0', weight: 6, opacity: 0.8 } }).addTo(map);
                    map.fitBounds(currentRouteLayer.getBounds().pad(0.2));

                    const distance = (route.distance / 1000).toFixed(1); // in km
                    const duration = Math.round(route.duration / 60); // in minutes
                    routeInfoText.innerHTML = `<strong>Route:</strong> ${distance} km, approx. ${duration} mins`;
                } catch (error) {
                    console.error('Routing Error:', error);
                    routeInfoText.innerHTML = 'Error: Could not calculate route.';
                }
            };

            clearRouteBtn.addEventListener('click', () => {
                if (currentRouteLayer) map.removeLayer(currentRouteLayer);
                document.getElementById('route-info-box').style.display = 'none';
                currentRouteLayer = null;
            });

            searchInput.addEventListener('input', () => { const searchTerm = searchInput.value.toLowerCase(); const alertItems = accordionContainer.querySelectorAll('.accordion-item'); alertItems.forEach(item => { const alertMessage = item.dataset.alertMessage || ''; item.style.display = alertMessage.includes(searchTerm) ? '' : 'none'; }); });
            refreshBtn.addEventListener('click', fetchAndRenderAlerts);
            fetchAndRenderAlerts();
        };

        // --- INITIALIZE ALL SYSTEMS ---
        setTimeout(initMap, 250);
        initSidebarToggles();
        initVictimCheckIn();
    });
</script>
</body>
</html>