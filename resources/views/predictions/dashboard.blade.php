<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>US Fire Heatmaps</title>
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .map-container {
            width: 100%;
            height: 600px; /* Adjust height as needed */
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden; /* Important for iframes */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .static-image {
            width: 100%;
            max-width: 1000px; /* Adjust max-width for static images */
            height: auto;
            display: block;
            margin: 0 auto 30px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #0056b3;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <h1>Historical US Fire Activity Visualizations</h1>
    <p>Explore various heatmaps and analyses of historical fires across the United States.</p>

    <h2>Interactive Heatmap (Basic)</h2>
    <p>A general overview of fire locations, showing density based on fire intensity.</p>
    <div class="map-container">
        <iframe src="{{ asset('maps/fire_heatmap_interactive.html') }}"></iframe>
    </div>

    <h2>Interactive Heatmap (Multi-Scale)</h2>
    <p>This map adapts its display based on zoom level for better detail.</p>
    <div class="map-container">
        <iframe src="{{ asset('maps/fire_heatmap_multi_scale.html') }}"></iframe>
    </div>

    <h2>Interactive Threshold Heatmap (Real-time Control)</h2>
    <p>Use the slider to filter and visualize only high-density fire areas in real-time.</p>
    <div class="map-container">
        <iframe src="{{ asset('maps/fire_interactive_threshold.html') }}"></iframe>
    </div>

    <h2>High Fire Concentration Areas (Threshold-based)</h2>
    <p>A static view showing only the most fire-dense regions based on a predefined threshold.</p>
    <div class="map-container">
        <iframe src="{{ asset('maps/fire_heatmap_threshold.html') }}"></iframe>
    </div>

    <h2>Static Fire Activity Heatmap</h2>
    <p>A high-resolution static image of overall fire activity.</p>
    <img src="{{ asset('images/fire_heatmap_static.png') }}" alt="Static Fire Heatmap" class="static-image">

    <h2>Fire Density Analysis: Before and After Threshold</h2>
    <p>Comparison of all fire activity vs. activity in high-concentration areas.</p>
    <img src="{{ asset('images/fire_threshold_analysis.png') }}" alt="Fire Threshold Analysis" class="static-image">

    <h2>Fire Count by State (Approximate)</h2>
    <p>A bar chart showing the approximate number of fires per major state.</p>
    <img src="{{ asset('images/fire_by_state.png') }}" alt="Fire by State" class="static-image">

</body>
</html>