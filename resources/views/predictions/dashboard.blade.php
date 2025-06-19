<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Artemis - Global Historical Heatmaps</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.css">

    @include('partials.styles')

    <style>
        /* Core Layout Styles */
        .main-content { flex-grow: 1; }
        .dashboard-container, .main-viewer-wrapper { height: 100%; }
        .main-viewer-wrapper {
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            padding-top: 0;
        }

        /* Region Selector Header */
        .region-header {
            padding: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--bs-border-color);
        }

        /* Main Content Viewer Styles */
        .viewer-header {
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--bs-border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        .viewer-header h3 { font-weight: 300; margin-bottom: 0; }
        .visualization-container {
            flex-grow: 1;
            position: relative;
        }
        .map-container, .image-container {
            width: 100%;
            height: 100%;
            border: 1px solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            overflow: hidden;
            background-color: var(--bs-tertiary-bg);
        }
        .map-container iframe, .image-container img {
            width: 100%;
            height: 100%;
            border: none;
            object-fit: contain;
        }
        .image-container img {
            cursor: zoom-in;
        }
        #viewer-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-direction: column;
            text-align: center;
        }
        .placeholder-icon {
            font-size: 3rem;
            color: var(--bs-secondary-color);
            margin-bottom: 1rem;
        }
        
        /* Right Sidebar Library Styles */
        .sidebar-wrapper { height: 100%; display: flex; flex-direction: column; }
        .sidebar-wrapper .card-body { flex-grow: 1; overflow-y: auto; padding: 0 !important; }
        .accordion-button { background-color: var(--bs-body-bg); }
        .accordion-button:not(.collapsed) { background-color: var(--bs-tertiary-bg); }
        .list-group-item-action {
            cursor: pointer;
            border-radius: 0 !important;
            border-left: 0;
            border-right: 0;
        }
        .list-group-item-action.active {
            background-color: var(--bs-primary);
            color: #fff;
            border-color: var(--bs-primary);
        }
        .list-group-item-action.disabled {
             background-color: var(--bs-tertiary-bg);
             cursor: not-allowed;
        }

        /* Viewer.js dark theme adjustments */
        .viewer-canvas {
            background-color: rgba(0, 0, 0, 0.85);
        }

        /* Responsive Layout Adjustments */
        @media (max-width: 991.98px) {
            .sidebar-area {
                position: static !important; width: 100% !important; transform: none !important;
                left: auto !important; top: auto !important; z-index: auto !important;
                transition: max-height 0.35s ease-in-out, padding 0.35s ease-in-out, border-width 0.35s ease-in-out;
                background-color: var(--bs-body-bg);
            }
            body.sidebar-close .sidebar-area {
                max-height: 0; overflow: hidden; padding-top: 0; padding-bottom: 0; border-width: 0;
            }
            body:not(.sidebar-close) .sidebar-area {
                max-height: 75vh; overflow-y: auto; border-bottom: 1px solid var(--bs-border-color);
            }
            .main-content { margin-left: 0 !important; width: 100% !important; transition: none !important; }
            .body-overlay { display: none !important; }
            #sidebar-area .sidebar-burger-menu { display: none !important; }
            .main-content > .header-area { position: sticky; top: 0; z-index: 1025; }
            .dashboard-container { flex-direction: column; }
            .dashboard-container .col-lg-4.border-start { border-left: 0 !important; border-top: 1px solid var(--bs-border-color) !important; }
            .main-viewer-wrapper { height: auto; }
            .map-container { min-height: 500px; height: 70vh; }
        }
    </style>
</head>
<body class="boxed-size">
    @include('partials.preloader')
    @include('partials.sidebar')

