@if(config('app.ga4_measurement_id'))
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.ga4_measurement_id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', '{{ config('app.ga4_measurement_id') }}', { anonymize_ip: true });
</script>
@endif
