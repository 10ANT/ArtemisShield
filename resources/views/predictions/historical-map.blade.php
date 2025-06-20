<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Artemis - Historical Fire Map</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    @include('partials.styles')
    <style>
        .main-content { flex-grow: 1; }
        .dashboard-container { height: 100%; }
        #map { width: 100%; height: 100%; min-height: 500px; background-color: var(--bs-tertiary-bg); }
        .map-wrapper { position: relative; height: 100%; overflow: hidden; }
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
        .sidebar-wrapper .tab-content { flex-grow: 1; overflow-y: auto; }
        
        #map-loader {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            z-index: 1001; background: rgba(var(--bs-dark-rgb), 0.8); color: white;
            padding: 20px 30px; border-radius: 8px; text-align: center; font-size: 1.2rem;
            display: none;
        }
        #map-loader.is-loading { display: block; }

        .leaflet-popup-content-wrapper, .leaflet-popup-tip {
            background: var(--bs-tertiary-bg) !important; color: var(--bs-body-color) !important;
            box-shadow: 0 3px 14px rgba(0,0,0,0.4);
        }
        .leaflet-draw-toolbar a { background-color: white !important; color: #333 !important; }

        .legend {
            padding: 6px 10px; font-size: 14px; background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; line-height: 24px; color: #555;
        }
        .legend i {
            width: 18px; height: 18px; float: left; margin-right: 8px; opacity: 0.9;
            border-radius: 50%; border: 2px solid #333;
        }

        .marker-cluster-custom {
            color: #fff;
            border-radius: 50%;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 0 5px rgba(0,0,0,0.5);
            border: 2px solid rgba(255, 255, 255, 0.7);
        }
        .marker-cluster-custom div {
            width: 30px;
            height: 30px;
            margin-left: 5px;
            margin-top: 5px;
            border-radius: 50%;
            line-height: 30px;
        }
        .marker-cluster-small { background-color: rgba(241, 211, 87, 0.8); }
        .marker-cluster-medium { background-color: rgba(253, 156, 115, 0.8); }
        .marker-cluster-large { background-color: rgba(241, 128, 23, 0.9); }
        .marker-cluster-xlarge { background-color: rgba(204, 75, 75, 0.9); }

        .modal-body .row { margin-bottom: 0.5rem; }
        .modal-body .row .col-md-4 { font-weight: bold; color: var(--bs-secondary-color); }
        .modal-body .row .col-md-8 { word-break: break-all; }

        /* START: TABLET/MOBILE SIDEBAR RESPONSIVENESS FIX */
        @media (max-width: 1199.98px) {
            .sidebar-area {
                position: static !important;
                width: 100% !important;
                transform: none !important;
                left: auto !important;
                top: auto !important;
                z-index: auto !important;
                transition: max-height 0.35s ease-in-out, padding 0.35s ease-in-out, border-width 0.35s ease-in-out;
                background-color: var(--bs-body-bg);
            }
            
            body.sidebar-close .sidebar-area {
                max-height: 0;
                overflow: hidden;
                padding-top: 0;
                padding-bottom: 0;
                border-width: 0;
            }

            body:not(.sidebar-close) .sidebar-area {
                max-height: 75vh;
                overflow-y: auto;
                border-bottom: 1px solid var(--bs-border-color);
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                transition: none !important;
            }

            .body-overlay {
                display: none !important;
            }

            #sidebar-area .sidebar-burger-menu {
                display: none !important;
            }

            .main-content > .header-area {
                position: sticky;
                top: 0;
                z-index: 1025; 
            }

            .dashboard-container {
                flex-direction: column;
            }
            .dashboard-container .col-lg-4.border-start {
                border-left: 0 !important;
                border-top: 1px solid var(--bs-border-color) !important;
            }
        }
        /* END: TABLET/MOBILE SIDEBAR RESPONSIVENESS FIX */
    </style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