<div class="main-content d-flex flex-column">
    @include('partials.header')
    
    <div class="dashboard-container row g-0 flex-grow-1">
        <!-- Main Content Viewer -->
        <div class="col-lg-8 col-md-12 d-flex flex-column">
             <div class="region-header">
                <label for="region-selector" class="form-label fw-bold">Select Region:</label>
                <select class="form-select" id="region-selector">
                    <option value="us" selected>United States</option>
                    <option value="ca">Canada (Data not available)</option>
                    <option value="au">Australia (Data not available)</option>
                </select>
            </div>
            <div class="main-viewer-wrapper flex-grow-1">
                <div id="viewer-content" class="h-100 d-flex flex-column" style="display: none !important;">
                    <div class="viewer-header">
                        <div>
                            <h3 id="visualization-title"></h3>
                            <p id="visualization-description" class="text-muted mb-0"></p>
                        </div>
                        <a href="#" id="export-button" class="btn btn-outline-primary btn-sm" style="display: none;" download>
                            <i class="fas fa-download me-2"></i>Export
                        </a>
                    </div>
                    <div id="visualization-container" class="visualization-container">
                        <!-- Dynamic content (iframe/img) goes here -->
                    </div>
                </div>
                <div id="viewer-placeholder" class="m-auto">
                    <i class="fas fa-arrow-left placeholder-icon"></i>
                    <h4 class="text-muted">Select a visualization from the library</h4>
                    <p class="text-muted">Click an item on the right to view it here.</p>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Navigation -->
        <div class="col-lg-4 col-md-12 border-start">
            <div class="sidebar-wrapper card h-100 rounded-0 border-0 bg-body">
                <div class="card-header p-3">
                    <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Visualization Library</h5>
                </div>
                <div class="card-body">
                    <div class="accordion accordion-flush" id="visualizationAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMaps" aria-expanded="true"><i class="fas fa-map-marked-alt fa-fw me-2"></i>Interactive Maps</button></h2>
                            <div id="collapseMaps" class="accordion-collapse collapse show" data-bs-parent="#visualizationAccordion">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="iframe" data-key="maps/fire_heatmap_interactive" data-title="Interactive Heatmap (Basic)" data-description="A general overview of fire locations, showing density based on fire intensity.">Heatmap (Basic)</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="iframe" data-key="maps/fire_heatmap_multi_scale" data-title="Interactive Heatmap (Multi-Scale)" data-description="This map adapts its display based on zoom level for better detail.">Heatmap (Multi-Scale)</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="iframe" data-key="maps/fire_interactive_threshold" data-title="Interactive Threshold Heatmap" data-description="Use the slider to filter and visualize only high-density fire areas in real-time.">Heatmap (Live Threshold)</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="iframe" data-key="maps/fire_heatmap_threshold" data-title="High Concentration Areas" data-description="A map showing only the most fire-dense regions based on a predefined threshold.">High Concentration Map</a>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnalysis"><i class="fas fa-chart-bar fa-fw me-2"></i>Data Analysis</button></h2>
                            <div id="collapseAnalysis" class="accordion-collapse collapse" data-bs-parent="#visualizationAccordion">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="img" data-key="images/fire_by_state" data-title="Fire Count by State" data-description="A bar chart showing the approximate number of fires per major state.">Fire Count by State</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="img" data-key="images/fire_spread_scatter" data-title="Digitized Fire Spread" data-description="A digitized scatter plot illustrating the geographic spread of recorded fire incidents.">Geographic Spread Plot</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="img" data-key="images/fire_threshold_analysis" data-title="Fire Density Analysis" data-description="A comparison of all fire activity vs. activity in high-concentration areas.">Density Analysis</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="img" data-key="images/fire_seasonal_patterns" data-title="Fire Seasonal Patterns" data-description="Analysis of fire occurrences aggregated by month to show seasonal trends.">Seasonal Patterns</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="img" data-key="images/fire_geographic_hotspots" data-title="Geographic Fire Hotspots" data-description="A kernel density estimation plot highlighting the most concentrated geographic hotspots of fire activity.">Geographic Hotspots</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="img" data-key="images/fire_intensity_analysis" data-title="Fire Intensity Analysis" data-description="A histogram showing the distribution of fire radiative power (FRP) to analyze event intensity.">Intensity Analysis</a>
                                    <a href="#" class="list-group-item list-group-item-action visualization-link" data-type="img" data-key="images/satellite_comparison" data-title="Satellite Data Comparison" data-description="A comparison of fire detections between different satellite systems.">Satellite Comparison</a>
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

