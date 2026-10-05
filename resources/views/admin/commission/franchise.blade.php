
@extends('admin.layouts.master')

@section('title')
    Franchise Commission
@endsection

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Franchise Commission</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.commission.franchise.save') }}">

                @csrf

                <div class="row">

                    {{-- Franchise --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Select Franchise
                        </label>

                        <select name="franchise_id"
                                class="form-select"
                                required>

                            <option value="">Select Franchise</option>

                            @foreach($franchises as $franchise)
                                <option value="{{ $franchise->id }}">
                                    {{ $franchise->franchise_no }}
                                    (ID: {{ $franchise->id }})
                                </option>
                            @endforeach

                        </select>
                    </div>

                </div>

                <hr>

                <h5 class="mb-3">Commission Rates</h5>

                <div class="row">

                    @foreach([
                        1 => 'Service 1',
                        3 => 'Service 3',
                        4 => 'Service 4',
                        5 => 'India Post Speed Post',
                        6 => 'India Post Business',
                        9 => 'E2H'
                    ] as $serviceType => $serviceName)

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                {{ $serviceName }}
                            </label>

                            <select
                                name="commission[{{ $serviceType }}]"
                                class="form-select"
                                required>

                                <option value="">Select Commission</option>

                                <option value="5">5%</option>
                                <option value="10">10%</option>
                                <option value="15">15%</option>
                                <option value="20">20%</option>

                            </select>

                        </div>

                    @endforeach

                </div>

                <button type="submit" class="btn btn-primary">
                    Save Commission
                </button>

            </form>

        </div>
    </div>

</div>

@endsection