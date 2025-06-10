<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        .layers-panel { top: 0; left: 0; }
        .weather-widget { top: 0; right: 0; }

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

        /* NEW: Styles for fire hydrant icons */
        .fire-hydrant-icon {
            background-color: transparent;
            border: none;
            text-align: center;
        }
        .fire-hydrant-icon i {
            color: #007bff; /* A distinct blue for hydrants */
            font-size: 20px; /* Slightly smaller than fire icon */
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
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
                            <div class="card-body p-3">
                                <h6 class="card-title d-flex align-items-center mb-2"><i class="fas fa-layer-group fa-fw me-2"></i>Map Layers</h6>
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
                                {{-- NEW: Fire Hydrants Layer Toggle --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="fire-hydrants-toggle" checked>
                                    <label class="form-check-label" for="fire-hydrants-toggle">Fire Hydrants</label>
                                </div>
                            </div>
                        </div>

                        <div class="map-overlay weather-widget card shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-title d-flex align-items-center mb-2"><i class="fas fa-cloud-sun fa-fw me-2"></i>Local Weather</h6>
                                <hr class="my-2">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">Temperature <span class="badge text-bg-primary" id="temp">28°C</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">Wind <span class="badge text-bg-primary" id="wind">15 km/h</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">Humidity <span class="badge text-bg-primary" id="humidity">45%</span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">Fire Risk <span class="badge text-bg-danger" id="fire-risk">High</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-5 border-start">
                    <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills nav-fill" id="sidebar-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="chat-tab-btn" data-bs-toggle="pill" data-bs-target="#chat-content" type="button" role="tab" aria-controls="chat-content" aria-selected="true">
                                        <i class="fas fa-comments me-1"></i> Ask Artemis
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="control-tab-btn" data-bs-toggle="pill" data-bs-target="#control-content" type="button" role="tab" aria-controls="control-content" aria-selected="false">
                                        <i class="fas fa-cogs me-1"></i> Control Panel
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body d-flex flex-column p-0">
                            <div class="tab-content">
                                <div class="tab-pane fade show active p-3" id="chat-content" role="tabpanel">
                                    <div class="chat-container">
                                        <div class="chat-messages mb-3" id="chat-messages">
                                            <div class="mb-3 text-start">
                                                <small class="text-body-secondary">Artemis AI Assistant</small>
                                                <div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">
                                                    Hello! I'm Artemis. Ask me about active fires, resource status, or weather conditions.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-input-group d-flex gap-2">
                                            <input type="text" class="form-control" placeholder="Ask a question..." id="chat-input">
                                            <button class="btn btn-primary" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade p-3" id="control-content" role="tabpanel">
                                    <h6 class="text-body-secondary">Quick Actions</h6>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6"><button class="btn btn-primary w-100"><i class="fas fa-rocket me-2"></i>Deploy</button></div>
                                        <div class="col-6"><button class="btn btn-danger w-100"><i class="fas fa-bullhorn me-2"></i>Evacuate</button></div>
                                        <div class="col-6"><button class="btn btn-success w-100"><i class="fas fa-file-alt me-2"></i>Report</button></div>
                                        <div class="col-6"><button class="btn btn-warning w-100"><i class="fas fa-hands-helping me-2"></i>Request Aid</button></div>
                                    </div>
                                    
                                    <h6 class="text-body-secondary">Live Data</h6>
                                    <ul class="list-group mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Active Fires <span class="badge text-bg-danger" id="active-fires-count">12</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Resources Deployed <span class="badge text-bg-info" id="resources-count">45</span></li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">Properties at Risk <span class="badge text-bg-warning" id="properties-count">234</span></li>
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
        // Global map instance (make it accessible)
let map;
// Layer group for fire hydrants
let fireHydrantsLayer;
let isHydrantsVisible = true; // Default visibility

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        // Initialize map centered on Chicago
        map = L.map('map').setView([41.8781, -87.6298], 12); // Centered on Chicago, zoom level 12

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Custom fire incident icon (unchanged from your original)
        const fireIcon = L.divIcon({
            className: 'fire-marker',
            html: '<i class="fas fa-fire" style="color: #FF4136; font-size: 24px;"></i>',
            iconSize: [24, 24],
            iconAnchor: [12, 24]
        });

        // Example: Add a static fire marker (can be removed once dynamic data is working)
        // L.marker([34.15, -118.30], { icon: fireIcon }).addTo(map)
        //     .bindPopup('<b>Griffith Park Fire</b><br>Active Incident.');

        // --- Fire Hydrant Icon Definition ---
        const fireHydrantIcon = L.divIcon({
            className: 'fire-hydrant-icon',
            html: '<i class="fas fa-faucet"></i>',
            iconSize: [20, 20],
            iconAnchor: [10, 20]
        });

        // --- Initialize Fire Hydrants GeoJSON Layer ---
        fireHydrantsLayer = L.geoJson(null, {
            pointToLayer: function (feature, latlng) {
                return L.marker(latlng, { icon: fireHydrantIcon });
            },
            onEachFeature: function (feature, layer) {
                if (feature.properties) {
                    let popupContent = `<strong>Fire Hydrant (OSM ID:</strong> ${feature.properties.osm_id})<br>`;
                    if (feature.properties.fire_hydrant_type) {
                        popupContent += `<strong>Type:</strong> ${feature.properties.fire_hydrant_type}<br>`;
                    }
                    if (feature.properties.color || feature.properties.colour) {
                        popupContent += `<strong>Color:</strong> ${feature.properties.color || feature.properties.colour}<br>`;
                    }
                    if (feature.properties.operator) {
                        popupContent += `<strong>Operator:</strong> ${feature.properties.operator}<br>`;
                    }
                    if (feature.properties.addr_street) {
                        popupContent += `<strong>Address:</strong> ${feature.properties.addr_housenumber || ''} ${feature.properties.addr_street}<br>`;
                    }
                    if (feature.properties.addr_city) {
                        popupContent += `<strong>City:</strong> ${feature.properties.addr_city}, ${feature.properties.addr_state}<br>`;
                    }
                    if (feature.properties.fire_hydrant_position) {
                        popupContent += `<strong>Position:</strong> ${feature.properties.fire_hydrant_position}<br>`;
                    }
                    // Loop through `all_tags` for additional details if needed, but the explicit properties are better
                    // for common attributes.
                    if (feature.properties.all_tags) {
                        // Example: Add only relevant tags if you don't want all of them
                        if (feature.properties.all_tags['fire_hydrant:pressure']) {
                            popupContent += `<strong>Pressure:</strong> ${feature.properties.all_tags['fire_hydrant:pressure']}<br>`;
                        }
                        if (feature.properties.all_tags['water_source']) {
                            popupContent += `<strong>Water Source:</strong> ${feature.properties.all_tags['water_source']}<br>`;
                        }
                    }
                    layer.bindPopup(popupContent);
                }
            }
        }).addTo(map);

        // --- Corrected Function to Load Fire Hydrants ---
        const loadFireHydrants = async () => {
            if (!isHydrantsVisible) {
                fireHydrantsLayer.clearLayers();
                return;
            }

            try {
                // This now hits your Laravel backend API endpoint
                const response = await fetch(`/api/fire_hydrants`); 
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                fireHydrantsLayer.clearLayers(); // Clear existing markers
                fireHydrantsLayer.addData(data); // Add new data to the layer
                console.log(`Loaded ${data.features.length} fire hydrants.`);

            } catch (error) {
                console.error("Could not fetch fire hydrants:", error);
            }
        };

        // --- Event Listener for Layer Toggle ---
        document.getElementById('fire-hydrants-toggle').addEventListener('change', function(e) {
            isHydrantsVisible = e.target.checked;
            if (isHydrantsVisible) {
                loadFireHydrants(); // Reload if turning on
            } else {
                fireHydrantsLayer.clearLayers(); // Clear if turning off
            }
        });

        // --- Initial load of fire hydrants on map load ---
        loadFireHydrants();

    }, 250); // Small delay for map rendering
});

