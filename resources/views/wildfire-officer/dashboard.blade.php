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

    @include('partials.styles')
    <style>
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

        .loading-spinner {
            display: none;
        }

        .loading .loading-spinner {
            display: inline-block;
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
                                <h6 class="card-title d-flex align-items-center mb-2">
                                    <i class="fas fa-layer-group fa-fw me-2"></i>Fire Data Layers
                                    <div class="loading-spinner spinner-border spinner-border-sm ms-auto" role="status"></div>
                                </h6>
                                <hr class="my-2">
                                
                                <div class="form-check form-switch">
                                    <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="viirs-snpp" data-source="VIIRS_SNPP_NRT" checked>
                                    <label class="form-check-label" for="viirs-snpp">VIIRS S-NPP (Real-Time)</label>
                                </div>
                                
                                <div class="form-check form-switch">
                                    <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="viirs-noaa20" data-source="VIIRS_NOAA20_NRT">
                                    <label class="form-check-label" for="viirs-noaa20">VIIRS NOAA-20 (Real-Time)</label>
                                </div>
                                
                                <div class="form-check form-switch">
                                    <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="modis-nrt" data-source="MODIS_NRT">
                                    <label class="form-check-label" for="modis-nrt">MODIS (Real-Time)</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input layer-toggle" type="checkbox" role="switch" id="modis-sp" data-source="MODIS_SP">
                                    <label class="form-check-label" for="modis-sp">MODIS Standard</label>
                                </div>

                                <hr class="my-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="fire-count">Loading fire data...</span>
                                </small>
                            </div>
                        </div>

                        <div class="map-overlay weather-widget card shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-title d-flex align-items-center mb-2">
                                    <i class="fas fa-cloud-sun fa-fw me-2"></i>Local Weather
                                    <div class="loading-spinner spinner-border spinner-border-sm ms-auto" role="status"></div>
                                </h6>
                                <hr class="my-2">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                        Temperature 
                                        <span class="badge text-bg-primary" id="temp">--°C</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                        Wind 
                                        <span class="badge text-bg-primary" id="wind">-- km/h</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                        Humidity 
                                        <span class="badge text-bg-primary" id="humidity">--%</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                        Fire Risk 
                                        <span class="badge text-bg-danger" id="fire-risk">--</span>
                                    </li>
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
                                    <button class="nav-link active" id="chat-tab-btn" data-bs-toggle="pill" data-bs-target="#chat-content" type="button" role="tab">
                                        <i class="fas fa-comments me-1"></i> Ask Artemis
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="control-tab-btn" data-bs-toggle="pill" data-bs-target="#control-content" type="button" role="tab">
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
                                                    Hello! I'm monitoring <span id="active-fires-chat">loading...</span> active fire detections using VIIRS real-time data. Ask me about fire locations, weather conditions, or resource deployment.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-input-group d-flex gap-2">
                                            <input type="text" class="form-control" placeholder="Ask about fire conditions..." id="chat-input">
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
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Active Fires 
                                            <span class="badge text-bg-danger" id="active-fires-count">--</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            High Confidence 
                                            <span class="badge text-bg-warning" id="high-confidence-count">--</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Last Updated 
                                            <span class="badge text-bg-info" id="last-updated">--</span>
                                        </li>
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

    @include('partials.theme_settings')
    @include('partials.scripts')
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        // Global variables
        let map;
        let fireLayerGroups = {};
        let weatherInterval;
        let fireDataInterval;
        let currentLat = 34.0522; // Default LA coordinates
        let currentLon = -118.2437;

        // API Configuration
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                initializeMap();
                loadInitialData();
                setupEventListeners();
                startPeriodicUpdates();
            }, 250);
        });

        function initializeMap() {
            map = L.map('map').setView([currentLat, currentLon], 6);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                maxZoom: 19
            }).addTo(map);

            // Initialize layer groups
            fireLayerGroups = {
                'VIIRS_SNPP_NRT': L.layerGroup().addTo(map),
                'VIIRS_NOAA20_NRT': L.layerGroup(),
                'MODIS_NRT': L.layerGroup(),
                'MODIS_SP': L.layerGroup()
            };

            // Update coordinates when map is moved
            map.on('moveend', function() {
                const center = map.getCenter();
                currentLat = center.lat;
                currentLon = center.lng;
                loadWeatherData();
            });
        }

        function setupEventListeners() {
            // Layer toggle switches
            document.querySelectorAll('.layer-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const source = this.dataset.source;
                    const layerGroup = fireLayerGroups[source];
                    
                    if (this.checked) {
                        if (!map.hasLayer(layerGroup)) {
                            map.addLayer(layerGroup);
                            loadFireData(source);
                        }
                    } else {
                        if (map.hasLayer(layerGroup)) {
                            map.removeLayer(layerGroup);
                        }
                    }
                });
            });

            // Chat input
            document.getElementById('chat-input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        }

        function loadInitialData() {
            loadFireData('VIIRS_SNPP_NRT'); // Load default layer (changed from MODIS_SP)
            loadWeatherData();
        }

        function startPeriodicUpdates() {
            // Update fire data every 5 minutes
            fireDataInterval = setInterval(() => {
                document.querySelectorAll('.layer-toggle:checked').forEach(toggle => {
                    loadFireData(toggle.dataset.source);
                });
            }, 300000);

            // Update weather every 5 minutes
            weatherInterval = setInterval(loadWeatherData, 300000);
        }

        async function loadFireData(source) {
            const layersPanel = document.querySelector('.layers-panel');
            layersPanel.classList.add('loading');

            try {
                // Get yesterday's date
                const yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                const dateStr = yesterday.toISOString().split('T')[0];

                const response = await axios.get('/api/v1/fire-data', {
                    params: {
                        source: source,
                        area: 'world',
                        day_range: 1,
                        date: dateStr
                    }
                });

                if (response.data.success) {
                    updateFireLayer(source, response.data.data);
                    updateFireStats(response.data.data);
                    updateRecentFires(response.data.data.slice(0, 5)); // Show 5 most recent
                    console.log(`Loaded ${response.data.count} fires from ${source} for ${dateStr}`);
                } else {
                    console.error('API returned error:', response.data.error);
                }
            } catch (error) {
                console.error('Failed to load fire data:', error);
                if (error.response) {
                    console.error('Response data:', error.response.data);
                }
            } finally {
                layersPanel.classList.remove('loading');
            }
        }

        function updateFireLayer(source, fires) {
            const layerGroup = fireLayerGroups[source];
            layerGroup.clearLayers();

            fires.forEach(fire => {
                const marker = createFireMarker(fire);
                layerGroup.addLayer(marker);
            });
        }

        function createFireMarker(fire) {
            const icon = L.divIcon({
                className: 'fire-marker',
                html: `<i class="fas fa-fire" style="color: ${fire.intensity_color}; font-size: 16px;"></i>`,
                iconSize: [16, 16],
                iconAnchor: [8, 16]
            });

            const marker = L.marker([fire.latitude, fire.longitude], { icon: icon });
            
            const popupContent = `
                <div class="fire-popup">
                    <h6><i class="fas fa-fire me-1"></i> Fire Detection</h6>
                    <hr class="my-2">
                    <p class="mb-1"><strong>Satellite:</strong> ${fire.satellite}</p>
                    <p class="mb-1"><strong>Confidence:</strong> <span class="badge text-bg-${fire.confidence_level === 'high' ? 'success' : fire.confidence_level === 'medium' ? 'warning' : 'secondary'}">${fire.confidence}${typeof fire.confidence === 'number' ? '%' : ''}</span></p>
                    <p class="mb-1"><strong>Brightness:</strong> ${fire.brightness}K</p>
                    <p class="mb-1"><strong>FRP:</strong> ${fire.frp} MW</p>
                    <p class="mb-1"><strong>Detected:</strong> ${fire.acq_date} ${String(fire.acq_time).padStart(4, '0').replace(/(\d{2})(\d{2})/, '$1:$2')}</p>
                    <p class="mb-0"><strong>Source:</strong> ${fire.source}</p>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            return marker;
        }

        async function loadWeatherData() {
            const weatherWidget = document.querySelector('.weather-widget');
            weatherWidget.classList.add('loading');

            try {
                const response = await axios.get('/api/v1/weather-data', {
                    params: {
                        lat: currentLat,
                        lon: currentLon
                    }
                });

                if (response.data.success) {
                    const weather = response.data.data;
                    document.getElementById('temp').textContent = `${weather.temperature}°C`;
                    document.getElementById('wind').textContent = `${weather.wind_speed} km/h`;
                    document.getElementById('humidity').textContent = `${weather.humidity}%`;
                    
                    const fireRiskBadge = document.getElementById('fire-risk');
                    fireRiskBadge.textContent = weather.fire_risk;
                    
                    // Update fire risk badge color
                    fireRiskBadge.className = 'badge ' + getFireRiskBadgeClass(weather.fire_risk);
                }
            } catch (error) {
                console.error('Failed to load weather data:', error);
            } finally {
                weatherWidget.classList.remove('loading');
            }
        }

        function getFireRiskBadgeClass(risk) {
            switch(risk.toLowerCase()) {
                case 'extreme': return 'text-bg-danger';
                case 'high': return 'text-bg-warning';
                case 'medium': return 'text-bg-info';
                default: return 'text-bg-success';
            }
        }

        function updateFireStats(fires) {
            const totalFires = fires.length;
            const highConfidenceFires = fires.filter(f => 
                (typeof f.confidence === 'number' && f.confidence >= 80) || 
                (typeof f.confidence === 'string' && f.confidence.toLowerCase() === 'high')
            ).length;
            
            document.getElementById('fire-count').textContent = `${totalFires} active detections`;
            document.getElementById('active-fires-count').textContent = totalFires;
            document.getElementById('high-confidence-count').textContent = highConfidenceFires;
            document.getElementById('active-fires-chat').textContent = `${totalFires}`;
            document.getElementById('last-updated').textContent = new Date().toLocaleTimeString();
       }

       function updateRecentFires(recentFires) {
           const container = document.getElementById('recent-fires');
           container.innerHTML = '';

           recentFires.forEach(fire => {
               const fireCard = document.createElement('div');
               fireCard.className = 'card bg-body-tertiary mb-2';
               fireCard.innerHTML = `
                   <div class="card-body p-2">
                       <div class="d-flex justify-content-between align-items-start">
                           <div>
                               <h6 class="card-title mb-1">${fire.satellite} Detection</h6>
                               <p class="card-text mb-1 small">
                                   <i class="fas fa-map-marker-alt me-1"></i>
                                   ${fire.latitude.toFixed(4)}, ${fire.longitude.toFixed(4)}
                               </p>
                               <small class="text-body-secondary">${fire.acq_date} ${String(fire.acq_time).padStart(4, '0').replace(/(\d{2})(\d{2})/, '$1:$2')}</small>
                           </div>
                           <span class="badge text-bg-${fire.confidence_level === 'high' ? 'success' : fire.confidence_level === 'medium' ? 'warning' : 'secondary'}">${fire.confidence}${typeof fire.confidence === 'number' ? '%' : ''}</span>
                       </div>
                   </div>
               `;
               
               // Add click event to zoom to fire location
               fireCard.addEventListener('click', () => {
                   map.setView([fire.latitude, fire.longitude], 12);
               });
               
               container.appendChild(fireCard);
           });
       }

       function sendMessage() {
           const input = document.getElementById('chat-input');
           const messageContainer = document.getElementById('chat-messages');
           const messageText = input.value.trim();

           if (messageText) {
               // Add user message
               messageContainer.innerHTML += `
                   <div class="mb-3 text-end">
                       <div class="p-3 rounded mt-1 bg-primary-subtle d-inline-block">
                           ${messageText}
                       </div>
                   </div>`;
               input.value = '';

               // Get current fire data for context-aware responses
               const activeFires = document.getElementById('active-fires-count').textContent;
               const fireRisk = document.getElementById('fire-risk').textContent;
               const temp = document.getElementById('temp').textContent;
               const wind = document.getElementById('wind').textContent;

               // Simulate AI response with real data
               setTimeout(() => {
                   let response = generateContextualResponse(messageText.toLowerCase(), {
                       activeFires,
                       fireRisk,
                       temp,
                       wind
                   });
                   
                   messageContainer.innerHTML += `
                       <div class="mb-3 text-start">
                           <small class="text-body-secondary">Artemis AI Assistant</small>
                           <div class="p-3 rounded mt-1 bg-body-secondary d-inline-block">
                               ${response}
                           </div>
                       </div>`;
                   messageContainer.scrollTop = messageContainer.scrollHeight;
               }, 1000);

               messageContainer.scrollTop = messageContainer.scrollHeight;
           }
       }

       function generateContextualResponse(message, data) {
           if (message.includes('fire') || message.includes('hotspot') || message.includes('viirs')) {
               return `Currently monitoring ${data.activeFires} active fire detections using VIIRS real-time data. The largest concentrations are visible on the map with high-confidence detections marked in red. VIIRS provides more frequent updates than MODIS.`;
           }
           
           if (message.includes('weather') || message.includes('wind') || message.includes('temperature')) {
               return `Current conditions: ${data.temp}, wind at ${data.wind}, fire risk level is ${data.fireRisk}. ${data.fireRisk === 'High' || data.fireRisk === 'Extreme' ? 'Recommend increased monitoring due to elevated fire risk.' : 'Conditions are relatively stable for fire management operations.'}`;
           }
           
           if (message.includes('risk') || message.includes('danger')) {
               return `Fire risk assessment: ${data.fireRisk}. Based on current temperature (${data.temp}), wind conditions (${data.wind}), and ${data.activeFires} active detections from VIIRS satellites, ${data.fireRisk === 'High' ? 'enhanced precautions recommended' : 'standard monitoring protocols apply'}.`;
           }
           
           if (message.includes('resource') || message.includes('deploy')) {
               return `Resource deployment analysis: With ${data.activeFires} active fires detected by VIIRS and ${data.fireRisk} risk conditions, I recommend prioritizing high-confidence detections shown in red markers. Consider wind direction (${data.wind}) for tactical positioning.`;
           }
           
           if (message.includes('evacuation') || message.includes('evacuate')) {
               return `Evacuation assessment: VIIRS satellites are monitoring ${data.activeFires} fire detections with real-time updates. Current fire risk is ${data.fireRisk}. I recommend establishing evacuation routes away from high-confidence fire clusters (red markers on map).`;
           }
           
           if (message.includes('satellite') || message.includes('data') || message.includes('source')) {
               return `Using VIIRS (Visible Infrared Imaging Radiometer Suite) real-time data from S-NPP and NOAA-20 satellites. VIIRS provides higher resolution and more frequent fire detection updates compared to MODIS. Currently tracking ${data.activeFires} active detections.`;
           }
           
           // Default responses
           const responses = [
               `VIIRS real-time monitoring active: ${data.activeFires} fire detections, current fire risk at ${data.fireRisk} level. What specific information do you need?`,
               `Current fire situation from VIIRS satellites: ${data.activeFires} detections, weather conditions show ${data.temp} and ${data.wind} winds. How can I assist with your operations?`,
               `Real-time fire monitoring: ${data.activeFires} hotspots detected by VIIRS, risk level ${data.fireRisk}. I can provide details on any specific fire location or weather conditions.`,
               `Operational status: ${data.activeFires} active detections from VIIRS real-time feed, environmental conditions: ${data.temp}, wind ${data.wind}, risk ${data.fireRisk}. What analysis do you need?`
           ];
           
           return responses[Math.floor(Math.random() * responses.length)];
       }

       // Cleanup intervals when page unloads
       window.addEventListener('beforeunload', function() {
           if (fireDataInterval) clearInterval(fireDataInterval);
           if (weatherInterval) clearInterval(weatherInterval);
       });
   </script>
</body>
</html>