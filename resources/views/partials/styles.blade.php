<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- PWA Theme Color -->
    <meta name="theme-color" content="#4A90E2">
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/sidebar-menu.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/simplebar.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/apexcharts.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/prism.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/rangeslider.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/quill.snow.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/google-icon.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/remixicon.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/swiper-bundle.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/fullcalendar.main.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/jsvectormap.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/lightpick.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ url('/assets/scss/style.css') }}" />
<!-- Link to your new custom stylesheet -->
<link rel="stylesheet" type="text/css" href="{{ url('/assets/css/custom-dashboard.css') }}" />
<link rel="icon" type="image/png" href="{{ url('/assets/images/favicon.png?v=2') }}">




<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
</script>