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
                            <th scope="col">Transaction Id</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">contact</th>
                            <th scope="col">Mode of payment</th>
                            <th scope="col">Transaction Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        <tr>
                        	<td> {{$transaction->txnid}} </td>
                        	<td> {{$transaction->email}} </td>
                              <td> {{$transaction->phone}} </td>
                        	<td> {{$transaction->mode}} </td>
                        	<td> {{$transaction->created_at}} </td>
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
