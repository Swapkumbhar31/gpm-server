@component('mail::message')
<h2>Welcome to Gain Passive Money! </h2>
<p>Hi {{$name}},</p>
<p>Dear Member, thanks for joining GPM Community! 
To complete the account verification process, please click on the link below - </p>

@component('mail::button', ['url' => 'http://fliteracy.gainpassivemoney.com/verify/mail/'.$link.'/'.$user_id])
Verify my email address 
@endcomponent

cheers,<br>
{{ config('app.name') }}
@endcomponent
