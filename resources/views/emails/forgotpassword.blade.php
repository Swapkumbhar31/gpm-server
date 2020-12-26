@component('mail::message')

Dear member, please use the OTP below to reset your password 
<br>
<b>{{$token}}<b>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
