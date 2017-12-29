@component('mail::message')
Confirm this shiz son!

You need to verify your email address!

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
