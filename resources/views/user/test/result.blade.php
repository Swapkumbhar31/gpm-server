@extends('layouts.app')

@section('breadcrumb')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Test</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Test</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
@if (session('result'))
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Result</h4>
                </div>
                <div class="card-body">
                    @if(session('result') === 'pass')
                    <p>Congratulations! You have successfully passed this chapter.</p>
                    @endif
                    @if(session('result') === 'fail')
                    <p>You have to answer all the questions correctly to pass the test!</p>
                    @endif
                </div>
                <div class="card-footer">
                    @if(session('result') === 'pass')
                    <a href="/view" class="btn btn-danger">Next Chapter</a>
                    @endif
                    @if(session('result') === 'fail')
                    <a href="/test/start" class="btn btn-danger">Retry</a>
                    <a href="/view" class="btn btn-warning">Watch video again</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(session('result') === 'fail')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
            @php($x = 0)
            @foreach(session('questions') as $question)
            @php ($x++)
                <div class="card mb-4">
                    <div class="card-body">
                        <p>{{$x}}. {{$question->question}}</p>
                        <ul>
                        @if($question->option1 !== 'N/A')
                            <li>{{$question->option1}}</li>
                        @endif
                        @if($question->option2 !== 'N/A')
                            <li>{{$question->option2}}</li>
                        @endif
                        @if($question->option3 !== 'N/A')
                            <li>{{$question->option3}}</li>
                        @endif
                        @if($question->option4 !== 'N/A')
                            <li>{{$question->option4}}</li>
                        @endif
                        </ul>
                        <p>Your answer : {{$question->user_answer}}</p>
                        <p>Correct answer : {{$question->answer}}</p>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
    @endif
@endif
@endsection()