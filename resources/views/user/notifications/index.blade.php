@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
						<div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Notifications</h4>
                            </div>
                            <div class="comment-widgets scrollable">
                            	@foreach($notifications as $notification)
                                <!-- Comment Row -->
                                <div class="d-flex flex-row comment-row">
                                    <div class="p-2">
										<i class="mdi mdi-bell font-24 rounded-circle"></i></div>
                                    <div class="comment-text w-100">
                                        <h6 class="font-medium">{{$notification->message}}</h6>
                                        <div class="comment-footer">
                                            <span class="text-muted float-right time" datetime="{{$notification->created_at}}">August 1, 2016</span> 
                                            <!-- <button type="button" class="btn btn-cyan btn-sm">Mark as read</button> -->
                                            {{ Form::open(array('url' => '/notifications/delete/'.$notification->id, 'method' => 'post')) }}
			                                {{Form::submit('Delete', array('class' => 'btn btn-danger btn-sm'))}}
			                                {{ Form::close() }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection