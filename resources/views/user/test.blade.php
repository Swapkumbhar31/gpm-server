@extends('layouts.app')

@section('breadcrumb')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Dashboard</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
@if($questions)
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{ Form::open(array('url' => '/test/check', 'method' => 'post', 'id' =>
            'myForm')) }}
            @foreach($questions as $question)
            <div class="card mb-4">
                <div class="card-body">
                    <p>{{$question->answer}}</p>
                    <h3>{{$question->question}}</h3>

                    <div class="custom-control custom-radio">
                        <input type="radio" value="1" class="custom-control-input" id="optradio1-{{$question->id}}"
                            name="optradio-{{$question->id}}">
                        <label class="custom-control-label" for="optradio1-{{$question->id}}">{{$question->option1}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" value="2" class="custom-control-input" id="optradio2-{{$question->id}}"
                            name="optradio-{{$question->id}}">
                        <label class="custom-control-label" for="optradio2-{{$question->id}}">{{$question->option2}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" value="3" class="custom-control-input" id="optradio3-{{$question->id}}"
                            name="optradio-{{$question->id}}">
                        <label class="custom-control-label" for="optradio3-{{$question->id}}">{{$question->option3}}</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" value="4" class="custom-control-input" id="optradio4-{{$question->id}}"
                            name="optradio-{{$question->id}}">
                        <label class="custom-control-label" for="optradio4-{{$question->id}}">{{$question->option4}}</label>
                    </div>
                </div>
            </div>
            @endforeach()
            <div class="d-flex justify-content-center">
                {{Form::submit('Submit', array('class' => 'btn btn-primary'))}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="modal modal-mask">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Result</h4>
                </div>
                <div class="modal-body">
                    <p>Congratulations, You pass this chapter.</p>
                    <p>Bad luck, You failed this chapter.</p>
                </div>
                <div class="modal-footer">
                    <a href="/view" class="btn btn-danger">Next Chapter</a>
                    <a href="'/test/'+chapter_id" class="btn btn-danger">Retry</a>
                    <a href="/view" class="btn btn-warning">Watch video again</a>
                </div>

            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <test-result-chart></test-result-chart>
        </div>
    </div>
</div>
@endif
@endsection


@section('script')
<script>
    // $(document).ready(function () {
    //     $('#myForm').submit(function (e) {
    //         e.preventDefault();
    //         let formData = $('#myForm').serialize();
    //         $.post("/api/test/check", formData,
    //             function (data, status) {
    //                 alert("Data: " + data + "\nStatus: " + status);
    //             });
    //     });
    // });
</script>
@endsection()