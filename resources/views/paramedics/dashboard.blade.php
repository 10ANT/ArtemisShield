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
<!-- Scripts that need to load in the <head> -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


@include('partials.styles')
<style>
    .main-content { flex-grow: 1; }
    .ems-dashboard-container { height: 100%; }
    #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); }
    .map-wrapper { position: relative; height: 100%; overflow: hidden; }
    .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
    .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
    
    #victim-check-in-content { display: flex; flex-direction: column; height: 100%; }
    #alert-accordion-container { overflow-y: auto; flex-grow: 1; }
    .alert-accordion .accordion-header { display: flex; align-items: center; }
    .alert-accordion .accordion-button { font-size: 0.9rem; font-weight: 600; flex-grow: 1; }
    .alert-accordion .accordion-body { padding: 0.5rem; background-color: var(--bs-tertiary-bg); }
    .user-status-list { max-height: 300px; overflow-y: auto; }

    .user-status-dot { height: 12px; width: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.7); }
    .user-status-dot-ok { background-color: var(--bs-success); }
    .user-status-dot-help { background-color: var(--bs-danger); }
    .user-status-dot-threat { background-color: var(--bs-warning); }
    .user-status-dot-awaiting { background-color: var(--bs-secondary); }

    #route-info-box { position: absolute; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1000; background: rgba(43, 48, 53, 0.9); color: #fff; padding: 8px 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.5); display: none; }
    #route-info-box .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    
    #map-layers-overlay { position: absolute; top: 10px; left: 10px; z-index: 1000; width: 220px; background-color: var(--bs-dark-bg-subtle); border: 1px solid var(--bs-border-color); border-radius: .5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.4); }
    .draggable-handle { cursor: move; padding: .5rem 1rem; border-bottom: 1px solid var(--bs-border-color); }

    .leaflet-control-sidebar-toggle a { background-image: none !important; font-family: "Font Awesome 6 Free"; font-weight: 900; content: "\f0c9"; display: inline-block; text-align: center; vertical-align: middle; font-size: 1.2em; }
    
    #map.cursor-crosshair { cursor: crosshair; }

    .user-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: .5rem; margin-top: .75rem; }
    .user-actions .btn { display: flex; align-items: center; justify-content: center; gap: .5rem; font-size: 0.8rem; padding: .5rem; }

    @media (max-width: 991.98px) { #map { min-height: 65vh; height: 65vh; } #right-sidebar-column { height: auto; } }
</style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
<div class="main-content d-flex flex-column">
    
    @include('partials.header')

    <div class="ems-dashboard-container row g-0 flex-grow-1">
        <div class="col-lg" id="map-column">
            <div class="map-wrapper">
                <div id="map"></div>
                <div id="route-info-box" class="d-flex align-items-center gap-3">
                    <div id="route-info-text"></div>
                    <button type="button" class="btn-close btn-sm" id="clear-route-btn" aria-label="Close"></button>
                </div>
                <div id="map-layers-overlay" class="card">
                    <div class="card-header draggable-handle d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>Map Views</h6>
                        <button class="btn btn-sm btn-close" data-bs-toggle="collapse" data-bs-target="#map-layers-body"></button>
                    </div>
                    <div id="map-layers-body" class="card-body collapse show">
                        <div id="basemap-selector-container" class="btn-group-vertical w-100" role="group"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4" id="right-sidebar-column">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-fill flex-nowrap" id="sidebar-tabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#victim-check-in-content"><i class="fas fa-user-check me-1"></i> Check-in</button></li>
                    </ul>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="tab-content h-100">
                        <div class="tab-pane fade show active" id="victim-check-in-content" role="tabpanel">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-broadcast-tower me-2"></i> Active Alert Zones</h5>
                                <button class="btn btn-sm btn-secondary" id="refresh-alerts-btn" title="Refresh Alert List"><i class="fas fa-sync-alt"></i></button>
                            </div>
                            <div class="p-3 border-bottom"><input type="text" id="alert-search-input" class="form-control" placeholder="Search alerts..."></div>
                            <div id="alert-accordion-container" class="alert-accordion">
                                <div class="text-center p-5 text-muted"><div class="spinner-border"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Fetching alerts...</p></div>
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
        
        let map, currentRouteLayer, commandUserLocation;
        let affectedUserLayers = {}, userMarkers = {};
        let isPlacingRouteDest = false, routeStartPoint = null;

        const initMap = () => { /* This function remains unchanged */
            const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
            const darkMode = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
            
            const baseLayers = { 'Street': streets, 'Dark': darkMode, 'Satellite': satellite };
            map = L.map('map', { center: [41.8781, -87.6298], zoom: 6, layers: [streets] });
            
            const basemapContainer = document.getElementById('basemap-selector-container');
            Object.keys(baseLayers).forEach((layerName, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `btn text-start ${index === 0 ? 'btn-primary' : 'btn-outline-primary'}`;
                button.textContent = layerName;
                button.addEventListener('click', () => {
                    Object.values(baseLayers).forEach(layer => map.hasLayer(layer) && map.removeLayer(layer));
                    map.addLayer(baseLayers[layerName]);
                    basemapContainer.querySelectorAll('button').forEach(btn => btn.classList.replace('btn-primary', 'btn-outline-primary'));
                    button.classList.replace('btn-outline-primary', 'btn-primary');
                });
                basemapContainer.appendChild(button);
            });

            new Draggabilly('#map-layers-overlay', { handle: '.draggable-handle' });

            const SidebarToggle = L.Control.extend({
                options: { position: 'topright' },
                onAdd: function (map) {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                    container.innerHTML = `<a href="#" title="Toggle Sidebar" role="button"><i class="fas fa-columns"></i></a>`;
                    container.onclick = (e) => {
                        e.stopPropagation(); e.preventDefault();
                        const sidebar = document.getElementById('right-sidebar-column');
                        const mapCol = document.getElementById('map-column');
                        sidebar.classList.toggle('d-none');
                        mapCol.className = sidebar.classList.contains('d-none') ? 'col-lg-12' : 'col-lg';
                        setTimeout(() => map.invalidateSize(), 310);
                    };
                    return container;
                }
            });
            map.addControl(new SidebarToggle());
        };

        const initVictimCheckIn = () => { /* This function remains mostly unchanged */
            const accordionContainer = document.getElementById('alert-accordion-container');
            const refreshBtn = document.getElementById('refresh-alerts-btn');
            const searchInput = document.getElementById('alert-search-input');
            const clearRouteBtn = document.getElementById('clear-route-btn');

            const fetchAndRenderAlerts = async () => {
                accordionContainer.innerHTML = `<div class="text-center p-5 text-muted"><div class="spinner-border"></div></div>`;
                Object.values(affectedUserLayers).forEach(layer => map.removeLayer(layer)); affectedUserLayers = {}; userMarkers = {};
                try {
                    const response = await axios.get("{{ route('command.relevant-alerts') }}");
                    commandUserLocation = response.data.command_user_location;
                    if (!response.data.alerts || response.data.alerts.length === 0) { accordionContainer.innerHTML = `<div class="text-center p-5 text-muted"><i class="fas fa-check-circle fa-2x"></i><p>No active alerts.</p></div>`; return; }
                    renderAlertsAccordion(response.data.alerts);
                } catch (error) { console.error("Alert fetch failed:", error); accordionContainer.innerHTML = `<div class="alert alert-danger m-3">Failed to load data.</div>`; }
            };
            
            const renderAlertsAccordion = (alerts) => {
                let html = '<div class="accordion" id="alerts-master-accordion">';
                alerts.forEach(alert => {
                    html += `<div class="accordion-item" data-alert-message="${alert.message.toLowerCase()}"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-alert-${alert.id}"><i class="fas fa-exclamation-triangle text-warning me-2"></i>${alert.message}</button><button class="btn btn-sm btn-outline-info ms-auto me-2 my-auto flex-shrink-0 zoom-to-alert-btn" title="View Zone" data-lat="${alert.latitude}" data-lng="${alert.longitude}" data-radius="${alert.radius}"><i class="fas fa-search-location"></i></button></h2><div id="collapse-alert-${alert.id}" class="accordion-collapse collapse" data-alert-id="${alert.id}" data-loaded="false"><div class="accordion-body"><div class="text-center p-3"><div class="spinner-border spinner-border-sm"></div></div></div></div></div>`;
                });
                accordionContainer.innerHTML = html + '</div>';
                accordionContainer.addEventListener('click', handleActionButtons);
                accordionContainer.addEventListener('input', handlePerAlertSearch); // **NEW** listener for user search
                document.getElementById('alerts-master-accordion').addEventListener('show.bs.collapse', handleAccordionOpen);
                document.getElementById('alerts-master-accordion').addEventListener('hide.bs.collapse', handleAccordionClose);
            };

            const handleActionButtons = async (event) => { /* Same logic as before */
                const button = event.target.closest('button');
                if (!button) return;
                event.stopPropagation();
                const { lat, lng, radius, userId } = button.dataset;
                const classList = button.classList;
                if (classList.contains('zoom-to-alert-btn')) { const center = L.latLng(lat, lng); const northEast = L.latLng(center.lat + (radius / 111320), center.lng + (radius / (111320 * Math.cos(center.lat * Math.PI / 180)))); const southWest = L.latLng(center.lat - (radius / 111320), center.lng - (radius / (111320 * Math.cos(center.lat * Math.PI / 180)))); map.fitBounds(L.latLngBounds(southWest, northEast)); }
                if (classList.contains('locate-user-btn')) { map.flyTo([lat, lng], 16); if (userMarkers[userId]) userMarkers[userId].openPopup(); }
                if (classList.contains('route-user-btn')) { if (commandUserLocation) calculateAndShowRoute(commandUserLocation, {latitude: lat, longitude: lng}); else alert('Your location is unknown.'); }
                if (classList.contains('route-to-point-btn')) { isPlacingRouteDest = true; routeStartPoint = {latitude: lat, longitude: lng}; document.getElementById('map').classList.add('cursor-crosshair'); alert('Click on the map to set the destination.'); }
                if (classList.contains('clear-user-btn')) { if (confirm('Are you sure you want to clear this user for this alert?')) clearUserStatus(userId, button.dataset.alertId, button); }
            };
            
            const handleAccordionOpen = async (event) => { /* Same logic as before */
                const body = event.target.querySelector('.accordion-body');
                const {alertId, loaded} = event.target.dataset;
                if (!alertId || loaded === 'true') return;
                try {
                    const response = await axios.get(`/alerts/${alertId}/affected-users`);
                    event.target.dataset.loaded = 'true'; renderUserTabsAndList(alertId, response.data); plotUsersOnMap(alertId, response.data);
                } catch(error) { console.error(`User fetch failed for alert ${alertId}:`, error); body.innerHTML = `<div class="alert alert-danger m-2 small">Error.</div>`; }
            };

            const handleAccordionClose = (event) => { /* Same logic as before */
                const {alertId} = event.target.dataset; if (affectedUserLayers[alertId]) { map.removeLayer(affectedUserLayers[alertId]); delete affectedUserLayers[alertId]; } userMarkers = {}; 
            };
            
            // **MODIFIED** to add user search bar HTML
            const renderUserTabsAndList = (alertId, users) => {
                const body = document.querySelector(`#collapse-alert-${alertId} .accordion-body`);
                const assistance = users.filter(u => u.status === 'needs_help' || u.status === 'threat_report');
                const cleared = users.filter(u => u.status === 'im_safe');
                const html = `<div class="mb-2"><input type="text" class="form-control form-control-sm per-alert-user-search" placeholder="Search user or ID..." data-alert-id="${alertId}"></div>
                    <ul class="nav nav-tabs nav-fill" role="tablist"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-all-${alertId}">All (${users.length})</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-help-${alertId}">Assistance (${assistance.length})</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-cleared-${alertId}">Cleared (${cleared.length})</button></li></ul>
                    <div class="tab-content pt-2"><div class="tab-pane fade show active" id="pane-all-${alertId}">${generateUserListHtml(users, alertId)}</div><div class="tab-pane fade" id="pane-help-${alertId}">${generateUserListHtml(assistance, alertId)}</div><div class="tab-pane fade" id="pane-cleared-${alertId}">${generateUserListHtml(cleared, alertId)}</div></div>`;
                body.innerHTML = html;
            };

            // **MODIFIED** to add user ID display and alertId to button
            const generateUserListHtml = (users, alertId) => {
                const statusMap = { im_safe: "text-bg-success", needs_help: "text-bg-danger", threat_report: "text-bg-warning", awaiting_response: "text-bg-secondary" };
                if (users.length === 0) return `<div class="text-center text-muted p-3 small">No users.</div>`;
                return '<div class="list-group list-group-flush user-status-list">' + users.map(user => {
                    const hasCoords = user.latitude && user.longitude;
                    const searchData = `${user.name.toLowerCase()} (id: ${user.id})`;
                    return `<div class="list-group-item bg-transparent user-status-item py-2" data-user-id="${user.id}" data-search-term="${searchData}">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <h6 class="mb-1 small fw-bold">${user.name} <span class="text-muted fw-normal">(ID: ${user.id})</span></h6>
                            <span class="badge ${statusMap[user.status] || 'text-bg-secondary'} rounded-pill">${(user.status || 'N/A').replace('_',' ')}</span>
                        </div>
                        <p class="mb-1 small text-muted fst-italic">${user.message || 'No message provided.'}</p>
                        <small class="text-white-50">Updated: ${user.last_update ? new Date(user.last_update).toLocaleTimeString() : 'N/A'}</small>
                        <div class="user-actions">
                            ${hasCoords ? `<button class="btn btn-outline-primary locate-user-btn" data-lat="${user.latitude}" data-lng="${user.longitude}" data-user-id="${user.id}"><i class="fas fa-map-marker-alt"></i>Locate</button>
                                <button class="btn btn-outline-info route-user-btn" data-lat="${user.latitude}" data-lng="${user.longitude}"><i class="fas fa-route"></i>Route</button>
                                <button class="btn btn-outline-warning route-to-point-btn" data-lat="${user.latitude}" data-lng="${user.longitude}"><i class="fas fa-map-pin"></i>To...</button>` : ''}
                            ${user.status !== 'im_safe' ? `<button class="btn btn-outline-success clear-user-btn" data-user-id="${user.id}" data-alert-id="${alertId}"><i class="fas fa-check"></i>Clear</button>` : ''}
                        </div>
                    </div>`;
                }).join('') + '</div>';
            };

            // **NEW** function to handle per-alert user search
            const handlePerAlertSearch = (event) => {
                if (!event.target.classList.contains('per-alert-user-search')) return;
                const searchTerm = event.target.value.toLowerCase();
                const alertId = event.target.dataset.alertId;
                const userItems = document.querySelectorAll(`#collapse-alert-${alertId} .user-status-item`);
                userItems.forEach(item => {
                    item.style.display = (item.dataset.searchTerm || '').includes(searchTerm) ? '' : 'none';
                });
            };

            // **MODIFIED** to pass alertId
            const clearUserStatus = async (userId, alertId, button) => {
                button.disabled = true; button.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;
                try {
                    const response = await axios.patch(`/users/${userId}/clear/${alertId}`);
                    if (response.data.success) {
                        const alertContainer = button.closest('.accordion-collapse');
                        const alertBody = alertContainer.querySelector('.accordion-body');
                        alertBody.innerHTML = `<div class="text-center p-3"><div class="spinner-border spinner-border-sm"></div></div>`;
                        const userResponse = await axios.get(`/alerts/${alertId}/affected-users`);
                        renderUserTabsAndList(alertId, userResponse.data);
                        plotUsersOnMap(alertId, userResponse.data);
                    }
                } catch (error) { console.error("Failed to clear user:", error); button.disabled = false; button.innerHTML = '<i class="fas fa-check"></i>Clear'; alert('Could not clear user.'); }
            };

            const plotUsersOnMap = (alertId, users) => { /* unchanged */
                if(affectedUserLayers[alertId]) map.removeLayer(affectedUserLayers[alertId]);
                const group = L.layerGroup();
                const dots = { im_safe: 'ok', needs_help: 'help', threat_report: 'threat', awaiting_response: 'awaiting' };
                users.forEach(u => { if(u.latitude && u.longitude) { const marker = L.marker([u.latitude, u.longitude], { icon: L.divIcon({ className: `user-status-dot user-status-dot-${dots[u.status] || 'awaiting'}`})}).bindPopup(`<b>${u.name} (ID: ${u.id})</b>`); userMarkers[u.id] = marker; group.addLayer(marker); }});
                affectedUserLayers[alertId] = group; map.addLayer(group);
            };

            const calculateAndShowRoute = async (start, end) => { /* unchanged */
                const box = document.getElementById('route-info-box'), text = document.getElementById('route-info-text');
                text.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Calculating...`; box.style.display = 'flex';
                const url = `https://router.project-osrm.org/route/v1/driving/${start.longitude},${start.latitude};${end.longitude},${end.latitude}?overview=full&geometries=geojson`;
                try {
                    const response = await fetch(url); const data = await response.json(); if (!data.routes || !data.routes.length) throw new Error('No route found.');
                    if (currentRouteLayer) map.removeLayer(currentRouteLayer);
                    currentRouteLayer = L.geoJson(data.routes[0].geometry, {style: {color:'#0dcaf0', weight:6, opacity:0.8}}).addTo(map);
                    map.fitBounds(currentRouteLayer.getBounds().pad(0.2));
                    text.innerHTML = `<strong>Route:</strong> ${(data.routes[0].distance / 1000).toFixed(1)} km, ~${Math.round(data.routes[0].duration / 60)} mins`;
                } catch(e) { console.error("Route error:", e); text.innerHTML = "Error calculating route."; }
            };

            map.on('click', function(e) { /* unchanged */
                if (!isPlacingRouteDest) return;
                calculateAndShowRoute(routeStartPoint, { latitude: e.latlng.lat, longitude: e.latlng.lng });
                document.getElementById('map').classList.remove('cursor-crosshair');
                isPlacingRouteDest = false; routeStartPoint = null;
            });
            
            clearRouteBtn.addEventListener('click', () => { if (currentRouteLayer) map.removeLayer(currentRouteLayer); document.getElementById('route-info-box').style.display = 'none'; currentRouteLayer = null; });
            searchInput.addEventListener('input', () => { const term = searchInput.value.toLowerCase(); accordionContainer.querySelectorAll('.accordion-item').forEach(item => { item.style.display = (item.dataset.alertMessage || '').includes(term) ? '' : 'none'; }); });
            refreshBtn.addEventListener('click', fetchAndRenderAlerts);
            fetchAndRenderAlerts();
        };

        initMap();
        initVictimCheckIn();
    });
</script>
</body>
</html>