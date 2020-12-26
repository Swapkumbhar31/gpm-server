@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7">
            <a class="btn btn-primary" href="{{ route('event') }}">Back</a>
            {{ Form::open(array('url' => '/admin/event/add', 'method' => 'post', 'id'=>'saveForm', 'class' => 'mt-5')) }}
            <div class="card p-2">
                <div class="form-group">
                    <label>Event Name:</label>
                    {{ Form::text('event_name', '',array('class' => 'form-control', 'placeholder' => 'Enter event name', 'required'=> 'required')) }}
                </div>
                <div class="form-group">
                    <label>Event Description:</label>
                    {{Form::hidden('description', '')}}
                    <div id="editor-container" style="min-height: 200px;">
                    </div>
                </div>
                <div class="form-group">
                    <label>Event Date:</label>
                    {{ Form::date('date', '',array('class' => 'form-control', 'placeholder' => 'Enter event date', 'required'=> 'required')) }}
                </div>
                <div class="form-group">
                    <label>Event Type:</label>
                    {{ Form::select('type', array('webinar' => 'Webinar', 'seminar' => 'Seminar'), null ,array('class' => 'form-control', 'placeholder' => 'Select event type', 'required'=> 'required')) }}
                </div>
                <div class="form-group">
                    <label>Event Time:</label>
                    {{ Form::time('time', '',array('class' => 'form-control', 'placeholder' => 'Enter event time', 'required'=> 'required')) }}
                </div>
                <div class="form-group">
                    <label>Event Speakers:</label>
                    {{ Form::textarea('speakers', '',array('class' => 'form-control','rows' => '5', 'placeholder' => 'Enter speakers', 'required'=> 'required')) }}
                    <small>Use comma for multiple speakers</small>
                </div>
                <div class="form-group">
                    <label>Event Topics:</label>
                    {{ Form::textarea('topics', '',array('class' => 'form-control','rows' => '5', 'placeholder' => 'Enter topics', 'required'=> 'required')) }}
                    <small>Use comma for multiple topics</small>
                </div>
                {{Form::hidden('audience', '1')}}  
                {{Form::submit('Save', array('class' => 'btn btn-primary mt-3'))}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    var quill = new Quill('#editor-container', {
        modules: {
            toolbar: [
                [{
                    header: [1, 2, false]
                }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block'],
                [{
                    list: 'ordered'
                }, {
                    list: 'bullet'
                }]
            ]
        },
        placeholder: 'Add description here...',
        theme: 'snow' // or 'bubble'
    });
    $('#saveForm').submit(() => {
        var description = document.querySelector('input[name=description]');
        description.value = quill.root.innerHTML;
    });
</script>
@endsection
