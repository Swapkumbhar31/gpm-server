@extends('layouts.app')

@section('content')


<video id="myVideo" class="card-img video-js mb-5 vjs-big-play-centered" controls preload="none" width="100%" height="264"
    data-setup='{"fluid": true , "center-big-play-button": true}' poster="{{ asset('images/null') }}">
    <source src="http://fliteracy.gainpassivemoney.com/video/{{$video_id}}.mp4" type="video/mp4">
</video>

@endsection

@section('script')
<script>
$(document).ready(function () {
      $("video").on("contextmenu", function () {
          return false;
      });
      $( document ).keypress(function(event) {
            if (event.which === 32) {
                  const v = $("video")[0];
                  if(v.paused) {
                        v.play();
                  } else {
                        v.pause();
                  }
                  return false;
            }
      });
});
</script>
@endsection()
