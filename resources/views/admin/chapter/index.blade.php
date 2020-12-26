@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row justify-content-between">
                <div class="col">
                    <a class="btn btn-primary mb-4" href="{{ route('modules') }}">Back</a>
                    <a href="{{ route('addchapter') }}" class="btn btn-primary mb-4">Add</a>
                </div>
                <div class="col">
                    <ul class="pagination float-right">
                        <li class="page-item {{$chapters->previousPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$chapters->previousPageUrl()}}">Previous</a></li>
                        <li class="page-item active"><a class="page-link" href="{{$chapters->url($chapters->currentPage())}}">{{$chapters->currentPage()}}</a></li>
                        <li class="page-item {{$chapters->nextPageUrl() === null ? 'disabled' : ''}}"><a class="page-link"
                                href="{{$chapters->nextPageUrl()}}">Next</a></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Chapter name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chapters as $chapter)
                        <tr>
                            <td>{{ $chapter->name}}</td>
                            <td>
                                {{ Form::open(array('url' => '/admin/chapter/delete/'.$chapter->id, 'method' =>
                                'post')) }}
                                <a class="btn btn-cyan btn-sm" href="/admin/chapter/edit/{{$chapter->id}}">Edit</a>
                                <a class="btn btn-success btn-sm" href="/admin/chapter/view/{{$chapter->id}}">View</a>
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