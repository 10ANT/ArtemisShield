// public/js/wildfire-layers.js

// Using an IIFE to not pollute the global scope
(function(window) {
    'use strict';

    // This object will be exposed to the global window object
    const WildfireLayers = {};

    let perimetersLayerGroup = null;

    // Helper function to format ugly timestamps into readable dates
    function formatDate(timestamp) {
        if (!timestamp || timestamp < 0) return 'N/A';
        try {
            return new Date(timestamp).toLocaleString([], {
                year: 'numeric', month: 'numeric', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
        } catch (e) {
            return 'Invalid Date';
        }
    }

    // Function to initialize the layer and return it to the main map script
    WildfireLayers.init = function(map) {
        perimetersLayerGroup = L.layerGroup();
        return perimetersLayerGroup;
    };

    // Main function to load and display the data
    WildfireLayers.loadData = async function() {
        if (!perimetersLayerGroup) {
            console.error("Perimeters layer has not been initialized.");
            return;
        }

        const loader = document.getElementById('main-loader');
        loader.classList.remove('d-none'); // Show loader
        
        perimetersLayerGroup.clearLayers(); // Clear old data before fetching new

        try {
            const response = await axios.get('/api/wildfire-perimeters');
            const geojsonData = response.data;

            if (!geojsonData || !geojsonData.features) return;
            
            // Use Leaflet's built-in GeoJSON handling
            const geoJsonLayer = L.geoJSON(geojsonData, {
                style: function(feature) {
                    // Style for the perimeter polygon
                    return {
                        color: "#e53e3e", // A strong red
                        weight: 2,
                        opacity: 0.8,
                        fillOpacity: 0.2
                    };
                },
                onEachFeature: function(feature, layer) {
                    // For each fire, create a popup and a custom icon at the center
                    const props = feature.properties;

                    // Build the popup content with checks for null values
                    const popupContent = `
                        <div class="fire-popup" style="max-width: 300px;">
                            <h6 class="mb-1">${props.poly_IncidentName || 'Unknown Incident'}</h6>
                            <p class="mb-1 small text-muted">${props.UniqueFireIdentifier || props.poly_IRWINID || ''}</p>
                            <hr class="my-1">
                            <p class="mb-1"><strong>Type:</strong> ${props.IncidentTypeCategory || 'N/A'}</p>
                            <p class="mb-1"><strong>Discovered:</strong> ${formatDate(props.FireDiscoveryDateTime)}</p>
                            <p class="mb-1"><strong>Cause:</strong> ${props.FireCause || 'N/A'}</p>
                            <p class="mb-1"><strong>Size:</strong> ${props.poly_GISAcres ? props.poly_GISAcres.toFixed(2) + ' acres' : 'N/A'}</p>
                            <p class="mb-2"><strong>Contained:</strong> ${formatDate(props.ContainmentDateTime)}</p>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">Perimeter from ${props.poly_Source || 'N/A'} (${formatDate(props.poly_PolygonDateTime)})</small>
                            <small class="text-muted d-block" style="font-size: 0.7rem;">Attributes from ${props.attr_Source || 'N/A'} (${formatDate(props.attr_ModifiedOnDateTime_dt)})</small>
                        </div>
                    `;
                    
                    layer.bindPopup(popupContent);

                    // Add a custom icon at the center of the polygon
                    if (layer.getBounds && typeof layer.getBounds === 'function') {
                        const bounds = layer.getBounds();
                        if (bounds.isValid()) {
                            const center = bounds.getCenter();
                            const icon = L.divIcon({
                                className: 'official-fire-icon',
                                html: '<i class="fas fa-certificate fa-lg" style="color: #c53030; text-shadow: 0 0 3px black;"></i>',
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            });
                            const marker = L.marker(center, { icon: icon }).bindPopup(popupContent);
                            perimetersLayerGroup.addLayer(marker);
                        }
                    }
                }
            });
            // It's important to add the created GeoJSON layer to our group
            perimetersLayerGroup.addLayer(geoJsonLayer);
            
        } catch (error) {
            console.error("Failed to load official wildfire perimeters:", error);
            alert("Could not load official wildfire data. Please check the console for details.");
        } finally {
            loader.classList.add('d-none'); // Hide loader
        }
    };

    // Expose the WildfireLayers object to the window
    window.WildfireLayers = WildfireLayers;

})(window);