// Your existing sendMessage function and other chat/control panel logic remains the same
function sendMessage() {
    const input = document.getElementById('chat-input');
    const messageContainer = document.getElementById('chat-messages');
    const messageText = input.value.trim();

    if (messageText) {
        messageContainer.innerHTML += `
            <div class="mb-3 text-end">
                <div class="p-3 rounded mt-1 bg-primary-subtle d-inline-block">
                    ${messageText}
                </div>
            </div>`;
        input.value = '';

        setTimeout(() => {
            const responses = [
                "I've found 3 active fires in the northern region. The largest is the Canyon Fire with 45% containment.",
                "Current resources deployed: 12 fire engines, 3 helicopters, and 45 firefighters across all active incidents.",
                "Weather conditions show high wind speeds from the northwest, which may affect fire spread patterns.",
                "I've identified 5 properties at high risk in the evacuation zone. Emergency services have been notified."
            ];
            const randomResponse = responses[Math.floor(Math.random() * responses.length)];
            
            messageContainer.innerHTML += `
                <div class="mb-3 text-start">
                    <small class="text-body-secondary">Artemis AI Assistant</small>
                    <div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">
                        ${randomResponse}
                    </div>
                </div>`;
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }, 1000);

        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
}

document.getElementById('chat-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
    </script>
</body>
</html>