<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>First Responder Dashboard - ArtemisShield</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @include('partials.styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        .main-content {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .dashboard-layout {
            display: flex;
            flex-direction: row; /* Default for desktop */
            flex-grow: 1;
            min-height: 0; /* Important for flexbox scrolling */
        }
        #requests-list-container {
            flex: 0 0 400px; /* Fixed width on desktop */
            overflow-y: auto;
            border-right: 1px solid var(--bs-border-color);
            display: flex;
            flex-direction: column;
        }
        #map-container {
            flex-grow: 1;
            position: relative; /* Needed for map to size correctly */
        }
        #map {
            height: 100%;
            width: 100%;
        }
        .request-card {
            border-left: 5px solid var(--bs-danger);
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        .request-card:hover, .request-card.active {
            background-color: var(--bs-tertiary-bg);
        }
        .new-request {
            animation: fadeInAndHighlight 2.5s ease-out;
        }
        @keyframes fadeInAndHighlight {
            from { background-color: var(--bs-danger-bg-subtle); opacity: 0; }
            50% { background-color: var(--bs-danger-bg-subtle); opacity: 1; }
            to { background-color: transparent; opacity: 1; }
        }

        /* --- THE NEW RESPONSIVE STYLES --- */
        @media (max-width: 991px) { /* Standard Bootstrap LG breakpoint */
            .dashboard-layout {
                flex-direction: column; /* Stack list on top of map */
                height: auto; /* Allow content to determine height */
                overflow-y: auto; /* Allow the whole page to scroll */
            }
            #requests-list-container {
                flex: 0 0 auto; /* Let content size determine height */
                height: 50vh; /* Take up half the screen */
                border-right: none;
                border-bottom: 1px solid var(--bs-border-color);
            }
            #map-container {
                flex: 1 1 50vh; /* Take up the other half of the screen */
                min-height: 300px; /* Ensure map is always visible */
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
            
            <div class="dashboard-layout">
                <!-- Assistance Requests List -->
                <div id="requests-list-container">
                    <div class="p-3 border-bottom">
                        <h4 class="mb-2">Assistance Requests</h4>
                        <!-- THE NEW SEARCH BAR -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="request-search-input" class="form-control" placeholder="Search by name or message...">
                        </div>
                    </div>
                    <div class="flex-grow-1" data-simplebar>
                        <div id="requests-list" class="p-3">
                            @if($assistanceRequests->isEmpty())
                                <p id="no-requests-message" class="text-center text-muted mt-4">No active requests for assistance.</p>
                            @else
                                @foreach($assistanceRequests as $request)
                                    @include('first-responder.partials.request-card', ['request' => $request])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div id="map-container">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.theme_settings')
    @include('partials.scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Map Initialization ---
            const map = L.map('map').setView([39.8283, -98.5795], 5);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '© CARTO'
            }).addTo(map);

            let requestMarkers = {};

            // --- Helper Functions ---
            function escapeHTML(str) {
                if (!str) return '';
                const p = document.createElement("p");
                p.appendChild(document.createTextNode(str));
                return p.innerHTML;
            }

            function addRequest(request, prepend = false) {
                const list = document.getElementById('requests-list');
                const noRequestsMessage = document.getElementById('no-requests-message');
                if (noRequestsMessage) noRequestsMessage.remove();

                const phoneNumber = request.contact_number ? escapeHTML(request.contact_number) : 'No Number';
                const phoneDisabledClass = !request.contact_number ? 'disabled' : '';
                const directionsLink = request.latitude && request.longitude ? `<a href="https://bing.com/maps/default.aspx?rtp=~pos.${request.latitude}_${request.longitude}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-directions me-1"></i> Directions</a>` : '';
                const userName = escapeHTML(request.user?.name || 'Unknown User');
                const message = escapeHTML(request.message);
                
                const cardHtml = `
                    <div class="card request-card mb-3 ${prepend ? 'new-request' : ''}" data-request-id="${request.id}" data-lat="${request.latitude}" data-lng="${request.longitude}" data-search-text="${(userName + ' ' + message).toLowerCase()}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="card-title mb-1">${userName}</h6>
                                <span class="text-muted small">${new Date(request.created_at).toLocaleTimeString()}</span>
                            </div>
                            <p class="card-text small mb-2">${message}</p>
                            <div class="d-flex justify-content-between">
                                <a href="tel:${request.contact_number}" class="btn btn-sm btn-outline-success ${phoneDisabledClass}">
                                    <i class="fas fa-phone me-1"></i> ${phoneNumber}
                                </a>
                                ${directionsLink}
                            </div>
                        </div>
                    </div>
                `;
                
                if (prepend) {
                    list.insertAdjacentHTML('afterbegin', cardHtml);
                } else {
                    list.insertAdjacentHTML('beforeend', cardHtml);
                }

                if (request.latitude && request.longitude) {
                    if (requestMarkers[request.id]) { map.removeLayer(requestMarkers[request.id]); }
                    const marker = L.marker([request.latitude, request.longitude])
                        .addTo(map)
                        .bindPopup(`<b>${userName}</b><br>${message}`);
                    requestMarkers[request.id] = marker;
                }
            }
            
            // --- Initial Data Loading ---
            document.querySelectorAll('.request-card').forEach(card => {
                const lat = card.dataset.lat;
                const lng = card.dataset.lng;
                const id = card.dataset.id;
                if (lat && lng) {
                    const marker = L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup(`<b>${card.querySelector('h6').textContent}</b><br>${card.querySelector('p').textContent}`);
                    requestMarkers[id] = marker;
                }
            });

            // --- Event Listeners ---
            document.getElementById('requests-list').addEventListener('click', function(e) {
                const card = e.target.closest('.request-card');
                if (!card) return;
                document.querySelectorAll('.request-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                const lat = card.dataset.lat;
                const lng = card.dataset.lng;
                if (lat && lng) {
                    map.flyTo([lat, lng], 14);
                    requestMarkers[card.dataset.id]?.openPopup();
                }
            });

            // --- THE NEW SEARCH FUNCTIONALITY ---
            const searchInput = document.getElementById('request-search-input');
            searchInput.addEventListener('keyup', () => {
                const searchTerm = searchInput.value.toLowerCase();
                const cards = document.querySelectorAll('.request-card');
                cards.forEach(card => {
                    const cardText = card.dataset.searchText || '';
                    if (cardText.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // --- Real-Time Logic (Unchanged) ---
            if (window.Echo) {
                console.log("SUCCESS: Echo is loaded. Subscribing to assistance requests.");
                window.Echo.private('officer-dashboard')
                    .listen('.status.update.received', (e) => {
                        if (e.classification === 'needs_help') {
                            console.log("New assistance request received, adding to dashboard.");
                            const sound = new Audio("https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3");
                            sound.play().catch(err => console.error("Alert sound failed:", err));
                            addRequest(e, true);
                        }
                    });
            } else {
                console.error("CRITICAL: Echo is not defined. Real-time updates will not work.");
            }
        });
    </script>
</body>
</html>