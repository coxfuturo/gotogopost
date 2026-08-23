@extends('franchise.layouts.master')

@section('title') Speed Post @endsection

@push('add-modal-code')
<div class="col-auto float-end ms-auto">  

    <button id="fullPrint" style="margin-right:10px;" class="btn add-btn">Print</button>
  
</div>
@endpush

@section('content')



<div class="card tab-box">
    <div class="row user-tabs">
        <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab" class="nav-link active">Profile</a></li>
                <li class="nav-item"><a href="#parceldailyReport" data-bs-toggle="tab" class="nav-link">Parcels</a></li>
                <li class="nav-item"><a href="#bank_statutory" data-bs-toggle="tab" class="nav-link">Business</a></li>
                <li class="nav-item"><a href="#emp_assets" data-bs-toggle="tab" class="nav-link">Users</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="tab-content">

    <!-- Profile Info Tab -->
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pickup Details</h5>
                    </div>
                    <div class="card-body">
                        <ul class="personal-info">
                            <li>
                                <div class="title">Name:</div>
                                <div class="text"><a href="#">{{$data->pickup_name}}</a></div>
                            </li>
                            <li>
                                <div class="title">Phone:</div>
                                <div class="text"><a href="#">{{$data->pickup_mobile}}</a></div>
                            </li>
                            <li>
                                <div class="title">Email:</div>
                                <div class="text"><a href="#">{{$data->pickup_email}}</a></div>
                            </li>
        
                            <li>
                                <div class="title">City/District:</div>
                                <div class="text">{{$data->pickup_city}}</div>
                            </li>
                            <li>
                                <div class="title">State:</div>
                                <div class="text">{{$data->pickup_state}}</div>
                            </li>
                            <li>
                                <div class="title">Pincode:</div>
                                <div class="text">{{$data->pickup_pincode}}</div>
                            </li>
                            <li>
                                <div class="title">Address:</div>
                                <div class="text">{{$data->pickup_address}}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Consignee Details</h5>
                    </div>
                    <div class="card-body">
                        <ul class="personal-info">
                            <li>
                                <div class="title">Name:</div>
                                <div class="text"><a href="#">{{$data->consignee_name}}</a></div>
                            </li>
                            <li>
                                <div class="title">Phone:</div>
                                <div class="text"><a href="#">{{$data->consignee_mobile}}</a></div>
                            </li>
                            <li>
                                <div class="title">Email:</div>
                                <div class="text"><a href="#">{{$data->consignee_email}}</a></div>
                            </li>
        
                            <li>
                                <div class="title">City/District:</div>
                                <div class="text">{{$data->consignee_city}}</div>
                            </li>
                            <li>
                                <div class="title">State:</div>
                                <div class="text">{{$data->consignee_state}}</div>
                            </li>
                            <li>
                                <div class="title">Pincode:</div>
                                <div class="text">{{$data->consignee_pincode}}</div>
                            </li>
                            <li>
                                <div class="title">Address:</div>
                                <div class="text">{{$data->consignee_address}}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Parcel Details</h5>
                    </div>
                    <div class="card-body">
                        <ul class="personal-info">
                            <li>
                                <div class="title" style="width:50%">Parcel Weight:</div>
                                <div class="text">{{$data->package_weight}}</div>
                            </li>
                            <li>
                                <div class="title" style="width:50%">Parcel Length:</div>
                                <div class="text">{{$data->package_length}}</div>
                            </li>
                            <li>
                                <div class="title" style="width:50%">Parcel Width:</div>
                                <div class="text">{{$data->package_width}}</div>
                            </li>
        
                            <li>
                                <div class="title" style="width:50%">Parcel Height:</div>
                                <div class="text">{{$data->package_height}}</div>
                            </li>
                            <li>
                                <div class="title" style="width:50%">Payment Method:</div>
                                <div class="text">{{$data->payment_method}}</div>
                            </li>
                            <li>
                                <div class="title" style="width:50%">Payment amount:</div>
                                <div class="text">{{$data->payment_amount}}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Barcode Details</h5>
                    </div>
                    <div class="card-body">
                        <ul class="personal-info">
                            <div class="col-md-4 mb-3">
                                <label for="PickupAddress">Barcode <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <img  src="data:image/png;base64,{{  $data->barcode_image_src }}" alt="Barcode" style="height:50px;"/>
                                    <p class="mt-2 text-center w-100">{{$data->barcode_no}}</p>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Profile Info Tab -->

    <!-- Adhar view  Modal -->
    {{-- <div class="modal custom-modal fade" id="adhar_view" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Adhar Card</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <img src="{{asset('admin/franchise/'.$data->generated_id.'/'.$data->kyc->adhar_front_img)}}">
                        </div>
                        <div class="row">
                            <img src="{{asset('admin/franchise/'.$data->generated_id.'/'.$data->kyc->adhar_back_img)}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- /Adhar view Modal -->

    <!-- Pan view  Modal -->
    {{-- <div class="modal custom-modal fade" id="pan_view" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Pan Card</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <img src="{{asset('admin/franchise/'.$data->generated_id.'/'.$data->kyc->pan_img)}}">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- /Pan view Modal -->

    <!-- Cheque view  Modal -->
    {{-- <div class="modal custom-modal fade" id="cheque_view" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Cancel Cheque</h3>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <img src="{{asset('admin/franchise/'.$data->generated_id.'/'.$data->kyc->cheque_img)}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- /Cheque view Modal -->

    <!-- Projects Tab -->
    {{-- <div class="tab-pane fade" id="parcel">
        <div class="table-responsive table-newdatatable m-0">
            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>SN#</th>
                        <th>Pickup Name</th>
                        <th>Pickup Pincode</th>
                        <th>Consignee Name</th>
                        <th>Consignee Pincode</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @if ($parcel->count()>0)
                    @foreach ($parcel as $i=>$item)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td>{{$item->pickup_name}}</td>
                        <td>{{$item->pickup_pincode}}</td>
                        <td>{{$item->consignee_name}}</td>
                        <td>{{$item->consignee_pincode}}</td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="{{route('admin.parcel.view',['id'=>$item->id])}}">
                                    <img src="{{asset('admin/assets/img/icons/eye.svg')}}" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
                    
                </tbody>
            </table>
        </div>
    </div> --}}
    <!-- /Projects Tab -->

    <!-- Bank Statutory Tab -->
    <div class="tab-pane fade" id="bank_statutory">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"> Basic Salary Information</h3>
                <form>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Salary basis <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select salary basis type</option>
                                    <option>Hourly</option>
                                    <option>Daily</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Salary amount <small class="text-muted">per month</small></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" placeholder="Type your salary amount" value="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Payment type</label>
                                <select class="select">
                                    <option>Select payment type</option>
                                    <option>Bank transfer</option>
                                    <option>Check</option>
                                    <option>Cash</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h3 class="card-title"> PF Information</h3>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">PF contribution</label>
                                <select class="select">
                                    <option>Select PF contribution</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">PF No. <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select PF contribution</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Employee PF rate</label>
                                <select class="select">
                                    <option>Select PF contribution</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Additional rate <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select additional rate</option>
                                    <option>0%</option>
                                    <option>1%</option>
                                    <option>2%</option>
                                    <option>3%</option>
                                    <option>4%</option>
                                    <option>5%</option>
                                    <option>6%</option>
                                    <option>7%</option>
                                    <option>8%</option>
                                    <option>9%</option>
                                    <option>10%</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Total rate</label>
                                <input type="text" class="form-control" placeholder="N/A" value="11%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Employee PF rate</label>
                                <select class="select">
                                    <option>Select PF contribution</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Additional rate <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select additional rate</option>
                                    <option>0%</option>
                                    <option>1%</option>
                                    <option>2%</option>
                                    <option>3%</option>
                                    <option>4%</option>
                                    <option>5%</option>
                                    <option>6%</option>
                                    <option>7%</option>
                                    <option>8%</option>
                                    <option>9%</option>
                                    <option>10%</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Total rate</label>
                                <input type="text" class="form-control" placeholder="N/A" value="11%">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h3 class="card-title"> ESI Information</h3>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">ESI contribution</label>
                                <select class="select">
                                    <option>Select ESI contribution</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">ESI No. <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select ESI contribution</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Employee ESI rate</label>
                                <select class="select">
                                    <option>Select ESI contribution</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Additional rate <span class="text-danger">*</span></label>
                                <select class="select">
                                    <option>Select additional rate</option>
                                    <option>0%</option>
                                    <option>1%</option>
                                    <option>2%</option>
                                    <option>3%</option>
                                    <option>4%</option>
                                    <option>5%</option>
                                    <option>6%</option>
                                    <option>7%</option>
                                    <option>8%</option>
                                    <option>9%</option>
                                    <option>10%</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Total rate</label>
                                <input type="text" class="form-control" placeholder="N/A" value="11%">
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Bank Statutory Tab -->

    <!-- Assets -->
    <div class="tab-pane fade" id="parceldailyReport">
        <div class="table-responsive table-newdatatable">
            <table class="table table-new custom-table mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Asset ID</th>
                        <th>Assigned Date</th>
                        <th>Assignee</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <a href="assets-details.html" class="table-imgname">
                                <img src="assets/img/laptop.png" class="me-2" alt="Laptop Image">
                                <span>Laptop</span>
                            </a>
                        </td>
                        <td>AST - 001</td>
                        <td>22 Nov, 2022 10:32AM</td>
                        <td class="table-namesplit">
                            <a href="javascript:void(0);" class="table-profileimage">
                                <img src="assets/img/profiles/avatar-02.jpg" class="me-2" alt="User Image">
                            </a>
                            <a href="javascript:void(0);" class="table-name">
                                <span>John Paul Raj</span>
                                <p>john@dreamguystech.com</p>
                            </a>
                        </td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="user-asset-details.html">
                                    <img src="assets/img/icons/eye.svg" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <a href="assets-details.html" class="table-imgname">
                                <img src="assets/img/laptop.png" class="me-2" alt="Laptop Image">
                                <span>Laptop</span>
                            </a>
                        </td>
                        <td>AST - 002</td>
                        <td>22 Nov, 2022 10:32AM</td>
                        <td class="table-namesplit">
                            <a href="javascript:void(0);" class="table-profileimage" data-bs-toggle="modal" data-bs-target="#edit-asset">
                                <img src="assets/img/profiles/avatar-05.jpg" class="me-2" alt="User Image">
                            </a>
                            <a href="javascript:void(0);" class="table-name">
                                <span>Vinod Selvaraj</span>
                                <p>vinod.s@dreamguystech.com</p>
                            </a>
                        </td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="user-asset-details.html">
                                    <img src="assets/img/icons/eye.svg" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            <a href="assets-details.html" class="table-imgname">
                                <img src="assets/img/keyboard.png" class="me-2" alt="Keyboard Image">
                                <span>Dell Keyboard</span>
                            </a>
                        </td>
                        <td>AST - 003</td>
                        <td>22 Nov, 2022 10:32AM</td>
                        <td class="table-namesplit">
                            <a href="javascript:void(0);" class="table-profileimage" data-bs-toggle="modal" data-bs-target="#edit-asset">
                                <img src="assets/img/profiles/avatar-03.jpg" class="me-2" alt="User Image">
                            </a>
                            <a href="javascript:void(0);" class="table-name">
                                <span>Harika </span>
                                <p>harika.v@dreamguystech.com</p>
                            </a>
                        </td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="user-asset-details.html">
                                    <img src="assets/img/icons/eye.svg" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>
                            <a href="#" class="table-imgname">
                                <img src="assets/img/mouse.png" class="me-2" alt="Mouse Image">
                                <span>Logitech Mouse</span>
                            </a>
                        </td>
                        <td>AST - 0024</td>
                        <td>22 Nov, 2022 10:32AM</td>
                        <td class="table-namesplit">
                            <a href="assets-details.html" class="table-profileimage">
                                <img src="assets/img/profiles/avatar-02.jpg" class="me-2" alt="User Image">
                            </a>
                            <a href="assets-details.html" class="table-name">
                                <span>Mythili</span>
                                <p>mythili@dreamguystech.com</p>
                            </a>
                        </td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="user-asset-details.html">
                                    <img src="assets/img/icons/eye.svg" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>
                            <a href="#" class="table-imgname">
                                <img src="assets/img/laptop.png" class="me-2" alt="Laptop Image">
                                <span>Laptop</span>
                            </a>
                        </td>
                        <td>AST - 005</td>
                        <td>22 Nov, 2022 10:32AM</td>
                        <td class="table-namesplit">
                            <a href="assets-details.html" class="table-profileimage">
                                <img src="assets/img/profiles/avatar-02.jpg" class="me-2" alt="User Image">
                            </a>
                            <a href="assets-details.html" class="table-name">
                                <span>John Paul Raj</span>
                                <p>john@dreamguystech.com</p>
                            </a>
                        </td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="user-asset-details.html">
                                    <img src="assets/img/icons/eye.svg" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>
                            <a href="#" class="table-imgname">
                                <img src="assets/img/laptop.png" class="me-2" alt="Laptop Image">
                                <span>Laptop</span>
                            </a>
                        </td>
                        <td>AST - 006</td>
                        <td>22 Nov, 2022 10:32AM</td>
                        <td class="table-namesplit">
                            <a href="javascript:void(0);" class="table-profileimage">
                                <img src="assets/img/profiles/avatar-05.jpg" class="me-2" alt="User Image">
                            </a>
                            <a href="javascript:void(0);" class="table-name">
                                <span>Vinod Selvaraj</span>
                                <p>vinod.s@dreamguystech.com</p>
                            </a>
                        </td>
                        <td>
                            <div class="table-actions d-flex">
                                <a class="delete-table me-2" href="user-asset-details.html">
                                    <img src="assets/img/icons/eye.svg" alt="Eye Icon">
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /Assets -->

</div>














@push('page-javascript')
    
<script>

    $(document).ready(function() {
        $('#fullPrint').on('click', function() {
            console.log("reached here")
            $.ajax({
                url: "{{ route('franchise.go-speed-post-parcel.full-print', ['id' => $data->id]) }}", 
                method: 'GET',
                success: function(response) {
                    var iframe = document.createElement('iframe');
                        iframe.style.position = 'absolute';
                        iframe.style.width = '0';
                        iframe.style.height = '0';
                        iframe.style.border = 'none';
                        document.body.appendChild(iframe);

                        iframe.contentDocument.open();
                        iframe.contentDocument.write(response.otherPageContent);
                        iframe.contentDocument.close();

                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();

                        document.body.removeChild(iframe);
                }
            });
        });
    });
</script>
@endpush








@endsection
