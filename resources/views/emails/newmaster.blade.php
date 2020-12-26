@component('mail::message')
@component('mail::panel')
Dear, {{$name}}
GPM Community added you as a Master Franchise.<br>
Access your account from the details provided below :<br>
Username : {{$username}} <br>
Password : {{$password}} <br>
@component('mail::button', ['url' => 'http://gainpassivemoney.com/login'])
Login Now
@endcomponent
@endcomponent
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
