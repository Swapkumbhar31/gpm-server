@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- <videoplayer></videoplayer> -->
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

                <div class="card description">
                    <div class="card-header text-center" data-toggle="collapse" href="#collapseTwo" style="background: #001747;cursor:pointer">
                        <a class="collapsed card-link text-white font-18">
                            <b>Read chapter</b>
                        </a>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-body">
                            <?php
                                echo $chapter->description;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            @if(Auth::user()->isModuleCompleted === "0")
            @if(isset($chapter->isFinised) && $chapter->isFinised)
                @if($chapter->nextChapter === null)
                    @if($chapter->ongoing)
                        <a class="card-footer btn btn-success bg-success" href="/test/start">Test your knowledge</a>
                    @else
                        <a class="card-footer btn btn-success bg-success" href="/">View Arenas</a>
                    @endif
                @else
                    <a class="card-footer btn btn-success bg-success" href="/view/{{$chapter->nextChapter->id}}">Next Chapter</a>
                @endif
            @else
                <a class="card-footer btn btn-success bg-success" href="/test/start">Test your knowledge</a>
            @endif()
            @endif
        </div>
    </div>
</div>
<div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Congratulations on finishing the video! Please test your knowledge by clicking the button below!
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="testBtn" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<style>
    .description img {
        width: 50%;
        margin-left: 25%;
    }

    .modal.show {
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    .modal.show .modal-dialog {
        width: 400px;
    }
</style>
<script>
    $(document).ready(function () {
        $('#readless').hide();
        $('#readmorebtn').click(() => {
            $('#readmore').hide();
            $('#readless').show();
        });
        $('#readlessbtn').click(() => {
            $('#readless').hide();
            $('#readmore').show();
        });
        $('#testBtn').click(() => {
            window.location.href = '/test/start';
        });
        $("video").on("contextmenu", function () {
            return false;
        });
        $('video').on('ended', function () {
            $('#alert').modal('show');
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
