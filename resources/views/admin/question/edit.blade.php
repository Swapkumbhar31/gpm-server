@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7">
            <a class="btn btn-primary" href="{{ url()->previous() }}">Back</a>
            {{ Form::open(array('url' => '/admin/question/edit/'.$question->id, 'method' => 'post', 'class' => 'mt-5')) }}
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Question</label>
                        {{Form::textarea('question', $question->question,array('class' => 'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label>Option 1</label>
                        {{Form::text('option1', $question->option1,array('class' => 'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label>Option 2</label>
                        {{Form::text('option2', $question->option2,array('class' => 'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label>Option 3</label>
                        {{Form::text('option3', $question->option3,array('class' => 'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label>Option 4</label>
                        {{Form::text('option4', $question->option4,array('class' => 'form-control'))}}
                    </div>
                    <div class="form-group">
                        <label>Answer</label>
                        {{Form::select('answer', array(
                        '1' => 'Option1',
                        '2' => 'Option2',
                        '3' => 'Option3',
                        '4' => 'Option4'
                        ), $question->answer,array('class' => 'form-control'))}}
                    </div>
                </div>
                {{Form::hidden('chapter_id',  $question->chapter_id)}}
                {{Form::submit('Save', array('class' => 'btn btn-primary mt-3'))}}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>


</script>
@endsection