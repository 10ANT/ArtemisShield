<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ArtemisShield - Wildfire Protection Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link href="https://cesium.com/downloads/cesiumjs/releases/1.117/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="{{ asset('css/leaflet-velocity.css') }}" />

    @include('partials.styles')
    <style>
        :root { --legend-width: 280px; --right-sidebar-width: 25%; }
        html, body { height: 100%; overflow: hidden; }
        
        .main-content { height: 100vh; display: flex; flex-direction: column; }
        .wildfire-dashboard-container { flex-grow: 1; min-height: 0; }
        
        .map-column { height: 100%; position: relative; transition: width 0.3s ease-in-out; }
        body.fullscreen .map-column { width: 100% !important; }
        body.fullscreen .right-sidebar-column { display: none !important; }
        body.fullscreen .header { display: none !important; }
        
        .right-sidebar-column { width: var(--right-sidebar-width); max-width: 600px; min-width: 320px; resize: horizontal; overflow: auto; border-left: 1px solid var(--bs-border-color); }
        .right-sidebar-column.d-none { display: none !important; }

        #map-wrapper, #map, #cesium-container { position: absolute; top:0; left:0; height: 100%; width: 100%; margin: 0; padding: 0; }
        #cesium-container { z-index: 0; }
        #map { z-index: 1; background: transparent; }
        
        /* FIXED: Hardcoded dark theme for Layers & Legend panel */
        #layers-sidebar { 
            position: absolute; 
            top: 15px; 
            left: 15px; 
            width: var(--legend-width); 
            min-width: 220px; 
            max-width: 500px; 
            z-index: 1010; 
            background-color: #212529; /* Hardcoded dark background */
            color: #dee2e6; /* Hardcoded light text */
            border: 1px solid rgba(255, 255, 255, 0.15); /* Hardcoded light border */
            border-radius: .5rem; 
            max-height: calc(100vh - 100px); 
            display: flex; 
            flex-direction: column; 
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); 
            resize: both; 
            overflow: auto; 
        }
        #layers-sidebar-header { cursor: move; flex-shrink: 0; }
        #layers-sidebar-content { overflow-y: auto; flex-grow: 1; transition: all 0.2s ease-out; max-height: 500px; }
        #layers-sidebar.collapsed #layers-sidebar-content { max-height: 0; padding-top: 0 !important; padding-bottom: 0 !important; opacity: 0; }
        #layers-sidebar.collapsed { max-height: 40px; }
        /* Ensure text within the legend is readable */
        #layers-sidebar .form-check-label, #layers-sidebar .legend-item, #layers-sidebar h6, #layers-sidebar .form-label {
            color: #dee2e6;
        }

        .legend-item { display: flex; align-items: center; font-size: 0.9rem; }
        .legend-icon { width: 30px; margin-right: 10px; text-align: center; }
        .cesium-viewer-bottom { display: none !important; }
        
        #map-controls { 
            position: absolute; 
            top: 75px; 
            right: 10px; 
            z-index: 1001; 
            display: flex; 
            flex-direction: column; 
            gap: 5px;
            background: rgba(181, 200, 230, 0.3); /* Use specific RGBA for dark frosted glass */
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: .5rem;
            padding: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        #map-controls .btn.active {
             background-color: var(--bs-primary);
             border-color: var(--bs-primary);
        }

        .leaflet-routing-container { display: none !important; }
        #recent-fires .card:hover { background-color: var(--bs-body-tertiary); cursor: pointer; }
        .frp-legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; border: 1px solid #666;}
        
        .weather-popup .card-body { padding: 0.75rem; }
        .weather-popup .weather-main { display: flex; align-items: center; justify-content: space-between; }
        .weather-popup .weather-main h4 { margin: 0; }
        .weather-popup .weather-details { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.9rem; }
        
        #goes-preview { position: fixed; z-index: 1002; background: rgba(0,0,0,0.7); border: 2px solid #fff; border-radius: 5px; pointer-events: none; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 5px; }
        #goes-preview img { width: 300px; height: 300px; }
        #goes-preview p { color: white; margin: 5px 0 0 0; font-size: 0.8em; text-align: center; }
        #goes-fire-temp-btn.active, #toggle-contained-btn.active { background-color: var(--bs-primary); border-color: var(--bs-primary); }
        #create-alert-btn.active, #measure-distance-btn.active { background-color: var(--bs-warning); border-color: var(--bs-warning); color: #000; }
        .map-container.goes-preview-active { cursor: crosshair; }
        #zoomed-goes-modal .modal-dialog { max-width: 90vw; }
        #zoomed-goes-modal .modal-content { background-color: rgba(10, 10, 10, 0.85); backdrop-filter: blur(5px); border: 1px solid #555; }
        #zoomed-goes-image-container { position: relative; cursor: crosshair; }
        #zoomed-goes-image { width: 100%; height: auto; max-height: 80vh; object-fit: contain; background-color: #000;}
        #detection-canvas { position: absolute; top: 0; left: 0; pointer-events: none; }
        #magnifier-loupe { width: 200px; height: 200px; position: absolute; border: 3px solid #fff; border-radius: 50%; box-shadow: 0 0 10px rgba(0,0,0,0.5); pointer-events: none; display: none; background-repeat: no-repeat; }
        #image-analysis-loader { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; }
        
        /* FIXED: Hardcoded dark theme for Timeline slider */
        #timeline-container { 
            position: absolute; 
            bottom: 10px; 
            left: 50%; 
            transform: translateX(-50%); 
            width: 70%; 
            max-width: 800px; 
            z-index: 1001; 
            background: rgba(33, 37, 41, 0.85); /* Hardcoded dark frosted glass */
            backdrop-filter: blur(4px); 
            border: 1px solid rgba(255, 255, 255, 0.15); /* Hardcoded light border */
            border-radius: .5rem; 
            padding: 10px 20px; 
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.2); 
            display: flex; 
            align-items: center; 
            gap: 15px; 
        }
        #timeline-label { 
            font-size: 0.9em; 
            font-weight: bold; 
            white-space: nowrap; 
            min-width: 100px; 
            text-align: center; 
            color: #dee2e6; /* Hardcoded light text */
        }
        #timeline-slider { flex-grow: 1; }
        
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
        .sidebar-wrapper > .card-body { flex: 1; min-height: 0; display: flex; flex-direction: column; }
        .sidebar-wrapper .tab-content { flex: 1; min-height: 0; position: relative; }
        
        .sidebar-wrapper .tab-pane {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: none;
            flex-direction: column;
        }
        
        .chat-container { height: 100%; display: flex; flex-direction: column; }
        .chat-messages { flex: 1; overflow-y: auto; min-height: 0; }
        #routes-content, #control-content { display: flex; flex-direction: column; height: 100%; }
        #routes-content .flex-grow-1, #control-content .flex-grow-1 { min-height: 0; overflow-y: auto; }
        
        #search-icon-btn { position: absolute; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1002; }
        #search-container {
            position: absolute; top: 55px; left: 50%; transform: translateX(-50%);
            width: 500px; max-width: 90%; z-index: 1002;
            background: rgba(var(--bs-body-bg-rgb), 0.85); backdrop-filter: blur(4px);
            border: 1px solid var(--bs-border-color); border-radius: .5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.2);
            transition: all 0.2s ease-in-out; opacity: 1; visibility: visible;
        }
        #search-container.hidden { opacity: 0; visibility: hidden; transform: translate(-50%, -10px); }
        .search-input-group { position: relative; }
        .search-results-container {
            position: absolute; top: 100%; left: 0; right: 0;
            max-height: 300px; overflow-y: auto;
            background-color: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color); border-top: none;
            border-radius: 0 0 .5rem .5rem;
        }
        .search-result-card { padding: 0.5rem 1rem; cursor: pointer; border-bottom: 1px solid var(--bs-border-color); }
        .search-result-card:last-child { border-bottom: none; }
        .search-result-card:hover { background-color: var(--bs-secondary-bg); }
        .search-result-card .result-name { font-weight: 500; }
        .search-result-card .result-details { font-size: 0.8em; color: var(--bs-secondary-color); }
        .spinner-border { position: absolute; right: 10px; top: 50%; margin-top: -0.5rem; }

        .analysis-card { border-left-width: 5px; cursor: pointer; }
        .analysis-card:hover { background-color: var(--bs-tertiary-bg); }
        .analysis-card .spinner-border { width: 1rem; height: 1rem; }
        
        @media (max-width: 991.98px) {
            .wildfire-dashboard-container {
                flex-direction: column;
                height: 100%;
            }
            .map-column {
                height: 60vh;
            }
            .right-sidebar-column {
                width: 100% !important;
                height: 40vh;
                min-height: 300px;
                border-left: none;
                border-top: 2px solid var(--bs-border-color);
                resize: none;
            }
            #map-controls {
                top: 10px;
            }
        }

    </style>
</head>

