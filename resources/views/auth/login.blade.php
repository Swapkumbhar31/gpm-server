@extends('layouts.layout2')

@section('content')
<div class="container">
    <div class="row justify-content-md-center mt-5 mb-5">
        <div class="col-md-8  my-auto">
        <div class="card login-card">
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <h4 class="card-title">Login</h4>
                    @if(session('msg'))
                    <div class="alert alert-danger">
                        <span>{{session('msg')}}</span>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label for="email" class="col-sm-3 text-left control-label col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 text-left control-label col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                        </div>
                    </div>
                </div>
                <div class="border-top">
                    <div class="card-body row">
                        <input type="submit" class="btn btn-primary col-sm-3" value="Login">
                        <div class="col-sm-9">
                                <!-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a> -->
                            </div>
                    </div>
                </form>
                </div>
        </div>
        </div>
    </div>
</div>
@endsection
