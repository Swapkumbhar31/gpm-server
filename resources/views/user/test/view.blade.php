@extends('layouts.app')

@section('breadcrumb')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Test</h4>
            <div class="ml-auto text-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Test</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <test-result-chart></test-result-chart>
        </div>
    </div>
</div>
@endsection()