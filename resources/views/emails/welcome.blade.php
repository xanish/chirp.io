@component('mail::message')
# Welcome aboard, {{ $user->name }}

Thank you so much for registering!

@component('mail::button', ['url' => 'http://chirp.io/'])
Start Chirping
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
