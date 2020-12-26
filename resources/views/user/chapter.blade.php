@extends('layouts.app')

@section('breadcrumb')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Arena</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')

<div class="container-fluid">
    <h2>{{$module->name}}</h2>
    <!-- <video id="myVideo" class="card-img video-js mb-5 vjs-big-play-centered" controls preload="none" width="100%" height="264"
        data-setup='{"fluid": true , "center-big-play-button": true}' poster="{{ asset('images/'.$image) }}">
        <source src="http://fliteracy.gainpassivemoney.com/video/{{$video}}.mp4" type="video/mp4">
    </video> -->
    <div class="row el-element-overlay">
          <div class="col-lg-3 col-md-6 col-6">
             <div class="card">
                  <div class="el-card-item">
                     <div class="el-card-avatar el-overlay-1">
                          <img src="/chapters/thumbnail/{{$image}}"
                              alt="chapter" />
                          <div class="el-overlay">
                              <ul class="list-style-none el-info">
                                  <li class="el-item">
                                        <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/view/video/{{$video}}">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                  </li>
                              </ul>
                          </div>
                     </div>
                     <div class="el-card-content">
                          <h4 class="m-b-0">Introduction</h4>
                     </div>
                  </div>
             </div>
          </div>
        @foreach($chapters as $chapter)
        <div class="col-lg-3 col-md-6 col-6">
            <div class="card">
                <div class="el-card-item">
                    <div class="el-card-avatar el-overlay-1">
                        <img src="/chapters/thumbnail/{{$chapter->pic}}.{{$chapter->image ? $chapter->image->extention : 'null'}}"
                            alt="chapter" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <li class="el-item">
                                    @if($chapter->isCompleted)
                                    <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/view/{{$chapter->id}}">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    @else
                                    <a class="btn default btn-outline image-popup-vertical-fit el-link" href="javascrip:void(0)"
                                        data-toggle="modal" data-target="#alert">
                                        <i class="mdi mdi-lock"></i>
                                    </a>
                                    @endif()
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="el-card-content">
                        <h4 class="m-b-0">{{$chapter->name}}</h4>
                    </div>
                </div>
            </div>
        </div>
    @endforeach()
    @if(isset($story_video))
    <div class="col-lg-3 col-md-6 col-6">
      <div class="card">
            <div class="el-card-item">
               <div class="el-card-avatar el-overlay-1">
                    <img src="/chapters/thumbnail/{{$story_image}}"
                        alt="chapter" />
                    <div class="el-overlay">
                        <ul class="list-style-none el-info">
                            <li class="el-item">
                                  <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/view/video/{{$story_video}}">
                                      <i class="mdi mdi-eye"></i>
                                  </a>
                            </li>
                        </ul>
                    </div>
               </div>
               <div class="el-card-content">
                    <h4 class="m-b-0">Case Study</h4>
               </div>
            </div>
      </div>
    </div>
    @endif()
    @if(isset($conclusion_video))
    <div class="col-lg-3 col-md-6 col-6">
      <div class="card">
            <div class="el-card-item">
               <div class="el-card-avatar el-overlay-1">
                    <img src="/chapters/thumbnail/{{$conclusion_image}}"
                        alt="chapter" />
                    <div class="el-overlay">
                        <ul class="list-style-none el-info">
                            <li class="el-item">
                                  <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/view/video/{{$conclusion_video}}">
                                      <i class="mdi mdi-eye"></i>
                                  </a>
                            </li>
                        </ul>
                    </div>
               </div>
               <div class="el-card-content">
                    <h4 class="m-b-0">Conclusion</h4>
               </div>
            </div>
      </div>
    </div>
    @endif()
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
            You have to pass the previous chapter to unlock this chapter!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $("video").on("contextmenu", function () {
            return false;
        });
    });
</script>
@endsection()
