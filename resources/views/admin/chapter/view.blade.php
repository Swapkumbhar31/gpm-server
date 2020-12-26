@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-primary" href="{{ route('chapter', $chapter->module_id) }}">Back</a>
            <div class="card mt-4">
                <div class="card-body">
                    <h3>{{ $chapter->name}}</h3>
                    <div class="container-fluid mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{$image_path}}" class="img-fluid">
                            </div>
                            <div class="col-md-8">
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
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Questions
                    <a href="/admin/question/add" class="btn btn-primary btn-sm float-right">Add new</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $question)
                            <tr>
                                <td>
                                    {{ $question->question}}
                                    <ol>
                                        <li>{{ $question->option1}}</li>
                                        <li>{{ $question->option2}}</li>
                                        <li>{{ $question->option3}}</li>
                                        <li>{{ $question->option4}}</li>
                                    </ol>
                                    <p>Answer: {{ $question->answer}}</p>
                                </td>
                                <td>
                                    {{ Form::open(array('url' => '/admin/question/delete/'.$question->id, 'method'
                                    => 'post')) }}
                                    <a class="btn btn-cyan btn-sm" href="/admin/question/edit/{{$question->id}}">Edit</a>
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
</div>

@endsection

@section('script')
<style>
        .description img {
            width: 100%;
        }
    </style>
@endsection