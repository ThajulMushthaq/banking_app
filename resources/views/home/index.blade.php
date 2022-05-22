@extends('include.template')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Welcome {{auth()->user()->name}}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Your Id</label>
                    </div>
                </div>
                <div class="col-sm-9">
                    {{auth()->user()->email}}
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Your Balance</label>
                    </div>
                </div>
                <div class="col-sm-9">	&#8377;
                    {{auth()->user()->account_balance}}
                </div>
            </div>
        </div>
    </div>
@endsection
