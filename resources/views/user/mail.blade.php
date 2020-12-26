@extends('layouts.layout2')

@section('content')
<div class="text-center" style="margin-top:150px;">
@if($result['status'] === 'matched')
<h3>Your Email ID has been verified successfully. Please wait while we create your dashboard!</h3>
<i class="fa fa-cog fa-spin fa-fw" style="font-size:100px" aria-hidden="true"></i>
<script>
setTimeout(() => {
    window.location.href = 'https://gainpassivemoney.com/profile';
}, 5000);
</script>
@elseif($result['status'] === 'verified')
<h3>You have already verified your email address. Please wait while we redirect you to your profile!</h3>
<i class="fa fa-cog fa-spin fa-fw" style="font-size:100px" aria-hidden="true"></i>
<script>
setTimeout(() => {
    window.location.href = 'https://gainpassivemoney.com/profile';
}, 5000);
</script>
@else
<h3>Link is expired.</h3>
<i class="fa fa-times" style="font-size:100px" aria-hidden="true"></i>
@endif
</div>
@endsection()