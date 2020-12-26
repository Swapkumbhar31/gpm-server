@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="row justify-content-between">
                <div class="col">
                    <a href="{{ route('addmedia') }}" class="btn btn-primary mb-4">Add</a>
                </div>
                <div class="col">
                    <ul class="pagination float-right">
                        <li class="page-item {{$media->previousPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$media->previousPageUrl()}}">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="{{$media->url($media->currentPage())}}">{{$media->currentPage()}}</a></li>
                        <li class="page-item {{$media->nextPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$media->nextPageUrl()}}">Next</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">File name</th>
                            <th scope="col">File size</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($media as $m)
                        <tr>
                            <td>{{ $m->originalFileName}}</td>
                            <td>{{ $m->size}}</td>
                            <td>
                                {{ Form::open(array('url' => '/admin/media/delete/'.$m->filename, 'method' => 'post')) }}
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