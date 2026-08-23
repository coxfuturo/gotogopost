@extends('deliveryBoy.layouts.master')
@section('title') {{$title}} @endsection
@section('content')


<style>
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

<form action="{{ route('deliveryBoy.bag.showAllDeliveredParcel', ['service_type' => request()->query('service_type')]) }}" method="GET">
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
        <div class="col-sm-6 col-md-1">
            <div class="d-grid d-flex">
                <button type="submit" class="btn btn-success">Search</button> &nbsp;&nbsp;

                @if(request()->query('date'))
                <a href="{{ route('deliveryBoy.bag.showAllDeliveredParcel', ['service_type' => request()->query('service_type')]) }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Reset">
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
                                <th class="text-center">Delivered / Cancelled Date</th>
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
                                <td>{{ $i+1 }}</td>
                                <td>{{ $data['barcode_no'] }}</td>
                    
                                {{-- ✅ Delivered Date ya Cancelled Reason --}}
                                <td class="text-center">
                                    @php
                                        $cancelledByUser = !empty($data['cancelDelivery']) 
                                            && !empty($data['cancelDelivery']['cancel_reason']) 
                                            && $data['cancelDelivery']['cancelled_by'] == auth()->guard('delboy')->user()->id;
                                    @endphp
                                
                                    @if (!empty($data['delivered_date']) && $data['delivered_date'] != "null")
                                        @if ($cancelledByUser)
                                            <span class="text-danger">{{ $data['cancelDelivery']['cancel_reason'] }}</span>
                                        @else
                                            {{ \Carbon\Carbon::parse($data['delivered_date'])->format('d-M-Y') }}
                                        @endif
                                    @elseif (!empty($data['cancelDelivery']) && !empty($data['cancelDelivery']['cancel_reason']))
                                        <span class="text-danger">{{ $data['cancelDelivery']['cancel_reason'] }}</span>
                                    @else
                                        <span class="text-muted">Pending</span>
                                    @endif
                                </td>
                                
                    
                                <td>{{ $data['pickup_address'] }}</td>
                                <td>{{ $data['pickup_state'] }}</td>
                                <td>{{ $data['pickup_city'] }}</td>
                                <td>{{ $data['pickup_pincode'] }}</td>
                                <td>{{ $data['pickup_mobile'] }}</td>
                                <td>{{ $data['consignee_address'] }}</td>
                                <td>{{ $data['consignee_state'] }}</td>
                                <td>{{ $data['consignee_city'] }}</td>
                                <td>{{ $data['consignee_pincode'] }}</td>
                                <td>{{ $data['consignee_mobile'] }}</td>
                                <td>{{ $data['package_weight'] }}</td>
                                <td>{{ number_format($data['payment_amount'], 2) }}</td>
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



<script>
    var datas = <?php echo json_encode($datas); ?>;
</script>

@endpush
@endsection