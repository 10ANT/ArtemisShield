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

    <aside id="layout-menu" class="layout-menu menu-vertical menu" data-simplebar>
        <ul class="menu-inner py-3">
            <!-- Wildfire Officer Views -->
            <li class="menu-item {{ Request::is('officer*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">supervisor_account</span>
                    <span class="title">Officer Views</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/wildfire-officer/dashboard" class="menu-link {{ Request::is('officer-dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/officer-reports" class="menu-link {{ Request::is('officer-reports') ? 'active' : '' }}">
                            Incident Reports
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Firefighter Views -->
            <li class="menu-item {{ Request::is('firefighter*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">fire_truck</span>
                    <span class="title">Firefighter Views</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/firefighter-dashboard" class="menu-link {{ Request::is('firefighter-dashboard') ? 'active' : '' }}">
                            Tactical Dashboard
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/firefighter-reports" class="menu-link {{ Request::is('firefighter-reports') ? 'active' : '' }}">
                            Submit Reports
                        </a>
                    </li>
                </ul>
            </li>

            <!-- First Responder Views -->
            <li class="menu-item {{ Request::is('responder*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">medical_services</span>
                    <span class="title">First Responder Views</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/responder-dashboard" class="menu-link {{ Request::is('responder-dashboard') ? 'active' : '' }}">
                            Response Dashboard
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/responder-incidents" class="menu-link {{ Request::is('responder-incidents') ? 'active' : '' }}">
                            Active Incidents
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Resident Views -->
            <li class="menu-item {{ Request::is('resident*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">home</span>
                    <span class="title">Resident Views</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/resident-dashboard" class="menu-link {{ Request::is('resident-dashboard') ? 'active' : '' }}">
                            Safety Dashboard
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/resident-alerts" class="menu-link {{ Request::is('resident-alerts') ? 'active' : '' }}">
                            Alerts
                        </a>
                    </li>
                </ul>
            </li>

            <!-- System Section -->
            <li class="menu-title small text-uppercase px-3 py-2">
                <span class="menu-title-text">SYSTEM</span>
            </li>
            <li class="menu-item {{ Request::is('notifications') ? 'active' : '' }}">
                <a href="/notifications" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">notifications</span>
                    <span class="title">Notifications</span>
                    <span class="count badge bg-danger ms-2">5</span>
                </a>
            </li>
        </ul>
    </aside>
</div>