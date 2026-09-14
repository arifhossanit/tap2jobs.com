@php
    $emailLogoSrc = getLogoUrl();
    $emailLogoPath = $logo_path ?? data_get($data ?? [], 'logo_path');
    $emailLogoDataUri = $logo_data_uri ?? data_get($data ?? [], 'logo_data_uri');

    if (! empty($emailLogoPath) && isset($message)) {
        $emailLogoSrc = $message->embed($emailLogoPath);
    } elseif (! empty($emailLogoDataUri)) {
        $emailLogoSrc = $emailLogoDataUri;
    }
@endphp
<img width="100"
     style="display:block;width:100px;max-width:100px;height:auto;margin:10px auto 20px;text-align:center;"
     src="{{ $emailLogoSrc }}" alt="{{ getAppName() }} logo" class="main-logo">
