<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ArtemisShield - Community Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @include('partials.styles')
    
    <!-- This loads your CSS and the globally configured Echo instance via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { height: 100%; overflow: hidden; }
        .main-content { height: 100vh; }
        .dashboard-container { flex-grow: 1; min-height: 0; }
        #map { height: 100%; min-height: 200px; width: 100%; border-radius: .5rem; }
        .sidebar-column { height: 100%; display: flex; flex-direction: column; }
        .sidebar-column .card { flex-grow: 1; min-height: 0; display: flex; flex-direction: column; }
        .sidebar-column .card-body { flex-grow: 1; min-height: 0; overflow-y: auto; }
        .chat-messages { flex-grow: 1; overflow-y: auto; min-height: 0; }
        .chat-container { height: 100%; display: flex; flex-direction: column; }
        .alert-item { border-left: 4px solid var(--bs-warning); cursor: pointer; }
        .alert-item:hover { background-color: var(--bs-tertiary-bg); }
        .btn-classification.active {
            transform: scale(1.05);
            box-shadow: 0 0 0 0.25rem var(--bs-primary-border-subtle);
        }

        /* Responsive behavior for smaller screens */
        @media (max-width: 991.98px) { /* Corresponds to Bootstrap's 'lg' breakpoint */
            #map {
                height: 300px; /* Adjust map height for stacked layout */
            }
        }

        /* --- FIX FOR INVISIBLE ACCORDION TEXT --- */
        /* This rule targets the accordion body and its list items specifically */
        /* within #safetyAccordion, and uses !important to override external styles. */
        #safetyAccordion .accordion-body,
#safetyAccordion .accordion-body *,
#safetyAccordion .accordion-body ul,
#safetyAccordion .accordion-body ul li,
#safetyAccordion .accordion-body p {
    color: var(--bs-body-color) !important;
    visibility: visible !important;
    opacity: 1 !important;
}


.sidebar-column {
    position: relative;
    z-index: 1000;
}

.sidebar-column .card {
    position: relative;
    z-index: 1001;
}
        /* --- END OF FIX --- */
    
        #map {
    z-index: 1;
    position: relative;
}



    </style>
