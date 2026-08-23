@extends('franchise.layouts.master') @section('title') {{$title}} @endsection @section('content')

<style>
    .submit-section {
        text-align: center;

        margin-top: 0px;

        float: left;

        width: 100%;
    }


    #add_role_user>div>div>div.modal-body>form>div.row>div:nth-child(2)>span {
        width: 100% !important;
    }

    .custom-modal .modal-content .modal-body {
        padding: 15px 30px;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        cursor: pointer;

        float: right;

        font-weight: bold;

        height: 26px;

        margin-right: 20px;

        padding-right: 0px;

        display: none;
    }

    .select2-container {
        box-sizing: border-box;

        display: inline-block;

        margin: 0;

        position: relative;

        vertical-align: middle;

        width: 100% !important;
    }

    h6 {
        font-weight: 500;

        font-size: 13px;

        margin-bottom: 0.5rem;
    }

    .page-wrapper .content .page-header {
        margin-bottom: -15px;
    }

    .tab-content {
        padding-top: 0px;
    }

    .form-check .form-check-input {
        cursor: pointer;
        border: 2px solid #405189;
    }

    a#new-bag {
        background: green;
        color: white;
        display: block
    }


    span#new-bag {
        background: #52bb5e;
        color: white;
        display: block;
        font-weight: 400;
    }

    span#returned-bag {
        background: #fc6075;
        color: white;
        display: block;
        font-weight: 400;
    }
    
</style>

@push('add-modal-code')

<div class="col-auto float-end ms-auto">
    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role_user"><i class="fa-solid fa-plus"></i> Add</a>
    <div class="view-icons">
        <div class="col-auto float-end ms-auto">
            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item">
                                <a href="#cms" class="nav-link active" data-bs-toggle="tab" data-tab-group="modal-cms-delboy-tabs">CMS</a>
                            </li>
                            <li class="nav-item">
                                <a href="#deliveryBoy" class="nav-link" data-bs-toggle="tab" data-tab-group="modal-cms-delboy-tabs">Delivery Boy</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endpush

