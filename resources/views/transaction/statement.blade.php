@extends('include.template')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Account Statement</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>{{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ Session::get('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (isset($errors) && count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#Id</th>
                                <th>Date time</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (@$data)
                                @foreach (@$data as $d)
                                    <tr>
                                        @php
                                            if($d->source_id && $d->target_id){
                                                $details = 'Transfer from '.$d->source.' to '.$d->target;
                                            }else {
                                                if ($d->type == 'Credit') {
                                                    $details = 'Deposit';
                                                }elseif ($d->type == 'Debit') {
                                                    $details = 'Withdraw';
                                                }
                                            }
                                        @endphp
                                        <td>{{ $d->id }}</td>
                                        <td>{{ date("jS M Y, h:i A",strtotime(@$d->created_at)) }}</td>
                                        <td>{{ $d->amount }}</td>
                                        <td>{{ $d->type }}</td>
                                        <td>{{ $details }}</td>
                                        <td>{{ $d->balance }}</td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