<div class="main-content d-flex flex-column">
    @include('partials.header')
    <div class="dashboard-container row g-0 flex-grow-1">
        <div class="col-lg-8 col-md-12">
            <div class="map-wrapper">
                <div id="map"></div>
                <div id="map-loader">
                    <div class="spinner-border text-light mb-3" role="status"></div>
                    <p>Fetching fire data...</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 border-start">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-fill flex-nowrap" id="sidebar-tabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#filters-content" type="button"><i class="fas fa-filter me-1"></i> Filters</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#settings-content" type="button"><i class="fas fa-cogs me-1"></i> Settings</button></li>
                    </ul>
                </div>
                <div class="card-body d-flex flex-column p-0">
                    <div class="tab-content h-100"><div class="tab-pane fade show active p-3" id="filters-content" role="tabpanel"><h5 class="mb-3">Filter Historical Data</h5><div class="mb-3"><label for="start-date" class="form-label">Start Date</label><input type="date" class="form-control" id="start-date"></div><div class="mb-3"><label for="end-date" class="form-label">End Date</label><input type="date" class="form-control" id="end-date"></div><div class="mb-3"><label class="form-label">Filter by Location</label><div class="alert alert-info py-2"><small>Use the <i class="fas fa-draw-polygon"></i> draw tools on the map to select a specific area, then click "Apply Filters".</small></div></div><div class="d-grid gap-2"><button class="btn btn-primary" id="apply-filters-btn"><i class="fas fa-check me-2"></i>Apply Filters</button><button class="btn btn-outline-secondary" id="reset-filters-btn"><i class="fas fa-times me-2"></i>Reset Filters</button></div></div><div class="tab-pane fade p-3" id="settings-content" role="tabpanel"><h5 class="mb-3"><i class="fas fa-map me-2"></i>Base Map Style</h5><div id="basemap-selector-container"></div></div></div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</div>

