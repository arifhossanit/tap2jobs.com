@component('mail::message')
# Dear {{ $candidateName }},

{{ $messageBody }}

**Position:** {{ $jobTitle }}  
**Company:** {{ $companyName }}  
**Status:** {{ $statusText }}

@component('mail::button', ['url' => $actionUrl])
View Application
@endcomponent

Thanks & Regards,<br>
{{ $companyName }}
@endcomponent
