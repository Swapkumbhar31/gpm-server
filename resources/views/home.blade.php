@extends('layouts.app')

@section('breadcrumb')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Dashboard</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- <videoplayer></videoplayer> -->
        @if($chapter)
            <div class="card">
                @if($video)
                <video id="myVideo" class="card-img video-js vjs-big-play-centered" controls preload="none" width="100%"
                    height="264" data-setup='{"fluid": true , "center-big-play-button": true}' @if($image) poster="/chapters/{{$image->filename}}.{{$image->extention}}"
                    @endif>
                    <source src="/video/{{$video->filename}}.{{$video->extention}}" type="video/mp4">
                </video>
                @else
                <div class="card-body">
                        <h2><i>No Video file available.....</i></h2>
                </div>
                @endif
                <div class="card-body">
                    <h1>{{$chapter->name}}</h1>
                    <?php
                        
                    ?>
                    <?php
                        echo substr($chapter->description, 500);
                    ?>
                </div>
                <a class="card-footer btn btn-success bg-success" href="/view">View</a>
            </div>
         @else
         <div class="card">
             <div class="card-body">
                 No Chapter to display
             </div>
         </div>
        @endif
        </div>
    <div class="col-md-4">
        <currentprogress></currentprogress>
        <nextchapters></nextchapters>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $("video").on("contextmenu",function(){
       return false;
    }); 
});
$(document).ready(function(){
	var maxLength = 300;
	$("#show-read-more").each(function(){
		var myStr = $(this).text();
		if($.trim(myStr).length > maxLength){
			var newStr = myStr.substring(0, maxLength);
			var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
			$(this).empty().html(newStr);
			$(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
			$(this).append('<span class="more-text">' + removedStr + '</span>');
		}
	});
	$(".read-more").click(function(){
		$(this).siblings(".more-text").contents().unwrap();
		$(this).remove();
	});
});
</script>
<style type="text/css">
    .show-read-more .more-text{
        display: none;
    }
</style>
@endsection()