@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <a class="btn btn-primary" href="{{ url()->previous() }}">Back</a>
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Add Module form</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(array('url' => route('savemodule'), 'method' => 'post', 'id' =>
                    'saveForm')) }}
                    <div class="form-group">
                        <label>Name:</label>
                        {{ Form::text('name', '',array('class' => 'form-control', 'placeholder' => 
                            'Enter module name', 'required'=> 'required')) }}
                    </div>
                    <div class="form-group">
                        <label>Index</label>
                        {{Form::number('mod_index', '', array('class' => 'form-control',
                        'placeholder' => 'Enter module index', 'required'=> 'required'))}}
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        {{Form::hidden('description', '')}}
                        <div id="editor-container" style="height: 200px">
                        </div>
                    </div>
                    {{Form::submit('Update', array('class' => 'btn btn-primary mt-3'))}}
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