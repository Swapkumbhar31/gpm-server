@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Add Notification for</h3>
                </div>
                <div class="card-body">
                    @if(isset($status))
                    	<p class="alert alert-success">Notifications sent successfully.</p>
                    @endif
                    {{ Form::open(array('url' => '/admin/notification/add', 'method' => 'post', 'id' => 'saveForm', 'required'=> 'required')) }}
                    <div class="form-group">
                        <label>Message: </label>
                        {{ Form::textarea('message', '',array('class' => 'form-control', 'placeholder' => 'Enter your Message', 'required'=> 'required')) }}
                    </div>
                    <div class="form-group">
                        <label>Select audience: </label>
                        {{ Form::select('audience', array('1' => 'All', '2' => 'All core members', '3' => 'All basic members', '4' => 'All master members'), '1', array('class' => 'form-control', 'required'=> 'required')) }}
                    </div>
                    {{Form::submit('Add', array('class' => 'btn btn-primary mt-3'))}}
                    {{ Form::close() }}
                </div>
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
        theme: 'snow' // or 'bubble'
    });
    $('#saveForm').submit(() => {
        var description = document.querySelector('input[name=description]');
        description.value = quill.root.innerHTML;
    });
</script>
@endsection
