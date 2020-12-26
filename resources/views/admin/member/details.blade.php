@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="actions mb-3">
        <a class="btn btn-primary" href="{{ route('students') }}">Back</a>
        <!-- <div class="float-right">
            @if(intval($user->adminApproval) === 0)
            {{ Form::open(array('url' => route('approve'), 'method' =>
            'post')) }}
            {{Form::hidden('id', $user->id)}}
            {{Form::hidden('api_key', Auth::user()->api_key)}}
            {{Form::submit('Approve', array('class' => 'btn btn-success'))}}
            {{ Form::close() }}
            @endif()
        </div> -->
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3">
                    <img src="/api/profile/pic/thumbnail/{{$user->profile_img_url ? $user->profile_img_url : 'null'}}"
                        class="img-fluid">
                </div>
                <div class="col-lg-9">
                    <div class="container-fluid">
                        <h3>Basic details</h3>
                        <div class="row">
                            <div class="col-md-3">Id</div>
                            <div class="col-md-9">{{$user->id}}</div>
                            <div class="col-md-3">Name</div>
                            <div class="col-md-9">{{$user->name}}</div>
                            <div class="col-md-3">E-mail</div>
                            <div class="col-md-9">{{$user->email}}</div>
                            <div class="col-md-3">Contact</div>
                            <div class="col-md-9">{{$user->contact}}</div>
                            <div class="col-md-3">Date of Birth</div>
                            <div class="col-md-9">{{date_format(date_create($user->dob),"d/m/Y")}}</div>
                            <div class="col-md-3">Address</div>
                            <div class="col-md-9">{{$user->address}}</div>
                            <div class="col-md-3">City</div>
                            <div class="col-md-9">{{$user->city}}</div>
                            <div class="col-md-3">State</div>
                            <div class="col-md-9">{{$user->state}}</div>
                            <div class="col-md-3">Membership</div>
                            <div class="col-md-9">{{$user->membership}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="container-fluid">
                        <h3>Bank details and Identity</h3>
                        <div class="row">
                            <div class="col-md-3">Id</div>
                            <div class="col-md-9">{{$user->id}}</div>
                            @if($user->bankdetails)
                            <div class="col-md-3">Bank Name</div>
                            <div class="col-md-9">{{$user->bankdetails->bank_name}}</div>
                            <div class="col-md-3">Account Number</div>
                            <div class="col-md-9">{{$user->bankdetails->account_number}}</div>
                            <div class="col-md-3">IFSC</div>
                            <div class="col-md-9">{{$user->bankdetails->ifsc}}</div>
                            <div class="col-md-3">Name</div>
                            <div class="col-md-9">{{$user->bankdetails->name}}</div>
                            @endif()
                            <div class="col-md-3">Pancard</div>
                            <div class="col-md-9 text-uppercase">{{$user->pancard}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection()
