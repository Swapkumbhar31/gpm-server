@component('mail::message')
@component('mail::panel')
<p>Dear Member,<br> Thank you for registering on gainpassivemoney.com! Please use the following credentials for unlocking the world of financial literacy. Also, make sure to reset your password once you login to your profile.</p>
Username : {{$username}} <br>
Password : {{$password}}
@component('mail::button', ['url' => 'http://gainpassivemoney.com/login'])
Click here to connect
@endcomponent
@endcomponent
Thanks,<br>
{{ config('app.name') }}
@endcomponent
