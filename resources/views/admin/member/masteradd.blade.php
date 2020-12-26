@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10">
            <a class="btn btn-primary" href="{{ url()->previous() }}">Back</a>
            <div class="card mt-4">
                <div class="card-header">
                    <h3>Add Master franchise form</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach()
                        </ul>
                    </div>
                    @endif
                    {{ Form::open(array('url' => '/admin/members/add/master', 'method' => 'post', 'files' => true, 'id'
                    => 'saveForm')) }}
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>First name:</label>
                                {{ Form::text('firstname', '',array('class' => 'form-control', 'placeholder' => 'Enter first name', 'required'=> 'required')) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Last name:</label>
                                {{ Form::text('lastname', '',array('class' => 'form-control', 'placeholder' => 'Enter last name', 'required'=> 'required')) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Email:</label>
                                {{ Form::email('email', '',array('class' => 'form-control', 'placeholder' => 'Enter email', 'required'=> 'required')) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Number:</label>
                                {{ Form::number('contact', '',array('class' => 'form-control', 'placeholder' => 'Enter contact', 'required'=> 'required')) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Pancard:</label>
                                {{ Form::text('pancard', '',array('class' => 'form-control', 'placeholder' => 'Enter pancard number', 'required'=> 'required')) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Date of birth:</label>
                                {{ Form::date('dob','', array('class' => 'form-control', 'placeholder' => 'Enter dob', 'required'=> 'required'))
                                }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Address:</label>
                                {{ Form::text('address','', array('class' => 'form-control', 'placeholder' => 'Enter address', 'required'=> 'required')) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>City:</label>
                                {{ Form::text('city','', array('class' => 'form-control', 'placeholder' => 'Enter city', 'required'=> 'required')) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>State:</label>
                                {{ Form::text('state','', array('class' => 'form-control', 'placeholder' => 'Enter state', 'required'=> 'required')) }}
                            </div>
                        </div>
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
@endsection
