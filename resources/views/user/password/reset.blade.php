@component('mail::message')
# Introduction

Dear User,
You have successfully reset your password. Click on the below button to login to your account.

@component('mail::button', ['url' => 'https://fliteracy.gainpassivemoney.com/login'])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
