@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7">
            <a class="btn btn-primary" href="{{ route('event') }}">Back</a>
            {{ Form::open(array('url' => '/admin/event/edit', 'method' => 'post', 'id'=>'saveForm', 'class' => 'mt-5'))
            }}
            <div class="card p-2">
                <div class="form-group">
                    <label>Event Name:</label>
                    {{ Form::text('event_name', $event->event_name,array('class' => 'form-control', 'placeholder' =>
                    'Enter event name', 'required'=> 'required')) }}
                    {{Form::hidden('id', $event->id)}}
                </div>
                <div class="form-group">
                    <label>Event Description:</label>
                    {{Form::hidden('description', '')}}
                    <div id="editor-container" style="min-height: 200px;">
                        <?php
print_r($event->description);
?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Event Date:</label>
                    {{ Form::date('date', date('Y-m-d', strtotime($event->held_on)) ,array('class' => 'form-control',
                    'value' => date('Y-d-m', strtotime($event->held_on)), 'placeholder' => 'Enter event date',
                    'required'=> 'required')) }}
                </div>
                <div class="form-group">
                    <label>Event Type:</label>
                    {{ Form::select('type', array('webinar' => 'Webinar', 'seminar' => 'Seminar'), $event->type
                    ,array('class' => 'form-control', 'placeholder' => 'Select event type', 'required'=> 'required'))
                    }}
                </div>
                <div class="form-group">
                    <label>Event Time:</label>
                    {{ Form::time('time', date('H:i:s', strtotime($event->held_on)) ,array('class' => 'form-control',
                    'placeholder' => 'Enter event time', 'required'=> 'required')) }}
                </div>
                <div class="form-group">
                    <label>Event Speakers:</label>
                    @php($speakers = '')
                    @foreach(json_decode($event->speakers) as $t)
                    @php($speakers = $speakers.','.$t)
                    @endforeach()
                    {{ Form::textarea('speakers', substr($speakers, 1, strlen($speakers)),array('class' => 'form-control','rows' => '5',
                    'placeholder' => 'Enter event name', 'required'=> 'required')) }}
                    <small>Use comma for multiple speakers</small>
                </div>
                <div class="form-group">
                    <label>Event Topics:</label>
                    @php($topics = '')
                    @foreach(json_decode($event->topics) as $t)
                    @php($topics =  $topics.',' .$t)
                    @endforeach()
                    {{ Form::textarea('topics', substr($topics, 1, strlen($speakers)),array('class' => 'form-control','rows' => '5',
                    'placeholder' => 'Enter event name', 'required'=> 'required')) }}
                    <small>Use comma for multiple topics</small>
                </div>
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
        placeholder: 'Compose an epic...',
        theme: 'snow'
    });
    $('#saveForm').submit(() => {
        var description = document.querySelector('input[name=description]');
        description.value = quill.root.innerHTML;
    });
</script>
@endsection