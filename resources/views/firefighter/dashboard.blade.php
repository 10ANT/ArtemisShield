<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ArtemisShield - Wildfire Protection Dashboard</title>
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Leaflet.markercluster CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

<!-- Scripts that need to load in the <head> -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<!-- Leaflet.markercluster JS -->
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

@include('partials.styles')
<style>
    /* Your existing CSS */
    .main-content { flex-grow: 1; }
    .wildfire-dashboard-container { height: 100%; }
    #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); }
    .map-wrapper { position: relative; height: 100%; overflow: hidden; }
    .map-overlay { position: absolute; z-index: 1000; margin: 1rem; width: 260px; max-height: calc(50vh - 2rem); overflow-y: auto; }
    .map-overlay .card-header { cursor: grab; }
    .map-overlay .card-header:active { cursor: grabbing; }

    /* MODIFICATION: Repositioned map overlays */
    .layers-panel { top: 25%; right: 0; left: auto; bottom: auto; }
    .basemap-panel { top: 0; right: 0; left: auto; bottom: auto; }

    /* FIX: Prevent draggable panel from stretching when moved from the bottom */

    .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
    .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
    .chat-container { height: 100%; display: flex; flex-direction: column; }
    .chat-messages { flex-grow: 1; overflow-y: auto; }
    .fire-marker i { text-shadow: 0 0 4px rgba(0, 0, 0, 0.7); }
    .custom-popup .leaflet-popup-content-wrapper { background-color: #2a2a2a; color: #e0e0e0; border-radius: 8px; padding: 0; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); max-width: 450px; min-width: 300px; height: auto; }
    .custom-popup .leaflet-popup-content { margin: 0; padding: 0; width: 100% !important; height: 100%; display: flex; flex-direction: column; }
    .custom-popup .leaflet-popup-tip { background-color: #2a2a2a; }
    .custom-popup .popup-header { background-color: #1a1a1a; padding: 10px 15px; border-top-left-radius: 8px; border-top-right-radius: 8px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
    .custom-popup .popup-header h4 { color: #fff; font-size: 1.1rem; margin: 0; }
    .custom-popup .popup-header .close-btn { background: none; border: none; color: #fff; font-size: 1.2rem; cursor: pointer; padding: 0 5px; }
    .custom-popup .popup-body { display: flex; flex-wrap: wrap; padding: 10px 15px; gap: 10px; flex-grow: 1; }
    .custom-popup .popup-section { background-color: #333333; border-radius: 6px; padding: 10px; flex: 1; min-width: 140px; display: flex; flex-direction: column; }
    .custom-popup .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 2px 0; }
    .custom-popup .detail-label { color: #b0b0b0; font-size: 0.8rem; flex-shrink: 0; margin-right: 5px; }
    .custom-popup .detail-value { color: #f0f0f0; font-weight: bold; font-size: 0.85rem; text-align: right; word-wrap: break-word; flex-grow: 1; }
    .collapse-icon { transition: transform 0.3s ease-in-out; }
    .collapse-icon.fa-chevron-up { transform: rotate(180deg); }

    /* --- Fire Incident Markers --- */
    .fire-incident-icon { text-align: center; text-shadow: 0px 0px 3px #000; cursor: pointer; }
    .fire-incident-icon-high { color: #dc3545; }
    .fire-incident-icon-medium { color: #fd7e14; }
    .fire-incident-icon-low { color: #ffc107; }
    .fire-incident-icon-selected { animation: pulse-glow 1.5s infinite; }
    @keyframes pulse-glow { 0% { transform: scale(1); text-shadow: 0 0 5px #ffc107; } 50% { transform: scale(1.2); text-shadow: 0 0 20px #ff5722; } 100% { transform: scale(1); text-shadow: 0 0 5px #ffc107; } }

    /* CSS for Clusters & Legend */
    .cluster-icon { color: #fff; text-align: center; border-radius: 50%; font-weight: bold; box-shadow: 0 0 5px rgba(0,0,0,0.5); border: 2px solid rgba(255,255,255,0.7); }
    .hydrant-cluster-small { background-color: rgba(2, 117, 216, 0.85); width: 30px; height: 30px; line-height: 26px; }
    .hydrant-cluster-medium { background-color: rgba(13, 202, 240, 0.85); width: 35px; height: 35px; line-height: 31px; }
    .hydrant-cluster-large { background-color: rgba(13, 110, 253, 0.9); width: 40px; height: 40px; line-height: 36px; }
    .station-cluster-small { background-color: rgba(40, 167, 69, 0.9); width: 30px; height: 30px; line-height: 26px; }
    .station-cluster-medium { background-color: rgba(253, 126, 20, 0.9); width: 35px; height: 35px; line-height: 31px; }
    .station-cluster-large { background-color: rgba(220, 53, 69, 0.9); width: 40px; height: 40px; line-height: 36px; }
    .fire-cluster-small, .fire-cluster-medium, .fire-cluster-large { background-color: rgba(220, 53, 69, 0.9); }
    .fire-cluster-small { width: 30px; height: 30px; line-height: 26px; }
    .fire-cluster-medium { width: 35px; height: 35px; line-height: 31px; }
    .fire-cluster-large { width: 40px; height: 40px; line-height: 36px; }

    /* Legend Control */
    .legend-control { padding: 8px 12px; font: 14px/16px Arial, Helvetica, sans-serif; background: rgba(43, 48, 53, 0.85); color: #f8f9fa; box-shadow: 0 0 15px rgba(0,0,0,0.3); border-radius: 5px; line-height: 20px; border: 1px solid rgba(255,255,255,0.2); }
    .legend-control h4 { margin: 8px 0 5px; color: #ffffff; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 4px; }
    .legend-control h4:first-child { margin-top: 0; }
    .legend-control i { width: 18px; height: 18px; float: left; margin-right: 8px; opacity: 0.9; }
    .legend-control .legend-item { display: flex; align-items: center; margin-bottom: 2px; }

    /* --- Fullscreen Map Control --- */
    .leaflet-control-fullscreen a { background-color: #2b3035 !important; width: 34px; height: 34px; line-height: 34px; text-align: center; font-size: 1.2em; color: #f8f9fa; display: block; cursor: pointer; border-radius: 4px; }
    .leaflet-control-fullscreen a:hover { background-color: #343a40 !important; color: #fff; }
    .leaflet-control-fullscreen { box-shadow: 0 1px 5px rgba(0,0,0,0.65); }
    .leaflet-bar .leaflet-control-fullscreen { border: none; }

    /* --- Application Fullscreen Mode Overrides --- */
    body.map-fullscreen-active .sidebar-nav-wrapper,
    body.map-fullscreen-active .main-content > .header,
    body.map-fullscreen-active .main-content > .footer { display: none !important; }
    body.map-fullscreen-active .main-content { margin-left: 0 !important; padding: 0 !important; height: 100vh; }
    body.map-fullscreen-active .wildfire-dashboard-container { height: 100% !important; }

    /* --- LIVE REPORT TAB --- */
    #live-report-content { display: flex; flex-direction: column; height: 100%; }
    .recording-controls { text-align: center; padding: 1rem 0; border-bottom: 1px solid var(--bs-border-color); }
    .record-btn { width: 90px; height: 90px; border-radius: 50%; background-color: var(--bs-secondary-bg); border: 4px solid var(--bs-primary); color: var(--bs-primary); font-size: 2rem; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .record-btn:hover { background-color: var(--bs-primary-bg-subtle); }
    .record-btn:disabled { background-color: var(--bs-secondary-bg); border-color: var(--bs-secondary); color: var(--bs-secondary); cursor: not-allowed; }
    .record-btn.is-recording { background-color: var(--bs-danger); border-color: var(--bs-danger-bg-subtle); color: #fff; animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); } 70% { box-shadow: 0 0 0 20px rgba(220, 53, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
    .recording-status { margin-top: 0.5rem; font-weight: 500; color: var(--bs-secondary-color); }
    .ai-analysis-container { flex-grow: 1; overflow-y: auto; padding-top: 1rem; }
    .ai-analysis-card { background-color: var(--bs-tertiary-bg); border: 1px solid var(--bs-border-color-translucent); }
    .ai-analysis-card .card-header { background-color: rgba(var(--bs-emphasis-color-rgb), 0.05); font-weight: 600; }
    .entity-tag { display: inline-block; padding: 0.35em 0.65em; font-size: .8em; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: var(--bs-border-radius); margin: 2px; }
    .entity-tag-location { background-color: var(--bs-primary); color: #fff; }
    .entity-tag-resource { background-color: var(--bs-info); color: #000; }
    .entity-tag-hazard { background-color: var(--bs-warning); color: #000; }
    .entity-tag-other { background-color: var(--bs-secondary); color: #fff; }
    .suggestion-item { display: flex; align-items: flex-start; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--bs-border-color-translucent); }
    .suggestion-item:last-child { border-bottom: none; }
    .suggestion-icon { font-size: 1.25rem; color: var(--bs-success); margin-top: 0.25rem; }
    .suggestion-item-tts { display: flex; justify-content: space-between; align-items: center; }

    /* MODIFICATION: Responsive Right Sidebar Toggling */
    #map-column, #right-sidebar-column {
        transition: flex 0.3s ease-in-out, width 0.3s ease-in-out, padding 0.3s ease-in-out, border 0.3s ease-in-out;
    }
    #right-sidebar-toggle {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 1001; /* Above map overlays */
        display: none; /* Hidden by default, shown on large screens via media query */
    }
    #right-sidebar-toggle i {
        transition: transform 0.3s ease-in-out;
    }
    .wildfire-dashboard-container.right-sidebar-collapsed #right-sidebar-column {
        flex: 0 0 0;
        width: 0;
        overflow: hidden;
        border: none !important;
        padding: 0 !important;
    }
    .wildfire-dashboard-container.right-sidebar-collapsed #map-column {
        flex: 0 0 100%;
        max-width: 100%;
    }

    /* MODIFICATION: Responsive Media Queries for Mobile/Tablet */
    @media (max-width: 991.98px) { /* Medium devices (tablets, <992px) and down */
        .wildfire-dashboard-container {
            height: auto; /* Allow container to grow vertically */
        }
        #map {
            min-height: 65vh; /* Ensure map has a good height on mobile */
            height: 65vh;
        }
        #right-sidebar-column {
            height: auto;
            border-top: 1px solid var(--bs-border-color) !important;
        }
        .sidebar-wrapper {
            height: auto; /* Let sidebar content determine its height */
        }
        .custom-popup .leaflet-popup-content-wrapper {
            min-width: 280px;
            max-width: calc(100vw - 40px); /* Fit popup within screen bounds */
        }
        .custom-popup .popup-body {
            flex-direction: column; /* Stack popup sections vertically */
        }
        .map-overlay {
            width: 75%; /* Make overlays less wide on mobile */
            max-width: 240px;
            max-height: calc(40vh - 2rem);
        }
        #sidebar-tabs .nav-link {
            padding: 0.5rem 0.25rem; /* Reduce padding on tabs to fit more */
            font-size: 0.85rem;
        }
    }
    
    @media (min-width: 992px) { /* Large devices (desktops, >=992px) */
        #right-sidebar-toggle {
            display: block; /* Show the toggle button only on large screens */
        }
    }

</style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
<div class="main-content d-flex flex-column">
    @include('partials.header')
    <div class="wildfire-dashboard-container row g-0 flex-grow-1">
        
        <!-- MODIFICATION: Changed col-md-7 to col-md-12 to stack on medium screens -->
        <div class="col-lg-8 col-md-12" id="map-column">
            <div class="map-wrapper">
                <div id="map"></div>

                <!-- MODIFICATION: This button is now controlled by CSS for responsiveness -->
                <button id="right-sidebar-toggle" class="btn btn-dark" type="button" title="Hide Sidebar">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Draggable Panels (positions updated via CSS) -->
                <div class="map-overlay layers-panel card shadow-sm">
                    <div class="card-header p-0" id="layersHeading"><h6 class="mb-0"><button class="btn btn-link w-100 text-start text-decoration-none text-dark p-3" type="button" data-bs-toggle="collapse" data-bs-target="#layersCollapse" aria-expanded="true" aria-controls="layersCollapse"><i class="fas fa-layer-group fa-fw me-2"></i>Map Layers <i class="fas fa-chevron-down float-end collapse-icon"></i></button></h6></div>
                    <div id="layersCollapse" class="collapse show" aria-labelledby="layersHeading"><div class="card-body p-3"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="modis-fires-toggle" checked><label class="form-check-label" for="modis-fires-toggle">MODIS Hotspots (24h)</label></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="fire-hydrants-toggle" checked><label class="form-check-label" for="fire-hydrants-toggle">Fire Hydrants</label></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="fire-stations-toggle" checked><label class="form-check-label" for="fire-stations-toggle">Fire Stations</label></div></div></div>
                </div>
                
                <!-- MODIFICATION: Removed the empty weather-widget div -->
                
                <div class="map-overlay basemap-panel card shadow-sm">
                    <div class="card-header p-0" id="basemapHeading"><h6 class="mb-0"><button class="btn btn-link w-100 text-start text-decoration-none text-dark p-3" type="button" data-bs-toggle="collapse" data-bs-target="#basemapCollapse" aria-expanded="true" aria-controls="basemapCollapse"><i class="fas fa-map fa-fw me-2"></i>Base Maps <i class="fas fa-chevron-down float-end collapse-icon"></i></button></h6></div>
                    <div id="basemapCollapse" class="collapse show" aria-labelledby="basemapHeading"><div class="card-body p-3" id="basemap-selector-container"></div></div>
                </div>
            </div>
        </div>
        
        <!-- MODIFICATION: This column stacks below map on screens <992px wide -->
        <div class="col-lg-4 col-md-12 border-start" id="right-sidebar-column">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-fill" id="sidebar-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="chat-tab-btn" data-bs-toggle="pill" data-bs-target="#chat-content" type="button" role="tab" aria-controls="chat-content" aria-selected="true"><i class="fas fa-comments me-1"></i> Ask Artemis</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link position-relative" id="notifications-tab-btn" data-bs-toggle="pill" data-bs-target="#notifications-content" type="button" role="tab" aria-controls="notifications-content" aria-selected="false"><i class="fas fa-bell me-1"></i> Notifications<span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">0</span></button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="live-report-tab-btn" data-bs-toggle="pill" data-bs-target="#live-report-content" type="button" role="tab" aria-controls="live-report-content" aria-selected="false"><i class="fas fa-microphone-alt me-1"></i> Live Report</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="route-tab-btn" data-bs-toggle="pill" data-bs-target="#route-content" type="button" role="tab" aria-controls="route-content" aria-selected="false"><i class="fas fa-route me-1"></i> Route</button></li>
                    </ul>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="tab-content h-100">
                        <div class="tab-pane fade show active p-3" id="chat-content" role="tabpanel">
                            <div class="chat-container">
                                <div class="chat-messages mb-3" id="chat-messages"><div class="mb-3 text-start"><small class="text-body-secondary">Artemis AI Assistant</small><div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">Hello! I'm Artemis. Ask me about active fires, resource status, or standard operating procedures from the knowledge base.</div></div></div>
                                <div class="chat-input-group d-flex gap-2"><input type="text" class="form-control" placeholder="Ask a question..." id="chat-input"><button class="btn btn-primary" id="send-chat-btn"><i class="fas fa-paper-plane"></i></button></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="notifications-content" role="tabpanel">
                            <div id="notifications-list" class="list-group list-group-flush overflow-auto">
                                <div id="notifications-placeholder" class="text-center text-muted p-5"><i class="fas fa-check-circle fa-3x mb-3"></i><p>No new notifications.</p></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="live-report-content" role="tabpanel">
                                <div class="recording-controls p-3 d-flex flex-column align-items-center"><button class="record-btn mb-2" id="record-button"><i class="fas fa-microphone"></i></button><p class="recording-status" id="recording-status">Tap to Start Field Report</p></div>
                            <div id="ai-analysis-results" class="ai-analysis-container px-3">
                                <div id="report-placeholder" class="text-center text-muted mt-5"><i class="fas fa-wind fa-3x mb-3"></i><p>Awaiting field report...</p></div>
                                <div id="report-error" class="alert alert-danger d-none" role="alert"></div>
                            </div>
                            <div class="px-3 pb-3 mt-4">
                                <hr>
                                <h5 class="mb-3 mt-4 text-white-50"><i class="fas fa-history me-2"></i>Previous Reports</h5>
                                <div id="previous-transcripts-container" style="max-height: 400px; overflow-y: auto;"><p id="previous-transcripts-loading" class="text-muted text-center p-4"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading history...</p></div>
                            </div>
                        </div>
                        <div  class="tab-pane fade p-3" id="route-content" role="tabpanel" aria-labelledby="route-tab-btn">
                            <div " class="">
                                <h5 class="mb-3"><i class="fas fa-route me-2"></i>Fire Response Routing</h5>
                                <div class="card bg-body-tertiary mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title">Instructions</h6>
                                        <p class="card-text small mb-1"><strong>1. Select Fire:</strong> Click a fire icon <i class="fas fa-fire-alt text-danger"></i> on the map.</p>
                                        <p class="card-text small mb-0"><strong>2. Calculate:</strong> Click the button below to find the route.</p>
                                    </div>
                                </div>
                                <div class="mb-3 p-2 rounded" id="selected-fire-info" style="background-color: rgba(var(--bs-primary-rgb), 0.1);"><p class="text-center text-muted mb-0">No fire selected.</p></div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" id="calculate-route-btn" disabled><span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span><i class="fas fa-calculator me-2 icon"></i> Calculate Route</button>
                                    <button class="btn btn-outline-danger" id="clear-route-btn" style="display: none;"><i class="fas fa-times me-2"></i> Clear Route & Selection</button>
                                </div>
                                <hr>
                                <div id="route-details-container" class="mt-2 flex-grow-1 overflow-auto">
                                    <div id="route-summary" class="d-none">
                                        <h6 class="text-white-50 mb-2">Route Summary</h6>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">Responding From: <strong id="route-station-name" class="text-end"></strong></li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">Distance: <span id="route-distance" class="badge text-bg-info fs-6"></span></li>
                                            <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">Estimated Time: <span id="route-duration" class="badge text-bg-info fs-6"></span></li>
                                        </ul>
                                    </div>
                                    <p id="route-placeholder" class="text-muted text-center mt-4">Route information will appear here.</p>
                                </div>
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
        // --- MAP & FEATURE SCRIPT LOGIC ---
        let map;
        let fireHydrantsLayer;
        let fireStationsLayer;
        let searchResultsLayer;
        let modisFiresLayer;
        
        let selectedFireFeature = null;
        let selectedFireMarker = null;
        let routeLayer = null;

        // --- FULLSCREEN CONTROL LOGIC ---
        const initFullScreenControl = () => {
            if (!map) {
                console.error("Map object not available for FullScreenControl initialization.");
                return;
            }

            const FullScreenControl = L.Control.extend({
                options: {
                    position: 'bottomright' 
                },
                onAdd: function(map) {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-fullscreen');
                    container.innerHTML = `<a href="#" title="Toggle Fullscreen View" role="button" aria-label="Toggle Fullscreen View"><i class="fas fa-expand"></i></a>`;
                    
                    L.DomEvent.disableClickPropagation(container);
                    L.DomEvent.on(container, 'click', this._toggleFullScreen, this);
                    
                    this._map = map;
                    return container;
                },
                _toggleFullScreen: function(e) {
                    L.DomEvent.stop(e);

                    const body = document.body;
                    const dashboardContainer = document.querySelector('.wildfire-dashboard-container');
                    const icon = this._container.querySelector('i');

                    body.classList.toggle('map-fullscreen-active');
                    
                    const isFullscreen = body.classList.contains('map-fullscreen-active');
                    
                    if (isFullscreen) {
                        dashboardContainer.classList.add('right-sidebar-collapsed');
                        icon.className = 'fas fa-compress';
                        this._container.querySelector('a').title = 'Exit Fullscreen View';
                    } else {
                        dashboardContainer.classList.remove('right-sidebar-collapsed');
                        icon.className = 'fas fa-expand';
                        this._container.querySelector('a').title = 'Toggle Fullscreen View';
                        body.classList.remove('left-sidebar-collapsed');
                    }

                    setTimeout(() => {
                        if(this._map) {
                            this._map.invalidateSize({ pan: true });
                        }
                    }, 300);
                }
            });

            new FullScreenControl().addTo(map);
        };
        
        const initMap = () => {
            console.log('Map initialization started.');
            const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: 'Tiles © <a href="https://www.esri.com/">Esri</a> — Source: Esri, et al.' });
            const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { attribution: 'Map data: © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, SRTM | Map style: © <a href="https://opentopomap.org">OpenTopoMap</a>' });
            const darkMode = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors © <a href="https://carto.com/attributions">CARTO</a>' });
            const baseMaps = { "Dark Mode": darkMode, "Streets": streets, "Satellite": satellite, "Topographic": topo };
            map = L.map('map', { center: [41.8781, -87.6298], zoom: 6, layers: [darkMode] });
            const basemapContainer = document.getElementById('basemap-selector-container');
            let first = true;
            for (const name in baseMaps) { const id = `basemap-radio-${name.replace(/\s+/g, '-')}`; const isChecked = first ? 'checked' : ''; basemapContainer.innerHTML += `<div class="form-check"><input class="form-check-input" type="radio" name="basemap-selector" id="${id}" value="${name}" ${isChecked}><label class="form-check-label" for="${id}">${name}</label></div>`; first = false; }
            basemapContainer.addEventListener('change', (event) => { const selectedBasemapName = event.target.value; if (baseMaps[selectedBasemapName]) { for (const name in baseMaps) { if (map.hasLayer(baseMaps[name])) { map.removeLayer(baseMaps[name]); } } map.addLayer(baseMaps[selectedBasemapName]); } });

            const createDetailRow = (label, value, valueClass = '') => { if (value === null || value === undefined || value === '') { return ''; } if (label.toLowerCase().includes('website') && value.startsWith('http')) { value = `<a href="${value}" target="_blank">View Site</a>`; } else if (label.toLowerCase().includes('email') && value.includes('@')) { value = `<a href="mailto:${value}">${value}</a>`; } if (label.toLowerCase().includes('wikipedia') && value.includes('wikipedia.org/wiki/')) { const pageTitle = value.split('/').pop().replace(/_/g, ' '); value = `<a href="${value}" target="_blank">${pageTitle}</a>`; } else if (label.toLowerCase().includes('wikidata') && value.startsWith('Q')) { value = `<a href="https://www.wikidata.org/wiki/${value}" target="_blank">${value}</a>`; } return ` <div class="detail-row"> <span class="detail-label">${label}:</span> <span class="detail-value ${valueClass}">${value}</span> </div> `; };
            const formatHydrantPopupContent = (props) => { const allTags = props.all_tags || {}; let generalDetails = ` ${createDetailRow("OSM ID", props.osm_id)} ${createDetailRow("Type", props.fire_hydrant_type || allTags['fire_hydrant:type'])} ${createDetailRow("Color", props.color || props.colour || allTags.colour || allTags.color)} ${createDetailRow("Operator", props.operator)} `; let locationDetails = ` ${createDetailRow("Street", props.addr_street || allTags['addr:street'])} ${createDetailRow("House No.", props.addr_housenumber || allTags['addr:housenumber'])} ${createDetailRow("City", props.addr_city || allTags['addr:city'])} ${createDetailRow("Postcode", props.addr_postcode || allTags['addr:postcode'])} ${createDetailRow("State", props.addr_state || allTags['addr:state'])} ${createDetailRow("Country", props.addr_country || allTags['addr:country'])} `; let technicalDetails = ` ${createDetailRow("Position", props.fire_hydrant_position || allTags['fire_hydrant:position'])} ${createDetailRow("Pressure", allTags['fire_hydrant:pressure'])} ${createDetailRow("Flow Rate", allTags['fire_hydrant:flow_rate'])} ${createDetailRow("Water Source", allTags['water_source'])} ${createDetailRow("Diameter", allTags.diameter)} `; let additionalText = props.note || allTags.note; return ` <div class="custom-popup"> <div class="popup-header"> <h4><i class="fas fa-faucet" style="color:#0dcaf0;"></i> Fire Hydrant Details</h4> <button class="close-btn" onclick="map.closePopup()">×</button> </div> <div class="popup-body two-columns"> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-info-circle"></i> General</div> ${generalDetails} </div> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-map-marker-alt"></i> Location</div> ${locationDetails} </div> <div class="popup-section" style="flex: 1 1 100%;"> <div class="popup-section-title"><i class="fas fa-tools"></i> Technical Specs</div> ${technicalDetails} </div> ${additionalText ? `<div class="popup-section" style="flex: 1 1 100%;"> <div class="popup-section-title"><i class="fas fa-sticky-note"></i> Notes</div> <div class="additional-text">${additionalText}</div> </div>` : ''} </div> </div> `; };
            const formatStationPopupContent = (props) => { const allTags = props.all_tags || {}; let primaryDetails = ` ${createDetailRow("Name", props.name || 'Unknown')} ${createDetailRow("Official Name", props.official_name)} ${createDetailRow("Operator", props.operator)} ${createDetailRow("Station Type", props.fire_station_type || allTags['fire_station:type'])} `; let contactDetails = ` ${createDetailRow("Phone", props.phone || allTags.phone)} ${createDetailRow("Emergency", props.emergency)} ${createDetailRow("Website", props.website || allTags.website)} ${createDetailRow("Email", props.email || allTags.email)} ${createDetailRow("Opening Hours", props.opening_hours || allTags['opening_hours'])} `; let addressDetails = ` ${createDetailRow("Street", props.addr_street || allTags['addr:street'])} ${createDetailRow("House No.", props.addr_housenumber || allTags['addr:housenumber'])} ${createDetailRow("City", props.addr_city || allTags['addr:city'])} ${createDetailRow("Postcode", props.addr_postcode || allTags['addr:postcode'])} ${createDetailRow("State", props.addr_state || allTags['addr:state'])} ${createDetailRow("Country", props.addr_country || allTags['addr:country'])} `; let operationalDetails = ` ${createDetailRow("Building Levels", props.building_levels || allTags['building:levels'])} ${createDetailRow("Apparatus", props.fire_station_apparatus || allTags['fire_station:apparatus'])} ${createDetailRow("Staffing", props.fire_station_staffing || allTags['fire_station:staffing'])} ${createDetailRow("Fire Station Code", props.fire_station_code || allTags['fire_station:code'])} `; let metaDetails = ` ${createDetailRow("OSM ID", props.osm_id)} ${createDetailRow("Source", props.source)} ${createDetailRow("Building Type", props.building)} ${createDetailRow("Wheelchair Access", props.wheelchair)} ${createDetailRow("Wikipedia", props.wikipedia)} ${createDetailRow("Wikidata", props.wikidata)} `; let additionalText = props.description || allTags.description || props.note || allTags.note; return ` <div class="custom-popup"> <div class="popup-header"> <h4><i class="fas fa-building" style="color:#fd7e14;"></i> Fire Station Details</h4> <button class="close-btn" onclick="map.closePopup()">×</button> </div> <div class="popup-body three-columns"> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-id-card-alt"></i> Identification</div> ${primaryDetails} </div> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-phone-alt"></i> Contact</div> ${contactDetails} </div> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-map-marked-alt"></i> Address</div> ${addressDetails} </div> <div class="popup-section" style="flex: 1 1 calc(50% - 10px);"> <div class="popup-section-title"><i class="fas fa-fire-extinguisher"></i> Operations</div> ${operationalDetails} </div> <div class="popup-section" style="flex: 1 1 calc(50% - 10px);"> <div class="popup-section-title"><i class="fas fa-globe"></i> Metadata</div> ${metaDetails} </div> ${additionalText ? `<div class="popup-section" style="flex: 1 1 100%;"> <div class="popup-section-title"><i class="fas fa-sticky-note"></i> Description</div> <div class="additional-text">${additionalText}</div> </div>` : ''} </div> </div> `; };
            const formatFireIncidentPopupContent = (props) => { let confidenceClass = 'text-warning'; if (props.confidence >= 80) confidenceClass = 'text-danger fw-bold'; else if (props.confidence >= 50) confidenceClass = 'text-info'; const details = ` ${createDetailRow("Date / Time", `${props.acq_date} @ ${props.acq_time} UTC`)} ${createDetailRow("Confidence", `${props.confidence}%`, confidenceClass)} ${createDetailRow("Brightness", `${props.brightness} K`)} ${createDetailRow("Fire Radiative Power", `${props.frp || 'N/A'} MW`)} ${createDetailRow("Satellite", props.satellite)} ${createDetailRow("Detected During", props.daynight)} ${createDetailRow("Coordinates", `${parseFloat(props.latitude).toFixed(4)}, ${parseFloat(props.longitude).toFixed(4)}`)} `; return ` <div class="custom-popup"> <div class="popup-header"> <h4><i class="fas fa-fire-alt" style="color:#dc3545;"></i> Active Hotspot</h4> <button class="close-btn" onclick="map.closePopup()">×</button> </div> <div class="popup-body"> <div class="popup-section" style="flex: 1 1 100%;"> <div class="popup-section-title"><i class="fas fa-info-circle"></i> Detection Details</div> ${details} </div> </div> </div>`; };

            fireHydrantsLayer = L.markerClusterGroup({ iconCreateFunction: function(cluster) { const count = cluster.getChildCount(); let c = ' hydrant-cluster-small'; if (count > 25) c = ' hydrant-cluster-medium'; if (count > 100) c = ' hydrant-cluster-large'; return L.divIcon({ html: `<div><span>${count}</span></div>`, className: 'cluster-icon' + c, iconSize: L.point(40, 40) }); }, spiderfyOnMaxZoom: true, maxClusterRadius: 60, showCoverageOnHover: true, zoomToBoundsOnClick: true }).addTo(map);
            fireStationsLayer = L.markerClusterGroup({ iconCreateFunction: function(cluster) { const count = cluster.getChildCount(); let c = ' station-cluster-small'; if (count > 5) c = ' station-cluster-medium'; if (count > 15) c = ' station-cluster-large'; return L.divIcon({ html: `<div><span>${count}</span></div>`, className: 'cluster-icon' + c, iconSize: L.point(40, 40) }); }, spiderfyOnMaxZoom: true, maxClusterRadius: 60, showCoverageOnHover: true, zoomToBoundsOnClick: true }).addTo(map);
            searchResultsLayer = L.geoJson(null, { pointToLayer: (feature, latlng) => L.circleMarker(latlng, { radius: 8, fillColor: feature.properties.hasOwnProperty('fire_hydrant_type') ? "#0dcaf0" : "#fd7e14", color: "#fff", weight: 2, opacity: 1, fillOpacity: 0.9 }), onEachFeature: (f, l) => l.bindPopup(f.properties.hasOwnProperty('fire_hydrant_type') ? formatHydrantPopupContent(f.properties) : formatStationPopupContent(f.properties), { className: 'custom-popup' }) }).addTo(map);
            modisFiresLayer = L.markerClusterGroup({ iconCreateFunction: function(cluster) { const c = cluster.getChildCount(); let s = ' fire-cluster-small'; if (c > 100) s = ' fire-cluster-medium'; if (c > 500) s = ' fire-cluster-large'; return L.divIcon({ html: `<div><span>${c}</span></div>`, className: 'cluster-icon' + s, iconSize: L.point(40, 40) }); }, maxClusterRadius: 60, spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true }).addTo(map);

            const loadDataForBounds = async (bounds) => { const bbox = bounds.toBBoxString(); console.log(`Loading data for bounds: ${bbox}`); if (document.getElementById('fire-hydrants-toggle').checked) { try { const r = await fetch(`/api/fire_hydrants?bbox=${bbox}&limit=5000`); if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`); const d = await r.json(); fireHydrantsLayer.clearLayers(); const hydrantsGeoJson = L.geoJson(d, { pointToLayer: (feature, latlng) => L.circleMarker(latlng, { radius: 8, fillColor: "#0dcaf0", color: "#0275d8", weight: 2, opacity: 1, fillOpacity: 0.8 }), onEachFeature: (f, l) => l.bindPopup(formatHydrantPopupContent(f.properties), { className: 'custom-popup' }) }); fireHydrantsLayer.addLayer(hydrantsGeoJson); } catch (e) { console.error("Could not fetch fire hydrants:", e); } } if (document.getElementById('fire-stations-toggle').checked) { try { const r = await fetch(`/api/fire_stations?bbox=${bbox}&limit=1000`); if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`); const d = await r.json(); fireStationsLayer.clearLayers(); const stationsGeoJson = L.geoJson(d, { pointToLayer: (feature, latlng) => L.circleMarker(latlng, { radius: 9, fillColor: "#fd7e14", color: "#d9534f", weight: 2, opacity: 1, fillOpacity: 0.8 }), onEachFeature: (f, l) => l.bindPopup(formatStationPopupContent(f.properties), { className: 'custom-popup' }) }); fireStationsLayer.addLayer(stationsGeoJson); } catch (e) { console.error("Could not fetch fire stations:", e); } } };
            const loadDataForDrawnRect = async (bounds) => { const bbox = bounds.toBBoxString(); searchResultsLayer.clearLayers(); const p = L.popup().setLatLng(bounds.getCenter()).setContent('Searching...').openOn(map); try { const [hr, sr] = await Promise.all([fetch(`/api/fire_hydrants?bbox=${bbox}`), fetch(`/api/fire_stations?bbox=${bbox}`)]); const h = await hr.json(); const s = await sr.json(); searchResultsLayer.addData(h); searchResultsLayer.addData(s); map.closePopup(p); const c = (h.features?.length || 0) + (s.features?.length || 0); L.popup().setLatLng(bounds.getCenter()).setContent(`Found ${c} assets.`).openOn(map); map.fitBounds(searchResultsLayer.getBounds().pad(0.1)); } catch (e) { console.error("Error during area search:", e); map.closePopup(p); L.popup().setLatLng(bounds.getCenter()).setContent('Error searching.').openOn(map); } };
            const loadModisFires = async () => { if (!document.getElementById('modis-fires-toggle').checked) { modisFiresLayer.clearLayers(); return; } try { const response = await fetch('/api/fire-incidents'); if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`); const geoJsonData = await response.json(); modisFiresLayer.clearLayers(); const fireMarkers = L.geoJson(geoJsonData, { pointToLayer: function (feature, latlng) { const confidence = feature.properties.confidence; let iconClass = 'fire-incident-icon-low'; if (confidence >= 80) iconClass = 'fire-incident-icon-high'; else if (confidence >= 50) iconClass = 'fire-incident-icon-medium'; const fireIcon = L.divIcon({ html: '<i class="fas fa-fire-alt fa-2x"></i>', className: `fire-incident-icon ${iconClass}`, iconSize: [24, 24] }); return L.marker(latlng, { icon: fireIcon }); }, onEachFeature: (feature, layer) => { layer.bindPopup(formatFireIncidentPopupContent(feature.properties), { className: 'custom-popup', minWidth: 320 }); layer.on('click', (e) => { if (selectedFireMarker) L.DomUtil.removeClass(selectedFireMarker.getElement(), 'fire-incident-icon-selected'); selectedFireFeature = feature; selectedFireMarker = layer; L.DomUtil.addClass(layer.getElement(), 'fire-incident-icon-selected'); const infoDiv = document.getElementById('selected-fire-info'); if (infoDiv) { const coords = `${parseFloat(feature.geometry.coordinates[1]).toFixed(4)}, ${parseFloat(feature.geometry.coordinates[0]).toFixed(4)}`; infoDiv.innerHTML = `<p class="text-primary mb-0 text-center fw-bold">Selected Fire:<br><small class="fw-normal">${coords}</small></p>`; } document.getElementById('calculate-route-btn').disabled = false; const routeTabBtn = document.getElementById('route-tab-btn'); if (routeTabBtn) new bootstrap.Tab(routeTabBtn).show(); }); } }); modisFiresLayer.addLayer(fireMarkers); } catch (e) { console.error("Could not fetch MODIS fire incidents:", e); } };

            document.getElementById('fire-hydrants-toggle').addEventListener('change', e => { if (e.target.checked) loadDataForBounds(map.getBounds()); else fireHydrantsLayer.clearLayers(); });
            document.getElementById('fire-stations-toggle').addEventListener('change', e => { if (e.target.checked) loadDataForBounds(map.getBounds()); else fireStationsLayer.clearLayers(); });
            document.getElementById('modis-fires-toggle').addEventListener('change', loadModisFires);
            map.on('moveend', () => loadDataForBounds(map.getBounds()));
            const drawnItems = new L.FeatureGroup(); map.addLayer(drawnItems);
            const drawControl = new L.Control.Draw({ draw: { polygon: false, polyline: false, circle: false, marker: false, circlemarker: false, rectangle: { shapeOptions: { color: '#007bff' }, showArea: false } }, edit: { featureGroup: drawnItems } });
            map.addControl(drawControl);
            map.on(L.Draw.Event.CREATED, (e) => loadDataForDrawnRect(e.layer.getBounds()));
            new Draggabilly(document.querySelector('.layers-panel'), { handle: '#layersHeading', containment: '.map-wrapper' });
            new Draggabilly(document.querySelector('.basemap-panel'), { handle: '#basemapHeading', containment: '.map-wrapper' });

            const legend = L.control({ position: 'bottomright' });
            legend.onAdd = function(map) { const div = L.DomUtil.create('div', 'info legend-control'); let labels = []; labels.push('<h4>Hotspot Confidence</h4>'); labels.push('<div class="legend-item"><i class="fas fa-fire-alt fire-incident-icon-high"></i> High (≥ 80%)</div>'); labels.push('<div class="legend-item"><i class="fas fa-fire-alt fire-incident-icon-medium"></i> Medium (50-79%)</div>'); labels.push('<div class="legend-item"><i class="fas fa-fire-alt fire-incident-icon-low"></i> Low (< 50%)</div>'); const hydrantGrades = [1, 26, 101]; const hydrantColors = [ 'rgba(2, 117, 216, 0.85)', 'rgba(13, 202, 240, 0.85)', 'rgba(13, 110, 253, 0.9)' ]; labels.push('<br><h4>Hydrant Density</h4>'); for (let i = 0; i < hydrantGrades.length; i++) { const from = hydrantGrades[i]; const to = hydrantGrades[i + 1]; labels.push( `<div class="legend-item"><i style="background:${hydrantColors[i]}"></i> ` + from + (to ? '–' + (to - 1) : '+') + '</div>' ); } const stationGrades = [1, 6, 16]; const stationColors = [ 'rgba(40, 167, 69, 0.9)', 'rgba(253, 126, 20, 0.9)', 'rgba(220, 53, 69, 0.9)' ]; labels.push('<br><h4 class="mt-2">Station Density</h4>'); for (let i = 0; i < stationGrades.length; i++) { const from = stationGrades[i]; const to = stationGrades[i + 1]; labels.push( `<div class="legend-item"><i style="background:${stationColors[i]}"></i> ` + from + (to ? '–' + (to - 1) : '+') + '</div>' ); } div.innerHTML = labels.join(''); return div; };
            legend.addTo(map);

            loadDataForBounds(map.getBounds());
            loadModisFires();
            initFullScreenControl();
            console.log('Map initialization finished.');
        };
        
        // --- MODIFICATION: Rewritten sidebar toggle logic for responsiveness ---
        const initSidebarToggles = () => {
            // Left Sidebar Toggle Logic (unchanged)
            const header = document.querySelector('.main-content .header'); 
            if (header) {
                const toggleBtnHtml = `<button class="left-sidebar-toggle p-0 border-0 bg-transparent ms-3" id="left-sidebar-toggle" title="Toggle Navigation"><i class="fas fa-bars"></i></button>`;
                header.insertAdjacentHTML('afterbegin', toggleBtnHtml);
                const leftSidebarToggle = document.getElementById('left-sidebar-toggle');
                if (leftSidebarToggle) {
                    leftSidebarToggle.addEventListener('click', () => {
                        document.body.classList.toggle('left-sidebar-collapsed');
                        document.body.classList.remove('map-fullscreen-active');
                        setTimeout(() => { if(map) map.invalidateSize(); }, 300); 
                    });
                }
            }
            
            // Right Sidebar Toggle Logic (new implementation)
            const rightSidebarToggle = document.getElementById('right-sidebar-toggle');
            const dashboardContainer = document.querySelector('.wildfire-dashboard-container');

            if(rightSidebarToggle && dashboardContainer) {
                rightSidebarToggle.addEventListener('click', () => {
                    dashboardContainer.classList.toggle('right-sidebar-collapsed');
                    document.body.classList.remove('map-fullscreen-active'); // Exit map fullscreen
                    
                    const isCollapsed = dashboardContainer.classList.contains('right-sidebar-collapsed');
                    const icon = rightSidebarToggle.querySelector('i');

                    // Update button icon and title based on state
                    if(isCollapsed) {
                        icon.className = 'fas fa-chevron-left';
                        rightSidebarToggle.title = 'Show Sidebar';
                    } else {
                        icon.className = 'fas fa-chevron-right';
                        rightSidebarToggle.title = 'Hide Sidebar';
                    }
                    
                    // Invalidate map size after CSS transition
                    setTimeout(() => {
                        if(map) map.invalidateSize();
                    }, 300);
                });
            } else {
                console.error('One or more elements for right sidebar toggle are missing.');
            }
        };

        // =========================================================================
        // START: CHAT FUNCTIONALITY (UNCHANGED)
        // =========================================================================
        const initChat = () => {
            const chatInput = document.getElementById('chat-input'); const sendBtn = document.getElementById('send-chat-btn'); const messageContainer = document.getElementById('chat-messages');
            const renderMessage = (text, sender) => { let html; const sanitizedText = text.replace(/</g, "<").replace(/>/g, ">"); if (sender === 'user') { html = `<div class="mb-3 text-end"><div class="p-3 rounded mt-1 bg-primary-subtle d-inline-block">${sanitizedText}</div></div>`; } else if (sender === 'ai-typing') { html = `<div class="mb-3 text-start" id="ai-typing-indicator"><small class="text-body-secondary">Artemis AI Assistant</small><div class="p-3 rounded mt-1 bg-body-secondary d-inline-block"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Typing...</div></div>`; } else { html = `<div class="mb-3 text-start"><small class="text-body-secondary">Artemis AI Assistant</small><div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">${sanitizedText}</div></div>`; } messageContainer.innerHTML += html; messageContainer.scrollTop = messageContainer.scrollHeight; };
            const sendMessage = async () => { const messageText = chatInput.value.trim(); if (!messageText) return; renderMessage(messageText, 'user'); chatInput.value = ''; chatInput.disabled = true; sendBtn.disabled = true; renderMessage('', 'ai-typing'); try { const response = await fetch('/api/chat', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ message: messageText }) }); const typingIndicator = document.getElementById('ai-typing-indicator'); if (typingIndicator) typingIndicator.remove(); if (!response.ok) { const errorData = await response.json(); throw new Error(errorData.error || 'The server returned an error.'); } const data = await response.json(); renderMessage(data.reply, 'ai'); } catch (error) { console.error('Chat Error:', error); const typingIndicator = document.getElementById('ai-typing-indicator'); if (typingIndicator) typingIndicator.remove(); renderMessage(`Sorry, I ran into a problem: ${error.message}`, 'ai'); } finally { chatInput.disabled = false; sendBtn.disabled = false; chatInput.focus(); } };
            sendBtn.addEventListener('click', sendMessage); chatInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
        };
        // =========================================================================
        // END: CHAT FUNCTIONALITY
        // =========================================================================
        
        const initGeneralUI = () => { document.querySelectorAll('.card-header button[data-bs-toggle="collapse"]').forEach(b => b.addEventListener('click', function() { const i = this.querySelector('.collapse-icon'); if (i) { i.classList.toggle('fa-chevron-down'); i.classList.toggle('fa-chevron-up'); } })); };
        const loadAndRenderReportHistory = async () => { try { const response = await fetch('/reports/history', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } }); if (!response.ok) throw new Error(`Server responded with status: ${response.status}`); const reports = await response.json(); renderPreviousTranscriptsAccordion(reports); renderNotificationsFromReports(reports); } catch (error) { console.error('Failed to load report history:', error); const errorHtml = `<div class="alert alert-warning text-center">Could not load report history.</div>`; const transcriptContainer = document.getElementById('previous-transcripts-container'); if (transcriptContainer) transcriptContainer.innerHTML = errorHtml; } };
        const renderPreviousTranscriptsAccordion = (reports) => { const container = document.getElementById('previous-transcripts-container'); const loadingIndicator = document.getElementById('previous-transcripts-loading'); if (loadingIndicator) loadingIndicator.style.display = 'none'; if (reports && reports.length > 0) { let html = '<div class="accordion" id="previousReportsAccordion">'; reports.forEach((report) => { const reportDate = new Date(report.created_at).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' }); const suggestionsList = report.ai_suggested_actions?.suggestions || report.ai_suggested_actions || []; let suggestionsHtml = ''; if (Array.isArray(suggestionsList) && suggestionsList.length > 0) { suggestionsHtml = '<ul class="list-group list-group-flush">'; suggestionsList.forEach(s => { suggestionsHtml += `<li class="list-group-item bg-transparent border-secondary"><i class="${s.icon || 'fas fa-lightbulb'} me-2 text-success"></i> ${s.suggestion || '...'}</li>`; }); suggestionsHtml += '</ul>'; } else { suggestionsHtml = '<p class="text-muted mb-0">No suggestions were generated for this report.</p>'; } html += `<div class="accordion-item bg-dark border-secondary mb-2"><h2 class="accordion-header" id="heading-history-${report.id}"><button class="accordion-button collapsed bg-body-tertiary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-history-${report.id}" aria-expanded="false" aria-controls="collapse-history-${report.id}">Report from ${reportDate}</button></h2><div id="collapse-history-${report.id}" class="accordion-collapse collapse" aria-labelledby="heading-history-${report.id}" data-bs-parent="#previousReportsAccordion"><div class="accordion-body"><h6 class="text-white-50">Transcript</h6><p class="mb-4 fst-italic">"${report.transcript || 'Transcript not available.'}"</p><h6 class="text-white-50">AI Suggested Actions</h6>${suggestionsHtml}</div></div></div>`; }); html += '</div>'; container.innerHTML = html; } else { container.innerHTML = '<p class="text-muted text-center p-4">No previous reports found.</p>'; } };
        const renderNotificationsFromReports = (reports) => { const list = document.getElementById('notifications-list'); const placeholder = document.getElementById('notifications-placeholder'); if (reports && reports.length > 0) { placeholder.classList.add('d-none'); let html = ''; reports.forEach(report => { const timeAgo = new Date(report.created_at).toLocaleString([], { dateStyle: 'short', timeStyle: 'short' }); const transcript = report.transcript || 'Transcript not available.'; html += `<div class="list-group-item list-group-item-action p-3"><div class="d-flex w-100 justify-content-between"><h6 class="mb-1 text-info"><i class="fas fa-file-alt me-2"></i>Field Report Logged</h6><small class="text-body-secondary">${timeAgo}</small></div><p class="mb-1 small fst-italic">"${transcript.substring(0, 150)}${transcript.length > 150 ? '...' : ''}"</p></div>`; }); list.innerHTML = html; } else { placeholder.classList.remove('d-none'); list.innerHTML = ''; list.appendChild(placeholder); } };
        const initLiveReport = () => { const recordButton = document.getElementById('record-button'); if (!recordButton) return; const recordIcon = recordButton.querySelector('i'); const recordingStatus = document.getElementById('recording-status'); const resultsContainer = document.getElementById('ai-analysis-results'); const placeholder = document.getElementById('report-placeholder'); const errorContainer = document.getElementById('report-error'); let mediaRecorder; let audioChunks = []; let isRecording = false; const setupAudio = async () => { if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) { try { const stream = await navigator.mediaDevices.getUserMedia({ audio: true }); mediaRecorder = new MediaRecorder(stream); mediaRecorder.addEventListener("dataavailable", e => audioChunks.push(e.data)); mediaRecorder.addEventListener("stop", async () => { const audioBlob = new Blob(audioChunks, { type: mediaRecorder.mimeType }); audioChunks = []; await sendAudioToServer(audioBlob); }); } catch (err) { console.error("Microphone Access Error:", err); showError("Microphone access denied. Please enable it in browser settings."); recordButton.disabled = true; } } else { showError("Audio recording not supported."); recordButton.disabled = true; } }; recordButton.addEventListener('click', () => { if (!mediaRecorder) return; if (!isRecording) { mediaRecorder.start(); isRecording = true; recordButton.classList.add('is-recording'); recordIcon.className = 'fas fa-stop'; recordingStatus.textContent = 'Listening... (Tap to stop)'; placeholder?.classList.add('d-none'); errorContainer?.classList.add('d-none'); if (resultsContainer) resultsContainer.innerHTML = ''; } else { mediaRecorder.stop(); isRecording = false; recordButton.classList.remove('is-recording'); recordIcon.className = 'fas fa-sync-alt fa-spin'; recordingStatus.textContent = 'Analyzing Report...'; recordButton.disabled = true; } }); const sendAudioToServer = async (audioBlob) => { const formData = new FormData(); formData.append('audio', audioBlob, 'report.webm'); try { const response = await fetch('/api/process-report', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }, body: formData }); if (!response.ok) { const errorData = await response.json(); throw new Error(errorData.error || `Server error: ${response.status}`); } const data = await response.json(); displayResults(data); loadAndRenderReportHistory(); } catch (err) { console.error('Error processing report:', err); showError(`Failed to process report: ${err.message}`); } finally { recordIcon.className = 'fas fa-microphone'; recordingStatus.textContent = 'Tap to Start Field Report'; recordButton.disabled = false; } }; const displayResults = (data) => { let entityHtml = ''; if (data.entities?.length) { data.entities.forEach(entity => { let c = 'entity-tag-other'; const cat = entity.category.toLowerCase(); if (cat.includes('location')) c = 'entity-tag-location'; else if (cat.includes('resource') || cat.includes('equipment')) c = 'entity-tag-resource'; else if (cat.includes('hazard') || cat.includes('skill')) c = 'entity-tag-hazard'; entityHtml += `<span class="entity-tag ${c}">${entity.text}</span> `; }); } let suggestionHtml = ''; const suggestionsList = data.suggestions?.suggestions || data.suggestions; if (Array.isArray(suggestionsList)) { suggestionsList.forEach(s => { const suggestionText = s.suggestion || '...'; suggestionHtml += `<li class="suggestion-item-tts px-3"><div class="d-flex align-items-start gap-3"><i class="${s.icon || 'fas fa-lightbulb'} suggestion-icon"></i><div><strong>${suggestionText}</strong></div></div><button class="btn btn-sm btn-outline-secondary tts-button" data-text="${suggestionText}" aria-label="Read suggestion aloud"><i class="fas fa-volume-up"></i></button></li>`; }); } const resultsHtml = `<div class="card ai-analysis-card mb-3"><div class="card-header"><i class="fas fa-brain me-2"></i>AI Summary</div><div class="card-body"><p class="card-text">${data.summary || 'No summary.'}</p></div></div><div class="card ai-analysis-card mb-3"><div class="card-header"><i class="fas fa-tags me-2"></i>Key Entities</div><div class="card-body">${entityHtml.trim() || '<span class="text-muted">No entities detected.</span>'}</div></div><div class="card ai-analysis-card mb-3"><div class="card-header"><i class="fas fa-tasks me-2"></i>AI-Suggested Actions</div><div class="card-body p-0"><ul class="list-unstyled mb-0">${suggestionHtml.trim() || '<li class="p-3 text-muted">No suggestions.</li>'}</ul></div></div><div class="accordion" id="transcriptAccordion"><div class="accordion-item bg-transparent border-secondary"><h2 class="accordion-header"><button class="accordion-button collapsed bg-body-tertiary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fas fa-file-alt me-2"></i>View Full Transcript</button></h2><div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#transcriptAccordion"><div class="accordion-body">${data.transcript || 'Transcript unavailable.'}</div></div></div></div>`; if (resultsContainer) resultsContainer.innerHTML = resultsHtml; }; const showError = (message) => { if (errorContainer) { errorContainer.textContent = message; errorContainer.classList.remove('d-none'); } if (placeholder) placeholder.classList.add('d-none'); if (resultsContainer) resultsContainer.innerHTML = ''; }; setupAudio(); };
        const initNotificationSystem = () => { const badge = document.getElementById('notification-badge'); if(badge) badge.classList.add('d-none'); };
        const initTextToSpeech = () => { if (!('speechSynthesis' in window)) { console.warn('Speech Synthesis not supported.'); return; } document.body.addEventListener('click', (event) => { const ttsButton = event.target.closest('.tts-button'); if (ttsButton) { const textToSpeak = ttsButton.dataset.text; if (textToSpeak) { window.speechSynthesis.cancel(); const utterance = new SpeechSynthesisUtterance(textToSpeak); utterance.pitch = 1; utterance.rate = 0.9; window.speechSynthesis.speak(utterance); } } }); };
        const initRouting = () => { const calculateBtn = document.getElementById('calculate-route-btn'); const clearBtn = document.getElementById('clear-route-btn'); const toggleLoadingState = (isLoading) => { const spinner = calculateBtn.querySelector('.spinner-border'); const icon = calculateBtn.querySelector('.icon'); calculateBtn.disabled = isLoading; if(isLoading) { spinner.classList.remove('d-none'); icon.classList.add('d-none'); } else { calculateBtn.disabled = (selectedFireFeature === null); spinner.classList.add('d-none'); icon.classList.remove('d-none'); } }; const clearRoute = () => { if (routeLayer) map.removeLayer(routeLayer); if (selectedFireMarker) L.DomUtil.removeClass(selectedFireMarker.getElement(), 'fire-incident-icon-selected'); routeLayer = null; selectedFireFeature = null; selectedFireMarker = null; document.getElementById('selected-fire-info').innerHTML = `<p class="text-center text-muted mb-0">No fire selected.</p>`; calculateBtn.disabled = true; clearBtn.style.display = 'none'; document.getElementById('route-summary').classList.add('d-none'); document.getElementById('route-placeholder').classList.remove('d-none'); document.getElementById('route-placeholder').textContent = 'Route information will appear here.'; map.closePopup(); }; calculateBtn.addEventListener('click', async () => { if (!selectedFireFeature) return; toggleLoadingState(true); const fireCoords = L.latLng(selectedFireFeature.geometry.coordinates[1], selectedFireFeature.geometry.coordinates[0]); const bbox = map.getBounds().toBBoxString(); try { const response = await fetch(`/api/fire_stations?bbox=${bbox}&limit=2000`); if (!response.ok) throw new Error('Could not fetch fire stations.'); const stationsData = await response.json(); if (!stationsData.features || stationsData.features.length === 0) { throw new Error('No fire stations found in the current map view. Please pan or zoom out.'); } let closestStation = null; let minDistance = Infinity; const getDistance = (lat1, lon1, lat2, lon2) => { const R=6371;const dLat=(lat2-lat1)*(Math.PI/180);const dLon=(lon2-lon1)*(Math.PI/180);const a=Math.sin(dLat/2)*Math.sin(dLat/2)+Math.cos(lat1*(Math.PI/180))*Math.cos(lat2*(Math.PI/180))*Math.sin(dLon/2)*Math.sin(dLon/2);const c=2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));return R*c;}; stationsData.features.forEach(stationFeature => { const stationCoords = L.latLng(stationFeature.geometry.coordinates[1], stationFeature.geometry.coordinates[0]); const distance = getDistance(fireCoords.lat, fireCoords.lng, stationCoords.lat, stationCoords.lng); if (distance < minDistance) { minDistance = distance; closestStation = stationFeature; } }); if (!closestStation) throw new Error('Could not determine closest station.'); const stationCoords = closestStation.geometry.coordinates; const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${stationCoords[0]},${stationCoords[1]};${fireCoords.lng},${fireCoords.lat}?overview=full&geometries=geojson`; const osrmResponse = await fetch(osrmUrl); if (!osrmResponse.ok) throw new Error(`Routing service failed: ${osrmResponse.statusText}`); const routeData = await osrmResponse.json(); if (!routeData.routes || routeData.routes.length === 0) throw new Error('No route could be found.'); const route = routeData.routes[0]; if (routeLayer) map.removeLayer(routeLayer); routeLayer = L.geoJson(route.geometry, { style: { color: '#0dcaf0', weight: 6, opacity: 0.8 } }).addTo(map); map.fitBounds(routeLayer.getBounds().pad(0.2)); document.getElementById('route-station-name').textContent = closestStation.properties.name || 'Unnamed Station'; document.getElementById('route-distance').textContent = `${(route.distance / 1000).toFixed(1)} km`; document.getElementById('route-duration').textContent = `${Math.round(route.duration / 60)} mins`; document.getElementById('route-summary').classList.remove('d-none'); document.getElementById('route-placeholder').classList.add('d-none'); clearBtn.style.display = 'block'; } catch (error) { console.error('Routing error:', error); alert(`Could not calculate route: ${error.message}`); document.getElementById('route-placeholder').textContent = `Error: ${error.message}`; } finally { toggleLoadingState(false); } }); clearBtn.addEventListener('click', clearRoute); };
        const initSidebarFix = () => { const sidebarTabs = document.querySelectorAll('#sidebar-tabs button[data-bs-toggle="pill"]'); sidebarTabs.forEach(tab => { tab.addEventListener('shown.bs.tab', () => { if (window.App && typeof window.App.updateSidebarScroll === 'function') { try { window.App.updateSidebarScroll(); } catch (e) { console.warn("Could not update sidebar scroll.", e); } } }); }); };

        // --- INITIALIZE ALL SYSTEMS ---
        setTimeout(initMap, 250);
        initChat();
        initGeneralUI();
        initLiveReport();
        initNotificationSystem();
        initTextToSpeech();
        loadAndRenderReportHistory();
        initRouting();
        initSidebarFix();
        initSidebarToggles();
    });
</script>
</body>
</html>