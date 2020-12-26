@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <admin-board></admin-board>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                    <div class="card-header">
                        Current Month Earning
                    </div>
                <div class="card-body">
                    <earning-chart></earning-chart>
                </div>
            </div>
        </div>
        <!-- <div class="col-lg-6 mt-3">
            <admin-approval></admin-approval>
        </div> -->
        <div class="col-lg-6 mt-3">
            <recent-activities></recent-activities>
        </div>
        <div class="col-lg-6 mt-3">
            <div class="card">
                <div class="card-header">
                    Age Wise Report
                </div>
                <div class="card-body">
                        <age-wise-graph></age-wise-graph>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection()