<div class="tab-content">
    <!--cms start -->

    <div id="cms" class="pro-overview tab-pane fade show active">
        <!-- Search Filter -->

        <form action="" id="cmsCreatedBagSearch" method="GET">
            <div class="row">
                <!-- Search Key Input -->
                <input type="hidden" id="service_type" name="service_type" value="{{ request()->query('service_type') }}" />
                <div class="col-sm-3 col-md-3">
                    <div class="input-block mb-3 form-focus">
                        <input type="text" name="cmsSearchKey" id="cmsSearchKey" class="form-control floating" value="{{ request('cmsSearchKey') }}" />
                        <label class="focus-label">Search</label>
                    </div>
                </div>

                <!-- From Date Input -->
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
                    <div class="input-block mb-3 form-focus">
                        <div class="cal-icon">
                            <input name="cmsCreatedDate" id="cmsCreatedDate" class="form-control floating datetimepicker fromDate" type="text" value="{{ request('cmsCreatedDate') }}" />
                        </div>
                        <label class="focus-label">Date</label>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="col-sm-6 col-md-1">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </div>

                <div class="col-sm-6 col-md-1">
                    <div class="d-grid">
                        <button id="cmsCreatedBagPrint" style="margin-right:10px;" class="btn add-btn">Print</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Search Filter -->

        @if ($errors->any())

        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach
            </ul>
        </div>

        @endif

        <div class="row staff-grid-row cmsBagContainer">
            @if ($bagsforCMS->count()>0) @foreach ( $bagsforCMS as $data)

            <div class="col-lg-2 d-flex" style="position:relative;padding:6px">
                <div class="dropdown dropdown-action" style="position: absolute; right: 0px; z-index: 5; top: 9px;">
                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role{{$data->id}}">
                            <i class="fa-solid fa-pencil m-r-5"></i> Edit
                        </a>
                        <a class="dropdown-item" onclick="delete_modal({{$data->id}})" href="#" data-bs-toggle="modal" data-bs-target="#delete_asset">
                            <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                        </a>
                    </div>
                </div>

                <a href="{{ route('franchise.bag.viewParcelCMSCreated', ['id' => $data->id, 'service_type' => $data->service_type]) }}" class="w-100 text-decoration-none">
                    <div class="profile-widget w-100 bagCard">
                        <div class="dash-card-icon">
                            <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                        </div>

                        <img src="data:image/png;base64,{{ $data->barcode_img_src }}" alt="Barcode" style="height:50px; display:none;" />

                        <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                            <span id="{{ $data->return_type == 1 ? 'returned-bag' : 'new-bag' }}">
                                {{ $data->return_type == 1 ? 'Return Bag' : 'New Bag' }}
                            </span>
                        </h4>
                        <h5 class="user-name mt-2 mb-0 text-ellipsis">ID: {{ $data->barcode_no }}</h5>
                        <h6 class="user-name mb-0 text-ellipsis" style="padding:2px;">CMSCode: {{ $data->cms->cms_no }}</h6>
                        <h6 class="user-name mb-0 text-ellipsis">City: {{ $data->cms->pincode }}</h6>
                    </div>
                </a>
            </div>


            <div id="edit_role{{$data->id}}" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-md">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Bag</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        @if($data)

                        <div class="modal-body">
                            <form action="{{ route('franchise.bag.update', ['id' => $data->id]) }}" method="POST">
                                @csrf

                                <div class="input-block mb-3">
                                    <label class="col-form-label">CMS <span class="text-danger">*</span></label>

                                    <select name="cms" class="floating" name="service_type" required>
                                        <option value=""> -- Select CMS -- </option>
                                        @foreach($allcms as $key=>$value)
                                        <option value="{{$value->id}}" {{ $data->cms_id == $value->id ? 'selected' : ''}}>Name:{{$value->name}} || city:{{$value->pincode}}</option>
                                        @endforeach
                                    </select>
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

            @endforeach @else

            <p>No result found</p>

            @endif
        </div>
    </div>

    <!-- cms end -->

    <!-- deliveryBoy start -->

    <div id="deliveryBoy" class="pro-overview tab-pane fade show">

        <!-- Search Filter -->

        <form action="" id="deliveryBoyCreatedBagSearch" method="GET">
            <div class="row">
                <!-- Search Key Input -->

                <input type="hidden" id="service_type" name="service_type" value="{{ request()->query('service_type') }}" />
                <div class="col-sm-3 col-md-3">
                    <div class="input-block mb-3 form-focus">
                        <input type="text" name="deliveryBoySearchKey" id="deliveryBoySearchKey" class="form-control floating" value="{{ request('deliveryBoySearchKey') }}" />
                        <label class="focus-label">Search</label>
                    </div>
                </div>

                <!-- From Date Input -->
                <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
                    <div class="input-block mb-3 form-focus">
                        <div class="cal-icon">
                            <input name="deliveryBoyCreatedDate" id="deliveryBoyCreatedDate" class="form-control floating datetimepicker fromDate" type="text" value="{{ request('deliveryBoyCreatedDate') }}" />
                        </div>
                        <label class="focus-label">Date</label>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="col-sm-6 col-md-1">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Search Filter -->

        @if ($errors->any())

        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach
            </ul>
        </div>

        @endif

        <div class="row staff-grid-row deliveryBoyBagContainer">
            @if ($bagsforDeliveryBoy->count()>0) @foreach ( $bagsforDeliveryBoy as $data)


            <div class="col-lg-2 d-flex" style="position:relative;padding:6px">
                <!-- Dropdown Menu (Placed Outside the <a> Tag) -->
                <div class="dropdown dropdown-action" style="position: absolute; right: 0px; z-index: 5; top: 9px;">
                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role{{$data->id}}">
                            <i class="fa-solid fa-pencil m-r-5"></i> Edit
                        </a>
                        <a class="dropdown-item" onclick="delete_modal({{$data->id}})" href="#" data-bs-toggle="modal" data-bs-target="#delete_asset">
                            <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                        </a>
                    </div>
                </div>

                <!-- Clickable Card -->
                <a href="{{ route('franchise.bag.viewParcelDeliveryBoyCreated', ['id' => $data->id, 'service_type' => $data->service_type]) }}" class="w-100">
                    <div class="profile-widget w-100" style="padding: 10px;">
                        <div class="dash-card-icon">
                            <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                        </div>

                        <img src="data:image/png;base64,{{ $data->barcode_img_src }}" alt="Barcode" style="height: 50px; display: none;" />

                        <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                            <span id="{{ $data->return_type == 1 ? 'returned-bag' : 'new-bag' }}">
                                {{ $data->return_type == 1 ? 'Return Bag' : 'New Bag' }}
                            </span>
                        </h4>

                        <h5 class="user-name mt-2 mb-0 text-ellipsis">ID: {{ $data->bag_id }}</h5>
                        <h6 class="user-name mb-0 text-ellipsis" style="padding:2px;">DelboyCode: {{ $data->deliveryBoy->delivery_boy_no }}</h6>
                        <h6 class="user-name mb-0 text-ellipsis">City: {{ $data->deliveryBoy->pincode }}</h6>
                    </div>
                </a>
            </div>


            <div id="edit_role{{$data->id}}" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-md">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Bag</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        @if($data)

                        <div class="modal-body">
                            <form action="{{ route('franchise.bag.update', ['id' => $data->id]) }}" method="POST">
                                @csrf

                                <div id="myModal" class="input-block mb-3">
                                    <label class="col-form-label">Delivery Boy <span class="text-danger">*</span></label>
                                    <select name="deliveryBoy" class="floating" name="service_type" required>
                                        <option value=""> -- Select Delivery Boy -- </option>
                                        @foreach($deliveryBoy as $key => $value)
                                        <option value="{{ $value->id }}" {{ $data->delivery_boy_id == $value->id ? 'selected' : '' }}> Name: {{ $value->name }} || city: {{ $value->pincode }} </option>
                                        @endforeach
                                    </select>
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

            @endforeach @else

            <p>No result found</p>

            @endif
        </div>
    </div>

    <!-- deliveryBoy end -->
