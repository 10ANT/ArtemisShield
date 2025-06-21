<header
    class="header-area bg-white mb-4 rounded-bottom-15"
    id="header-area"
>
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <div class="row align-items-center">
        <div class="col-lg-4 col-sm-6">
            <div class="left-header-content">
                <ul
                    class="d-flex align-items-center ps-0 mb-0 list-unstyled justify-content-center justify-content-sm-start"
                >
                    <li>
                        <button
                            class="header-burger-menu bg-transparent p-0 border-0"
                            id="header-burger-menu"
                        >
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                    </li>
                    <li>
                        <!-- === DISABLED SEARCH FEATURE === -->
                        <div id="disabled-search-wrapper" style="cursor: pointer;">
                             <form class="src-form position-relative" onsubmit="return false;">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search is currently disabled"
                                    disabled
                                />
                                <button
                                    type="submit"
                                    class="src-btn position-absolute top-50 end-0 translate-middle-y bg-transparent p-0 border-0"
                                    disabled
                                >
                                    <span class="material-symbols-outlined">search</span>
                                </button>
                            </form>
                        </div>
                    </li>
                    <li>
                        <div
                            class="dropdown notifications apps"
                        >
                            <button
                                class="btn btn-secondary border-0 p-0 position-relative"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <span
                                    class="material-symbols-outlined"
                                    >apps</span
                                >
                            </button>
                            <div
                                class="dropdown-menu dropdown-lg p-0 border-0 py-4 px-3 max-h-312"
                                data-simplebar
                            >
                                <div
                                    class="notification-menu d-flex flex-wrap justify-content-between gap-4"
                                >
                                    
                                        <a href=""
                                        target="_blank"
                                        class="dropdown-item p-0 text-center"
                                    >
                                        <img
                                            src="/assets/images/"
                                            class="wh-25"
                                            alt="united-states"
                                        />
                                        <span>yes</span>
                                    </a>
                                 
                                  
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-8 col-sm-6">
            <div class="right-header-content mt-2 mt-sm-0">
                <ul
                    class="d-flex align-items-center justify-content-center justify-content-sm-end ps-0 mb-0 list-unstyled"
                >
                    <li class="header-right-item">
                        <div class="light-dark">
                            <button
                                class="switch-toggle settings-btn dark-btn p-0 bg-transparent"
                                id="switch-toggle"
                            >
                                <span class="dark"
                                    ><i
                                        class="material-symbols-outlined"
                                        >light_mode</i
                                    ></span
                                >
                                <span class="light"
                                    ><i
                                        class="material-symbols-outlined"
                                        >dark_mode</i
                                    ></span
                                >
                            </button>
                        </div>
                    </li>
                    <li class="header-right-item">
                        <!-- === DISABLED LANGUAGE FEATURE === -->
                        <div class="dropdown notifications language" id="disabled-language-wrapper" style="cursor: pointer;">
                            <button
                                class="btn btn-secondary dropdown-toggle border-0 p-0 position-relative"
                                type="button"
                            >
                                <span
                                    class="material-symbols-outlined"
                                    >translate</span
                                >
                            </button>
                            <div
                                class="dropdown-menu dropdown-lg p-0 border-0 dropdown-menu-end"
                            >
                                <!-- Dropdown content remains but will not be shown -->
                            </div>
                        </div>
                    </li>
                    <li class="header-right-item">
                        <button
                            class="fullscreen-btn bg-transparent p-0 border-0"
                            id="fullscreen-button"
                        >
                            <i
                                class="material-symbols-outlined text-body"
                                >fullscreen</i
                            >
                        </button>
                    </li>
                    <li class="header-right-item">
                        <div
                            class="dropdown notifications noti"
                        >
                            <button
                                class="btn btn-secondary border-0 p-0 position-relative badge"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <span
                                    class="material-symbols-outlined"
                                    >notifications</span
                                >
                            </button>
                            <div
                                class="dropdown-menu dropdown-lg p-0 border-0 p-0 dropdown-menu-end"
                            >
                                <div
                                    class="d-flex justify-content-between align-items-center title"
                                >
                                    <span
                                        class="fw-semibold fs-15 text-secondary"
                                        >Notifications
                                        <span
                                            class="fw-normal text-body fs-14"
                                            >(03)</span
                                        ></span
                                    >
                                    <button
                                        class="p-0 m-0 bg-transparent border-0 fs-14 text-primary"
                                    >
                                        Clear All
                                    </button>
                                </div>
                                <div
                                    class="max-h-217"
                                    data-simplebar
                                >
                                    
                                    <div
                                        class="notification-menu unseen"
                                    >
                                        
                                            <a href="/notification"
                                            class="dropdown-item"
                                        >
                                            <div
                                                class="d-flex align-items-center"
                                            >
                                                <div
                                                    class="flex-shrink-0"
                                                >
                                                    <i
                                                        class="material-symbols-outlined text-info"
                                                        >person</i
                                                    >
                                                </div>
                                                <div
                                                    class="flex-grow-1 ms-3"
                                                >
                                                    <p>
                                                        A new
                                                        user
                                                        added in
                                                       ArtemisShield - Wildfire Protection Dashboard
                                                    </p>
                                                    <span
                                                        class="fs-13"
                                                        >  hrs
                                                        ago</span
                                                    >
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                 
                               
                                 
                                 
                                </div>
                                
                                    <a href=""
                                    class="dropdown-item text-center text-primary d-block view-all fw-medium rounded-bottom-3"
                                >
                                    <span>
                                        
                                    </span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="header-right-item">
                        <div class="dropdown admin-profile">
                            @auth
                            {{ \Log::info('Header dropdown is being rendered for user: ' . Auth::user()->name) }}
                            <div
                                class="d-xxl-flex align-items-center bg-transparent border-0 text-start p-0 cursor dropdown-toggle"
                                data-bs-toggle="dropdown"
                            >
                                <div class="flex-shrink-0">
                                    <img class="rounded-circle wh-40 administrator" 
                                         src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                         alt="{{ Auth::user()->name }}" />
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <div
                                        class="d-flex align-items-center justify-content-between"
                                    >
                                        <div
                                            class="d-none d-xxl-block"
                                        >
                                            <div
                                                class="d-flex align-content-center"
                                            >
                                                <div>
                                                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="dropdown-menu border-0 bg-white dropdown-menu-end"
                            >
                                <div
                                    class="d-flex align-items-center info"
                                >
                                    <div class="flex-shrink-0">
                                       <img class="rounded-circle wh-40 administrator" 
                                            src="{{ Auth::user()->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                            alt="{{ Auth::user()->name }}" />
                                    </div>
                                    <div
                                        class="flex-grow-1 ms-2"
                                    >
                                        <h3 class="fw-medium">
                                            {{ Auth::user()->name }}
                                        </h3>
                                        <span class="fs-12 font-medium text-base text-gray-800">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                                <ul
                                    class="admin-link ps-0 mb-0 list-unstyled"
                                >
                                    <li>
                                        <a
                                            class="dropdown-item d-flex align-items-center text-body"
                                            href="{{ route('profile.show') }}"
                                        >
                                            <i
                                                class="material-symbols-outlined"
                                                >account_circle</i
                                            >
                                            <span class="ms-2"
                                                >{{ __('Profile') }}</span
                                            >
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            @endauth
                        </div>
                    </li>

                    @auth
                    {{ \Log::info('Dedicated logout button is being rendered for user: ' . Auth::user()->name) }}
                    <li class="header-right-item">
                         <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf

                            <button type="submit" class="bg-transparent p-0 border-0"
                                    title="Logout"
                                     @click.prevent="$root.submit();">
                                <i
                                class="material-symbols-outlined text-body"
                                style="font-size: 36px; vertical-align: middle;"
                                >logout</i>
                            </button>
                        </form>
                    </li>
                    @endauth
                    
                    <li class="header-right-item">
                        <button
                            class="theme-settings-btn p-0 border-0 bg-transparent"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasScrolling"
                            aria-controls="offcanvasScrolling"
                        >
                            <i
                                class="material-symbols-outlined"
                                data-bs-toggle="tooltip"
                                data-bs-placement="left"
                                data-bs-title="Click On Theme Settings"
                                >settings</i
                            >
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- === SCRIPT TO HANDLE DISABLED FEATURES === -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // This check prevents the same event listeners from being added multiple times
    // if the header is ever loaded dynamically.
    if (!window.headerFeatureDisablerAttached) {
        console.log("Attaching feature disabler script to header.");

        const disabledSearchWrapper = document.getElementById('disabled-search-wrapper');
        if (disabledSearchWrapper) {
            disabledSearchWrapper.addEventListener('click', function(e) {
                e.preventDefault();
                alert('The search feature is currently disabled.');
                console.log('User clicked on the disabled search feature.');
            });
        }

        const disabledLanguageWrapper = document.getElementById('disabled-language-wrapper');
        if (disabledLanguageWrapper) {
            disabledLanguageWrapper.addEventListener('click', function(e) {
                e.preventDefault();
                alert('The language selection feature is currently disabled.');
                console.log('User clicked on the disabled language feature.');
            });
        }

        window.headerFeatureDisablerAttached = true;
    }
});
</script>