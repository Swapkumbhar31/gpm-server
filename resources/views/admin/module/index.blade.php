@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row justify-content-between">
                <div class="col">
                    <a href="{{ route('addmodule') }}" class="btn btn-primary mb-4">Add new</a>
                </div>
                <div class="col">
                    <ul class="pagination float-right">
                        <li class="page-item {{$modules->previousPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$modules->previousPageUrl()}}">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="{{$modules->url($modules->currentPage())}}">{{$modules->currentPage()}}</a></li>
                        <li class="page-item {{$modules->nextPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$modules->nextPageUrl()}}">Next</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Module name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modules as $module)
                        <tr>
                            <td>{{ $module->name}}</td>
                            <td>
                                {{ Form::open(array('url' => '/admin/module/delete/'.$module->id, 'method' =>
                                'post')) }}
                                <a class="btn btn-cyan btn-sm" href="{{ route('editmodule', $module->id)}}">Edit</a>
                                <a class="btn btn-success btn-sm" href="{{ route('chapter', $module->id)}}">View</a>
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