</div>

@php $service_type = request()->query('service_type'); @endphp

<!-- Add Role Modal -->



<div id="add_role_user" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Bag</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-2 d-flex justify-content-between align-items-center">
                        <div style="padding: 10px 10px; background: gainsboro; border-radius: 3px;">
                            @if(request()->query('service_type')==1 || request()->query('service_type')==3 || request()->query('service_type')==4)
                            <div class="">FO-CODE-{{$linkDetail->franchise_no ?? 0 }}-SP-{{$linkDetail->cms_no ?? 0}}-CI-{{$linkDetail->pph_no ?? 0}}</div>
                            @elseif(request()->query('service_type')==5 || request()->query('service_type')==6 || request()->query('service_type')==7)
                            <div class=""> FO-CODE-{{$linkDetail->franchise_no ?? 0 }}-BNPL-{{$linkDetail->bnpl_no ?? 0}}-CI-{{$linkDetail->contract_id ?? 0 }}</div>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6 mb-2 d-flex justify-content-between align-items-center">
                        <div class="view-icons">
                            <div class="col-auto float-end ms-auto">
                                <div class="card tab-box">
                                    <div class="row user-tabs">
                                        <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                                            <ul class="nav nav-tabs nav-tabs-bottom">
                                                <li class="nav-item"><a href="#cmsform" data-bs-toggle="tab" class="nav-link active">CMS</a></li>
                                                <li class="nav-item"><a href="#deliveryBoyform" data-bs-toggle="tab" class="nav-link">Delivery Boy</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content">

                    <!--cms start -->
                    <div id="cmsform" class="pro-overview tab-pane fade show active">
                        <form action="{{route('franchise.bag.store')}}" method="POST">
                            @csrf

                            <input type="text" hidden value="{{$code}}" name="barcode_no" />

                            <input type="text" hidden value="{{$service_type}}" name="service_type" />

                            <input type="text" hidden value="{{ $barcode }}" name="barcode_img_src" />

                            <input type="text" hidden value="cms" name="role" />

                            <div class="row">
                                <div class="col-lg-5">
                                    <label class="col-form-label">Select cms <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-lg-7">
                                    <select name="bagAssignedToCms" id="bagAssignedToCms" required>
                                        <option value="">Select an option</option>

                                        @forEach($allcms as $item)

                                        <option value="{{$item->id}}">{{$item->name}}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span style="display: inline-block; margin-right: 10px;">Bag Available Barcodes:</span>
                                <span style="display: inline-block;">{{ $availableBarcodes }}</span>
                            </div> 
                            <div class="col-md-5 m-auto mt-3">

                                <div class="input-group">
                                    <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="height: 50px;" />

                                    <p class="mt-2 text-center w-100" style="margin: 0">{{$code}}</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center p-2">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="cms_bag_type" id="cms_new_bag" value="0" checked>
                                    <label class="form-check-label" for="cms_new_bag">New Bag</label>
                                </div>

                                <div class="form-check form-check-inline" style="margin-left: 30px;">
                                    <input class="form-check-input" type="radio" name="cms_bag_type" id="cms_return_bag" value="1">
                                    <label class="form-check-label" for="cms_return_bag">Return Bag</label>
                                </div>

                            </div>


                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>

                    <!-- cms start -->

                    <!-- deliveryBoy start -->

                    <div id="deliveryBoyform" class="pro-overview tab-pane fade show">
                        <form action="{{route('franchise.bag.store')}}" method="POST">
                            @csrf

                            <input type="text" hidden value="{{$service_type}}" name="service_type" />

                            <input type="text" hidden value="delboy" name="role" />

                            <div class="row mt-4">
                                <div class="col-lg-5">
                                    <label class="col-form-label">Select Delivery Boy <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-lg-7">
                                    <select name="bagAssignedTodelboy" id="bagAssignedTodelboy" required>
                                        <option value="">Select an option</option>

                                        @forEach($deliveryBoy as $boy)

                                        <option value="{{$boy->id}}">{{$boy->name}}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="d-flex justify-content-center p-2 mt-2">

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delivery_boy_bag_type" id="delivery_boy_new_bag" value="0" checked>
                                    <label class="form-check-label" for="delivery_boy_new_bag">New Bag</label>
                                </div>

                                <div class="form-check form-check-inline" style="margin-left: 30px;">
                                    <input class="form-check-input" type="radio" name="delivery_boy_bag_type" id="delivery_boy_return_bag" value="1">
                                    <label class="form-check-label" for="delivery_boy_return_bag">Return Bag</label>
                                </div>

                            </div> --}}


                            <div class="submit-section mt-5">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>

                    <!-- deliveryBoy end -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /Add Role Modal -->

