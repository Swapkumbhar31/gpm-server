@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row justify-content-between">
                <div class="col">
                    <a href="{{ route('addevnet') }}" class="btn btn-primary mb-4">Add</a>
                </div>
                <div class="col">
                    <ul class="pagination float-right">
                        <li class="page-item {{$events->previousPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$events->previousPageUrl()}}">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="{{$events->url($events->currentPage())}}">{{$events->currentPage()}}</a></li>
                        <li class="page-item {{$events->nextPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$events->nextPageUrl()}}">Next</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Event Name</th>
                            <th scope="col">Event Type</th>
                            <th scope="col">Scheduled on</th>
                            <th scope="col">Topic</th>
                            <th scope="col">Speaker Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $m)
                        <tr>
                            <td>{{ $m->event_name}}</td>
                            <td class="text-capitalize">{{ $m->type}}</td>
                            <td>{{ $m->held_on}}</td>
                            <td>
                             @foreach(json_decode($m->topics) as $t)
                             {{$t}} 
                             @endforeach()
                            </td>
                            <td>
                             @foreach(json_decode($m->speakers) as $t)
                             {{$t}} 
                             @endforeach()
                            </td>
                            <td>
                                {{ Form::open(array('url' => '/admin/event/delete/'.$m->id, 'method' => 'post')) }}
                                <a href="{{route('editevent', $m->id)}}" class="btn btn-success btn-sm">Edit</a>
                                {{Form::submit('Delete', array('class' => 'btn btn-danger btn-sm'))}}
                                {{ Form::close() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>

@endsection

@section('script')
<script>

</script>
@endsection