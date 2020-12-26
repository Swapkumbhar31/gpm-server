@component('mail::message')

@if(isset($request))
<ul>
    <li>Name: {{$request->name}}</li>
    <li>Email: {{$request->email}}</li>
    <li>Message: {{$request->message}}</li>
</ul>
@else
<p>Dear Member,<br> 
Thanks for contacting gainpassivemoney.com 
Our team will get back to you shortly! </p>

Thanks,<br>
{{ config('app.name') }}
@endif

@endcomponent