<!-- Delete  Modal -->

<div class="modal custom-modal fade" id="delete_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete</h3>

                    <p>Are you sure want to delete?</p>
                </div>

                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="" id="delete_button" class="btn btn-primary continue-btn">Delete</a>
                        </div>

                        <div class="col-6">
                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /Delete  Modal -->

@push('page-javascript')

<script type="text/javascript">
    function delete_modal(id) {
        const deleteUrl = "{{ route('franchise.bag.delete', ['id' => ':id']) }}".replace(":id", id);

        $("#delete_button").attr("href", deleteUrl);

        $("#delete_modal").modal("show");
    }
</script>



<script>
    $(document).ready(function() {
        $("#cmsCreatedBagSearch").on("submit", function(e) {
            e.preventDefault();

            // Collect form data
            var searchKey = $("#cmsSearchKey").val();
            var createdDate = $("#cmsCreatedDate").val();
            var serviceType = $("#service_type").val();

            $.ajax({
                url: '{{ route("franchise.bag.cretedBagsSearch") }}',
                type: "POST",
                data: {
                    searchKey: searchKey,
                    createdDate: createdDate,
                    serviceType: serviceType,
                    bag_type: "bagsCreatedForCMS",
                    _token: "{{ csrf_token() }}",
                },

                success: function(response) {
                    console.log("reached here", response.html);
                    $(".cmsBagContainer").html(response.html);
                    $("select").select2({
                        tags: true,
                        placeholder: "Select an option",
                        allowClear: true,
                    });
                },

                error: function(xhr, status, error) {
                    // Handle errors

                    console.error(error);
                },
            });
        });
        $("#deliveryBoyCreatedBagSearch").on("submit", function(e) {
            e.preventDefault();

            // Collect form data
            var searchKey = $("#deliveryBoySearchKey").val();
            var createdDate = $("#deliveryBoyCreatedDate").val();
            var serviceType = $("#service_type").val();

            $.ajax({
                url: '{{ route("franchise.bag.cretedBagsSearch") }}',
                type: "POST",
                data: {
                    searchKey: searchKey,
                    createdDate: createdDate,
                    serviceType: serviceType,
                    bag_type: "bagsCreatedForDeliveryBoy",
                    _token: "{{ csrf_token() }}",
                },

                success: function(response) {

                    $(".deliveryBoyBagContainer").html(response.html);
                    $("select").select2({
                        tags: true,
                        placeholder: "Select an option",
                        allowClear: true,
                    });
                },

                error: function(xhr, status, error) {
                    // Handle errors

                    console.error(error);
                },
            });
        });

        $('#cmsCreatedBagPrint').on('click', function(e) {
            e.preventDefault();
            let date = $('#cmsCreatedDate').val();
            let searchKey = $("#cmsSearchKey").val();
            $.ajax({

                url: "{{ route('franchise.bag.printCMSCreatedBag',['service_type'=>request()->query('service_type')]) }}",
                method: 'GET',
                data: {
                    date: date,
                    searchKey: searchKey
                },
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



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabGroup = 'modal-cms-delboy-tabs';
        const defaultTab = '#cms';
        const tabs = document.querySelectorAll('[data-tab-group="' + tabGroup + '"]');
    
        if (!tabs.length) {
            console.warn("No tabs found for group:", tabGroup);
            return;
        }
    
        // Get saved tab
        const savedTab = localStorage.getItem('activeTab_' + tabGroup);
        const activeHref = savedTab || defaultTab;
    
        // Show the saved or default tab
        const activeTab = document.querySelector('[data-tab-group="' + tabGroup + '"][href="' + activeHref + '"]');
        if (activeTab) {
            new bootstrap.Tab(activeTab).show();
            console.log("Activating tab:", activeHref);
        } else {
            console.warn("Tab not found for href:", activeHref);
        }
    
        // Save clicked tab
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                const clickedHref = e.target.getAttribute('href');
                localStorage.setItem('activeTab_' + tabGroup, clickedHref);
                console.log("Saved tab in localStorage:", clickedHref);
            });
        });
    });
</script>
    
    

@endpush @endsection