@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row text-center">
                        @if (Auth::user()->checkAdmin())
                        <div class="col-sm-4">
                            <a href="/management">
                                <p><img width="50" src="{{asset('images/folder-management.png')}}" class="img-thumbnail"  /></p>
                                <h4>Management</h4>
                            </a>
                        </div>
                        @endif
                        <div class="col-sm-4">
                            <a href="/cashier">
                                <p><img width="50" src="{{asset('images/cash-machine.png')}}" class="img-thumbnail" /></p>
                                <h4>Cashier</h4>
                            </a>
                        </div>
                        @if (Auth::user()->checkAdmin())
                        <div class="col-sm-4">
                            <a href="/report">
                                <p><img width="50" src="{{asset('images/business-report.png')}}" class="img-thumbnail" /></p>
                                <h4>Report</h4>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
