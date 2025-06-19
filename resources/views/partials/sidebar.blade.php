<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative p-3 d-flex align-items-center">
        <a href="/dashboard" class="d-block text-decoration-none position-relative d-flex align-items-center">
            <img src="/assets/images/logo.png" alt="Artemis Logo" width="30" height="30" class="me-2">
            <span class="logo-text fw-bold">Artemis</span>
        </a>
        <button class="sidebar-burger-menu bg-transparent p-0 border-0 position-absolute top-50 end-0 translate-middle-y d-lg-none" id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>
    <audio id="notification-sound" src="https://www.soundjay.com/buttons/sounds/button-7.mp3" preload="auto"></audio>
    <aside id="layout-menu" class="layout-menu menu-vertical menu" data-simplebar>
        <ul class="menu-inner py-3">

            <!-- Data Analyst Views -->
            <li class="menu-item {{ Request::is('analyst*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">analytics</span>
                    <span class="title">Data Analyst</span>
                </a>
                <ul class="menu-sub">
                     
                    <li class="menu-item">
                        <a href="/analyst-dashboard" class="menu-link {{ Request::is('analyst-dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                    </li>
                   
                    <li class="menu-item">
                        <a href="/analyst-wildfire-risk" class="menu-link {{ Request::is('analyst-wildfire-risk') ? 'active' : '' }}">
                            Wildfire Risk Map
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/historical-map" class="menu-link {{ Request::is('historical-map') ? 'active' : '' }}">
                           Historical Fires Map
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/analyst-reports" class="menu-link {{ Request::is('analyst-reports') ? 'active' : '' }}">
                            Generate Reports
                        </a>
                    </li>
                </ul>
            </li>


            <!-- System Section -->
            <li class="menu-title small text-uppercase px-3 py-2">
                <span class="menu-title-text">SYSTEM</span>
            </li>
            <li class="menu-item {{ Request::is('wildfire-officer/status-updates') ? 'active' : '' }}">
                   <a href="/wildfire-officer/status-updates" id="status-updates-link" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">forum</span>
                    <span class="title">Community Updates</span>
                    <!-- MODIFIED: Changed badge color to bg-warning (orange) -->
                    <span id="status-updates-badge" class="badge bg-warning rounded-pill ms-2 d-none"></span>
                </a>
            </li>
            {{-- Add a log to indicate the sidebar is being rendered --}}
            {{ \Log::info('Sidebar rendering: Checking for authenticated user.') }}

            @auth
                {{-- This whole section will only be rendered if a user is logged in --}}
                {{ \Log::info('Sidebar rendering: User ' . auth()->user()->name . ' is authenticated. Building menu based on role.') }}

                <!-- Wildfire Officer Views - Only visible to Officers -->
                @if(auth()->user()->role && auth()->user()->role->name === 'Wildfire Management Officer')
                    <li class="menu-item {{ Request::is('wildfire-officer*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <span class="material-symbols-outlined menu-icon">supervisor_account</span>
                            <span class="title">Officer Views</span>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ Request::is('wildfire-officer/dashboard') ? 'active' : '' }}">
                                <a href="/wildfire-officer/dashboard" class="menu-link">Dashboard</a>
                            </li>
                            <li class="menu-item {{ Request::is('wildfire-officer/status-updates') ? 'active' : '' }}">
                                <a href="/wildfire-officer/status-updates" class="menu-link">Community Updates</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Firefighter Views - Visible to Officers and Firefighters -->
                @if(auth()->user()->role && in_array(auth()->user()->role->name, ['Wildfire Management Officer', 'Firefighter']))
                    <li class="menu-item {{ Request::is('firefighter*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <span class="material-symbols-outlined menu-icon">fire_truck</span>
                            <span class="title">Firefighter Views</span>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="/firefighter-dashboard" class="menu-link">Tactical Dashboard</a>
                            </li>
                            <li class="menu-item">
                                <a href="/firefighter-reports" class="menu-link">Submit Reports</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- First Responder Views - Visible to Officers and Ambulance Staff -->
                @if(auth()->user()->role && in_array(auth()->user()->role->name, ['Wildfire Management Officer', 'Ambulance Staff']))
                    <li class="menu-item {{ Request::is('responder*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <span class="material-symbols-outlined menu-icon">medical_services</span>
                            <span class="title">First Responder View</span>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="/responder-dashboard" class="menu-link">View Alerts</a>
                            </li>
                            <li class="menu-item">
                                <a href="first-responder/dashboard" class="menu-link">Help Requests</a>
                            </li>
                         
                        </ul>
                    </li>
                @endif

                <!-- Resident Views - Visible to all logged-in roles -->
                <li class="menu-item {{ Request::is('end-user*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <span class="material-symbols-outlined menu-icon">home</span>
                        <span class="title">Resident Views</span>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="/end-user/dashboard" class="menu-link">Safety Dashboard</a>
                        </li>
                    </ul>
                </li>

                <!-- System Section -->
               
                <!-- Community Updates - Only visible to Officers -->
                @if(auth()->user()->role && auth()->user()->role->name === 'Wildfire Management Officer')
                 <li class="menu-title small text-uppercase px-3 py-2">
                    <span class="menu-title-text">SYSTEM</span>
                </li>
                
                <li class="menu-item {{ Request::is('wildfire-officer/status-updates') ? 'active' : '' }}">
                    <a href="/wildfire-officer/status-updates" id="status-updates-link" class="menu-link">
                        <span class="material-symbols-outlined menu-icon">forum</span>
                        <span class="title">Community Updates</span>
                        <span id="status-updates-badge" class="badge bg-warning rounded-pill ms-2 d-none"></span>
                    </a>
                </li>
                @endif

            @else
                {{-- Log that no authenticated user was found, so no menu items will be shown --}}
                {{ \Log::info('Sidebar rendering: No authenticated user found. Role-based menu will be empty.') }}
            @endauth
        </ul>
    </aside>
</div>