@include('partials.theme_settings')
@include('partials.scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.6/viewer.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelector = document.getElementById('region-selector');
    const links = document.querySelectorAll('.visualization-link');
    const viewerContent = document.getElementById('viewer-content');
    const placeholder = document.getElementById('viewer-placeholder');
    const titleEl = document.getElementById('visualization-title');
    const descriptionEl = document.getElementById('visualization-description');
    const container = document.getElementById('visualization-container');
    const exportButton = document.getElementById('export-button');
    let imageViewer = null;
    let assetBaseUrl = "{{ rtrim(asset(''), '/') }}";

    function updateAssetPaths() {
        const region = regionSelector.value;
        
        links.forEach(link => {
            const key = link.dataset.key;
            const extension = link.dataset.type === 'iframe' ? '.html' : '.png';
            
            // Use original path for 'us', and add suffix for others
            const pathSuffix = region === 'us' ? '' : `_${region}`;
            const newSrc = `${assetBaseUrl}/${key}${pathSuffix}${extension}`;
            
            link.dataset.src = newSrc;
        });
    }

    function resetViewer() {
        if (imageViewer) {
            imageViewer.destroy();
            imageViewer = null;
        }
        links.forEach(l => l.classList.remove('active'));
        placeholder.style.display = 'flex';
        viewerContent.style.display = 'none';
        exportButton.style.display = 'none';
    }

    regionSelector.addEventListener('change', function() {
        updateAssetPaths();
        resetViewer();
        
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value !== 'us') {
            placeholder.innerHTML = `<i class="fas fa-exclamation-triangle placeholder-icon"></i><h4 class="text-muted">Data for this region is not available.</h4><p class="text-muted">Please select another region to view visualizations.</p>`;
            links.forEach(link => link.classList.add('disabled'));
        } else {
            placeholder.innerHTML = `<i class="fas fa-arrow-left placeholder-icon"></i><h4 class="text-muted">Select a visualization from the library</h4><p class="text-muted">Click an item on the right to view it here.</p>`;
            links.forEach(link => link.classList.remove('disabled'));
        }
    });

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if(this.classList.contains('disabled')) return;

            resetViewer();
            placeholder.style.display = 'none';

            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            const type = this.dataset.type;
            const src = this.dataset.src;
            const title = this.dataset.title;
            const description = this.dataset.description;

            titleEl.textContent = title;
            descriptionEl.textContent = description;
            container.innerHTML = '';

            if (type === 'iframe') {
                const iframeWrapper = document.createElement('div');
                iframeWrapper.className = 'map-container';
                iframeWrapper.innerHTML = `<iframe src="${src}" title="${title}"></iframe>`;
                container.appendChild(iframeWrapper);
            } else if (type === 'img') {
                const imgWrapper = document.createElement('div');
                imgWrapper.className = 'image-container';
                const imgEl = document.createElement('img');
                imgEl.src = src;
                imgEl.alt = title;
                imgEl.onerror = () => {
                     container.innerHTML = `<div class="d-flex align-items-center justify-content-center h-100 text-center"><div class="alert alert-warning">This visualization could not be loaded. Check file path.</div></div>`;
                     exportButton.style.display = 'none';
                };
                imgWrapper.appendChild(imgEl);
                container.appendChild(imgWrapper);
                
                const region = regionSelector.value;
                const filenameSuffix = region === 'us' ? '' : `_${region}`;
                const filename = `${title.toLowerCase().replace(/[^a-z0-9]+/g, '-')}${filenameSuffix}.png`;
                exportButton.href = src;
                exportButton.setAttribute('download', filename);
                exportButton.style.display = 'block';

                imageViewer = new Viewer(imgWrapper, {
                    inline: false, zoomRatio: 0.25,
                    toolbar: { zoomIn: true, zoomOut: true, oneToOne: true, reset: true, prev: false, play: false, next: false, rotateLeft: false, rotateRight: false, flipHorizontal: false, flipVertical: false },
                    title: false, transition: true
                });
            }
            
            viewerContent.style.display = 'flex';
        });
    });

    const initMobileSidebarToggle = () => {
        const burgerMenu = document.querySelector('.header-burger-menu');
        const body = document.body;
        if (burgerMenu && body) {
            if (window.innerWidth < 992 && !body.classList.contains('sidebar-close')) {
                body.classList.add('sidebar-close');
            }
            burgerMenu.addEventListener('click', function(event) {
                if (window.innerWidth < 992) {
                    event.preventDefault();
                    event.stopPropagation();
                    body.classList.toggle('sidebar-close');
                }
            }, true);
        }
    };
    
    // Initial setup
    updateAssetPaths();
    initMobileSidebarToggle();
});
</script>
</body>
</html>