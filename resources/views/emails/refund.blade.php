@component('mail::message')
Dear Admin, {{$email}} has been rejected since it conflicts with a previously registered email ID. You are henceforth requested to please provide a refund to the concerned parties.<br>
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
