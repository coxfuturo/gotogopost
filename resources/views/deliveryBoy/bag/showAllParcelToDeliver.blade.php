@extends('deliveryBoy.layouts.master')
@section('title') {{$title}} @endsection
@section('content')


<style>
    .page-wrapper .content .page-header {
        margin-bottom: 0.675rem;
    }

    div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
    display: block;
}

.verify-btn{
    background: #ff9b44;
    background: linear-gradient(to right, #ff9b44 0%, #fc6075 100%);
    border: 0;
    display: block;
    font-size: 12px;
    color: white;
    border-radius: 4px;
    padding: 5px 6px;
    margin: auto;

}

.table td a {
    color: #ffffff;
}
</style>


<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-center">Barcode</th>
                                <th class="text-center">Verify</th>
                                <th class="text-center">Cancel</th>
                                <th class="text-center">Type</th>
                                <th class="text-center"><span>From Addresss</span></th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">To Addresss</th>
                                <th class="text-center">State</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Pincode</th>
                                <th class="text-center">Mobile</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $i => $data)
                            <tr>
                                <img style="display:none" src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height:50px;" />
                                <td>{{$i+1}}</td>
                                <td>{{$data->barcode_no}}</td>
                                <td>
                                    <a class="verify-btn" href="{{ route('deliveryBoy.bag.verifyOtp', ['barcode_no' => $data->barcode_no,'service_type'=> $data->service_type]) }}" >Verify OTP</a>
                                </td>
                                <td>
                                    <a class="verify-btn"  data-bs-toggle="modal" data-bs-target="#edit_role{{$data->id}}" href="{{ route('deliveryBoy.bag.verifyOtp', ['barcode_no' => $data->barcode_no,'service_type'=> $data->service_type]) }}" >Cancle</a>
                                </td>

                                <td> {{$data->return_type==1?"Return":"New"}}</td>
                                
                                <td> {{$data->pickup_address}}</td>
                                <td>{{$data->pickup_state}}</td>
                                <td>{{$data->pickup_city}}</td>
                                <td>{{$data->pickup_pincode}}</td>
                                <td>{{$data->pickup_mobile}}</td>
                                <td>{{$data->pickup_email}}</td>
                                <td>{{$data->consignee_address}}</td>
                                <td>{{$data->consignee_state}}</td>
                                <td>{{$data->consignee_city}}</td>
                                <td>{{$data->consignee_pincode}}</td>
                                <td>{{$data->consignee_mobile}}</td>
                                <td>{{$data->consignee_email}}</td>
                                <td>{{$data->package_weight}}</td>
                                <td>{{$data->payment_amount}}</td>
                            </tr>


                            <div id="edit_role{{$data->id}}" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-md">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cancel Delivery</h5>
                
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                
                                        @if($data)
                
                                        <div class="modal-body">
                                            <form action="{{ route('deliveryBoy.bag.cancelDelivery', ['id' => $data->id,'service_type'=> $data->service_type]) }}" method="POST">
                                                @csrf
                
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Reason For Cancle <span class="text-danger">*</span></label>
                                                    <input class="form-control" name="reason" type="text" required="">
                                                </div>
                
                                                <div class="submit-section">
                                                    <button class="btn btn-primary submit-btn">Save</button>
                                                </div>
                                            </form>
                                        </div>
                
                                        @endif
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
    var datas = <?php echo json_encode($datas); ?>;
</script>

@endpush
@endsection