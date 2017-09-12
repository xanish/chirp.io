@component('mail::message')
# Hey, {{ $user->name }}

Follow the link below to confirm your registration at Chirp.

@component('mail::button', ['url' => 'http://chirp.io/verifyemail/'.$user->email_token])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
