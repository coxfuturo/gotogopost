@extends('franchise.layouts.master')
@section('title') {{$title}} @endsection
@section('content')


<style>
    .page-wrapper .content .page-header {
        margin-bottom: 0.675rem;
    }

    .page-wrapper .content .page-header {
        margin-bottom: 0.675rem;
    }

    .page-wrapper .content .page-header {
        margin-bottom: 0.675rem;
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }

    .custom-table td {
        padding: 6px 6px !important;
        text-align: center;
        font-size: 14px;
    }
</style>

<!-- Search Filter -->

<form action="{{ route('franchise.bag.showAllDeliveredParcel', ['service_type' => request()->query('service_type')]) }}" method="GET">
    <div class="row">
        <!-- Search Key Input -->

        <input type="hidden" name="service_type" value="{{ request()->query('service_type') }}">

        <!-- To Date Input -->
        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input id="date" name="date" class="form-control floating datetimepicker" type="text" value="{{ request('date') }}">
                </div>
                <label class="focus-label">Date</label>
            </div>
        </div>

        <!-- Search Button -->
        <!-- Search Button -->
        <div class="col-sm-6 col-md-1">
            <div class="d-grid d-flex">
                <button type="submit" class="btn btn-success">Search</button> &nbsp;&nbsp;

                @if(request()->query('date'))
                <a href="{{ route('franchise.bag.showAllDeliveredParcel', ['service_type' => request()->query('service_type')]) }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Reset">
                    <i class="fa-regular fa-trash-can m-r-5 align-middle"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</form>

<!-- Search Filter -->



<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <span class="text-center date-above-table">{{ now()->format('d-F-Y') }}</span>
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-center">Barcode</th>
                                <th class="text-center">Delivered/Cancelled Date</th>
                                <th class="text-center">Cancel Reason</th>
                                <th class="text-center">Delivery Boy Name</th>
                                <th class="text-center">Delivery Boy ID</th>
                                <th class="text-center">Delivery Boy Phone</th>
                                <th class="text-center"><span>From Address</span></th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">To Address</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $i => $data)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td class="text-center">{{ $data['barcode_no'] }}</td>
                    
                                {{-- Delivered or Cancelled Date --}}
                                <td class="text-center">
                                    @if (!empty($data['delivered_date']))
                                        {{ \Carbon\Carbon::parse($data['delivered_date'])->format('d-M-Y') }}
                                    @elseif (!empty($data['cancelDelivery']))
                                        {{ \Carbon\Carbon::parse($data['cancelDelivery']['created_at'])->format('d-M-Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                    
                                {{-- Cancel Reason --}}
                                <td class="text-center">
                                    {{ $data['cancelDelivery']['cancel_reason'] ?? '—' }}
                                </td>
                    
                                {{-- Delivered or Cancelled By --}}
                                <td class="text-center">
                                    {{ $data['delivery_by_name'] ?? $data['cancelled_by_name'] ?? 'N/A' }}
                                </td>
                    
                                {{-- ID --}}
                                <td class="text-center">
                                    {{ $data['delivery_by_generated_id'] ?? $data['cancelled_by_generated_id'] ?? 'N/A' }}
                                </td>
                    
                                {{-- Phone --}}
                                <td class="text-center">
                                    {{ $data['delivery_by_mobile'] ?? $data['cancelled_by_mobile'] ?? 'N/A' }}
                                </td>
                    
                                {{-- Pickup Info --}}
                                <td class="text-center">{{ $data['pickup_address'] }}</td>
                                <td class="text-center">{{ $data['pickup_state'] }}</td>
                                <td class="text-center">{{ $data['pickup_city'] }}</td>
                                <td class="text-center">{{ $data['pickup_pincode'] }}</td>
                                <td class="text-center">{{ $data['pickup_mobile'] }}</td>
                    
                                {{-- Consignee Info --}}
                                <td class="text-center">{{ $data['consignee_address'] }}</td>
                                <td class="text-center">{{ $data['consignee_state'] }}</td>
                                <td class="text-center">{{ $data['consignee_city'] }}</td>
                                <td class="text-center">{{ $data['consignee_pincode'] }}</td>
                                <td class="text-center">{{ $data['consignee_mobile'] }}</td>
                    
                                {{-- Parcel Info --}}
                                <td class="text-center">{{ $data['package_weight'] }}</td>
                                <td class="text-center">{{ $data['payment_amount'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>







@push('page-javascript')





@endpush
@endsection