<div class="modal fade" id="fire-details-modal" tabindex="-1" aria-labelledby="fireDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fireDetailsModalLabel">Full Fire Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Content will be injected here by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('partials.theme_settings')
@include('partials.scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let map;
    let baseMaps;
    let fireMarkers = null;
    let filterAreaLayer = L.featureGroup();
    let drawControl;
    let fullFireData = [];
    let fireDetailsModal;

    const loader = document.getElementById('map-loader');
    const apiBaseUrl = "{{ route('api.historical.fires') }}";

    const initMap = () => {
        const streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
        const satelliteMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { attribution: '© Esri' });
        const darkMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '© CARTO' });
        
        baseMaps = { "Streets": streetMap, "Satellite": satelliteMap, "Dark Mode": darkMap };
        map = L.map('map', { center: [39.82, -98.57], zoom: 4, layers: [streetMap] });
        map.addLayer(filterAreaLayer);
        drawControl = new L.Control.Draw({ edit: false, draw: { polygon: { shapeOptions: { color: '#0dcaf0' } }, rectangle: { shapeOptions: { color: '#0dcaf0' } }, circle: { shapeOptions: { color: '#0dcaf0' } }, marker: false, circlemarker: false, polyline: false } });
        map.addControl(drawControl);
        map.on(L.Draw.Event.CREATED, (e) => { filterAreaLayer.clearLayers(); filterAreaLayer.addLayer(e.layer); });
        addLegend();
        setupUI();
        
        fireDetailsModal = new bootstrap.Modal(document.getElementById('fire-details-modal'));

        fetchHistoricalFires();
    };
    
    const addLegend = () => {
        const legend = L.control({position: 'bottomright'});
        legend.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'legend');
            div.innerHTML += '<i style="background: #FF4500;"></i> Historical Fire Location';
            return div;
        };
        legend.addTo(map);
    };

    const setupUI = () => {
        const basemapContainer = document.getElementById('basemap-selector-container');
        Object.keys(baseMaps).forEach(name => {
            const id = `basemap-radio-${name.replace(/\s+/g, '-')}`;
            basemapContainer.innerHTML += `<div class="form-check form-switch mb-2"><input class="form-check-input" type="radio" name="basemap-selector" id="${id}" value="${name}" ${name === "Streets" ? 'checked' : ''}><label class="form-check-label" for="${id}">${name}</label></div>`;
        });
        basemapContainer.addEventListener('change', (e) => { Object.values(baseMaps).forEach(layer => map.removeLayer(layer)); map.addLayer(baseMaps[e.target.value]); });
        document.getElementById('apply-filters-btn').addEventListener('click', applyFilters);
        document.getElementById('reset-filters-btn').addEventListener('click', resetFilters);
    };

    window.showFireDetails = function(fireId) {
        const fireData = fullFireData.find(f => f.id === fireId);
        if (!fireData) {
            console.error('Fire data not found for ID:', fireId);
            return;
        }

        let modalContent = '';
        const fields = [
            { label: 'Latitude', key: 'latitude' }, { label: 'Longitude', key: 'longitude' },
            { label: 'Brightness (Kelvin)', key: 'brightness' }, { label: 'Acquisition Date', key: 'acq_date' },
            { label: 'Acquisition Time', key: 'acq_time' }, { label: 'Satellite', key: 'satellite' },
            { label: 'Instrument', key: 'instrument' }, { label: 'Confidence', key: 'confidence' },
            { label: 'Data Version', key: 'version' }, { label: 'Fire Radiative Power (MW)', key: 'frp' },
            { label: 'Brightness Temp (T31)', key: 'bright_t31' }, { label: 'Scan', key: 'scan' },
            { label: 'Track', key: 'track' }, { label: 'Day/Night', key: 'daynight' }, { label: 'Type', key: 'type' }
        ];

        fields.forEach(field => {
            let value = fireData[field.key] !== null ? fireData[field.key] : 'N/A';
            if(field.key === 'acq_time' && value !== 'N/A') value = String(value).padStart(4, '0').replace(/(\d{2})(\d{2})/, '$1:$2');
            if(field.key === 'confidence') value = `${value}%`;
            
            modalContent += `
                <div class="row">
                    <div class="col-md-4">${field.label}</div>
                    <div class="col-md-8">${value}</div>
                </div>`;
        });

        document.getElementById('modal-body-content').innerHTML = modalContent;
        fireDetailsModal.show();
    };

    const applyFilters = () => {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;
        const params = new URLSearchParams();
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);
        const drawnLayers = filterAreaLayer.getLayers();
        if (drawnLayers.length > 0) { const bounds = drawnLayers[0].getBounds(); params.append('bbox', bounds.toBBoxString()); }
        fetchHistoricalFires(params.toString());
    };

    const resetFilters = () => {
        document.getElementById('start-date').value = '';
        document.getElementById('end-date').value = '';
        filterAreaLayer.clearLayers();
        fetchHistoricalFires();
    };

    const fetchHistoricalFires = async (queryParams = '') => {
        loader.classList.add('is-loading');
        try {
            const response = await fetch(`${apiBaseUrl}?${queryParams}`);
            if (!response.ok) throw new Error(`Server responded with status: ${response.status}`);
            const fires = await response.json();
            fullFireData = fires;
            plotFires(fires);
        } catch (error) {
            console.error('Failed to fetch historical fire data:', error);
            alert(`Error loading fire data. Please check the console.`);
        } finally {
            loader.classList.remove('is-loading');
        }
    };

    const plotFires = (fires) => {
        if (fireMarkers) map.removeLayer(fireMarkers);
        fireMarkers = L.markerClusterGroup({
            chunkedLoading: true, maxClusterRadius: 80,
            iconCreateFunction: function(cluster) {
                const count = cluster.getChildCount();
                let c = 'marker-cluster-';
                if (count < 100) c += 'small';
                else if (count < 500) c += 'medium';
                else if (count < 1000) c += 'large';
                else c += 'xlarge';
                return new L.DivIcon({ html: `<div><span>${count}</span></div>`, className: `marker-cluster-custom ${c}`, iconSize: new L.Point(40, 40) });
            }
        });

        fires.forEach(fire => {
            const time = String(fire.acq_time).padStart(4, '0');
            const formattedTime = time.substring(0, 2) + ':' + time.substring(2, 4);

            const popupContent = `
                <div style="font-size: 14px;">
                    <strong>Date:</strong> ${fire.acq_date}<br>
                    <strong>Time:</strong> ${formattedTime} UTC<br>
                    <strong>Confidence:</strong> ${fire.confidence}%<br>
                    <hr class="my-2">
                    <button class="btn btn-primary btn-sm w-100" onclick="showFireDetails(${fire.id})">
                        <i class="fas fa-search-plus me-1"></i> View Full Details
                    </button>
                </div>`;
            
            const marker = L.circleMarker([fire.latitude, fire.longitude], {
                radius: 7, color: '#000000', weight: 1.5,
                fillColor: '#FF4500', fillOpacity: 0.8
            }).bindPopup(popupContent);

            fireMarkers.addLayer(marker);
        });
        map.addLayer(fireMarkers);
    };

    const initMobileSidebarToggle = () => {
        const burgerMenu = document.querySelector('.header-burger-menu');
        const body = document.body;

        if (burgerMenu && body) {
            // Updated Breakpoint: from 992 to 1200
            if (window.innerWidth < 1200 && !body.classList.contains('sidebar-close')) {
                body.classList.add('sidebar-close');
            }

            burgerMenu.addEventListener('click', function(event) {
                // Updated Breakpoint: from 992 to 1200
                if (window.innerWidth < 1200) {
                    event.preventDefault();
                    event.stopPropagation();
                    body.classList.toggle('sidebar-close');
                }
            }, true);
        }
    };

    initMap();
    initMobileSidebarToggle();
});
</script>
</body>
</html>