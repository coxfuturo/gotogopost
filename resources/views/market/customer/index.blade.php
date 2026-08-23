@extends('market.layouts.master')
@section('title') Customer List @endsection
@section('content')

@push('add-modal-code')

@endpush
<style>
    /* end even */
/* Make icons white inside dropdown items in odd (green) rows */
/* .table-striped tbody tr.odd .dropdown-item i,
.table-striped tbody tr.odd a i {
    color: #fff !important;
} */

/* Also make the text (like inside <a>) white if needed */
/* .table-striped tbody tr.odd .dropdown-item,
.table-striped tbody tr.odd a {
    color: #fff !important;
} */

</style>
<!-- Search Filter -->

<form action="{{ route('market.customer.index') }}" method="GET">
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

    <a class="btn marketOrangeBackground" style="margin-right:20px" href="{{ route('market.customer.export')}}">Download</a>

                <a href="{{route('market.customer.create')}}"><button type="button" class="btn marketGreenBackground" style="width:fit-content;float:right;margin-right:20px">Add Customer</button></a>
            
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
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                 <th class="text-end" style="width:2px">Booking<br>View </th>
                                 <th class="text-end" style="width:2px">Cancel<br>Parcel </th>
                                 <th class="text-center">Update</th>
                                <th>SN#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">GST</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">City</th>
                                <th class="text-center">State</th>
                                <th class="text-center">Address</th>
                                 <th class="text-center">Toatal Parcel</th>
                                 <th class="text-center">Toatal Amount</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                         
                            @foreach($data as $i => $data)
                            <tr>
                                 <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons text-white">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right marketOrangeBackground" >
                                            
                                            <a class="dropdown-item" href="{{route('market.customer.view.index',['id' => $data->id, 'type'=>1])}}"><i class="fa-regular fa-eye m-r-5"></i>Super Speed Packet</a>
                                            <a class="dropdown-item" href="{{route('market.customer.view.index',['id' => $data->id, 'type'=>2])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Express Package</a>
                                            <a class="dropdown-item" href="{{route('market.customer.view.index',['id' => $data->id, 'type'=>3])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Secure Packet</a>
                                            <a class="dropdown-item" href="{{route('market.customer.view.index',['id' => $data->id, 'type'=>4])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Speed Packet/Parcel</a>
                                            <a class="dropdown-item" href="{{route('market.customer.view.index',['id' => $data->id, 'type'=>5])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Business Package</a>
                                            
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons text-white">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right marketOrangeBackground" >
                                            
                                            <a class="dropdown-item" href="{{route('market.customer.parcel.cancel.report',['id' => $data->id, 'type'=>1])}}"><i class="fa-regular fa-eye m-r-5"></i>Super Speed Packet</a>
                                            <a class="dropdown-item" href="{{route('market.customer.parcel.cancel.report',['id' => $data->id, 'type'=>2])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Express Package</a>
                                            <a class="dropdown-item" href="{{route('market.customer.parcel.cancel.report',['id' => $data->id, 'type'=>3])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Secure Packet</a>
                                            <a class="dropdown-item" href="{{route('market.customer.parcel.cancel.report',['id' => $data->id, 'type'=>4])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Speed Packet/Parcel</a>
                                            <a class="dropdown-item" href="{{route('market.customer.parcel.cancel.report',['id' => $data->id, 'type'=>5])}}"><i class="fa-regular fa-eye m-r-5"></i> Post Business Package</a>
                                            
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a class="dropdown-item text-white" href="{{route('market.customer.edit',$data->id)}}"><i class="fa-regular fa-edit m-r-5" style="font-size:20px"></i></a>
                                </td>
                                <td>{{$i+1}}</td>
                                <td>{{$data->name}}</td>
                                <td> {{$data->phone}}</td>
                                <td>{{$data->gst_no}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->pincode}}</td>
                                <td>{{$data->city}}</td>
                                <td>{{$data->state}}</td>
                                <td title="{{ $data->address }}">{{ \Illuminate\Support\Str::limit($data->address, 30) }}</td>
                                <td>{{$data->parcel_count}}</td>
                                <td>₹{{$data->parcel_amount}}</td>

                                
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
    var datas = <?php echo json_encode($data); ?>;
</script>

@endpush
@endsection