@extends('layouts.layout2')

@section('content')
<div class="text-center" style="margin-top:150px;">

<h3>Your account upgraded to core successfully. Please wait while we create your core membership  dashboard!</h3>
<i class="fa fa-cog fa-spin fa-fw" style="font-size:100px" aria-hidden="true"></i>
<script>
setTimeout(() => {
    window.location.href = 'https://gainpassivemoney.com/profile';
}, 5000);
</script>
</div>
@endsection()