</head>
<body class="boxed-size">
    <audio id="alert-sound" src="https://www.soundjay.com/buttons/sounds/button-3.mp3" preload="auto"></audio>
    @include('partials.preloader')
    @include('partials.sidebar')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            @include('partials.header')
            <div class="main_content_iner overly_inner dashboard-container">
                <div class="row g-3 h-100">
                    <div class="col-lg-8 d-flex flex-column">
                        <div id="map"></div>
                    </div>
                    <div class="col-lg-4 h-100 sidebar-column">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation"><button class="nav-link active" id="alerts-tab" data-bs-toggle="tab" data-bs-target="#alerts" type="button" role="tab"><i class="fas fa-bell me-1"></i> Alerts</button></li>
                                    <li class="nav-item" role="presentation"><button class="nav-link" id="report-tab" data-bs-toggle="tab" data-bs-target="#report" type="button" role="tab"><i class="fas fa-bullhorn me-1"></i> Report</button></li>
                                    <li class="nav-item" role="presentation"><button class="nav-link" id="ask-tab" data-bs-toggle="tab" data-bs-target="#ask" type="button" role="tab"><i class="fas fa-question-circle me-1"></i> Ask AI</button></li>
                                    <li class="nav-item" role="presentation"><button class="nav-link" id="safety-tab" data-bs-toggle="tab" data-bs-target="#safety" type="button" role="tab"><i class="fas fa-shield-alt me-1"></i> Safety</button></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="alerts" role="tabpanel">
                                        <h5 class="mb-3">Alerts In Your Area</h5>
                                        <div id="alerts-container"><p class="text-muted">Checking your location...</p></div>
                                    </div>
                                    <div class="tab-pane fade" id="report" role="tabpanel">
                                        <h5 class="mb-3">Submit a Status Update</h5>

                                        <div class="alert alert-info d-flex align-items-center small p-2" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <div>
                                                <strong>Data Notice:</strong> By submitting, you acknowledge your location and any contact details provided will be collected to assist response teams.
                                            </div>
                                        </div>

                                        <form id="status-update-form">
                                            <input type="hidden" id="status-latitude" name="latitude">
                                            <input type="hidden" id="status-longitude" name="longitude">
                                            <div class="mb-3">
                                                <label class="form-label">Select Report Type:</label>
                                                <div class="btn-group w-100" role="group" id="classification-group">
                                                    <button type="button" class="btn btn-outline-success btn-classification" data-value="im_safe"><i class="fas fa-check-circle me-1"></i> I'm Safe</button>
                                                    <button type="button" class="btn btn-outline-warning btn-classification active" data-value="threat_report"><i class="fas fa-exclamation-triangle me-1"></i> See Threat</button>
                                                    <button type="button" class="btn btn-outline-danger btn-classification" data-value="needs_help"><i class="fas fa-first-aid me-1"></i> Need Help</button>
                                                </div>
                                                <input type="hidden" id="classification-input" name="classification" value="threat_report">
                                            </div>
                                            <div class="mb-3">
                                                <label for="status-message" class="form-label">Message</label>
                                                <textarea id="status-message" class="form-control" rows="4" placeholder="Provide details about your situation or what you are observing..." required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="contact-number" class="form-label">Contact (Optional)</label>
                                                <input type="tel" id="contact-number" class="form-control" placeholder="Your phone number for follow-up">
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button type="submit" id="send-update-btn" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Send Update</button>
                                                <button type="button" id="record-status-btn" class="btn btn-secondary"><i class="fas fa-microphone me-2"></i>Record Message</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade h-100" id="ask" role="tabpanel">
                                        <div class="chat-container">
                                            <div class="chat-messages p-2" id="chat-messages"><div class="text-start mb-3"><div class="p-3 rounded bg-body-secondary d-inline-block">Hello! Ask me about wildfire safety.</div></div></div>
                                            <div class="chat-input-group d-flex gap-2 mt-2">
                                                <input type="text" class="form-control" placeholder="Ask a question..." id="chat-input">
                                                <button class="btn btn-secondary" id="record-chat-btn" title="Record question"><i class="fas fa-microphone"></i></button>
                                                <button class="btn btn-primary" id="send-chat-btn"><i class="fas fa-paper-plane"></i></button>
                                                <button class="btn btn-secondary" id="reset-chat-btn" title="Reset Conversation"><i class="fas fa-sync-alt"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="safety" role="tabpanel">
                                        <h5 class="mb-3">Wildfire Safety Guides</h5>
                                        <div class="accordion" id="safetyAccordion">
                                            <div class="accordion-item"><h2 class="accordion-header" id="headingOne"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">Before a Wildfire (Be Ready)</button></h2><div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#safetyAccordion"><div class="accordion-body"><ul><li>Create defensible space.</li><li>Assemble an emergency kit.</li><li>Develop an evacuation plan.</li></ul></div></div></div>
                                            <div class="accordion-item"><h2 class="accordion-header" id="headingTwo"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">During a Wildfire (Be Safe)</button></h2><div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#safetyAccordion"><div class="accordion-body"><ul><li>Evacuate immediately if told.</li><li>Stay informed.</li><li>If trapped, find a clear area.</li></ul></div></div></div>
                                            <div class="accordion-item"><h2 class="accordion-header" id="headingThree"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">After a Wildfire (Be Cautious)</button></h2><div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#safetyAccordion"><div class="accordion-body"><ul><li>Do not return home until safe.</li><li>Check for hot spots.</li><li>Be aware of flash flood risks.</li></ul></div></div></div>
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
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (window.Echo) {
            console.log("SUCCESS: Echo is loaded on end-user page. Attaching real-time alert listeners.");
            setupRealtimeAlertListeners();
        } else {
            console.error("CRITICAL: Echo not found on end-user page. Real-time alerts will not work.");
        }

        const map = L.map('map').setView([34.0522, -118.2437], 10);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors, © CARTO'
        }).addTo(map);

        let userLatLng = null; 
        let alertObjects = {};
        const alertSound = document.getElementById('alert-sound');

        function setupRealtimeAlertListeners() {
            window.Echo.channel('public-alerts')
                .listen('AlertCreated', (e) => {
                    console.log('Real-time Event: AlertCreated received', e);
                    if (!e.alert) return;
                    addOrUpdateAlertOnMap(e.alert);
                    updateSidebarAlertList();
                })
                .listen('AlertDeleted', (e) => {
                    console.log('Real-time Event: AlertDeleted received', e);
                    if (e.alertId) {
                        removeAlertFromMap(e.alertId);
                        updateSidebarAlertList();
                    }
                });
        }
        
        function escapeHTML(str) {
            if (!str) return '';
            let p = document.createElement("p");
            p.appendChild(document.createTextNode(str));
            return p.innerHTML;
        }

        function loadAllAlertsAndDisplay() {
            axios.get("{{ route('api.alerts.index') }}")
                .then(response => {
                    console.log(`Loaded ${response.data.length} initial alerts.`);
                    response.data.forEach(addOrUpdateAlertOnMap);
                    updateSidebarAlertList();
                })
                .catch(error => console.error('Error fetching initial alerts:', error));
        }

        function addOrUpdateAlertOnMap(alertData) {
            if (alertObjects[alertData.id]) { map.removeLayer(alertObjects[alertData.id]); }
            const circle = L.circle([alertData.latitude, alertData.longitude], {
                radius: alertData.radius, color: '#ffc107', fillColor: '#ffc107', fillOpacity: 0.3
            }).addTo(map).bindPopup(`<b>Community Alert:</b><br>${escapeHTML(alertData.message)}`);
            alertObjects[alertData.id] = circle;
        }

        function removeAlertFromMap(alertId) {
            if (alertObjects[alertId]) {
                map.removeLayer(alertObjects[alertId]);
                delete alertObjects[alertId];
            }
        }

        function updateSidebarAlertList() {
            const container = document.getElementById('alerts-container');
            container.innerHTML = '';
            let alertsInRadiusFound = 0;
            if (!userLatLng) { container.innerHTML = '<p class="text-muted">Could not get your location to check for nearby alerts.</p>'; return; }
            for (const alertId in alertObjects) {
                const alertCircle = alertObjects[alertId];
                if (userLatLng.distanceTo(alertCircle.getLatLng()) <= alertCircle.getRadius()) {
                    alertsInRadiusFound++;
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'p-3 mb-2 rounded alert-item';
                    alertDiv.innerHTML = `<h6 class="mb-1">Warning: You Are In An Alert Zone</h6><p class="mb-0">${escapeHTML(alertCircle.getPopup().getContent().split('<br>')[1])}</p>`;
                    alertDiv.addEventListener('click', () => map.fitBounds(alertCircle.getBounds()));
                    container.appendChild(alertDiv);
                }
            }
            if (alertsInRadiusFound === 0) {
                container.innerHTML = '<p class="text-muted">You are not currently inside any active alert zones. Stay safe!</p>';
            }
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLatLng = L.latLng(position.coords.latitude, position.coords.longitude);
                document.getElementById('status-latitude').value = userLatLng.lat;
                document.getElementById('status-longitude').value = userLatLng.lng;
                L.marker(userLatLng).addTo(map).bindPopup("Your Location").openPopup();
                map.setView(userLatLng, 13);
                loadAllAlertsAndDisplay();
            }, 
            (error) => {
                console.warn("Geolocation failed or was denied:", error.message);
                loadAllAlertsAndDisplay(); 
            }
        );

        const classificationGroup = document.getElementById('classification-group');
        const classificationInput = document.getElementById('classification-input');
        classificationGroup.addEventListener('click', (e) => {
            const button = e.target.closest('.btn-classification');
            if (!button) return;
            classificationGroup.querySelectorAll('.btn-classification').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            classificationInput.value = button.dataset.value;
        });

        const statusForm = document.getElementById('status-update-form');
        statusForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const btn = document.getElementById('send-update-btn');
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Sending...`;

            axios.post("{{ route('api.status-updates.store') }}", {
                message: document.getElementById('status-message').value,
                contact_number: document.getElementById('contact-number').value,
                latitude: document.getElementById('status-latitude').value || null,
                longitude: document.getElementById('status-longitude').value || null,
                classification: document.getElementById('classification-input').value
            }).then(() => {
                alert('Success! Your status update has been sent.');
                statusForm.reset();
                classificationGroup.querySelector('[data-value="threat_report"]').click();
            }).catch(error => {
                let errorMsg = 'Could not send your update. ' + (error.response?.data?.message || '');
                alert(errorMsg);
                console.error('Error sending status update:', error.response?.data);
            }).finally(() => {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-paper-plane me-2"></i>Send Update`;
            });
        });
        
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            const recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.lang = 'en-US';
            let activeSpeechButton = null, targetInput = null;
            recognition.onstart = () => { if (activeSpeechButton) { activeSpeechButton.innerHTML = '<i class="fas fa-stop"></i>'; activeSpeechButton.classList.add('btn-danger'); }};
            recognition.onerror = (event) => { if (event.error !== 'no-speech') { console.error('Speech error:', event.error); alert('Speech error: ' + event.error); }};
            recognition.onend = () => { if (activeSpeechButton) { activeSpeechButton.innerHTML = '<i class="fas fa-microphone"></i>'; activeSpeechButton.classList.remove('btn-danger'); activeSpeechButton = null; }};
            recognition.onresult = (event) => { if (targetInput) { targetInput.value = event.results[0][0].transcript; }};
            const setupSpeech = (btnId, inputId) => {
                const button = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                if (button && input) {
                    button.addEventListener('click', () => {
                        if (activeSpeechButton) { recognition.stop(); } 
                        else { activeSpeechButton = button; targetInput = input; recognition.start(); }
                    });
                }
            };
            setupSpeech('record-status-btn', 'status-message');
            setupSpeech('record-chat-btn', 'chat-input');
        } else {
            console.warn("Speech Recognition not supported.");
            ['record-status-btn', 'record-chat-btn'].forEach(id => {
                const btn = document.getElementById(id);
                if(btn) btn.style.display = 'none';
            });
        }

        class EndUserAgentHandler {
            constructor() {
                this.chatMessages = document.getElementById('chat-messages');
                this.chatInput = document.getElementById('chat-input');
                this.sendBtn = document.getElementById('send-chat-btn');
                this.resetBtn = document.getElementById('reset-chat-btn');
                this.isBusy = false;

                this.sendBtn.addEventListener('click', () => this.sendMessage());
                this.resetBtn.addEventListener('click', () => this.resetConversation());
                this.chatInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); this.sendMessage(); }
                });
            }
            displayMessage(text, role) {
                const alignClass = role === 'user' ? 'text-end' : 'text-start';
                const bgClass = role === 'user' ? 'bg-primary-subtle' : 'bg-body-secondary';
                const messageDiv = document.createElement('div');
                messageDiv.className = `mb-3 ${alignClass}`;
                messageDiv.innerHTML = `<div class="p-3 rounded mt-1 ${bgClass} d-inline-block">${escapeHTML(text)}</div>`;
                this.chatMessages.appendChild(messageDiv);
                this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
            }
            setBusy(busy) {
                this.isBusy = busy;
                this.chatInput.disabled = busy;
                this.sendBtn.disabled = busy;
                if (busy) {
                    this.sendBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;
                    this.chatInput.placeholder = "Agent is thinking...";
                } else {
                    this.sendBtn.innerHTML = `<i class="fas fa-paper-plane"></i>`;
                    this.chatInput.placeholder = "Ask a question...";
                    this.chatInput.focus();
                }
            }
            async sendMessage() {
                const message = this.chatInput.value.trim();
                if (!message || this.isBusy) return;
                this.displayMessage(message, 'user');
                this.chatInput.value = '';
                this.setBusy(true);
                try {
                    const response = await axios.post("{{ route('end-user.agent.chat') }}", { message });
                    if (response.data.status === 'completed') {
                        const lastMessage = response.data.messages.filter(m => m.role === 'assistant').pop();
                        const textContent = lastMessage?.content?.find(c => c.type === 'text');
                        if (textContent && textContent.text.value) { this.displayMessage(textContent.text.value, 'assistant'); } 
                        else { this.displayMessage("I received a response, but it was empty.", 'assistant'); }
                    } else {
                        console.error("Unhandled agent status:", response.data);
                        this.displayMessage("Sorry, I encountered an unexpected issue.", 'assistant');
                    }
                } catch (error) {
                    console.error("End-user agent chat error:", error.response?.data || error);
                    this.displayMessage("Sorry, I'm having trouble connecting right now.", 'assistant');
                } finally {
                    this.setBusy(false);
                }
            }
            async resetConversation() {
                await axios.post("{{ route('end-user.agent.reset') }}");
                this.chatMessages.innerHTML = '';
                this.displayMessage("Conversation reset. How can I help?", 'assistant');
            }
        }
        new EndUserAgentHandler();
    });
    </script>
</body>
</html>