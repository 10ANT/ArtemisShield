<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add CSRF Token for Laravel POST requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ArtemisShield - Wildfire Protection Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @include('partials.styles')
    <style>
        /* Your existing CSS */
        .main-content {
            flex-grow: 1;
        }

        .wildfire-dashboard-container {
            height: 100%;
        }

        #map {
            width: 100%;
            height: 100%;
            min-height: 500px;
            background-color: var(--bs-tertiary-bg);
        }

        .map-wrapper {
            position: relative;
            height: 100%;
        }

        .map-overlay {
            position: absolute;
            z-index: 1000;
            margin: 1rem;
            width: 260px;
            max-height: calc(50vh - 2rem);
            overflow-y: auto;
        }

        .layers-panel {
            top: 0;
            left: 0;
        }

        .weather-widget {
            top: 0;
            right: 0;
        }

        .sidebar-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .sidebar-wrapper .tab-content {
            flex-grow: 1;
            overflow-y: auto;
        }

        .chat-container {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .chat-messages {
            flex-grow: 1;
            overflow-y: auto;
        }

        .fire-marker i {
            text-shadow: 0 0 4px rgba(0, 0, 0, 0.7);
        }

        .fire-hydrant-icon {
            background-color: transparent;
            border: none;
            text-align: center;
        }

        .fire-hydrant-icon i {
            color: #007bff;
            font-size: 20px;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        }

        .fire-station-icon {
            background-color: transparent;
            border: none;
            text-align: center;
        }

        .fire-station-icon i {
            color: #FFA500;
            font-size: 20px;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        }

        /* --- NEW & REVISED: Custom Popup Styles for Landscape Layout --- */
        .custom-popup .leaflet-popup-content-wrapper {
            background-color: #2a2a2a; /* Darker background */
            color: #e0e0e0; /* Light text */
            border-radius: 8px;
            padding: 0; /* Remove default padding for internal layout control */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            max-width: 450px; /* Max width to ensure landscape shape */
            min-width: 300px; /* Min width for smaller screens */
            height: auto;
        }

        .custom-popup .leaflet-popup-content {
            margin: 0;
            padding: 0;
            width: 100% !important; /* Take full width of wrapper */
            height: 100%;
            display: flex; /* Use flexbox for horizontal layout */
            flex-direction: column;
        }

        .custom-popup .leaflet-popup-tip {
            background-color: #2a2a2a; /* Match background */
        }

        .custom-popup .popup-header {
            background-color: #1a1a1a; /* Even darker header */
            padding: 10px 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-popup .popup-header h4 {
            color: #fff;
            font-size: 1.1rem;
            margin: 0;
        }

        .custom-popup .popup-header .close-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0 5px;
        }

        .custom-popup .popup-body {
            display: flex; /* Flex container for content sections */
            flex-wrap: wrap; /* Allow sections to wrap on smaller popups if needed */
            padding: 10px 15px;
            gap: 10px; /* Space between sections */
            flex-grow: 1; /* Allow body to take available height */
        }

        .custom-popup .popup-section {
            background-color: #333333; /* Slightly lighter card background */
            border-radius: 6px;
            padding: 10px;
            flex: 1; /* Allows sections to grow and shrink */
            min-width: 140px; /* Minimum width for each section */
            display: flex;
            flex-direction: column;
        }
        /* For two sections in the body */
        .custom-popup .popup-body.two-columns .popup-section {
            flex: 1 1 calc(50% - 10px); /* Two columns with gap */
        }
        /* For three sections */
        .custom-popup .popup-body.three-columns .popup-section {
            flex: 1 1 calc(33.333% - 10px); /* Three columns with gap */
        }


        .custom-popup .popup-section-title {
            color: #87CEEB; /* Title color matching the image */
            font-size: 0.9rem;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .custom-popup .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2px 0;
        }

        .custom-popup .detail-label {
            color: #b0b0b0;
            font-size: 0.8rem;
            flex-shrink: 0; /* Prevent label from shrinking */
            margin-right: 5px;
        }

        .custom-popup .detail-value {
            color: #f0f0f0; /* Default value color */
            font-weight: bold;
            font-size: 0.85rem;
            text-align: right;
            word-wrap: break-word;
            flex-grow: 1; /* Allow value to grow */
        }

        .custom-popup .detail-value.highlight-blue {
            color: #87CEEB; /* Blue highlight for primary values */
        }
        .custom-popup .detail-value.highlight-orange {
            color: #FFA500; /* Orange highlight for specific values like "confidence" */
        }
        .custom-popup .detail-value.highlight-green {
            color: #28a745; /* Green for success/good status */
        }

        .custom-popup a {
            color: #87CEEB;
            text-decoration: none;
        }
        .custom-popup a:hover {
            text-decoration: underline;
        }

        .custom-popup .additional-text {
            margin-top: 10px;
            font-size: 0.8rem;
            color: #c0c0c0;
            line-height: 1.4;
            max-height: 80px; /* Limit height for long descriptions */
            overflow-y: auto; /* Enable scrolling for long descriptions */
            padding-right: 5px; /* Space for scrollbar */
        }
        /* Scrollbar styles for dark theme */
        .custom-popup .additional-text::-webkit-scrollbar {
            width: 8px;
        }
        .custom-popup .additional-text::-webkit-scrollbar-track {
            background: #444;
            border-radius: 4px;
        }
        .custom-popup .additional-text::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .custom-popup .additional-text::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        /* Custom styles for the collapse icon rotation */
        .collapse-icon {
            transition: transform 0.3s ease-in-out;
        }

        .collapse-icon.fa-chevron-up {
            transform: rotate(180deg);
        }

        /* Ensure card-header doesn't have extra padding from card-title */
        .card-header .btn-link {
            padding: 0; /* Adjust as needed, but prevent default btn-link padding */
        }
        
        /* --- NEW CSS FOR LIVE REPORT TAB --- */
        #live-report-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .recording-controls {
            text-align: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .record-btn {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background-color: var(--bs-secondary-bg);
            border: 4px solid var(--bs-primary);
            color: var(--bs-primary);
            font-size: 2rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .record-btn:hover {
            background-color: var(--bs-primary-bg-subtle);
        }
        
        .record-btn:disabled {
            background-color: var(--bs-secondary-bg);
            border-color: var(--bs-secondary);
            color: var(--bs-secondary);
            cursor: not-allowed;
        }

        .record-btn.is-recording {
            background-color: var(--bs-danger);
            border-color: var(--bs-danger-bg-subtle);
            color: #fff;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { box-shadow: 0 0 0 20px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }

        .recording-status {
            margin-top: 0.5rem;
            font-weight: 500;
            color: var(--bs-secondary-color);
        }
        
        .ai-analysis-container {
            flex-grow: 1;
            overflow-y: auto;
            padding-top: 1rem;
        }

        .ai-analysis-card {
            background-color: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color-translucent);
        }

        .ai-analysis-card .card-header {
            background-color: rgba(var(--bs-emphasis-color-rgb), 0.05);
            font-weight: 600;
        }

        .entity-tag {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: .8em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: var(--bs-border-radius);
            margin: 2px;
        }

        .entity-tag-location { background-color: var(--bs-primary); color: #fff; }
        .entity-tag-resource { background-color: var(--bs-info); color: #000; }
        .entity-tag-hazard { background-color: var(--bs-warning); color: #000; }
        .entity-tag-other { background-color: var(--bs-secondary); color: #fff; }

        .suggestion-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--bs-border-color-translucent);
        }
        .suggestion-item:last-child {
            border-bottom: none;
        }
        .suggestion-icon {
            font-size: 1.25rem;
            color: var(--bs-success);
            margin-top: 0.25rem;
        }

    </style>
</head>

<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            @include('partials.header')
            <div class="wildfire-dashboard-container row g-0 flex-grow-1">

                <div class="col-lg-8 col-md-7">
                    <div class="map-wrapper">
                        <div id="map"></div>

                        <div class="map-overlay layers-panel card shadow-sm">
                            <div class="card-header p-0" id="layersHeading">
                                <h6 class="mb-0">
                                    <button class="btn btn-link w-100 text-start text-decoration-none text-dark p-3" type="button" data-bs-toggle="collapse" data-bs-target="#layersCollapse" aria-expanded="true" aria-controls="layersCollapse">
                                        <i class="fas fa-layer-group fa-fw me-2"></i>Map Layers <i class="fas fa-chevron-down float-end collapse-icon"></i>
                                    </button>
                                </h6>
                            </div>
                            <div id="layersCollapse" class="collapse show" aria-labelledby="layersHeading">
                                <div class="card-body p-3">
                                    <hr class="my-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="active-incidents" checked>
                                        <label class="form-check-label" for="active-incidents">Active Incidents</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="viirs-24" checked>
                                        <label class="form-check-label" for="viirs-24">VIIRS Hotspots (24h)</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="infrastructure">
                                        <label class="form-check-label" for="infrastructure">Infrastructure</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="evacuation">
                                        <label class="form-check-label" for="evacuation">Evac Routes</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="fire-hydrants-toggle" checked>
                                        <label class="form-check-label" for="fire-hydrants-toggle">Fire Hydrants</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="fire-stations-toggle" checked>
                                        <label class="form-check-label" for="fire-stations-toggle">Fire Stations</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="map-overlay weather-widget card shadow-sm">
                            <div class="card-header p-0" id="weatherHeading">
                                <h6 class="mb-0">
                                    <button class="btn btn-link w-100 text-start text-decoration-none text-dark p-3" type="button" data-bs-toggle="collapse" data-bs-target="#weatherCollapse" aria-expanded="true" aria-controls="weatherCollapse">
                                        <i class="fas fa-cloud-sun fa-fw me-2"></i>Local Weather <i class="fas fa-chevron-down float-end collapse-icon"></i>
                                    </button>
                                </h6>
                            </div>
                            <div id="weatherCollapse" class="collapse show" aria-labelledby="weatherHeading">
                                <div class="card-body p-3">
                                    <hr class="my-2">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                            Temperature <span class="badge text-bg-primary" id="temp">28°C</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                            Wind <span class="badge text-bg-primary" id="wind">15 km/h</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                            Humidity <span class="badge text-bg-primary" id="humidity">45%</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                            Fire Risk <span class="badge text-bg-danger" id="fire-risk">High</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-5 border-start">
                    <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-fill" id="sidebar-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="chat-tab-btn" data-bs-toggle="pill"
                                        data-bs-target="#chat-content" type="button" role="tab"
                                        aria-controls="chat-content" aria-selected="true">
                                        <i class="fas fa-comments me-1"></i> Ask Artemis
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="control-tab-btn" data-bs-toggle="pill"
                                        data-bs-target="#control-content" type="button" role="tab"
                                        aria-controls="control-content" aria-selected="false">
                                        <i class="fas fa-cogs me-1"></i> Control Panel
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="live-report-tab-btn" data-bs-toggle="pill"
                                        data-bs-target="#live-report-content" type="button" role="tab"
                                        aria-controls="live-report-content" aria-selected="false">
                                        <i class="fas fa-microphone-alt me-1"></i> Live Report
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body d-flex flex-column p-0">
                            <div class="tab-content h-100">
                                <div class="tab-pane fade show active p-3" id="chat-content" role="tabpanel">
                                    <div class="chat-container">
                                        <div class="chat-messages mb-3" id="chat-messages">
                                            <div class="mb-3 text-start">
                                                <small class="text-body-secondary">Artemis AI Assistant</small>
                                                <div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">
                                                    Hello! I'm Artemis. Ask me about active fires, resource status, or
                                                    weather conditions.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-input-group d-flex gap-2">
                                            <input type="text" class="form-control" placeholder="Ask a question..."
                                                id="chat-input">
                                            <button class="btn btn-primary" onclick="sendMessage()"><i
                                                    class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade p-3" id="control-content" role="tabpanel">
                                    <h6 class="text-body-secondary">Quick Actions</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6"><button class="btn btn-primary w-100"><i
                                                    class="fas fa-rocket me-2"></i>Deploy</button></div>
                                        <div class="col-6"><button class="btn btn-danger w-100"><i
                                                    class="fas fa-bullhorn me-2"></i>Evacuate</button></div>
                                        <div class="col-6"><button class="btn btn-success w-100"><i
                                                    class="fas fa-file-alt me-2"></i>Report</button></div>
                                        <div class="col-6"><button class="btn btn-warning w-100"><i
                                                    class="fas fa-hands-helping me-2"></i>Request Aid</button></div>
                                    </div>

                                    <h6 class="text-body-secondary">Live Data</h6>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Active Fires <span class="badge text-bg-danger"
                                                id="active-fires-count">12</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Resources Deployed <span class="badge text-bg-info"
                                                id="resources-count">45</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Properties at Risk <span class="badge text-bg-warning"
                                                id="properties-count">234</span></li>
                                    </ul>

                                    <h6 class="text-body-secondary">Recent Reports</h6>
                                    <div class="card bg-body-tertiary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-title mb-1">Fire Containment</h6>
                                                <span class="badge text-bg-success">Success</span>
                                            </div>
                                            <p class="card-text mb-1">North Ridge Area containment line holding.</p>
                                            <small class="text-body-secondary">10 min ago</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- ================================================================= -->
                                <!-- START: FULLY FUNCTIONAL LIVE REPORT TAB CONTENT                   -->
                                <!-- ================================================================= -->
                                <div class="tab-pane fade p-3" id="live-report-content" role="tabpanel">
                                    <div class="recording-controls d-flex flex-column align-items-center">
                                        <button class="record-btn mb-2" id="record-button">
                                            <i class="fas fa-microphone"></i>
                                        </button>
                                        <p class="recording-status" id="recording-status">Tap to Start Field Report</p>
                                    </div>
                                
                                    <div id="ai-analysis-results" class="ai-analysis-container">
                                        <!-- AI results will be injected here by JavaScript -->
                                        <div id="report-placeholder" class="text-center text-muted mt-5">
                                            <i class="fas fa-wind fa-3x mb-3"></i>
                                            <p>Awaiting field report...</p>
                                        </div>
                                        <div id="report-error" class="alert alert-danger d-none" role="alert"></div>
                                    </div>
                                </div>
                                <!-- ================================================================= -->
                                <!-- END: LIVE REPORT TAB CONTENT                                      -->
                                <!-- ================================================================= -->

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
        // --- MAP & ORIGINAL SCRIPT LOGIC ---
        let map;
        let fireHydrantsLayer;
        let isHydrantsVisible = true;
        let fireStationsLayer;
        let isStationsVisible = true;

        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                map = L.map('map').setView([41.8781, -87.6298], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                const fireHydrantIcon = L.divIcon({
                    className: 'fire-hydrant-icon',
                    html: '<i class="fas fa-faucet"></i>', iconSize: [20, 20], iconAnchor: [10, 20]
                });
                const fireStationIcon = L.divIcon({
                    className: 'fire-station-icon',
                    html: '<i class="fas fa-building"></i>', iconSize: [20, 20], iconAnchor: [10, 20]
                });

                function createDetailRow(label, value, valueClass = '') { if (value === null || value === undefined || value === '') { return ''; } if (label.toLowerCase().includes('website') && value.startsWith('http')) { value = `<a href="${value}" target="_blank">View Site</a>`; } else if (label.toLowerCase().includes('email') && value.includes('@')) { value = `<a href="mailto:${value}">${value}</a>`; } if (label.toLowerCase().includes('wikipedia') && value.includes('wikipedia.org/wiki/')) { const pageTitle = value.split('/').pop().replace(/_/g, ' '); value = `<a href="${value}" target="_blank">${pageTitle}</a>`; } else if (label.toLowerCase().includes('wikidata') && value.startsWith('Q')) { value = `<a href="https://www.wikidata.org/wiki/${value}" target="_blank">${value}</a>`; } return ` <div class="detail-row"> <span class="detail-label">${label}:</span> <span class="detail-value ${valueClass}">${value}</span> </div> `; }
                function formatHydrantPopupContent(props) { const allTags = props.all_tags || {}; let generalDetails = ` ${createDetailRow("OSM ID", props.osm_id)} ${createDetailRow("Type", props.fire_hydrant_type || allTags['fire_hydrant:type'])} ${createDetailRow("Color", props.color || props.colour || allTags.colour || allTags.color, 'highlight-orange')} ${createDetailRow("Operator", props.operator)} `; let locationDetails = ` ${createDetailRow("Street", props.addr_street || allTags['addr:street'])} ${createDetailRow("House No.", props.addr_housenumber || allTags['addr:housenumber'])} ${createDetailRow("City", props.addr_city || allTags['addr:city'])} ${createDetailRow("Postcode", props.addr_postcode || allTags['addr:postcode'])} ${createDetailRow("State", props.addr_state || allTags['addr:state'])} ${createDetailRow("Country", props.addr_country || allTags['addr:country'])} `; let technicalDetails = ` ${createDetailRow("Position", props.fire_hydrant_position || allTags['fire_hydrant:position'])} ${createDetailRow("Pressure", allTags['fire_hydrant:pressure'], 'highlight-blue')} ${createDetailRow("Flow Rate", allTags['fire_hydrant:flow_rate'])} ${createDetailRow("Water Source", allTags['water_source'])} ${createDetailRow("Diameter", allTags.diameter)} `; let additionalText = props.note || allTags.note; let content = ` <div class="custom-popup"> <div class="popup-header"> <h4>Fire Hydrant Details</h4> <button class="close-btn" onclick="map.closePopup()">×</button> </div> <div class="popup-body two-columns"> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-info-circle"></i> General</div> ${generalDetails} </div> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-map-marker-alt"></i> Location</div> ${locationDetails} </div> <div class="popup-section" style="flex: 1 1 100%;"> <div class="popup-section-title"><i class="fas fa-tools"></i> Technical Specs</div> ${technicalDetails} </div> ${additionalText ? `<div class="popup-section" style="flex: 1 1 100%;"> <div class="popup-section-title"><i class="fas fa-sticky-note"></i> Notes</div> <div class="additional-text">${additionalText}</div> </div>` : ''} </div> </div> `; return content; }
                function formatStationPopupContent(props) { const allTags = props.all_tags || {}; let primaryDetails = ` ${createDetailRow("Name", props.name || 'Unknown')} ${createDetailRow("Official Name", props.official_name)} ${createDetailRow("Operator", props.operator)} ${createDetailRow("Station Type", props.fire_station_type || allTags['fire_station:type'])} `; let contactDetails = ` ${createDetailRow("Phone", props.phone || allTags.phone)} ${createDetailRow("Emergency", props.emergency, 'highlight-green')} ${createDetailRow("Website", props.website || allTags.website)} ${createDetailRow("Email", props.email || allTags.email)} ${createDetailRow("Opening Hours", props.opening_hours || allTags['opening_hours'])} `; let addressDetails = ` ${createDetailRow("Street", props.addr_street || allTags['addr:street'])} ${createDetailRow("House No.", props.addr_housenumber || allTags['addr:housenumber'])} ${createDetailRow("City", props.addr_city || allTags['addr:city'])} ${createDetailRow("Postcode", props.addr_postcode || allTags['addr:postcode'])} ${createDetailRow("State", props.addr_state || allTags['addr:state'])} ${createDetailRow("Country", props.addr_country || allTags['addr:country'])} `; let operationalDetails = ` ${createDetailRow("Building Levels", props.building_levels || allTags['building:levels'])} ${createDetailRow("Apparatus", props.fire_station_apparatus || allTags['fire_station:apparatus'])} ${createDetailRow("Staffing", props.fire_station_staffing || allTags['fire_station:staffing'])} ${createDetailRow("Fire Station Code", props.fire_station_code || allTags['fire_station:code'])} `; let metaDetails = ` ${createDetailRow("OSM ID", props.osm_id)} ${createDetailRow("Source", props.source)} ${createDetailRow("Building Type", props.building)} ${createDetailRow("Wheelchair Access", props.wheelchair)} ${createDetailRow("Wikipedia", props.wikipedia)} ${createDetailRow("Wikidata", props.wikidata)} `; let additionalText = props.description || allTags.description || props.note || allTags.note; let content = ` <div class="custom-popup"> <div class="popup-header"> <h4>Fire Station Details</h4> <button class="close-btn" onclick="map.closePopup()">×</button> </div> <div class="popup-body three-columns"> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-id-card-alt"></i> Identification</div> ${primaryDetails} </div> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-phone-alt"></i> Contact</div> ${contactDetails} </div> <div class="popup-section"> <div class="popup-section-title"><i class="fas fa-map-marked-alt"></i> Address</div> ${addressDetails} </div> <div class="popup-section" style="flex: 1 1 calc(50% - 10px);"> <div class="popup-section-title"><i class="fas fa-fire-extinguisher"></i> Operations</div> ${operationalDetails} </div> <div class="popup-section" style="flex: 1 1 calc(50% - 10px);"> <div class="popup-section-title"><i class="fas fa-globe"></i> Metadata</div> ${metaDetails} </div> ${additionalText ? `<div class="popup-section" style="flex: 1 1 100%;"> <div class="popup-section-title"><i class="fas fa-sticky-note"></i> Description</div> <div class="additional-text">${additionalText}</div> </div>` : ''} </div> </div> `; return content; }

                fireHydrantsLayer = L.geoJson(null, { pointToLayer: (f, l) => L.marker(l, { icon: fireHydrantIcon }), onEachFeature: (f, l) => l.bindPopup(formatHydrantPopupContent(f.properties), { className: 'custom-popup' }) }).addTo(map);
                fireStationsLayer = L.geoJson(null, { pointToLayer: (f, l) => L.marker(l, { icon: fireStationIcon }), onEachFeature: (f, l) => l.bindPopup(formatStationPopupContent(f.properties), { className: 'custom-popup' }) }).addTo(map);

                const loadFireHydrants = async () => { if (!isHydrantsVisible) { fireHydrantsLayer.clearLayers(); return; } try { const r = await fetch(`/api/fire_hydrants`); if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`); const d = await r.json(); fireHydrantsLayer.clearLayers(); fireHydrantsLayer.addData(d); } catch (e) { console.error("Could not fetch fire hydrants:", e); } };
                const loadFireStations = async () => { if (!isStationsVisible) { fireStationsLayer.clearLayers(); return; } try { const r = await fetch(`/api/fire_stations`); if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`); const d = await r.json(); fireStationsLayer.clearLayers(); fireStationsLayer.addData(d); } catch (e) { console.error("Could not fetch fire stations:", e); } };

                document.getElementById('fire-hydrants-toggle').addEventListener('change', e => { isHydrantsVisible = e.target.checked; if (isHydrantsVisible) loadFireHydrants(); else fireHydrantsLayer.clearLayers(); });
                document.getElementById('fire-stations-toggle').addEventListener('change', e => { isStationsVisible = e.target.checked; if (isStationsVisible) loadFireStations(); else fireStationsLayer.clearLayers(); });

                loadFireHydrants();
                loadFireStations();
            }, 250);
        });

        // --- CHAT LOGIC ---
        function sendMessage() {
            const input = document.getElementById('chat-input');
            const messageContainer = document.getElementById('chat-messages');
            const messageText = input.value.trim();
            if (messageText) {
                messageContainer.innerHTML += `<div class="mb-3 text-end"><div class="p-3 rounded mt-1 bg-primary-subtle d-inline-block">${messageText}</div></div>`;
                input.value = '';
                setTimeout(() => { const r = ["I've found 3 active fires...", "Current resources deployed...", "Weather conditions show high wind..."]; messageContainer.innerHTML += `<div class="mb-3 text-start"><small class="text-body-secondary">Artemis AI Assistant</small><div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">${r[Math.floor(Math.random()*r.length)]}</div></div>`; messageContainer.scrollTop = messageContainer.scrollHeight; }, 1000);
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        }
        document.getElementById('chat-input')?.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

        // --- COLLAPSIBLE ICON LOGIC ---
        document.querySelectorAll('.card-header button[data-bs-toggle="collapse"]').forEach(b => b.addEventListener('click', function() { const i = this.querySelector('.collapse-icon'); if (i) { i.classList.toggle('fa-chevron-down'); i.classList.toggle('fa-chevron-up'); } }));

        
        // --- NEW JAVASCRIPT FOR LIVE REPORT TAB (FULLY FUNCTIONAL) ---
        document.addEventListener('DOMContentLoaded', () => {
            const recordButton = document.getElementById('record-button');
            const recordIcon = recordButton.querySelector('i');
            const recordingStatus = document.getElementById('recording-status');
            const resultsContainer = document.getElementById('ai-analysis-results');
            const placeholder = document.getElementById('report-placeholder');
            const errorContainer = document.getElementById('report-error');

            let mediaRecorder;
            let audioChunks = [];
            let isRecording = false;

            const setupAudio = async () => {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        mediaRecorder = new MediaRecorder(stream);

                        mediaRecorder.addEventListener("dataavailable", event => {
                            audioChunks.push(event.data);
                        });

                        mediaRecorder.addEventListener("stop", async () => {
                            const audioBlob = new Blob(audioChunks, { type: mediaRecorder.mimeType });
                            audioChunks = []; // Clear chunks for next recording
                            await sendAudioToServer(audioBlob);
                        });

                    } catch (err) {
                        console.error("Error accessing microphone:", err);
                        showError("Microphone access denied. Please enable it in your browser settings.");
                        recordButton.disabled = true;
                    }
                } else {
                    showError("Audio recording not supported on this browser.");
                    recordButton.disabled = true;
                }
            };
            
            recordButton.addEventListener('click', () => {
                if (!mediaRecorder) return;

                if (!isRecording) {
                    // Start recording
                    mediaRecorder.start();
                    isRecording = true;
                    recordButton.classList.add('is-recording');
                    recordIcon.className = 'fas fa-stop';
                    recordingStatus.textContent = 'Listening... (Tap to stop)';
                    placeholder?.classList.add('d-none');
                    errorContainer.classList.add('d-none');
                    resultsContainer.innerHTML = ''; // Clear previous results
                } else {
                    // Stop recording
                    mediaRecorder.stop();
                    isRecording = false;
                    recordButton.classList.remove('is-recording');
                    recordIcon.className = 'fas fa-sync-alt fa-spin'; // Processing icon
                    recordingStatus.textContent = 'Analyzing Report...';
                    recordButton.disabled = true; // Prevent clicks during processing
                }
            });

            const sendAudioToServer = async (audioBlob) => {
    const formData = new FormData();
    formData.append('audio', audioBlob, 'report.webm');

    try {
        const response = await fetch('/api/process-report', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });

        if (!response.ok) {
            const errorData = await response.json();
            let errorMessage = errorData.error || `Server error: ${response.status}`;
            if (errorMessage.includes('FFmpeg')) {
                errorMessage = 'Audio conversion failed. Please contact support to verify FFmpeg setup.';
            } else if (errorMessage.includes('Speech-to-text')) {
                errorMessage = 'Speech recognition failed. Please check your microphone or try again later.';
            } else if (errorMessage.includes('Azure Language')) {
                errorMessage = 'Text analysis failed. Please try again or contact support.';
            }
            throw new Error(errorMessage);
        }

        const data = await response.json();
        displayResults(data);

    } catch (err) {
        console.error('Error processing report:', err);
        showError(`Failed to process report: ${err.message}`);
    } finally {
        recordIcon.className = 'fas fa-microphone';
        recordingStatus.textContent = 'Tap to Start Field Report';
        recordButton.disabled = false;
    }
};
            
            const displayResults = (data) => {
                let entityHtml = '';
                if(data.entities && data.entities.length > 0) {
                    data.entities.forEach(entity => {
                        let tagClass = 'entity-tag-other';
                        const category = entity.category.toLowerCase();
                        if (category.includes('location')) tagClass = 'entity-tag-location';
                        else if (category.includes('resource') || category.includes('equipment')) tagClass = 'entity-tag-resource';
                        else if (category.includes('hazard') || category.includes('skill')) tagClass = 'entity-tag-hazard';
                        entityHtml += `<span class="entity-tag ${tagClass}">${entity.text}</span> `;
                    });
                }
                
                let suggestionHtml = '';
                // Check for nested suggestion structure from OpenAI
                const suggestionsList = data.suggestions?.suggestions || data.suggestions;
                if(suggestionsList && Array.isArray(suggestionsList)) {
                     suggestionsList.forEach(s => {
                        suggestionHtml += `
                        <li class="suggestion-item px-3">
                            <i class="${s.icon || 'fas fa-lightbulb'} suggestion-icon"></i>
                            <div><strong>${s.suggestion || 'Suggestion text missing.'}</strong></div>
                        </li>`;
                    });
                }
            
                const resultsHtml = `
                    <div class="card ai-analysis-card mb-3">
                        <div class="card-header"><i class="fas fa-brain me-2"></i>AI Summary</div>
                        <div class="card-body">
                            <p class="card-text">${data.summary || 'No summary available.'}</p>
                        </div>
                    </div>
                    <div class="card ai-analysis-card mb-3">
                        <div class="card-header"><i class="fas fa-tags me-2"></i>Key Entities</div>
                        <div class="card-body">${entityHtml.trim() ? entityHtml : '<span class="text-muted">No specific entities detected.</span>'}</div>
                    </div>
                    <div class="card ai-analysis-card mb-3">
                        <div class="card-header"><i class="fas fa-tasks me-2"></i>AI-Suggested Actions</div>
                        <div class="card-body p-0">
                            <ul class="list-unstyled mb-0">${suggestionHtml.trim() ? suggestionHtml : '<li class="p-3 text-muted">No suggestions available.</li>'}</ul>
                        </div>
                    </div>
                    <div class="accordion" id="transcriptAccordion">
                        <div class="accordion-item bg-transparent border-secondary">
                          <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed bg-body-tertiary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                              <i class="fas fa-file-alt me-2"></i>View Full Transcript
                            </button>
                          </h2>
                          <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#transcriptAccordion">
                            <div class="accordion-body">${data.transcript || 'Transcript unavailable.'}</div>
                          </div>
                        </div>
                    </div>
                `;
                resultsContainer.innerHTML = resultsHtml;
            };

            const showError = (message) => {
                errorContainer.textContent = message;
                errorContainer.classList.remove('d-none');
                if(placeholder) placeholder.classList.add('d-none');
                resultsContainer.innerHTML = '';
            };

            // Initialize audio setup when the DOM is ready
            setupAudio();
        });
    </script>
</body>
</html>