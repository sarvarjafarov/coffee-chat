@php($analyticsId = config('services.google_analytics.measurement_id'))
@if(! empty($analyticsId))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $analyticsId }}');
    </script>
@endif
