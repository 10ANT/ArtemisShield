<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Search Results') }} - Artemis</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    @include('partials.styles')

    <style>
        /* Ensures main content area fills the available space */
        .main-content {
            flex-grow: 1;
        }

        /* Responsive styling for the results list */
        .search-results-list .list-group-item {
            transition: background-color 0.2s ease-in-out;
        }

        .search-results-list .list-group-item:hover {
            background-color: var(--bs-tertiary-bg);
        }

        .search-results-list .list-group-item-action .result-title {
            color: var(--bs-primary);
            font-size: 1.1rem;
        }

        .search-results-list .list-group-item-action .result-url {
            font-size: 0.9rem;
            color: var(--bs-secondary-color);
        }

        .no-results-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        
        /* START: SIDEBAR Z-INDEX FIX */
        @media (min-width: 1200px) {
            .sidebar-area {
                z-index: 1035 !important;
            }
        }
        /* END: SIDEBAR Z-INDEX FIX */

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
        }
        /* END: TABLET/MOBILE SIDEBAR RESPONSIVENESS FIX */
    </style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

    <div class="main-content d-flex flex-column">
        @include('partials.header')

        <div class="container-fluid content-inner pb-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">
                                @if ($query)
                                    {{ __('Search Results for:') }} <span class="text-primary">"{{ $query }}"</span>
                                @else
                                    {{ __('Search') }}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            @if ($query)
                                @if (count($results) > 0)
                                    <p class="text-muted">{{ trans_choice('{1} :count result found.|[2,*] :count results found.', count($results)) }}</p>
                                    <div class="list-group search-results-list">
                                        @foreach ($results as $result)
                                            <a href="{{ route($result['route']) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1 result-title">{{ $result['title'] }}</h5>
                                                </div>
                                                <small class="result-url">{{ url(route($result['route'], [], false)) }}</small>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center p-4 no-results-card">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5 class="mb-1">{{ __("No results found") }}</h5>
                                        <p class="text-muted">{{ __("We couldn't find anything matching your search. Please try a different keyword.") }}</p>
                                    </div>
                                @endif
                            @else
                                <div class="text-center p-4">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <h5 class="mb-1">{{ __("Search the site") }}</h5>
                                    <p class="text-muted">{{ __("Please enter a keyword in the search bar above to begin.") }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </div>

    @include('partials.theme_settings')
    @include('partials.scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // This script block is for initializing any specific JS needed for this page.
            // For now, it just re-initializes the mobile sidebar toggle logic.

            const initMobileSidebarToggle = () => {
                const burgerMenu = document.querySelector('.header-burger-menu');
                const body = document.body;

                if (burgerMenu && body) {
                    if (window.innerWidth < 1200 && !body.classList.contains('sidebar-close')) {
                        body.classList.add('sidebar-close');
                    }
                    burgerMenu.addEventListener('click', function(event) {
                        if (window.innerWidth < 1200) {
                            event.preventDefault();
                            event.stopPropagation();
                            body.classList.toggle('sidebar-close');
                        }
                    }, true);
                }
            };
            initMobileSidebarToggle();
        });
    </script>
</body>
</html>
