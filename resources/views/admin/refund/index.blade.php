@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="row justify-content-between">
                <div class="col">
                    <a class="btn btn-primary mb-4" href="{{ route('home') }}">Back</a>
                </div>
            </div>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">contact</th>
                            <th scope="col">Amount earned</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                        	<td> {{$user->name}} </td>
                        	<td> {{$user->email}} </td>
                        	<td> {{$user->contact}} </td>
                        	<td> {{$user->total}} </td>
                        	<td>
                        	  {{ Form::open(array('url' => '/payment/pay/confirm/'.$user->id, 'method' => 'post')) }}
                                {{Form::submit('Pay', array('class' => 'btn btn-primary btn-sm'))}}
                                {{ Form::close() }}
                        	</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>

</script>
@endsection
