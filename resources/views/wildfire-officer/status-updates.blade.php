<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ArtemisShield - Community Status Updates</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @include('partials.styles')
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .status-card { transition: all 0.3s ease-in-out; }
        .new-update {
            background-color: var(--bs-warning-bg-subtle) !important;
            border-left: 5px solid var(--bs-warning);
            animation: fadeIn 1.5s ease-out;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        #locationModalMap { height: 400px; border-radius: .5rem; }
    </style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')
    
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            @include('partials.header')
            <div class="main_content_iner overly_inner">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Community Status Updates</h4>
                                <div id="pusher-status" class="d-flex align-items-center">
                                    <div id="pusher-status-light" class="rounded-pill" style="width: 10px; height: 10px; background-color: grey; margin-right: 8px;" title="Connecting..."></div>
                                    <span id="pusher-status-text" class="small">Connecting...</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3"><input type="text" id="searchInput" class="form-control" placeholder="Search by name, message, classification..."></div>
                                <div id="updates-container">
                                    @if($updates->isEmpty())
                                        <p id="no-updates-message" class="text-center text-muted">No status updates submitted yet.</p>
                                    @else
                                        @foreach($updates as $update)
                                            @php
                                                $classificationText = ucwords(str_replace('_', ' ', $update->classification));
                                                $badgeColor = 'secondary';
                                                if ($update->classification === 'im_safe') $badgeColor = 'success';
                                                if ($update->classification === 'threat_report') $badgeColor = 'warning';
                                                if ($update->classification === 'needs_help') $badgeColor = 'danger';
                                            @endphp
                                            <div class="card status-card mb-3" data-search-text="{{ strtolower(optional($update->user)->name . ' ' . $update->message . ' ' . $classificationText . ' ' . $update->contact_number) }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <p class="card-text mb-0">{{ $update->message }}</p>
                                                        <span class="badge text-bg-{{ $badgeColor }} text-nowrap ms-3">{{ $classificationText }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center text-muted small">
                                                    <span><strong>From:</strong> {{ optional($update->user)->name ?? 'Unknown User' }}</span>
                                                    <span>{{ $update->created_at->diffForHumans() }}</span>
                                                    @if($update->latitude && $update->longitude)
                                                        <button class="btn btn-sm btn-outline-info show-on-map-btn" data-lat="{{ $update->latitude }}" data-lng="{{ $update->longitude }}" data-message="{{ $update->message }}" data-bs-toggle="modal" data-bs-target="#locationModal">Show on Map</button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="d-flex justify-content-center">{{ $updates->links() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('partials.footer')
        </div>
    </div>
    
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="locationModalLabel">Report Location</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body"><div id="locationModalMap"></div></div>
            </div>
        </div>
    </div>

    @include('partials.theme_settings')
    @include('partials.scripts')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize a global counter if it doesn't already exist from the layout script.
            if (typeof window.statusUpdateNotificationCount === 'undefined') {
                window.statusUpdateNotificationCount = 0;
            }

            if (window.Echo) {
                console.log("SUCCESS: Echo is loaded. Initializing real-time features for status updates page.");
                initializeRealtimeFeatures();
            } else {
                console.error("CRITICAL FAILURE: Echo is not defined.");
            }

            // This function is responsible for showing the notification.
            function triggerNotification() {
                const badge = document.getElementById('status-updates-badge');
                const sound = document.getElementById('notification-sound');

                // This logic is now handled by the global script to avoid double-counting.
                // This page's job is just to add the card. The global script handles the badge
                // when the user is NOT on this page.
            }

            // This function resets the badge since the user is now on the page.
            function setupNotificationClearer() {
                const badge = document.getElementById('status-updates-badge');

                if (badge) {
                    console.log("User is viewing the updates page. Clearing notification count.");
                    window.statusUpdateNotificationCount = 0;
                    badge.textContent = '';
                    badge.classList.add('d-none');
                }

                // Add listeners to both sidebar links to clear the badge.
                ['status-updates-link', 'status-updates-sub-link'].forEach(id => {
                    const link = document.getElementById(id);
                    if (link) {
                        link.addEventListener('click', () => {
                            window.statusUpdateNotificationCount = 0;
                            if (badge) {
                                badge.textContent = '';
                                badge.classList.add('d-none');
                            }
                        });
                    }
                });
            }

            // Call the clearer function as soon as the DOM is loaded.
            setupNotificationClearer();

            function initializeRealtimeFeatures() {
                const statusLight = document.getElementById('pusher-status-light');
                const statusText = document.getElementById('pusher-status-text');
                
                window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                    const state = states.current;
                    statusText.textContent = state.charAt(0).toUpperCase() + state.slice(1);
                    console.log("Pusher connection state changed to: " + state);
                    if (state === 'connected') statusLight.style.backgroundColor = 'lime';
                    else if (state === 'connecting') statusLight.style.backgroundColor = 'orange';
                    else statusLight.style.backgroundColor = 'red';
                });
                
                window.Echo.private('officer-dashboard')
                    .listen('.status.update.received', (e) => {
                        console.log("SUCCESS: Status update event received on page.", e);
                        
                        // Action 1: Add the new card to the top of the list
                        addNewUpdateCard(e);
                        
                        // Action 2: Since we are on the page, we don't increment the badge,
                        // but you could play a subtle sound here if you want an in-page notification.
                        const sound = document.getElementById('notification-sound');
                        if (sound) {
                           // sound.play().catch(err => console.error("In-page sound failed:", err));
                        }
                    });
            }

            function escapeHTML(str) {
                if (!str) return '';
                const p = document.createElement("p");
                p.appendChild(document.createTextNode(str));
                return p.innerHTML;
            }

            function addNewUpdateCard(update) {
                console.log("Adding new update card to UI:", update);
                const updatesContainer = document.getElementById('updates-container');
                const noUpdatesMessage = document.getElementById('no-updates-message');
                if (noUpdatesMessage) noUpdatesMessage.remove();

                const classificationText = (update.classification || 'general').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                let badgeColor = 'secondary';
                if (update.classification === 'im_safe') badgeColor = 'success';
                if (update.classification === 'threat_report') badgeColor = 'warning';
                if (update.classification === 'needs_help') badgeColor = 'danger';
                
                const userName = update.user ? escapeHTML(update.user.name) : 'Unknown User';
                const userEmail = update.user ? escapeHTML(update.user.email) : '';

                const newCard = document.createElement('div');
                newCard.className = 'card status-card mb-3 new-update';
                newCard.dataset.searchText = `${userName} ${userEmail} ${escapeHTML(update.message)} ${classificationText} ${escapeHTML(update.contact_number)}`.toLowerCase();
                
                const mapButtonHtml = update.latitude && update.longitude ? `<button class="btn btn-sm btn-outline-info show-on-map-btn" data-lat="${update.latitude}" data-lng="${update.longitude}" data-message="${escapeHTML(update.message)}" data-bs-toggle="modal" data-bs-target="#locationModal">Show on Map</button>` : '';

                newCard.innerHTML = `
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <p class="card-text mb-0">${escapeHTML(update.message)}</p>
                            <span class="badge text-bg-${badgeColor} text-nowrap ms-3">${classificationText}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center text-muted small">
                        <span><strong>From:</strong> ${userName}</span>
                        <span>Just now</span>
                        ${mapButtonHtml}
                    </div>
                `;
                
                updatesContainer.prepend(newCard);
                setTimeout(() => { newCard.classList.remove('new-update'); }, 3000);
            }

            // Other page logic like search and map modals
            const searchInput = document.getElementById('searchInput');
            searchInput?.addEventListener('input', (e) => {
                 const searchTerm = e.target.value.toLowerCase();
                 document.querySelectorAll('#updates-container .status-card').forEach(card => {
                     card.style.display = (card.dataset.searchText || '').includes(searchTerm) ? '' : 'none';
                 });
            });

            const locationModal = document.getElementById('locationModal');
            let locationMap = null, locationMarker = null;
            locationModal?.addEventListener('shown.bs.modal', function (event) {
                const button = event.relatedTarget;
                const lat = button.dataset.lat;
                const lng = button.dataset.lng;
                const message = button.dataset.message;
                if (!locationMap) {
                    locationMap = L.map('locationModalMap').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(locationMap);
                    locationMarker = L.marker([lat, lng]).addTo(locationMap).bindPopup(escapeHTML(message)).openPopup();
                } else {
                    locationMap.setView([lat, lng], 15);
                    locationMarker.setLatLng([lat, lng]).setPopupContent(escapeHTML(message)).openPopup();
                }
                setTimeout(() => locationMap.invalidateSize(), 10);
            });
        });
    </script>
</body>
</html>