<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

    <div class="container-fluid">
        <div class="main-content">
            @include('partials.header')
            <div class="wildfire-dashboard-container row g-0 flex-grow-1">
                <div class="col map-column ">
                    <div id="map-wrapper">
                        <div id="cesium-container" class="d-none"></div>
                        <div id="map"></div>
                        <button id="search-icon-btn" class="btn btn-secondary" title="Search"><i class="fas fa-search"></i></button>
                        <div id="search-container" class="hidden">
                            <div class="search-input-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="unified-search-input" placeholder="Search for fires or places...">
                                    <div id="search-loader" class="spinner-border spinner-border-sm text-secondary d-none" role="status"></div>
                                </div>
                                <div class="search-results-container" id="search-results"></div>
                            </div>
                        </div>
                        <div id="map-controls">
                            <button id="toggle-3d-btn" class="btn btn-secondary" title="Toggle 3D View"><i class="fas fa-cube"></i></button>
                            <button id="expand-map-btn" class="btn btn-secondary" title="Toggle Fullscreen Map"><i class="fas fa-expand-arrows-alt"></i></button>
                            <button id="measure-distance-btn" class="btn btn-secondary" title="Measure Distance"><i class="fas fa-ruler"></i></button>
                            <button id="get-weather-btn" class="btn btn-secondary" title="Get Weather for a Point"><i class="fas fa-cloud-sun-rain"></i></button>
                            <button id="goes-fire-temp-btn" class="btn btn-secondary" title="Toggle GOES Fire Temp Preview"><i class="fas fa-fire-alt"></i></button>
                            <button id="toggle-contained-btn" class="btn btn-secondary" title="Hide Contained & Out Fires"><i class="fas fa-shield-alt"></i></button>
                            <button id="create-alert-btn" class="btn btn-secondary" title="Create Community Alert"><i class="fas fa-bullhorn text-warning"></i></button>
                            <button id="analyze-all-goes-btn" class="btn btn-warning" title="Analyze All GOES Sectors"><i class="fas fa-satellite"></i><i class="fas fa-search ms-1"></i></button>
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
                                    <div class="ps-2"><div class="legend-item"><span class="legend-icon"><i class="fas fa-circle text-danger"></i></span>&lt; 24h (by Size)</div><div class="legend-item"><span class="legend-icon"><i class="fas fa-circle text-warning"></i></span>&lt; 3d (by Size)</div><div class="legend-item"><span class="legend-icon"><i class="fas fa-circle text-info"></i></span>&gt; 3d (by Size)</div></div>
                                    <div class="mt-2">
                                        <label for="discovery-date-filter" class="form-label small">Show fires discovered on or after</label>
                                        <div class="input-group input-group-sm"><input type="date" id="discovery-date-filter" class="form-control form-control-sm"><button id="apply-date-filter" class="btn btn-outline-secondary" type="button" title="Apply Filter"><i class="fas fa-check"></i></button><button id="clear-date-filter" class="btn btn-outline-secondary" type="button" title="Clear Filter"><i class="fas fa-times"></i></button></div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="mb-3">
                                    <h6>Community Alerts</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="community-alerts-layer" checked>
                                        <label class="form-check-label legend-item" for="community-alerts-layer"><span class="legend-icon"><i class="fas fa-bullhorn text-warning"></i></span>Show Alerts</label>
                                    </div>
                                </div>
                                <hr class="my-2"><div class="mb-3"><h6>Weather Overlays</h6><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="weather-precipitation"><label class="form-check-label legend-item" for="weather-precipitation"><span class="legend-icon"><i class="fas fa-cloud-showers-heavy"></i></span>Precipitation</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="weather-temp"><label class="form-check-label legend-item" for="weather-temp"><span class="legend-icon"><i class="fas fa-temperature-high"></i></span>Temperature</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="weather-wind"><label class="form-check-label legend-item" for="weather-wind"><span class="legend-icon"><i class="fas fa-compass"></i></span>Static Wind</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="animated-wind"><label class="form-check-label legend-item" for="animated-wind"><span class="legend-icon"><i class="fas fa-wind"></i></span>Animated Wind</label></div></div>
                                <hr class="my-2"><div class="mb-3"><h6>Map Overlays</h6><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="state-boundaries"><label class="form-check-label legend-item" for="state-boundaries"><span class="legend-icon"><i class="fas fa-border-all"></i></span>State Boundaries</label></div><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="drought-layer"><label class="form-check-label legend-item" for="drought-layer"><span class="legend-icon"><div style="width: 15px; height: 15px; background: rgba(255, 255, 0, 0.5); border: 1px solid #ccc;"></div></span>Abnormally Dry</label></div></div>
                                <hr class="my-2"><div class="mb-3"><h6>Live Imagery</h6><div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="goes-imagery"><label class="form-check-label legend-item" for="goes-imagery"><span class="legend-icon"><i class="fas fa-satellite"></i></span>GOES Satellite</label></div></div>
                                <hr class="my-2">
                                <div class="mb-3">
                                    <h6>Satellite Hotspots (by FRP)</h6>
                                    <div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="viirs-hotspots" data-source="VIIRS" ><label class="form-check-label legend-item" for="viirs-hotspots"><span class="legend-icon"><i class="fas fa-satellite-dish text-primary"></i></span>VIIRS Hotspots</label></div>
                                    <div class="form-check form-switch"><input class="form-check-input layer-toggle" type="checkbox" role="switch" id="modis-hotspots" data-source="MODIS" checked><label class="form-check-label legend-item" for="modis-hotspots"><span class="legend-icon"><i class="fas fa-satellite-dish text-success"></i></span>MODIS Hotspots</label></div>
                                    <div class="d-flex align-items-center justify-content-around small text-muted mt-1 px-2"><span>Low</span><span class="frp-legend-dot" style="background-color: #ffff00;"></span><span class="frp-legend-dot" style="background-color: #ffaa00;"></span><span class="frp-legend-dot" style="background-color: #ff4500;"></span><span class="frp-legend-dot" style="background-color: #d40202;"></span><span>High</span></div>
                                </div>
                                <hr class="my-2">
                                <div class="mb-3">
                                    <h6>Automated Analysis</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="recurring-analysis-toggle">
                                        <label class="form-check-label" for="recurring-analysis-toggle">Run every</label>
                                    </div>
                                    <div class="input-group input-group-sm mt-2">
                                        <input type="number" class="form-control" id="analysis-interval-minutes" value="30" min="5">
                                        <span class="input-group-text">minutes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="timeline-container"><label for="timeline-slider" id="timeline-label">Current</label><input type="range" min="0" max="90" value="0" class="form-range" id="timeline-slider"></div>
                    </div>
                </div>
                <!-- Right Sidebar Column -->
                <div class="col-lg-3 col-md-4 right-sidebar-column">
                    <div class="sidebar-wrapper">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-fill" id="sidebar-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#chat-content" type="button" role="tab"><i class="fas fa-comments me-1"></i> Ask</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#routes-content" type="button" role="tab"><i class="fas fa-route me-1"></i> Routes</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#control-content" type="button" role="tab"><i class="fas fa-cogs me-1"></i> Data</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content h-100">
                                <div class="tab-pane fade show active p-3" id="chat-content" role="tabpanel">
                                    <div class="chat-container">
                                        <div class="chat-messages" id="chat-messages"></div>
                                        <div class="chat-input-group d-flex gap-2 mt-2">
                                            <input type="text" class="form-control" placeholder="Ask a question or use the mic..." id="chat-input">
                                            <button class="btn btn-secondary" id="speech-to-text-btn" title="Talk to Agent"><i class="fas fa-microphone"></i></button>
                                            <button class="btn btn-primary" id="send-chat-btn"><i class="fas fa-paper-plane"></i></button>
                                            <button class="btn btn-secondary" id="reset-chat-btn" title="Reset Conversation"><i class="fas fa-sync-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-3" id="routes-content" role="tabpanel">
                                    <div>
                                        <h6 class="text-body-secondary">Route Planner</h6>
                                        <p class="small text-muted">Use the marker tool (<i class="fas fa-map-marker-alt"></i>) on the map to place a start and end point.</p>
                                        <div class="mb-3"><label for="route-name" class="form-label small">Route Name</label><input type="text" class="form-control" id="route-name" placeholder="e.g., Evacuation Route A"></div>
                                        <div class="d-grid gap-2"><button class="btn btn-primary" id="calculate-route-btn" disabled><i class="fas fa-calculator me-2"></i>Calculate & Save Route</button><button class="btn btn-secondary" id="clear-markers-btn"><i class="fas fa-times me-2"></i>Clear Markers</button></div>
                                        <hr>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-body-secondary">Saved Routes</h6>
                                        <div class="input-group input-group-sm mb-2"><span class="input-group-text" id="route-search-addon"><i class="fas fa-search"></i></span><input type="text" id="route-search-input" class="form-control" placeholder="Search saved routes..." aria-label="Search saved routes" aria-describedby="route-search-addon"></div>
                                        <div id="saved-routes-list-container"><ul class="list-group list-group-flush" id="saved-routes-list"></ul></div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-3" id="control-content" role="tabpanel">
                                     <h6 class="text-body-secondary">Live Data</h6>
                                     <ul class="list-group mb-3"><li class="list-group-item d-flex justify-content-between align-items-center">Satellite Detections <span class="badge text-bg-danger" id="active-fires-count">--</span></li><li class="list-group-item d-flex justify-content-between align-items-center">High Confidence <span class="badge text-bg-warning" id="high-confidence-count">--</span></li></ul>
                                     <h6 class="text-body-secondary">Recent Detections (&lt; 3 hours)</h6>
                                     <div id="recent-fires" class="flex-grow-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="fire-details-modal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="fire-details-modal-title"></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body" id="fire-details-modal-body"></div></div></div></div>
    <div id="goes-preview"><img id="goes-preview-img" src="" alt="GOES Preview"><p id="goes-preview-label">Move mouse over map</p></div>
    
    <div class="modal fade" id="zoomed-goes-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="zoomed-goes-modal-title">Fire Temperature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" id="zoomed-goes-image-container">
                    <img id="zoomed-goes-image" src="" alt="Zoomed GOES Fire Temperature Image">
                    <canvas id="detection-canvas"></canvas>
                    <div id="magnifier-loupe"></div>
                    <div id="image-analysis-loader" class="d-none">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Analyzing...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="text-start">
                        <div id="analysis-result-text" class="text-muted small"></div>
                        <p class="text-white-50 small mb-0">Click 'Analyze for Fire' to send this image to the Custom Vision AI.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="analyze-image-btn"><i class="fas fa-search-location me-2"></i>Analyze for Fire</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="analysis-results-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">GOES Sector Analysis Results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Analysis in progress. Results will appear below as they are completed. Green means no fire detected, Red means potential fire found. Click a card to navigate to that sector.</p>
                    <div id="analysis-results-grid" class="row g-3">
                        <!-- Results will be injected here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="analysis-progress-text" class="me-auto"></span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alert-modal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Create New Community Alert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">A circular alert area has been drawn on the map. Add a message and broadcast it to the public.</p>
                    <form id="alert-form">
                        <input type="hidden" id="alert-lat">
                        <input type="hidden" id="alert-lng">
                        <input type="hidden" id="alert-radius">
                        <div class="mb-3">
                            <label for="alert-message" class="form-label">Alert Message</label>
                            <textarea id="alert-message" class="form-control" rows="3" required placeholder="e.g., Evacuation order for the north side of the valley due to rapid fire spread."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save and Broadcast Alert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('partials.scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cesium.com/downloads/cesiumjs/releases/1.117/Build/Cesium/Cesium.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.min.js"></script>
    <script src="{{ asset('js/leaflet-velocity.js') }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/echo.js') }}"></script>
    
    <script>
        const OWM_API_KEY = "{{ config('services.openweather.api_key', 'YOUR_FALLBACK_KEY') }}";
        Cesium.Ion.defaultAccessToken = "{{ config('services.cesium.ion_access_token', 'YOUR_FALLBACK_KEY') }}";
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // --- Custom Vision API Credentials ---
        const VISION_PREDICTION_URL = 'https://southcentralus.api.cognitive.microsoft.com/customvision/v3.0/Prediction/3ea6c153-66ff-4e81-9ca7-e42b28785583/detect/iterations/firetempiteration2/image';
        const VISION_PREDICTION_KEY = 'e88a49568d634bdc8ebedcb798b18f29';

        let agentHandler;
        let map, fireDetailsModal, weatherMarkerDrawer, drawnItems, measureTool;
        const fireLayerGroups = { 'VIIRS': L.layerGroup(), 'MODIS': L.layerGroup() };
        const fireDataCache = { 'VIIRS': [], 'MODIS': [] };
        let officialPerimetersLayer, stateBoundariesLayer, droughtLayer, weatherPrecipLayer, weatherTempLayer, staticWeatherWindLayer, animatedWindLayer, goesLayer, weatherPointMarker, predictedSpreadLayer, lastOfficialFireLayer;
        let cesiumViewer, is3D = false;
        let startMarker = null, endMarker = null, currentRouteControl = null, savedRoutesLayer = null;
        let isGoesPreviewActive = false;
        let goesPreviewContainer, goesPreviewImg, goesPreviewLabel, zoomedGoesModal, zoomedGoesImage, zoomedGoesTitle, magnifierLoupe, zoomedGoesContainer;
        let goesImageRequestTimer = null;
        let lastValidGoesUrl = '';
        let timelineSlider, timelineLabel, selectedDate = null;
        let hideContainedFires = false;
        const NOAA_SECTORS = { 'conus':{ satellite: 'GOES19', name: 'CONUS', bounds: L.latLngBounds([[24, -125], [50, -67]]) }, 'sp':   { satellite: 'GOES19', name: 'Southern Plains', bounds: L.latLngBounds([[25, -107], [40, -92]]) }, 'se':   { satellite: 'GOES19', name: 'Southeast', bounds: L.latLngBounds([[24, -92], [37, -75]]) }, 'sr':   { satellite: 'GOES19', name: 'Southern Rockies', bounds: L.latLngBounds([[31, -114], [42, -102]]) }, 'nr':   { satellite: 'GOES19', name: 'Northern Rockies', bounds: L.latLngBounds([[41, -117], [50, -103]]) }, 'umv':  { satellite: 'GOES19', name: 'Upper Mississippi Valley', bounds: L.latLngBounds([[39, -98], [48, -86]]) }, 'gl':   { satellite: 'GOES19', name: 'Great Lakes', bounds: L.latLngBounds([[41, -92], [49, -76]]) }, 'ne':   { satellite: 'GOES19', name: 'Northeast', bounds: L.latLngBounds([[39, -83], [48, -67]]) }, 'pr':   { satellite: 'GOES19', name: 'Puerto Rico', bounds: L.latLngBounds([[17, -68], [19, -65]]) }, 'wus':  { satellite: 'GOES18', name: 'West US', bounds: L.latLngBounds([[31, -125], [49, -102]]) }, 'psw':  { satellite: 'GOES18', name: 'Pacific Southwest', bounds: L.latLngBounds([[32, -124], [43, -114]]) }, 'pnw':  { satellite: 'GOES18', name: 'Pacific Northwest', bounds: L.latLngBounds([[42, -125], [49, -116]]) }, 'ak':   { satellite: 'GOES18', name: 'Alaska', bounds: L.latLngBounds([[51, -179], [72, -129]]) }, 'hi':   { satellite: 'GOES18', name: 'Hawaii', bounds: L.latLngBounds([[18, -161], [23, -154]]) }, };

        let alertModal, alertDrawer, communityAlertsLayer;
        let activeAlerts = {};
        
        let mediaRecorder, audioChunks = [], isRecording = false;
        
        let analysisResultsModal;
        let recurringAnalysisTimer = null;

        class AgentHandler {
            constructor(chatMessagesContainer, chatInput) {
                this.chatMessages = chatMessagesContainer;
                this.chatInput = chatInput;
                this.runId = null;
                this.isBusy = false;
                this.functionTools = [
                    { type: "function", function: { name: "searchFires", description: "Searches for active wildfires by name and zooms the map to the best result.", parameters: { type: "object", properties: { query: { type: "string", description: "The name of the fire." } }, required: ["query"] } }, executor: this.searchFires },
                    { type: "function", function: { name: "zoomToLocation", description: "Zooms the map to a specific geographic location name.", parameters: { type: "object", properties: { location: { type: "string", description: "The location name." } }, required: ["location"] } }, executor: this.zoomToLocation },
                    { type: "function", function: { name: "analyzeAndOutlineRiskArea", description: "Analyzes the area near a named fire for significant features and draws a polygon around them. Valid riskType values are 'infrastructure' (hospitals, schools), 'habitat' (parks, reserves, forests), and 'populated' (towns, residential areas).", parameters: { type: "object", properties: { fireName: { type: "string", description: "The name of an official fire." }, riskType: { type: "string", description: "The type of risk to analyze. E.g., 'habitat'" } }, required: ["fireName", "riskType"] } }, executor: this.analyzeAndOutlineRiskArea },
                    { type: "function", function: { name: "measureDistance", description: "Measures the straight-line distance in kilometers between two named points on the map.", parameters: { type: "object", properties: { from: { type: "string", description: "The starting point (can be a fire name or place)." }, to: { type: "string", description: "The ending point (can be a fire name or place)." } }, required: ["from", "to"] } }, executor: this.measureDistance },
                    { type: "function", function: { name: "toggleLayer", description: "Toggles a map data layer on or off. layerName can be 'wind', 'drought', 'satellite', 'perimeters', etc.", parameters: { type: "object", properties: { layerName: { type: "string", description: "The name of the layer to toggle." }, state: { type: "string", enum: ["on", "off"], description: "The desired state." } }, required: ["layerName", "state"] } }, executor: this.toggleLayer },
                    { type: "function", function: { name: "getFireDetails", description: "Retrieves detailed data for a specific official fire, such as its size and discovery date.", parameters: { type: "object", properties: { fireName: { type: "string", description: "The name of the fire to get details for." } }, required: ["fireName"] } }, executor: this.getFireDetails },
                    { type: "function", function: { name: "planRoute", description: "Draws and saves an evacuation or logistics route between two points.", parameters: { type: "object", properties: { start: { type: "string" }, end: { type: "string" }, name: {type: "string"} }, required: ["start", "end", "name"] } }, executor: this.planRoute },
                    { type: "function", function: { name: "analyzeAllGoesSectors", description: "Initiates a comprehensive AI analysis of all available GOES satellite sectors for potential fire hotspots. This is an automated, long-running process, and results will appear in a modal window." }, executor: this.analyzeAllGoesSectors },
                    { type: "function", function: { name: "setAlertRadius", description: "Draws a circular alert radius on the map centered at a specific location with a given size to notify the community.", parameters: { type: "object", properties: { location: { type: "string", description: "The center point of the alert (e.g., a city name, address, or fire name)." }, radius: { type: "number", description: "The radius of the alert circle in kilometers." } }, required: ["location", "radius"] } }, executor: this.setAlertRadius },
                    { type: "function", function: { name: "deleteAlert", description: "Deletes a specific community alert from the map using its message content as an identifier.", parameters: { type: "object", properties: { alertMessage: { type: "string", description: "A key phrase from the alert message to identify which alert to delete." } }, required: ["alertMessage"] } }, executor: this.deleteAlert }
                ];
                document.getElementById('reset-chat-btn').addEventListener('click', () => this.resetConversation());
                console.log("AgentHandler initialized with final toolset.");
            }
            async resetConversation() {
                console.log("Resetting conversation...");
                this.setBusy(true);
                try {
                    await axios.post('/agent/reset');
                    this.chatMessages.innerHTML = '';
                    this.displayMessage("Conversation has been reset.", 'assistant');
                } catch (error) { console.error("Failed to reset chat session:", error); } finally { this.setBusy(false); }
            }
            getToolDefinitions() { return this.functionTools.map(t => ({ type: t.type, function: t.function })); }
            displayMessage(text, role) {
                const alignClass = role === 'user' ? 'text-end' : 'text-start';
                const bgClass = role === 'user' ? 'bg-primary-subtle' : 'bg-body-secondary';
                const authorHtml = role === 'assistant' ? '<small class="text-body-secondary">Artemis AI</small>' : '';
                this.chatMessages.innerHTML += `<div class="mb-3 ${alignClass}">${authorHtml}<div class="p-3 rounded mt-1 ${bgClass} d-inline-block">${text}</div></div>`;
                this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
            }
            setBusy(busy) {
                this.isBusy = busy;
                this.chatInput.disabled = busy;
                this.chatInput.placeholder = busy ? "Artemis is thinking..." : "Ask a question or use the mic...";
                if (!busy) this.runId = null;
            }
            async sendMessage(messageText) {
                if (!messageText.trim() || this.isBusy) return;
                this.setBusy(true);
                this.displayMessage(messageText, 'user');
                try {
                    const response = await axios.post('/agent/chat', { message: messageText, tools: this.getToolDefinitions() });
                    await this.handleAgentResponse(response.data);
                } catch (error) {
                    console.error('Error sending message:', error.response?.data || error.message);
                    this.displayMessage("An error occurred. Please try resetting the conversation.", 'assistant');
                    this.setBusy(false);
                }
            }
            async handleAgentResponse(response) {
                console.log("Agent response received with status:", response.status);
                if (response.status === 'requires_action') {
                    const toolCalls = response.required_action.submit_tool_outputs.tool_calls;
                    this.runId = response.id;
                    console.log("Action required. Executing tools...", toolCalls);
                    const toolOutputs = await Promise.all(toolCalls.map(toolCall => {
                        return this.invokeTool(toolCall.function).then(output => ({ tool_call_id: toolCall.id, output: JSON.stringify(output) }));
                    }));
                    console.log("Tools executed. Submitting outputs...", toolOutputs);
                    try {
                        const nextResponse = await axios.post('/agent/submit-tool-output', { run_id: this.runId, tool_outputs: toolOutputs });
                        await this.handleAgentResponse(nextResponse.data);
                    } catch (error) {
                        console.error('Error submitting tool output:', error.response?.data || error.message);
                        this.displayMessage("I had trouble submitting my tool results. Please reset.", 'assistant');
                        this.setBusy(false);
                    }
                } else if (response.status === 'completed') {
                    console.log("Run completed. Displaying final message.");
                    const lastMessage = response.messages.filter(m => m.role === 'assistant').pop();
                    const textContent = lastMessage?.content?.find(c => c.type === 'text');
                    if (textContent) this.displayMessage(textContent.text.value, 'assistant');
                    this.setBusy(false);
                } else {
                    console.error("Unhandled agent status:", response.status, response);
                    this.displayMessage("An unknown error occurred. Please reset the conversation.", 'assistant');
                    this.setBusy(false);
                }
            }
            async invokeTool(funcCall) {
                console.log(`Invoking tool: ${funcCall.name} with args: ${funcCall.arguments}`);
                const tool = this.functionTools.find(t => t.function.name === funcCall.name);
                if (!tool || !tool.executor) {
                    console.error(`Tool executor for ${funcCall.name} not found.`);
                    return { error: `Tool executor for ${funcCall.name} not found.` };
                }
                try {
                    const args = funcCall.arguments ? JSON.parse(funcCall.arguments) : {};
                    const result = await tool.executor.call(this, args);
                    console.log(`Tool ${funcCall.name} executed successfully with result:`, result);
                    return result;
                } catch (error) {
                    console.error(`Error executing tool ${funcCall.name}:`, error);
                    return { error: `Failed to execute tool: ${error.message}` };
                }
            }
            async searchFires({ query }) {
                console.log(`[AI Tool] Searching for fire: "${query}"`);
                const results = searchFires(query); 
                if (results.length > 0) {
                    const firstFire = results[0];
                    map.fitBounds(firstFire.bbox);
                    return { found: true, message: `Okay, I've found the ${firstFire.name} and zoomed to its location.` };
                }
                return { found: false, message: `I couldn't find any active fires named "${query}".` };
            }
            async zoomToLocation({ location }) {
                console.log(`[AI Tool] Searching for place: "${location}"`);
                const results = await searchPlaces(location);
                if (results.length > 0) {
                    map.fitBounds(results[0].bbox);
                    return { success: true, message: `I have zoomed the map to ${results[0].name}.` };
                }
                return { success: false, message: `Could not find a location named "${location}".` };
            }
            async analyzeAndOutlineRiskArea({ fireName, riskType }) {
                this.displayMessage(`Okay, analyzing risk to "${riskType}" near the ${fireName}...`, 'assistant');
                const fireResult = searchFires(fireName);
                if (fireResult.length === 0) {
                    return { success: false, message: `I couldn't find an official fire named "${fireName}" to assess.` };
                }
                const fireCenter = fireResult[0].bbox.getCenter();
                const searchTerms = {
                    infrastructure: ['hospital', 'school', 'power station'],
                    habitat: ['park', 'nature reserve', 'forest', 'campground'],
                    populated: ['city', 'town', 'village', 'suburb']
                };
                const termsToSearch = searchTerms[riskType.toLowerCase()] || [riskType];
                let pointsOfInterest = [];
                for (const term of termsToSearch) {
                    const places = await searchPlaces(`${term} near ${fireCenter.lat},${fireCenter.lng}`);
                    places.forEach(p => pointsOfInterest.push(p.bbox.getCenter()));
                }
                if (pointsOfInterest.length < 3) return { success: false, message: `I couldn't find enough features of type "${riskType}" near the ${fireName} to draw a meaningful boundary.` };
                const hullPoints = calculateConvexHull(pointsOfInterest.map(p => [p.lat, p.lng]));
                const riskPolygon = L.polygon(hullPoints, { color: 'orange', weight: 3, dashArray: '5, 10' }).addTo(map);
                map.fitBounds(riskPolygon.getBounds().pad(0.2));
                return { success: true, message: `I have found ${pointsOfInterest.length} points of interest related to "${riskType}" and drawn a risk boundary around them on the map.` };
            }
            async measureDistance({ from, to }) {
                const [fromResults, toResults] = await Promise.all([findFirstLocation(from), findFirstLocation(to)]);
                if (!fromResults) return { error: `Could not find a location for "${from}".` };
                if (!toResults) return { error: `Could not find a location for "${to}".` };
                const fromPoint = fromResults.bbox.getCenter();
                const toPoint = toResults.bbox.getCenter();
                const distance = (fromPoint.distanceTo(toPoint) / 1000).toFixed(2);
                L.polyline([fromPoint, toPoint], { color: 'cyan', dashArray: '5, 5' }).addTo(map).bindPopup(`${distance} km`).openPopup();
                return { distance_km: distance, message: `The distance between ${fromResults.name} and ${toResults.name} is approximately ${distance} km.` };
            }
            toggleLayer({ layerName, state }) {
                const layerMap = { 'precipitation': 'weather-precipitation', 'temperature': 'weather-temp', 'static wind': 'weather-wind', 'animated wind': 'animated-wind', 'state boundaries': 'state-boundaries', 'drought': 'drought-layer', 'goes imagery': 'goes-imagery', 'viirs hotspots': 'viirs-hotspots', 'modis hotspots': 'modis-hotspots', 'perimeters': 'official-perimeters' };
                const id = layerMap[layerName.toLowerCase()];
                if (!id) return { success: false, message: `Unknown layer: ${layerName}.` };
                const checkbox = document.getElementById(id);
                if (!checkbox) return { success: false, message: `Layer toggle for ${layerName} not found.` };
                const isChecked = checkbox.checked;
                if ((state === 'on' && !isChecked) || (state === 'off' && isChecked)) { checkbox.click(); }
                return { success: true, message: `The ${layerName} layer has been turned ${state}.` };
            }
            getFireDetails({ fireName }) {
                const results = searchFires(fireName);
                if (results.length === 0) return { found: false, message: `No official fire named "${fireName}" found.` };
                const props = officialPerimetersLayer.getLayers().find(l => l.options?.fireProperties?.poly_IncidentName === results[0].name)?.options?.fireProperties;
                if (!props) return { found: false, message: "Could not retrieve full details for that fire." };
                const details = { name: props.poly_IncidentName, acres: props.poly_GISAcres ? props.poly_GISAcres.toFixed(2) : 'N/A', cause: props.attr_FireCause || 'N/A', discovered: formatDate(props.attr_FireDiscoveryDateTime), contained: formatDate(props.attr_ContainmentDateTime) };
                return { found: true, details: details };
            }
            async planRoute({ start, end, name }) {
                const [startResult, endResult] = await Promise.all([ findFirstLocation(start), findFirstLocation(end) ]);
                if (!startResult) return { success: false, message: `Could not find a starting location for "${start}".`};
                if (!endResult) return { success: false, message: `Could not find an ending location for "${end}".`};
                if (currentRouteControl) map.removeControl(currentRouteControl);
                currentRouteControl = L.Routing.control({ 
                    waypoints: [startResult.bbox.getCenter(), endResult.bbox.getCenter()],
                    routeWhileDragging: false, addWaypoints: false, createMarker: () => null,
                    lineOptions: { styles: [{ color: 'blue', opacity: 0.8, weight: 6 }] }
                }).on('routesfound', function(e) {
                    const route = e.routes[0];
                    const geometry = L.polyline(route.coordinates.map(c => [c.lat, c.lng])).toGeoJSON();
                    const routeData = { name: name, start_latitude: startResult.bbox.getCenter().lat, start_longitude: startResult.bbox.getCenter().lng, end_latitude: endResult.bbox.getCenter().lat, end_longitude: endResult.bbox.getCenter().lng, geometry: JSON.stringify(geometry.geometry) };
                    axios.post('/api/routes', routeData).then(() => fetchAndDisplaySavedRoutes()).catch(error => console.error('Error saving route via AI:', error));
                }).addTo(map);
                return { success: true, message: `Okay, planning a route from ${startResult.name} to ${endResult.name} and saving it as "${name}".`};
            }
            async analyzeAllGoesSectors() {
                console.log("[AI Tool] Triggering full GOES sector analysis.");
                analyzeAllGoesSectors(); 
                return { success: true, message: "I've started the comprehensive analysis of all GOES sectors. The results will appear in a modal window shortly." };
            }
            async setAlertRadius({ location, radius }) {
                console.log(`[AI Tool] Setting alert for "${location}" with a ${radius}km radius.`);
                const locationResult = await findFirstLocation(location);
                if (!locationResult) {
                    return { success: false, message: `I'm sorry, I could not find a location named "${location}".` };
                }
                const centerPoint = locationResult.bbox.getCenter();
                const radiusMeters = radius * 1000;
                
                const alertData = { 
                    latitude: centerPoint.lat, 
                    longitude: centerPoint.lng, 
                    radius: radiusMeters, 
                    message: `AI-generated alert for ${locationResult.name}.` 
                };
                
                try {
                    const response = await axios.post("{{ route('api.alerts.store') }}", alertData);
                    console.log("[AI Tool] AI-generated alert saved successfully via API. Proactively adding to map.");
                    addAlertToMap(response.data);
                    
                    map.fitBounds(L.circle(centerPoint, {radius: radiusMeters}).getBounds().pad(0.2));
                    return { success: true, message: `Action complete. A ${radius}km alert for ${locationResult.name} was successfully created and is now visible on the map.` };
                } catch (error) {
                    console.error("[AI Tool] Error saving AI-generated alert:", error.response?.data);
                    return { success: false, message: "I found the location, but failed to save the alert to the server." };
                }
            }
            async deleteAlert({ alertMessage }) {
                console.log(`[AI Tool] Attempting to delete alert with message containing: "${alertMessage}"`);
                const lowerCaseMessage = alertMessage.toLowerCase();
                let foundAlertId = null;

                for (const alertId in activeAlerts) {
                    const layer = activeAlerts[alertId];
                    if (layer.alertMessage && layer.alertMessage.toLowerCase().includes(lowerCaseMessage)) {
                        foundAlertId = layer.alertId;
                        break;
                    }
                }

                if (foundAlertId) {
                    console.log(`[AI Tool] Found alert with ID ${foundAlertId}. Triggering API deletion.`);
                    try {
                        await axios.delete(`/api/alerts/${foundAlertId}`);
                        removeAlertFromMap(foundAlertId);
                        return { success: true, message: `The alert regarding "${alertMessage}" has been deleted.` };
                    } catch (error) {
                        console.error(`[AI Tool] API Error deleting alert ${foundAlertId}:`, error);
                        return { success: false, message: "I found the alert, but there was an error trying to delete it from the server." };
                    }
                }

                console.warn(`[AI Tool] Could not find an alert matching "${alertMessage}".`);
                return { success: false, message: `I could not find an active alert with the message "${alertMessage}". Please be more specific.` };
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            console.log("DOM fully loaded and parsed. Initializing dashboard in 250ms.");
            setTimeout(() => {
                console.log("Timeout triggered. Starting initialization sequence.");
                initializeMap();
                fireDetailsModal = new bootstrap.Modal(document.getElementById('fire-details-modal'));
                alertModal = new bootstrap.Modal(document.getElementById('alert-modal'));
                analysisResultsModal = new bootstrap.Modal(document.getElementById('analysis-results-modal'));
                goesPreviewContainer = document.getElementById('goes-preview'); 
                goesPreviewImg = document.getElementById('goes-preview-img'); 
                goesPreviewLabel = document.getElementById('goes-preview-label');
                zoomedGoesModal = new bootstrap.Modal(document.getElementById('zoomed-goes-modal')); 
                zoomedGoesContainer = document.getElementById('zoomed-goes-image-container'); 
                zoomedGoesImage = document.getElementById('zoomed-goes-image'); 
                zoomedGoesTitle = document.getElementById('zoomed-goes-modal-title'); 
                magnifierLoupe = document.getElementById('magnifier-loupe');
                timelineSlider = document.getElementById('timeline-slider'); 
                timelineLabel = document.getElementById('timeline-label');
                
                initializeRobustTabSystem();
                initializeTimeline();
                setupEventListeners();
                initializeMagnifier();
                initializeAudioRecording();
                initializeAlertManagement();
                
                loadInitialData();
                fetchAndDisplaySavedRoutes();
                
                const chatMessagesContainer = document.getElementById('chat-messages');
                const chatInput = document.getElementById('chat-input');
                agentHandler = new AgentHandler(chatMessagesContainer, chatInput);

                console.log("Dashboard initialization complete.");
            }, 250);
        });

        function initializeRobustTabSystem() {
            console.log("[Tab System] Initializing robust tab system.");
            const tabContainer = document.querySelector('#sidebar-tabs');
            if (!tabContainer) {
                console.error("[Tab System] CRITICAL: Tab container #sidebar-tabs not found. Tab system will not work.");
                return;
            }
            const tabPanes = document.querySelectorAll('.sidebar-wrapper .tab-pane');
            const syncTabView = (activeTab) => {
                if (!activeTab) return;
                const activePaneId = activeTab.getAttribute('data-bs-target');
                console.log(`[Tab System] Syncing tab view. Active pane should be: ${activePaneId}`);
                tabPanes.forEach(pane => {
                    if (`#${pane.id}` === activePaneId) {
                        pane.style.display = 'flex';
                        console.log(`[Tab System] -> Set #${pane.id} to 'display: flex'.`);
                    } else {
                        pane.style.display = 'none';
                    }
                });
            };
            
            tabContainer.addEventListener('shown.bs.tab', (event) => {
                syncTabView(event.target);
            });
            
            const initialActiveTab = tabContainer.querySelector('.nav-link.active');
            if (initialActiveTab) {
                console.log("[Tab System] Found initial active tab on page load. Setting its view directly.");
                syncTabView(initialActiveTab);
            } else {
                console.warn("[Tab System] No initial active tab found. The sidebar might appear empty until a tab is clicked.");
            }
        }

        function initializeMap() {
            console.log("Initializing Leaflet map with additional layers.");
            
            const streets = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community' });
            const openstreetmap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap contributors' });
            const dark = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
            
            const baseLayers = {
                "Streets": streets,
                "Satellite": satellite,
                "OpenStreetMap": openstreetmap,
                "Dark Mode": dark
            };
            
            map = L.map('map', { center: [39.8283, -98.5795], zoom: 5, layers: [streets] });
            
            map.createPane('goesPreviewPane').style.zIndex = 450; 
            map.createPane('goesLayerPane').style.zIndex = 250;
            
            L.control.layers(baseLayers, null, { position: 'topright' }).addTo(map);
            L.control.scale({ imperial: false }).addTo(map);
            
            drawnItems = new L.FeatureGroup().addTo(map);
            new L.Control.Draw({ position: 'topleft', edit: { featureGroup: drawnItems }, draw: { polyline: false, polygon: true, circle: false, rectangle: true, marker: { tooltip: { start: 'Click map to place start/end point for routing.' } } } }).addTo(map);
            
            measureTool = new L.Draw.Polyline(map, {
                shapeOptions: { color: '#0dcaf0', weight: 4, opacity: 0.7 },
                showLength: true,
                metric: true,
                tooltip: { start: 'Click to start measuring distance.' }
            });

            officialPerimetersLayer = L.layerGroup(); 
            stateBoundariesLayer = L.layerGroup(); 
            droughtLayer = L.layerGroup(); 
            savedRoutesLayer = L.layerGroup().addTo(map);
            communityAlertsLayer = L.layerGroup().addTo(map);
            predictedSpreadLayer = L.layerGroup().addTo(map); 
            
            weatherPrecipLayer = L.tileLayer(`https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid=${OWM_API_KEY}`);
            weatherTempLayer = L.tileLayer(`https://tile.openweathermap.org/map/temp_new/{z}/{x}/{y}.png?appid=${OWM_API_KEY}`);
            staticWeatherWindLayer = L.tileLayer(`https://tile.openweathermap.org/map/wind_new/{z}/{x}/{y}.png?appid=${OWM_API_KEY}`);
            weatherMarkerDrawer = new L.Draw.Marker(map, { icon: L.divIcon({ className: 'leaflet-draw-icon', html: '<i class="fas fa-cloud-sun-rain fa-2x text-info"></i>', iconSize: [32,32] }) });
        }
        
        function initializeTimeline() {
            const today = new Date(); today.setUTCHours(0, 0, 0, 0); selectedDate = today.toISOString().split('T')[0];
            timelineSlider.max = 90; timelineSlider.value = 0; timelineLabel.textContent = 'Current';
            timelineSlider.addEventListener('input', handleTimelineInput);
            timelineSlider.addEventListener('change', handleTimelineChange);
        }

        function handleTimelineInput() {
            const dateFilterInput = document.getElementById('discovery-date-filter');
            const daysAgo = parseInt(timelineSlider.value, 10);
            const targetDate = new Date();
            targetDate.setUTCDate(targetDate.getUTCDate() - daysAgo);
            selectedDate = targetDate.toISOString().split('T')[0];

            if (daysAgo === 0) {
                timelineLabel.textContent = 'Current';
                dateFilterInput.value = '';
                console.log("Timeline set to Current. Clearing date filter.");
            } else {
                const dateLabel = targetDate.toLocaleDateString('en-CA', { year: 'numeric', month: 'short', day: 'numeric' });
                timelineLabel.textContent = daysAgo === 1 ? 'Yesterday' : dateLabel;
                dateFilterInput.value = selectedDate;
                console.log(`Timeline set to ${dateLabel}. Date filter input set to ${selectedDate}.`);
            }
        }
        
        function handleTimelineChange() {
            console.log("Timeline change committed. Reloading filtered data for date:", selectedDate);
            if (document.getElementById('viirs-hotspots').checked) { loadFireData('VIIRS'); }
            if (document.getElementById('modis-hotspots').checked) { loadFireData('MODIS'); }
            if (document.getElementById('official-perimeters').checked) {
                loadOfficialPerimeters();
            }
        }
        
        function setActiveDrawTool(toolToActivate) {
            const tools = [measureTool, alertDrawer, weatherMarkerDrawer];
            const buttons = [
                document.getElementById('measure-distance-btn'),
                document.getElementById('create-alert-btn'),
                document.getElementById('get-weather-btn')
            ];

            tools.forEach((tool, index) => {
                if (tool === toolToActivate) {
                    if (tool.enabled()) {
                        tool.disable();
                        buttons[index].classList.remove('active');
                    } else {
                        tool.enable();
                        buttons[index].classList.add('active');
                    }
                } else {
                    tool.disable();
                    buttons[index].classList.remove('active');
                }
            });
        }

        function setupEventListeners() {
            console.log("Setting up global event listeners.");
            makeDraggable(document.getElementById('layers-sidebar'), document.getElementById('layers-sidebar-header'));
            document.getElementById('sidebar-toggle').addEventListener('click', (e) => { 
                const sidebar = document.getElementById('layers-sidebar');
                sidebar.classList.toggle('collapsed');
                e.currentTarget.querySelector('i').className = sidebar.classList.contains('collapsed') ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
            });
            document.querySelectorAll('.layer-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    console.log(`Layer toggle changed: ${this.id}, checked: ${this.checked}`);
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
                            case 'community-alerts-layer': this.checked ? map.addLayer(communityAlertsLayer) : map.removeLayer(communityAlertsLayer); break;
                        }
                    }
                    if (is3D && this.dataset.source !== 'VIIRS' && this.dataset.source !== 'MODIS') { synchronizeLayersToCesium(); }
                });
            });
            document.getElementById('toggle-3d-btn').addEventListener('click', toggle3DView);
            document.getElementById('expand-map-btn').addEventListener('click', () => { 
                document.body.classList.toggle('fullscreen'); 
                setTimeout(() => { map.invalidateSize({ pan: true }); }, 310); 
            });
            document.getElementById('get-weather-btn').addEventListener('click', () => setActiveDrawTool(weatherMarkerDrawer));
            document.getElementById('measure-distance-btn').addEventListener('click', () => setActiveDrawTool(measureTool));
            document.getElementById('create-alert-btn').addEventListener('click', () => setActiveDrawTool(alertDrawer));

            document.getElementById('goes-fire-temp-btn').addEventListener('click', toggleGoesPreview);
            map.on(L.Draw.Event.CREATED, (event) => {
                console.log(`Leaflet Draw CREATED event fired for layer type: ${event.layerType}`);
                
                if (alertDrawer.enabled()) {
                    setActiveDrawTool(alertDrawer); 
                    const layer = event.layer;
                    const latlng = layer.getLatLng();
                    const radius = layer.getRadius();
                    document.getElementById('alert-lat').value = latlng.lat;
                    document.getElementById('alert-lng').value = latlng.lng;
                    document.getElementById('alert-radius').value = radius;
                    alertModal.show();
                } else if (weatherMarkerDrawer.enabled()) { 
                    setActiveDrawTool(weatherMarkerDrawer);
                    getAndShowWeatherForPoint(event.layer.getLatLng());
                } else if (measureTool.enabled()) {
                    setActiveDrawTool(measureTool);
                    const layer = event.layer;
                    const latlngs = layer.getLatLngs();
                    let totalDistance = 0;
                    for (let i = 0; i < latlngs.length - 1; i++) {
                        totalDistance += latlngs[i].distanceTo(latlngs[i+1]);
                    }
                    const distanceKm = (totalDistance / 1000).toFixed(2);
                    layer.bindPopup(`<b>Total Distance:</b><br>${distanceKm} km`).openPopup();
                    drawnItems.addLayer(layer);
                } else if (event.layerType === 'marker') {
                    if (!startMarker) { startMarker = event.layer.addTo(map).setIcon(L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] })).bindPopup('Start Point').openPopup(); }
                    else if (!endMarker) { endMarker = event.layer.addTo(map).setIcon(L.icon({ iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png', shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41] })).bindPopup('End Point').openPopup(); }
                    updateCalculateButtonState();
                } else { 
                    drawnItems.addLayer(event.layer); 
                }
            });
            document.getElementById('toggle-contained-btn').addEventListener('click', function() { hideContainedFires = !hideContainedFires; this.classList.toggle('active', hideContainedFires); loadOfficialPerimeters(); });
            document.getElementById('apply-date-filter').addEventListener('click', loadOfficialPerimeters);
            document.getElementById('clear-date-filter').addEventListener('click', () => {
                console.log("Clear date filter button clicked.");
                document.getElementById('discovery-date-filter').value = '';
                if (timelineSlider) {
                    timelineSlider.value = 0;
                    handleTimelineInput();
                }
                loadOfficialPerimeters();
            });

            document.getElementById('route-search-input').addEventListener('input', filterSavedRoutes);
            document.getElementById('calculate-route-btn').addEventListener('click', calculateAndSaveRoute);
            document.getElementById('clear-markers-btn').addEventListener('click', clearRouteMarkers);
            document.getElementById('saved-routes-list').addEventListener('click', handleSavedRouteClick);
            initializeUnifiedSearch();
            document.getElementById('send-chat-btn').addEventListener('click', sendMessage);
            document.getElementById('chat-input').addEventListener('keypress', function(e) { if (e.key === 'Enter') { e.preventDefault(); sendMessage(); } });
            document.getElementById('alert-form').addEventListener('submit', saveAlert);
            
            document.getElementById('analyze-image-btn').addEventListener('click', analyzeGoesImageForFire);
            const zoomedModalEl = document.getElementById('zoomed-goes-modal');
            zoomedModalEl.addEventListener('hidden.bs.modal', () => {
                console.log("Zoomed GOES modal hidden. Clearing canvas and image source.");
                const canvas = document.getElementById('detection-canvas');
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('zoomed-goes-image').src = '';
                document.getElementById('analysis-result-text').textContent = '';
            });
             zoomedModalEl.addEventListener('shown.bs.modal', () => {
                console.log("Zoomed GOES modal shown. Clearing any previous drawings.");
                const canvas = document.getElementById('detection-canvas');
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('analysis-result-text').textContent = '';
            });
            document.getElementById('analyze-all-goes-btn').addEventListener('click', analyzeAllGoesSectors);
            document.getElementById('recurring-analysis-toggle').addEventListener('change', handleRecurringAnalysisToggle);

            document.getElementById('fire-details-modal-body').addEventListener('click', function(event) {
                const intensityBtn = event.target.closest('#get-ai-prediction-btn');
                if (intensityBtn) {
                    const fireData = JSON.parse(intensityBtn.dataset.fire);
                    fetchIntensityPrediction(fireData, intensityBtn);
                }
                const spreadBtn = event.target.closest('#predict-spread-btn');
                if (spreadBtn) {
                    const fireProps = JSON.parse(spreadBtn.dataset.fireProperties);
                    getPredictedSpread(fireProps, spreadBtn);
                }
                const revertBtn = event.target.closest('#revert-to-perimeter-btn');
                if (revertBtn) {
                    revertToPerimeterView();
                }
            });
        }

        function loadInitialData() { 
            console.log("Loading initial data for checked layers.");
            document.querySelectorAll('.layer-toggle:checked').forEach(toggle => { 
                toggle.dispatchEvent(new Event('change')); 
            }); 
        }
        
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
            predictedSpreadLayer.clearLayers(); 
            const params = new URLSearchParams();
            const dateValue = document.getElementById('discovery-date-filter').value;
            if (dateValue) {
                params.append('discovery_date', dateValue);
                console.log(`[Official Fires] Loading data for discovery date: ${dateValue}`);
            } else {
                console.log(`[Official Fires] Loading data without a date filter.`);
            }
            if (hideContainedFires) { params.append('hide_contained', 'true'); }
            const queryString = params.toString();
            const url = `/api/wildfire-perimeters${queryString ? '?' + queryString : ''}`;
            try {
                const response = await axios.get(url); 
                console.log(`[Official Fires] Received ${response.data.features?.length || 0} features.`);
                if (!response.data || !response.data.features || response.data.features.length === 0) { 
                    if (is3D) synchronizeLayersToCesium();
                    return; 
                }
                const now = Date.now(), oneDay = 86400000, threeDays = 3 * oneDay;
                L.geoJSON(response.data, { 
                    onEachFeature: (feature, layer) => {
                        const props = feature.properties; const discoveryTs = props.attr_FireDiscoveryDateTime; const age = now - discoveryTs;
                        let color = '#0dcaf0'; if (age < oneDay) color = '#dc3545'; else if (age < threeDays) color = '#fd7e14';
                        let radius = Math.max(5, Math.min(20, 5 + Math.log((props.poly_GISAcres || 0) + 1))); 
                        if (layer && typeof layer.getBounds === 'function') {
                            const bounds = layer.getBounds();
                            if (bounds.isValid()) {
                                const center = bounds.getCenter();
                                layer.fireProperties = props; 
                                const dot = L.circleMarker(center, { radius, fillColor: color, color: '#fff', weight: 1.5, opacity: 1, fillOpacity: 0.8 }).on('click', () => showOfficialFireModal(props, layer));
                                officialPerimetersLayer.addLayer(dot);
                                layer.setStyle({ color, weight: 2, opacity: 0.6, fillOpacity: 0.15 }).on('click', () => showOfficialFireModal(props, layer));
                                officialPerimetersLayer.addLayer(layer);
                            }
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
        
        function getRenderedImageDimensions(img) {
            const { naturalWidth, naturalHeight, width, height } = img;
            if (!naturalWidth || !naturalHeight) return { renderedWidth: 0, renderedHeight: 0, offsetX: 0, offsetY: 0};
            
            const naturalRatio = naturalWidth / naturalHeight;
            const elementRatio = width / height;

            let renderedWidth, renderedHeight, offsetX, offsetY;

            if (naturalRatio > elementRatio) {
                renderedWidth = width;
                renderedHeight = width / naturalRatio;
                offsetX = 0;
                offsetY = (height - renderedHeight) / 2;
            } else {
                renderedHeight = height;
                renderedWidth = height * naturalRatio;
                offsetY = 0;
                offsetX = (width - renderedWidth) / 2;
            }
            return { renderedWidth, renderedHeight, offsetX, offsetY };
        }

        function initializeMagnifier() {
            const img = zoomedGoesImage;
            const container = zoomedGoesContainer;
            const loupe = magnifierLoupe;
            const zoom = 2.5;

            const moveLoupe = (e) => {
                const { renderedWidth, renderedHeight, offsetX, offsetY } = getRenderedImageDimensions(img);

                if (renderedWidth === 0) {
                    loupe.style.display = 'none';
                    return;
                }
                
                const rect = img.getBoundingClientRect();
                let mouseX = e.clientX - rect.left;
                let mouseY = e.clientY - rect.top;

                if (mouseX < offsetX || mouseX > offsetX + renderedWidth || mouseY < offsetY || mouseY > offsetY + renderedHeight) {
                    loupe.style.display = 'none';
                    return;
                }
                
                loupe.style.display = 'block';
                const imgX = mouseX - offsetX;
                const imgY = mouseY - offsetY;
                
                loupe.style.left = (mouseX - loupe.offsetWidth / 2) + 'px';
                loupe.style.top = (mouseY - loupe.offsetHeight / 2) + 'px';

                loupe.style.backgroundImage = `url('${img.src}')`;
                loupe.style.backgroundSize = `${renderedWidth * zoom}px ${renderedHeight * zoom}px`;

                const bgX = -(imgX * zoom - loupe.offsetWidth / 2);
                const bgY = -(imgY * zoom - loupe.offsetHeight / 2);
                loupe.style.backgroundPosition = `${bgX}px ${bgY}px`;
            };

            const hideLoupe = () => { loupe.style.display = 'none'; };

            container.addEventListener('mousemove', moveLoupe);
            container.addEventListener('mouseleave', hideLoupe);
            console.log("Magnifier initialized with aspect-ratio correction.");
        }

        async function analyzeGoesImageForFire(imageUrl) {
            const img = document.getElementById('zoomed-goes-image');
            const loader = document.getElementById('image-analysis-loader');
            const analyzeBtn = document.getElementById('analyze-image-btn');
            const resultText = document.getElementById('analysis-result-text');

            const targetUrl = typeof imageUrl === 'string' ? imageUrl : img.src;

            if (!targetUrl || !targetUrl.startsWith('http')) {
                alert('No valid image loaded to analyze.');
                return;
            }
            
            console.log("Starting fire analysis for image:", targetUrl);
            if (loader) loader.classList.remove('d-none');
            if (analyzeBtn) analyzeBtn.disabled = true;
            if (resultText) resultText.textContent = "Analyzing...";

            try {
                const urlParts = new URL(targetUrl);
                const noaaPath = urlParts.pathname.substring(1) + urlParts.search;
                const proxyUrl = `/proxy/noaa/${noaaPath}`;
                console.log('Requesting image via internal proxy:', proxyUrl);
                const imageResponse = await fetch(proxyUrl);
                
                if (!imageResponse.ok) {
                    throw new Error(`Failed to fetch image via proxy: ${imageResponse.status} ${imageResponse.statusText}`);
                }
                const imageBlob = await imageResponse.blob();
                console.log("Image fetched via proxy, size:", imageBlob.size);

                if (imageBlob.size === 0) { throw new Error("Fetched image blob is empty."); }

                const predictionResponse = await axios.post(VISION_PREDICTION_URL, imageBlob, {
                    headers: { 'Prediction-Key': VISION_PREDICTION_KEY, 'Content-Type': 'application/octet-stream' }
                });
                
                console.log("Custom Vision API Response:", predictionResponse.data);
                
                if (typeof imageUrl !== 'string') { drawDetectionBoxes(predictionResponse.data.predictions); }
                return predictionResponse.data.predictions;

            } catch (error) {
                console.error("Error during fire analysis:", error.response?.data || error.message, error);
                if (resultText) resultText.textContent = "Analysis failed.";
                if (typeof imageUrl !== 'string') { alert('An error occurred while analyzing the image. Check the browser console for details.'); }
                return null;
            } finally {
                if (loader) loader.classList.add('d-none');
                if (analyzeBtn) analyzeBtn.disabled = false;
            }
        }

        function drawDetectionBoxes(predictions) {

            const HORIZONTAL_OFFSET = 15;
            const VERTICAL_OFFSET = 15;
            const img = document.getElementById('zoomed-goes-image');
            const canvas = document.getElementById('detection-canvas');
            const resultText = document.getElementById('analysis-result-text');
            const ctx = canvas.getContext('2d');

            const { renderedWidth, renderedHeight, offsetX, offsetY } = getRenderedImageDimensions(img);

            canvas.width = renderedWidth;
            canvas.height = renderedHeight;
            canvas.style.left = (offsetX + HORIZONTAL_OFFSET) + 'px';
            canvas.style.top = (offsetY + VERTICAL_OFFSET) + 'px';

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            const fireDetections = predictions.filter(p => 
                p.tagName.toLowerCase().includes('fire') && p.probability > 0.61
            );

            if (fireDetections.length === 0) {
                console.log("No fires detected above threshold.");
                resultText.textContent = "Result: No significant fire detected.";
                return;
            }

            console.log(`Found ${fireDetections.length} fire detections.`);
            resultText.textContent = `Result: ${fireDetections.length} potential fire(s) detected!`;

            fireDetections.forEach(pred => {
                const { boundingBox } = pred;
                const x = boundingBox.left * canvas.width;
                const y = boundingBox.top * canvas.height;
                const w = boundingBox.width * canvas.width;
                const h = boundingBox.height * canvas.height;
                
                ctx.strokeStyle = 'rgba(255, 221, 0, 0.9)';
                ctx.lineWidth = 3;
                ctx.strokeRect(x, y, w, h);

                ctx.fillStyle = 'rgba(255, 221, 0, 0.9)';
                ctx.font = '16px sans-serif';
                const label = `${(pred.probability * 100).toFixed(0)}% ${pred.tagName}`;
                const textMetrics = ctx.measureText(label);
                ctx.fillRect(x, y - 20, textMetrics.width + 8, 20);
                ctx.fillStyle = '#000';
                ctx.fillText(label, x + 4, y - 5);
                console.log(`Drew box for detection with ${label} confidence.`);
            });
        }

        async function analyzeSingleSector(sector, sectorCode) {
            console.log(`[All-Scan] Analyzing sector: ${sector.name}`);
            const card = document.getElementById(`analysis-card-${sectorCode}`);
            const statusIcon = card.querySelector('.status-icon');
            const statusText = card.querySelector('.status-text');

            statusIcon.innerHTML = `<div class="spinner-border text-primary" role="status"></div>`;
            statusText.textContent = "Analyzing...";

            const imageUrl = `https://cdn.star.nesdis.noaa.gov/${sector.satellite}/ABI/SECTOR/${sectorCode.toUpperCase()}/FireTemperature/latest.jpg`;
            
            const predictions = await analyzeGoesImageForFire(imageUrl);

            if (predictions) {
                const fireDetections = predictions.filter(p => 
                    p.tagName.toLowerCase().includes('fire') && p.probability > 0.61
                );

                if (fireDetections.length > 0) {
                    card.classList.remove('border-secondary');
                    card.classList.add('border-danger');
                    statusIcon.innerHTML = `<i class="fas fa-fire-alt text-danger fa-lg"></i>`;
                    statusText.textContent = `Fire Detected (${fireDetections.length})`;
                    console.log(`[All-Scan] Fire DETECTED in ${sector.name}`);
                } else {
                    card.classList.remove('border-secondary');
                    card.classList.add('border-success');
                    statusIcon.innerHTML = `<i class="fas fa-check-circle text-success fa-lg"></i>`;
                    statusText.textContent = "Clear";
                    console.log(`[All-Scan] Sector ${sector.name} is clear.`);
                }
            } else {
                console.error(`[All-Scan] Failed to analyze sector ${sector.name}`);
                card.classList.remove('border-secondary');
                card.classList.add('border-warning');
                statusIcon.innerHTML = `<i class="fas fa-exclamation-triangle text-warning fa-lg"></i>`;
                statusText.textContent = "Error";
            }
        }

        async function analyzeAllGoesSectors() {
            console.log("--- STARTING FULL GOES SECTOR ANALYSIS ---");
            const grid = document.getElementById('analysis-results-grid');
            const progressText = document.getElementById('analysis-progress-text');
            grid.innerHTML = '';
            progressText.textContent = '';
            
            analysisResultsModal.show();
            
            const sectorEntries = Object.entries(NOAA_SECTORS);
            let completedCount = 0;

            sectorEntries.forEach(([code, sector]) => {
                const cardHtml = `
                    <div class="col-md-6 col-lg-4">
                        <div class="card analysis-card border-secondary" id="analysis-card-${code}" data-sector-code="${code}">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 status-icon">
                                    <i class="fas fa-hourglass-start text-secondary fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0">${sector.name}</h6>
                                    <small class="text-muted status-text">Pending...</small>
                                </div>
                            </div>
                        </div>
                    </div>`;
                grid.insertAdjacentHTML('beforeend', cardHtml);
            });
            
            grid.addEventListener('click', (event) => {
                const card = event.target.closest('.analysis-card');
                if (card) {
                    const sectorCode = card.dataset.sectorCode;
                    if (sectorCode && NOAA_SECTORS[sectorCode]) {
                        console.log(`Navigating to GOES sector: ${sectorCode}`);
                        map.fitBounds(NOAA_SECTORS[sectorCode].bounds);
                        analysisResultsModal.hide();
                    }
                }
            });

            const promises = sectorEntries.map(([code, sector]) => () => 
                analyzeSingleSector(sector, code).finally(() => {
                    completedCount++;
                    progressText.textContent = `Analysis complete for ${completedCount} of ${sectorEntries.length} sectors.`;
                })
            );

            const concurrency = 4;
            for (let i = 0; i < promises.length; i += concurrency) {
                const batch = promises.slice(i, i + concurrency).map(p => p());
                await Promise.allSettled(batch);
                console.log(`[All-Scan] Batch ${Math.floor(i/concurrency) + 1} completed.`);
            }
            
            console.log("--- FULL GOES SECTOR ANALYSIS COMPLETE ---");
            progressText.textContent = `All ${sectorEntries.length} sectors analyzed. Last updated: ${new Date().toLocaleTimeString()}`;
        }

        function handleRecurringAnalysisToggle(event) {
            const toggle = event.target;
            const intervalInput = document.getElementById('analysis-interval-minutes');

            if (toggle.checked) {
                if (recurringAnalysisTimer) {
                    clearInterval(recurringAnalysisTimer);
                }
                const minutes = parseInt(intervalInput.value, 10);
                if (isNaN(minutes) || minutes < 5) {
                    alert("Please enter a valid interval of 5 minutes or more.");
                    toggle.checked = false;
                    return;
                }
                const intervalMs = minutes * 60 * 1000;
                console.log(`Starting recurring analysis every ${minutes} minutes.`);
                analyzeAllGoesSectors();
                recurringAnalysisTimer = setInterval(analyzeAllGoesSectors, intervalMs);
                intervalInput.disabled = true;

            } else {
                if (recurringAnalysisTimer) {
                    clearInterval(recurringAnalysisTimer);
                    recurringAnalysisTimer = null;
                    console.log("Stopped recurring analysis.");
                }
                intervalInput.disabled = false;
            }
        }

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
        
        async function fetchIntensityPrediction(fire, button) {
            const resultContainer = document.getElementById('intensity-prediction-result');
            if (!resultContainer) {
                console.error("Could not find the prediction result container in the modal.");
                return;
            }
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Querying...`;
            resultContainer.innerHTML = '';

            const confidenceMap = { 'low': 30, 'nominal': 70, 'high': 95 };
            const numericConfidence = typeof fire.confidence === 'string' 
                ? (confidenceMap[fire.confidence.toLowerCase()] || 50) 
                : fire.confidence;

            const payload = {
                latitude: fire.latitude,
                longitude: fire.longitude,
                brightness: fire.brightness,
                confidence: numericConfidence,
                bright_t31: fire.bright_t31,
                daynight: fire.daynight,
            };

            console.log("Sending data for AI prediction:", payload);
            
            for (const key in payload) {
                if (payload[key] === undefined || payload[key] === null) {
                    console.error(`AI Prediction Aborted: Missing required feature '${key}'.`, fire);
                    resultContainer.innerHTML = `<p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Prediction failed.</p><small class="text-muted">The satellite data is missing the required '${key}' field.</small>`;
                    button.disabled = false;
                    button.innerHTML = `<i class="fas fa-brain me-2"></i>Get AI Prediction`;
                    return;
                }
            }

            try {
                const response = await axios.post("{{ route('api.predict.intensity') }}", payload);
                const prediction = response.data.predicted_frp;
                console.log("Received prediction from backend:", prediction);

                if (prediction !== undefined && prediction !== null) {
                    resultContainer.innerHTML = `<p class="fs-3 mb-0 text-center fw-bold" style="color:${getColorForFRP(prediction)}">${prediction.toFixed(2)} <span class="fs-6 text-muted fw-normal">MW</span></p><p class="text-center text-muted small mb-0">(AI Predicted FRP)</p>`;
                } else {
                    throw new Error("Prediction value not found in the server response.");
                }
            } catch (error) {
                console.error('AI Prediction Failed:', error.response ? error.response.data : error.message);
                let errorMessage = "Could not retrieve prediction from the model.";
                if (error.response?.data?.message) {
                    errorMessage = `<small class="text-muted d-block">${error.response.data.message}</small>`;
                }
                resultContainer.innerHTML = `<p class="text-danger mb-0 text-center"><i class="fas fa-exclamation-triangle me-2"></i>Prediction Failed</p><div class="text-center">${errorMessage}</div>`;
            } finally {
                button.disabled = false;
                button.innerHTML = `<i class="fas fa-brain me-2"></i>Get AI Prediction`;
            }
        }
        
        async function showSatelliteFireModal(fire) { 
            document.getElementById('fire-details-modal-title').innerHTML = `<i class="fas fa-satellite-dish text-info me-2"></i> Satellite Detection`; 
            const modalBody = document.getElementById('fire-details-modal-body');
            
            modalBody.innerHTML = `<div>Loading details...</div>`; 
            fireDetailsModal.show(); 
            
            let weatherHtml = '<p class="text-muted">Weather data unavailable.</p>'; 
            try { 
                const response = await axios.get('/api/weather-for-point', { params: { lat: fire.latitude, lon: fire.longitude } }); 
                const w = response.data; weatherHtml = `<p class="mb-2 d-flex justify-content-between">Temperature: <strong>${w.main.temp.toFixed(1)}°C</strong></p><p class="mb-2 d-flex justify-content-between">Humidity: <strong>${w.main.humidity}%</strong></p><p class="mb-0 d-flex justify-content-between">Winds: <strong>${(w.wind.speed * 3.6).toFixed(1)} km/h</strong></p>`; 
            } catch (error) { 
                console.error('Failed to load weather for modal:', error); 
            } 
            
            modalBody.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge text-bg-secondary">${fire.satellite || 'N/A'}</span>
                        <h4 class="mt-1">Detection at ${fire.latitude.toFixed(4)}, ${fire.longitude.toFixed(4)}</h4>
                    </div>
                </div>
                <div class="row g-2 text-center mb-4">
                    <div class="col">
                        <div class="p-2 bg-body-tertiary rounded">
                            <small class="text-muted">CONFIDENCE</small>
                            <div class="fs-5 fw-bold text-warning">${fire.confidence}</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2 bg-body-tertiary rounded">
                            <small class="text-muted">DETECTED</small>
                            <div class="fs-5 fw-bold">${fire.acq_date} ${fire.acq_time.slice(0,2)}:${fire.acq_time.slice(2)}</div>
                        </div>
                    </div>
                    <div class="col">
                         <div class="p-2 bg-body-tertiary rounded">
                            <small class="text-muted">FRP (MW)</small>
                            <div class="fs-5 fw-bold" style="color:${getColorForFRP(fire.frp)}">${fire.frp}</div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title mb-3"><i class="fas fa-cloud-sun me-2"></i>Nearby Weather</h6>
                                ${weatherHtml}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                             <div class="card-body">
                                <h6 class="card-title mb-3"><i class="fas fa-chart-line me-2"></i>Sensor Data</h6>
                                <p class="mb-2 d-flex justify-content-between">Brightness (Ch I4): <strong>${fire.brightness || 'N/A'} K</strong></p>
                                <p class="mb-2 d-flex justify-content-between">Brightness (Ch I5): <strong>${fire.bright_t31 || 'N/A'} K</strong></p>
                                <p class="mb-0 d-flex justify-content-between">Day/Night: <strong>${fire.daynight || 'N/A'}</strong></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card h-100 bg-body-tertiary">
                            <div class="card-body">
                                <h6 class="card-title mb-2 text-center">Azure ML Intensity Prediction</h6>
                                <div class="text-center mb-3">
                                    <button class="btn btn-primary" id="get-ai-prediction-btn" data-fire='${escapeHTML(JSON.stringify(fire))}'>
                                        <i class="fas fa-brain me-2"></i>Get AI Prediction
                                    </button>
                                </div>
                                <div id="intensity-prediction-result" style="min-height: 50px;">
                                    <!-- Prediction result will be injected here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        async function getPredictedSpread(fireProperties, button) {
            console.log("Requesting AI spread prediction for fire:", fireProperties.poly_IncidentName);
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Predicting...`;
            
            try {
                const payload = {
                    latitude: fireProperties.attr_InitialLatitude,
                    longitude: fireProperties.attr_InitialLongitude,
                    fire_properties: fireProperties
                };

                const response = await axios.post("{{ route('api.predict.spread') }}", payload);

                if (response.data.success) {
                    console.log("Spread prediction received:", response.data);
                    drawPredictedSpread(fireProperties, response.data);
                } else {
                    throw new Error(response.data.message || "Prediction failed on the server.");
                }
            } catch (error) {
                console.error("Failed to get predicted spread:", error.response?.data?.message || error.message);
                alert(`Could not get spread prediction: ${error.response?.data?.message || error.message}`);
            } finally {
                button.disabled = false;
                button.innerHTML = `<i class="fas fa-wind me-2"></i>Predict Potential Spread`;
            }
        }
        
        function drawPredictedSpread(fireProperties, predictionData) {
            predictedSpreadLayer.clearLayers(); 

            const fireSizeClassToDistanceKm = {
                'A': 0.1, 'B': 0.5, 'C': 2.0, 'D': 5.0,
                'E': 10.0, 'F': 20.0, 'G': 50.0
            };

            const distanceKm = fireSizeClassToDistanceKm[predictionData.spread_class] || 0.1;
            const windBearing = predictionData.wind_direction;
            const center = L.latLng(fireProperties.attr_InitialLatitude, fireProperties.attr_InitialLongitude);
            const coneAngle = 40; 
            const p1 = center;
            const p2 = calculateDestinationPoint(center.lat, center.lng, windBearing - coneAngle / 2, distanceKm);
            const p3 = calculateDestinationPoint(center.lat, center.lng, windBearing + coneAngle / 2, distanceKm);
            
            lastOfficialFireLayer = findOfficialFireLayer(fireProperties.GlobalID);

            const spreadPolygon = L.polygon([p1, p2, p3], {
                color: '#0dcaf0', weight: 2, fillColor: '#0dcaf0', fillOpacity: 0.4
            });

            const popupContent = `
                <div class="text-center">
                    <h6 class="mb-1"><i class="fas fa-brain me-1"></i> AI Spread Suggestion</h6>
                    <p class="mb-2 small text-muted">This is a heuristic projection based on current wind and a classification model. Not a guarantee.</p>
                    <ul class="list-unstyled text-start small">
                        <li><strong>Wind Direction:</strong> ${windBearing}°</li>
                        <li><strong>Predicted Class:</strong> ${predictionData.spread_class}</li>
                        <li><strong>Projected Distance:</strong> ${distanceKm} km</li>
                    </ul>
                    <button class="btn btn-sm btn-outline-light" id="revert-to-perimeter-btn">Revert to Perimeter</button>
                </div>`;
            spreadPolygon.bindPopup(popupContent).openPopup();

            predictedSpreadLayer.addLayer(spreadPolygon);
            if (!map.hasLayer(predictedSpreadLayer)) {
                map.addLayer(predictedSpreadLayer);
            }
            map.fitBounds(spreadPolygon.getBounds().pad(0.2)); 
            fireDetailsModal.hide();
        }

        function findOfficialFireLayer(globalId) {
            let foundLayer = null;
            officialPerimetersLayer.eachLayer(layer => {
                if (layer.fireProperties && layer.fireProperties.GlobalID === globalId) {
                    foundLayer = layer;
                }
            });
            return foundLayer;
        }

        function revertToPerimeterView() {
            predictedSpreadLayer.clearLayers();
            if (lastOfficialFireLayer) {
                map.fitBounds(lastOfficialFireLayer.getBounds());
                lastOfficialFireLayer.openPopup(); 
            }
        }

        function calculateDestinationPoint(lat, lon, bearing, distanceKm) {
            const R = 6371; 
            const latRad = (lat * Math.PI) / 180;
            const lonRad = (lon * Math.PI) / 180;
            const bearingRad = (bearing * Math.PI) / 180;

            const lat2Rad = Math.asin(Math.sin(latRad) * Math.cos(distanceKm / R) +
                Math.cos(latRad) * Math.sin(distanceKm / R) * Math.cos(bearingRad));
            
            let lon2Rad = lonRad + Math.atan2(Math.sin(bearingRad) * Math.sin(distanceKm / R) * Math.cos(latRad),
                Math.cos(distanceKm / R) - Math.sin(latRad) * Math.sin(lat2Rad));
            
            return L.latLng((lat2Rad * 180) / Math.PI, (lon2Rad * 180) / Math.PI);
        }

        function showOfficialFireModal(props, layer) {
            lastOfficialFireLayer = layer;
            document.getElementById('fire-details-modal-title').innerHTML = `<i class="fas fa-certificate text-danger me-2"></i> Official Incident`;
            
            const propertiesJson = escapeHTML(JSON.stringify(props));

            document.getElementById('fire-details-modal-body').innerHTML = `
                <div class="mb-3">
                    <h4>${props.poly_IncidentName || 'Unknown'}</h4>
                    <p class="text-muted mb-0">${props.UniqueFireIdentifier || ''}</p>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-body-tertiary h-100">
                            <div class="card-body">
                                <h6 class="card-title">Details</h6>
                                <p class="mb-1 d-flex justify-content-between"><strong>Cause:</strong> <span>${props.attr_FireCause || 'N/A'}</span></p>
                                <p class="mb-1 d-flex justify-content-between"><strong>Size:</strong> <span>${props.poly_GISAcres ? props.poly_GISAcres.toFixed(2) + ' acres' : 'N/A'}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-body-tertiary h-100">
                            <div class="card-body">
                                <h6 class="card-title">Timeline</h6>
                                <p class="mb-1 d-flex justify-content-between"><strong>Discovered:</strong> <span>${formatDate(props.attr_FireDiscoveryDateTime)}</span></p>
                                <p class="mb-1 d-flex justify-content-between"><strong>Contained:</strong> <span>${formatDate(props.attr_ContainmentDateTime)}</span></p>
                                <p class="mb-1 d-flex justify-content-between"><strong>Fire Out:</strong> <span>${formatDate(props.attr_FireOutDateTime)}</span></p>
                                <p class="mb-1 d-flex justify-content-between"><strong>Last Update:</strong> <span>${formatDate(props.poly_PolygonDateTime)}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="d-grid">
                            <button id="predict-spread-btn" class="btn btn-info" data-fire-properties='${propertiesJson}'>
                                <i class="fas fa-wind me-2"></i>Predict Potential Spread
                            </button>
                        </div>
                        <p class="text-center small text-muted mt-1">Uses live weather & AI to project potential spread direction.</p>
                    </div>
                </div>`;
            fireDetailsModal.show();
        }

        function updateCalculateButtonState() { document.getElementById('calculate-route-btn').disabled = !(startMarker && endMarker); }
        function clearRouteMarkers() { if (startMarker) map.removeLayer(startMarker); if (endMarker) map.removeLayer(endMarker); if (currentRouteControl) map.removeControl(currentRouteControl); startMarker = null; endMarker = null; currentRouteControl = null; updateCalculateButtonState(); }
        function calculateAndSaveRoute() { if (!startMarker || !endMarker) return; const routeName = document.getElementById('route-name').value.trim(); if (!routeName) return alert('Please enter a name for the route.'); if (currentRouteControl) map.removeControl(currentRouteControl); currentRouteControl = L.Routing.control({ waypoints: [startMarker.getLatLng(), endMarker.getLatLng()], routeWhileDragging: false, addWaypoints: false, createMarker: () => null, lineOptions: { styles: [{ color: 'blue', opacity: 0.8, weight: 6 }] } }).on('routesfound', function(e) { const route = e.routes[0]; const geometry = L.polyline(route.coordinates.map(c => [c.lat, c.lng])).toGeoJSON(); const routeData = { name: routeName, start_latitude: startMarker.getLatLng().lat, start_longitude: startMarker.getLatLng().lng, end_latitude: endMarker.getLatLng().lat, end_longitude: endMarker.getLatLng().lng, geometry: JSON.stringify(geometry.geometry) }; axios.post('/api/routes', routeData).then(() => { alert('Route saved successfully!'); clearRouteMarkers(); document.getElementById('route-name').value = ''; fetchAndDisplaySavedRoutes(); }).catch(error => { console.error('Error saving route:', error.response?.data); alert('Failed to save route.'); }); }).addTo(map); }
        async function fetchAndDisplaySavedRoutes() { try { const response = await axios.get('/api/routes'); const routes = response.data; const listContainer = document.getElementById('saved-routes-list'); savedRoutesLayer.clearLayers(); listContainer.innerHTML = routes.length === 0 ? '<li class="list-group-item text-muted small">No routes saved yet.</li>' : ''; routes.forEach(route => { const latlngs = route.geometry.coordinates.map(coord => [coord[1], coord[0]]); const polyline = L.polyline(latlngs, { color: 'green', weight: 5, opacity: 0.7 }).bindPopup(`<b>${route.name}</b>`); savedRoutesLayer.addLayer(polyline); const li = document.createElement('li'); li.className = 'list-group-item d-flex justify-content-between align-items-center list-group-item-action'; li.innerHTML = `<span><i class="fas fa-route me-2"></i>${route.name}</span> <button class="btn btn-sm btn-outline-danger delete-route-btn" data-id="${route.id}"><i class="fas fa-trash"></i></button>`; li.addEventListener('click', (e) => { if (!e.target.closest('.delete-route-btn')) { map.fitBounds(polyline.getBounds()); } }); listContainer.appendChild(li); }); filterSavedRoutes(); } catch (error) { console.error('Failed to fetch saved routes:', error); } }
        async function handleSavedRouteClick(event) { const deleteButton = event.target.closest('.delete-route-btn'); if (deleteButton) { const routeId = deleteButton.dataset.id; if (confirm('Are you sure you want to delete this route?')) { try { await axios.delete(`/api/routes/${routeId}`); fetchAndDisplaySavedRoutes(); } catch (error) { console.error('Failed to delete route:', error); alert('Could not delete the route.'); } } } }
        function filterSavedRoutes() { const searchTerm = document.getElementById('route-search-input').value.toLowerCase(); const routes = document.querySelectorAll('#saved-routes-list li'); routes.forEach(route => { const routeName = route.querySelector('span').textContent.toLowerCase(); route.style.display = routeName.includes(searchTerm) ? '' : 'none'; }); }
        function debounce(func, delay) { let timeout; return function(...args) { clearTimeout(timeout); timeout = setTimeout(() => func.apply(this, args), delay); }; }
        function initializeUnifiedSearch() {
            const searchIcon = document.getElementById('search-icon-btn'); const searchContainer = document.getElementById('search-container'); const searchInput = document.getElementById('unified-search-input'); const resultsContainer = document.getElementById('search-results'); const loader = document.getElementById('search-loader');
            searchIcon.addEventListener('click', (e) => { e.stopPropagation(); searchContainer.classList.toggle('hidden'); if (!searchContainer.classList.contains('hidden')) { searchInput.focus(); } });
            searchInput.addEventListener('keyup', debounce(async (e) => {
                const query = e.target.value; if (query.length < 2) { resultsContainer.innerHTML = ''; return; }
                loader.classList.remove('d-none');
                const [fireResults, placeResults] = await Promise.all([ searchFires(query), searchPlaces(query) ]);
                const combinedResults = [...fireResults, ...placeResults];
                loader.classList.add('d-none'); renderUnifiedResults(combinedResults, resultsContainer);
            }, 350));
            document.addEventListener('click', (e) => { if (!searchContainer.contains(e.target) && e.target !== searchIcon && !searchIcon.contains(e.target)) { resultsContainer.innerHTML = ''; searchContainer.classList.add('hidden'); } });
        }
        async function findFirstLocation(query) {
            const fireResults = searchFires(query); if(fireResults.length > 0) return fireResults[0];
            const placeResults = await searchPlaces(query); if(placeResults.length > 0) return placeResults[0];
            return null;
        }
        function searchFires(query) {
            const results = []; const addedFireNames = new Set();
            if (map.hasLayer(officialPerimetersLayer)) {
                const cleanQuery = query.toLowerCase().replace(/\s+fire$/, '').trim();
                officialPerimetersLayer.eachLayer(layer => {
                    try {
                        if (!layer || typeof layer.getBounds !== 'function') return;
                        const props = layer.feature?.properties || layer.options?.fireProperties;
                        if (!props || !props.poly_IncidentName || addedFireNames.has(props.poly_IncidentName)) return;
                        const cleanFireName = props.poly_IncidentName.toLowerCase().replace(/\s+fire$/, '').trim();
                        if (cleanFireName.includes(cleanQuery)) { const bounds = layer.getBounds(); if (bounds.isValid()) { addedFireNames.add(props.poly_IncidentName); results.push({ name: props.poly_IncidentName, details: `${props.poly_GISAcres ? props.poly_GISAcres.toFixed(0) : 'N/A'} acres`, bbox: bounds, type: 'fire' }); } }
                    } catch (e) { console.warn('[Search] Error processing fire layer:', e); }
                });
            }
            return results;
        }
        async function searchPlaces(query) {
            try {
                const response = await axios.get('/api/geocode', { params: { q: query } });
                if (response.data && Array.isArray(response.data)) {
                    return response.data.map(item => { const bbox = item.boundingbox; if (!bbox || bbox.length < 4) return null; const bounds = L.latLngBounds([[parseFloat(bbox[0]), parseFloat(bbox[2])], [parseFloat(bbox[1]), parseFloat(bbox[3])]]); if (!bounds.isValid()) return null; return { name: item.display_name, details: item.type.charAt(0).toUpperCase() + item.type.slice(1), bbox: bounds, type: 'place' }; }).filter(p => p !== null);
                }
            } catch (error) { console.error("[Search] Place search API failed:", error); }
            return [];
        }
        function renderUnifiedResults(results, container) {
            if (results.length === 0) { container.innerHTML = '<div class="search-result-card"><span class="result-details">No results found.</span></div>'; return; }
            container.innerHTML = results.map(result => { const icon = result.type === 'fire' ? 'fas fa-fire text-danger' : 'fas fa-map-pin text-info'; return `<div class="search-result-card" data-bbox="${result.bbox.toBBoxString()}"><div class="d-flex align-items-center"><i class="${icon} me-3"></i><div><div class="result-name">${result.name}</div><div class="result-details">${result.details}</div></div></div>`; }).join('');
            container.querySelectorAll('.search-result-card').forEach(card => {
                card.addEventListener('click', (e) => { const bboxStr = e.currentTarget.dataset.bbox; if (bboxStr) { const parts = bboxStr.split(','); const bounds = L.latLngBounds([[parts[1], parts[0]],[parts[3], parts[2]]]); map.fitBounds(bounds); container.innerHTML = ''; document.getElementById('unified-search-input').value = ''; document.getElementById('search-container').classList.add('hidden'); } });
            });
        }
        
        function makeDraggable(element, handle) { let isDragging=false,x,y; handle.addEventListener('mousedown',function(e){isDragging=true;x=e.clientX-element.offsetLeft;y=e.clientY-element.offsetTop; e.preventDefault();}); document.addEventListener('mousemove',function(e){if(isDragging===true){element.style.left=Math.max(5, (e.clientX-x))+'px';element.style.top=Math.max(5, (e.clientY-y))+'px';}}); document.addEventListener('mouseup',function(e){isDragging=false;}); }
        function calculateConvexHull(points) {
            points.sort((a, b) => a[1] - b[1] || a[0] - b[0]);
            const cross = (o, a, b) => (a[1] - o[1]) * (b[0] - o[0]) - (a[0] - o[0]) * (b[1] - o[1]);
            const lower = []; for (const p of points) { while (lower.length >= 2 && cross(lower[lower.length - 2], lower[lower.length - 1], p) <= 0) { lower.pop(); } lower.push(p); }
            const upper = []; for (let i = points.length - 1; i >= 0; i--) { const p = points[i]; while (upper.length >= 2 && cross(upper[upper.length - 2], upper[upper.length - 1], p) <= 0) { upper.pop(); } upper.push(p); }
            return lower.slice(0, -1).concat(upper.slice(0, -1));
        }

        function sendMessage() { const input = document.getElementById('chat-input'); const messageText = input.value.trim(); if (agentHandler && messageText) { agentHandler.sendMessage(messageText); input.value = ''; } else { console.log("Agent handler not ready or message is empty."); } }

        function initializeAudioRecording() {
            console.log("Initializing audio recording feature.");
            const recordBtn = document.getElementById('speech-to-text-btn');
            const chatInput = document.getElementById('chat-input');
            const icon = recordBtn.querySelector('i');
            recordBtn.addEventListener('click', () => { isRecording ? stopRecording() : startRecording(); });
            async function startRecording() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true }); isRecording = true; audioChunks = []; mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.ondataavailable = event => { audioChunks.push(event.data); };
                    mediaRecorder.onstop = () => { stream.getTracks().forEach(track => track.stop()); const audioBlob = new Blob(audioChunks, { type: 'audio/webm' }); sendAudioForTranscription(audioBlob); };
                    mediaRecorder.start();
                    icon.classList.remove('fa-microphone'); icon.classList.add('fa-stop', 'text-danger'); recordBtn.title = "Stop Recording"; chatInput.placeholder = "Listening... Click stop when finished."; chatInput.disabled = true;
                } catch (err) { console.error("[Speech-to-Text] Error accessing microphone:", err); alert("Could not access the microphone. Please ensure you have granted permission."); isRecording = false; }
            }
            function stopRecording() { if (mediaRecorder && mediaRecorder.state === "recording") { mediaRecorder.stop(); } }
            async function sendAudioForTranscription(blob) {
                const recordBtn = document.getElementById('speech-to-text-btn'); const chatInput = document.getElementById('chat-input'); const icon = recordBtn.querySelector('i');
                icon.classList.remove('fa-stop', 'text-danger'); icon.classList.add('fa-spinner', 'fa-spin'); recordBtn.title = "Transcribing..."; recordBtn.disabled = true; chatInput.placeholder = "Transcribing audio...";
                const formData = new FormData(); formData.append('audio', blob, 'speech_input.webm');
                try {
                    const response = await axios.post('/transcribe/audio', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
                    const transcript = response.data.transcript; console.log("[Speech-to-Text] Received transcript:", transcript);
                    if (transcript) { chatInput.value = transcript; sendMessage(); } else { console.warn("[Speech-to-Text] Received empty transcript from server."); }
                } catch (error) {
                    console.error("[Speech-to-Text] Failed to transcribe audio:", error.response?.data || error.message); const errorMessage = error.response?.data?.error || "Could not transcribe the audio.";
                    agentHandler.displayMessage(`<strong>Transcription Failed:</strong> ${errorMessage}`, 'assistant');
                } finally {
                    isRecording = false; icon.classList.remove('fa-spinner', 'fa-spin'); icon.classList.add('fa-microphone'); recordBtn.title = "Talk to Agent"; recordBtn.disabled = false; chatInput.placeholder = "Ask a question or use the mic..."; chatInput.disabled = false;
                }
            }
        }
        
        function initializeAlertManagement() {
            console.log("Initializing community alert management.");
            alertDrawer = new L.Draw.Circle(map, { shapeOptions: { color: '#ffc107', weight: 3, fillColor: '#ffc107', fillOpacity: 0.3 }, showRadius: true, metric: true });
            loadCommunityAlerts();
            setupAlertPusherListener();
        }

        async function saveAlert(event) {
            event.preventDefault();
            const btn = event.target.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Broadcasting...`;
            const formData = { latitude: document.getElementById('alert-lat').value, longitude: document.getElementById('alert-lng').value, radius: document.getElementById('alert-radius').value, message: document.getElementById('alert-message').value };
            try {
                const response = await axios.post("{{ route('api.alerts.store') }}", formData);
                console.log("Alert saved successfully via API. Proactively adding to map.", response.data);
                addAlertToMap(response.data); 
                alertModal.hide();
                document.getElementById('alert-form').reset();
            } catch (error) {
                console.error("Error saving alert:", error.response?.data);
                alert("Failed to save alert. " + (error.response?.data?.message || 'Check console for details.'));
            } finally {
                btn.disabled = false;
                btn.innerHTML = `Save and Broadcast Alert`;
            }
        }

        function deleteAlert(alertId) {
            if (!confirm('Are you sure you want to delete this community alert? This will remove it for all users immediately.')) return;
            
            console.log(`Requesting deletion for alert ID: ${alertId}`);
            axios.delete(`/api/alerts/${alertId}`)
                .then(() => {
                    console.log(`Successfully requested deletion for alert ${alertId}. Other clients will update via Pusher.`);
                    removeAlertFromMap(alertId);
                })
                .catch(error => {
                    console.error(`Error deleting alert ${alertId}:`, error);
                    alert('Failed to delete alert.');
                });
        }
        
        function loadCommunityAlerts() {
            axios.get("{{ route('api.alerts.index') }}")
                .then(response => { communityAlertsLayer.clearLayers(); activeAlerts = {}; response.data.forEach(alert => addAlertToMap(alert)); console.log(`Loaded ${response.data.length} community alerts.`); })
                .catch(error => console.error("Error loading community alerts:", error));
        }

        function addAlertToMap(alert) {
            if (activeAlerts[alert.id]) { communityAlertsLayer.removeLayer(activeAlerts[alert.id]); }
            console.log(`Adding alert ${alert.id} to map with message: "${alert.message}"`);
            const circle = L.circle([alert.latitude, alert.longitude], { radius: alert.radius, color: '#ffc107', fillColor: '#ffc107', fillOpacity: 0.3 }).addTo(communityAlertsLayer);
            
            circle.alertId = alert.id;
            circle.alertMessage = alert.message;

            const popupContent = `<div><b>Community Alert:</b><br>${escapeHTML(alert.message)}<br><small>Radius: ${(alert.radius/1000).toFixed(1)}km</small><hr class="my-1"><button class="btn btn-sm btn-danger w-100" onclick="deleteAlert(${alert.id})"><i class="fas fa-trash me-1"></i>Delete Alert</button></div>`;
            circle.bindPopup(popupContent); 
            activeAlerts[alert.id] = circle;
        }

        function removeAlertFromMap(alertId) {
             if (activeAlerts[alertId]) { 
                 console.log(`Removing alert ${alertId} from map.`); 
                 communityAlertsLayer.removeLayer(activeAlerts[alertId]); 
                 delete activeAlerts[alertId];
                 map.closePopup();
            }
        }

        function setupAlertPusherListener() {
            try {
                window.Pusher = Pusher;
                window.Echo = new Echo({ broadcaster: 'pusher', key: "{{ config('broadcasting.connections.pusher.key') }}", cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}", forceTLS: true });
                console.log("Setting up public-alerts channel listener on Pusher.");
                window.Echo.channel('public-alerts')
                    .listen('AlertCreated', (e) => { 
                        console.log('Pusher Event: AlertCreated received.', e); 
                        addAlertToMap(e.alert); 
                    })
                    .listen('AlertDeleted', (e) => { 
                        console.log('Pusher Event: AlertDeleted received.', e); 
                        removeAlertFromMap(e.alertId); 
                    });
            } catch(e) { console.error("Pusher/Echo initialization failed. Real-time alerts will not function. Check that pusher.min.js and echo.js are loaded.", e); }
        }

        function escapeHTML(str) { 
            if (typeof str !== 'string') return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

    </script>
</body>
</html>