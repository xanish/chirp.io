@component('mail::message')
# Hey, {{ $user->name }}

You have successfully changed your email from {{ $user->email }} to {{ $newmail }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
