@extends('layouts.app')

@section('breadcrumb')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title"></h4>
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
      <div class="alert text-white text-center" id="alert-box" style="background-color: #1F262D">
             <span class="cursor-pointer float-rightn close text-white" id="alert-close">x</span>
            <h4> <span class="mb-2">Please watch the Introduction videos before proceeding with the 4 Arenas!</span>
                  </h4>


      </div>
    <!-- <video id="myVideo" class="card-img mb-5 video-js vjs-big-play-centered" controls preload="none" width="100%"
    poster="{{ asset('images/GPM_Intro_Thumb.jpg') }}"
        height="264" data-setup='{"fluid": true , "center-big-play-button": true}'>
        <source src="http://fliteracy.gainpassivemoney.com/video/e9a68280dab911e8a0bf138ad7ccc4a2.mp4" type="video/mp4">
    </video> -->
    <h2 class="text-center text-uppercase">Arenas</h2>
    <div class="row el-element-overlay">
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="el-card-item p-0">
                    <div class="el-card-avatar el-overlay-1 m-0">
                        <img src="{{ asset('images/GPM_Intro_Thumb.jpg') }}" alt="introduction" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <h3>Introduction</h3>
                                <li class="el-item">
                                    <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/view/video/98510260463611e9be72a184e196df89">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="col-lg-6 col-md-6">
          <div class="card">
              <div class="el-card-item p-0">
                  <div class="el-card-avatar el-overlay-1 m-0">
                      <img src="{{ asset('images/mindset.jpg') }}" alt="mindset" />
                      <div class="el-overlay">
                          <ul class="list-style-none el-info">
                              <h3>Mindset</h3>
                              <li class="el-item">
                                  <a class="btn default btn-outline image-popup-vertical-fit el-link" href="{{ route('mindset')}}">
                                      <i class="mdi mdi-eye"></i>
                                  </a>
                              </li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
      </div>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="el-card-item p-0">
                    <div class="el-card-avatar el-overlay-1 m-0">
                        <img src="{{ asset('images/spending_habbits.jpg') }}" alt="mindset" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <h3>SPENDING PATTERN</h3>
                                <li class="el-item">
                                    <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/chapter/spending-pattern">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="el-card-item p-0">
                    <div class="el-card-avatar el-overlay-1 m-0">
                        <img src="{{ asset('images/passive_income.jpg') }}" alt="mindset" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <h3>PASSIVE INCOME</h3>
                                <li class="el-item">
                                    <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/chapter/passive-income">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="el-card-item p-0">
                    <div class="el-card-avatar el-overlay-1 m-0">
                        <img src="{{ asset('images/networking.jpg') }}" alt="mindset" />
                        <div class="el-overlay">
                            <ul class="list-style-none el-info">
                                <h3>NETWORKING</h3>
                                <li class="el-item">
                                    <a class="btn default btn-outline image-popup-vertical-fit el-link" href="/chapter/networking">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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
                Kindly you need to pass previous chapters.
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
        $('#alert-close').click(() => {
             $("#alert-box").fadeOut();
       });
    });
</script>
@endsection()
