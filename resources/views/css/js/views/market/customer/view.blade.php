@extends('market.layouts.master')

@section('title') Customer Booking List @endsection

@section('content')

@push('add-modal-code')
@endpush

<!-- Search Filter -->
<form action="{{ route('market.customer.view.index', ['id' => $id, 'type' => $type]) }}" method="GET">
    <div class="card">
        <div class="row card-body">
            <!-- Search Key Input -->
            <div class="col-sm-3 col-md-3">
                <div class="input-block mb-3 form-focus">
                    <input type="text" id="searchKey" name="searchKey" class="form-control floating input2" value="{{ request('searchKey') }}">
                    <label class="focus-label">Search</label>
                </div>
            </div>

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
            <div class="col-sm-3">
                <div class="d-grid">
                    <button type="submit" class="btn marketGreenBackground" style="width:fit-content;">Search</button>
                </div>
            </div>

            <div class="col-sm-4 text-end">
                <a class="btn marketOrangeBackground" style="margin-right: 20px"
                   href="{{ route('market.customer.export.parcel', array_merge(['id' => $id, 'type' => $type], request()->only('date', 'searchKey'))) }}">
                   Download
                </a>

                <a href="{{ route('market.customer.index') }}">
                    <button type="button" class="btn marketGreenBackground" style="width:fit-content;float:right;">Back</button>
                </a>
            </div>
        </div>
    </div>
</form>
<!-- End Search Filter -->

<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <span class="text-center date-above-table">{{ now()->format('d-m-Y') }}</span>
                    <span class="text-center date-above-table" style="margin-left: 150px;">Total Parcel: {{ $count }}</span>
                    <span class="text-center date-above-table" style="margin-left: 400px;">Total Amount: ₹{{ $total_amount }}</span>

                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th class="text-end" style="width:2px">Booking<br>View </th>
                                <th>SN#</th>
                                <th class="text-center">Barcode</th>
                                <th class="text-center">From Address</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">To Address</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">Amount (₹)</th>
                                <th class="text-center">GST (₹)</th>
                                <th class="text-center">Total (₹)</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $i => $data)
                                <tr>
                                    <img style="display:none" src="data:image/png;base64,{{ $data->barcode_image_src }}" alt="Barcode" />
                                    <td class="text-end">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons text-white">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right marketOrangeBackground">
                                                <a class="dropdown-item" href="{{ route('market.customer.parcel.details', ['id' => $data->id, 'type'=>$type]) }}">
                                                    <i class="fa-regular fa-eye m-r-5"></i> View
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profile_info_{{ $data->id }}">
                                                    <i class="fa-regular fa-trash-can m-r-5"></i> Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $data->barcode_no }}</td>
                                    <td>{{ $data->pickup_address }}</td>
                                    <td>{{ $data->pickup_state }}</td>
                                    <td>{{ $data->pickup_city }}</td>
                                    <td>{{ $data->pickup_pincode }}</td>
                                    <td>{{ $data->pickup_mobile }}</td>
                                    <td>{{ $data->pickup_email }}</td>
                                    <td>{{ $data->consignee_address }}</td>
                                    <td>{{ $data->consignee_state }}</td>
                                    <td>{{ $data->consignee_city }}</td>
                                    <td>{{ $data->consignee_pincode }}</td>
                                    <td>{{ $data->consignee_mobile }}</td>
                                    <td>{{ $data->consignee_email }}</td>
                                    <td>{{ $data->package_weight }}</td>
                                    <td>₹{{ number_format($data->payment_amount / 1.18, 2) }}</td>
                                    <td>₹{{ number_format($data->payment_amount - ($data->payment_amount / 1.18), 2) }}</td>
                                    <td>₹{{ $data->payment_amount }}</td>
                                    
                                </tr>

                                <!-- Cancel Modal (inside loop) -->
                                <div id="profile_info_{{ $data->id }}" class="modal custom-modal fade" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cancel Parcel Request</h5>
                                                
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <center> <h4>Your Parcel Status @if($data->status == 1)
                                                                         <span style="color:#FF671F ">Cancel Pending</span>

                                                                        @else if($data->status == 2)
                                                                           <span style="color:#046A38"> Cancel successfully</span>

                                                                         @endif
                                                </h4></center><hr>
                                            <div class="modal-body">
                                                <form id="franchiseForm_{{ $data->id }}" method="post" action="{{ route('market.customer.parcel.cancel', ['id' => $data->id, 'type' => $type]) }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="input-block mb-3">
                                                                <label class="col-form-label">Reason for Cancellation</label>
                                                                <textarea name="discription" class="form-control" required>{{ $data->discription }}</textarea>
                                                                
                                                        </div>
                                                    </div>
                                                    <div class="submit-section">
                                                        <button class="btn submit-btn" style="background:#FF671F;color:#fff ">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    // JS functions or variables (if needed)
    var datas = @json($datas);
</script>
@endpush

@endsection
