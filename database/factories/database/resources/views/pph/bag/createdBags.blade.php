@extends('pph.layouts.master') @section('title') Bag Management @endsection @section('content')



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

    .form-focus {
        height: 35px;
        position: relative;
    }

    .profile-widget {
        background-color: #ffffff;
        border: 1px solid #ededed;
        margin-bottom: 0px;
        padding: 5px;
        text-align: center;
        position: relative;
        overflow: hidden;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
        -moz-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
    }


    .filteredColor {
        background: #d0ffff;
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

</div>

@endpush

<div id="cms" class="pro-overview tab-pane fade show">

    <!-- Search Filter -->

    <form id="cmsCreatedBagSearch" method="GET">
        <div class="row">
            <!-- Search Key Input -->

            <input type="hidden" id="service_type" name="service_type" value="{{ request()->query('service_type') }}" />
            <div class="col-sm-3 col-md-3">
                <div class="input-block mb-3 form-focus">
                    <input type="text" name="searchKey" id="cmsSearchKey" class="form-control floating" value="{{ request('searchKey') }}" />
                    <label class="focus-label">Search</label>
                </div>
            </div>

            <!-- From Date Input -->
            <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
                <div class="input-block mb-3 form-focus">
                    <div class="cal-icon">
                        <input name="fromDate" id="cmsCreatedDate" class="form-control floating datetimepicker fromDate" type="text" value="{{ request('fromDate') }}" />
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
                    <button id="printCMSCreatedBag" style="margin-right:10px;" class="btn add-btn">Print</button>
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
            <!-- Dropdown for Edit & Delete -->
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

            <!-- Clickable Profile Widget -->
            <a class="w-100" href="{{ route('pph.bag.viewParcelCMSCreated', ['id' => $data->id, 'service_type' => $data->service_type]) }}" style="text-decoration: none;">
                <div class="profile-widget w-100" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: #fff; transition: 0.3s;">

                    <div class="dash-card-icon">
                        <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                    </div>

                    <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                        <span id="{{ $data->return_type == 1 ? 'returned-bag' : 'new-bag' }}">
                            {{ $data->return_type == 1 ? 'Return Bag' : 'New Bag' }}
                        </span>
                    </h4>

                    <img src="data:image/png;base64,{{ $data->barcode_img_src }}" alt="Barcode" style="height: 50px; width: 150px; display:none;" />

                    <h6 class="user-name mt-2 mb-0 text-ellipsis">ID: {{ $data->barcode_no }}</h6>
                    <h6 class="user-name mb-0 text-ellipsis" style="padding:2px">CMSCode: {{ $data->cms->cms_no }}</h6>
                    <h6 class="user-name mb-0 text-ellipsis">City: {{ $data->cms->pincode }}</h6>

                </div>
            </a>

        </div>




        <div id="edit_role{{$data->id}}" class="edit_role modal custom-modal fade" role="dialog">

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
                        <form action="{{ route('pph.bag.update', ['id' => $data->id]) }}" method="POST">
                            @csrf

                            <div class="input-block mb-3">
                                <label class="col-form-label">CMS <span class="text-danger">*</span></label>

                                <select name="cms" class="floating" name="service_type">
                                    <option value=""> -- Select CMS -- </option>
                                    @foreach($allcms as $key=>$value)
                                    <option value="{{$value->id}}" {{ $data->destination_cms_id == $value->id ? 'selected' : ''}}>Name:{{$value->name}} || city:{{$value->pincode}}</option>
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

                <div class="col-md-12 mb-2 d-flex justify-content-between align-items-center">

                    <div style="padding: 10px 10px; background: gainsboro; border-radius: 3px;">

                        @if(request()->query('service_type')==1 || request()->query('service_type')==3 || request()->query('service_type')==4)
                        <div class="">FO-CODE-{{$linkDetail->franchise_no ?? 0}}-SP-{{$linkDetail->cms_no ?? 0}}-CI-{{$linkDetail->pph_no ?? 0}}</div>
                        @elseif(request()->query('service_type')==5 || request()->query('service_type')==6 || request()->query('service_type')==7)
                        <div class=""> FO-CODE-{{$linkDetail->franchise_no ?? 0}}-BNPL-{{$linkDetail->bnpl_no ?? 0}}-CI-{{$linkDetail->contract_id ?? 0}}</div>
                        @endif

                    </div>

                </div>

                <form action="{{route('pph.bag.store')}}" method="POST">

                    @csrf

                    <input type="text" hidden value="{{$code}}" name="barcode_no" />

                    <input type="text" hidden value="{{$service_type}}" name="service_type" />

                    <input type="text" hidden value="{{ $barcode }}" name="barcode_img_src" />



                    <div class="row">


                        <div class="col-md-12">

                            <label class="col-form-label">Select cms<span class="text-danger">*</span></label>

                            <select name="bagAssignedTo" id="bagAssignedTo" required>

                                <option value=""> -- Select CMS -- </option>
                                @foreach($allcms as $key=>$value)
                                <option value="{{$value->id}}">Name:{{$value->name}} || city:{{$value->pincode}}</option>
                                @endforeach

                            </select>

                        </div>

                    </div>


                    <div class="mt-3" style="margin-bottom: -13px;">
                        <span style="display: inline-block; margin-right: 10px;">Bag Available Barcodes:</span>
                        <span style="display: inline-block;">{{ $availableBarcodes }}</span>
                    </div>


                    <div class="col-md-5 m-auto">

                        <div class="input-group mt-4">

                            <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="height: 50px;" />

                            <p class="mt-2 text-center w-100 m-0">{{$code}}</p>

                        </div>

                    </div>

                    <div class="d-flex justify-content-center p-2">

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="return_type" id="return_type" value="0" checked>
                            <label class="form-check-label" for="return_type">New Bag</label>
                        </div>

                        <div class="form-check form-check-inline" style="margin-left: 30px;">
                            <input class="form-check-input" type="radio" name="return_type" id="return_type" value="1">
                            <label class="form-check-label" for="return_type">Return Bag</label>
                        </div>

                    </div>

                    <div class="submit-section">

                        <button class="btn btn-primary submit-btn">Submit</button>

                    </div>

                </form>

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

        const deleteUrl = "{{ route('pph.bag.delete', ['id' => ':id']) }}".replace(':id', id);

        $('#delete_button').attr('href', deleteUrl);

        $('#delete_modal').modal('show');

    }
</script>



<script>
    $(document).ready(function() {
        $("#cmsCreatedBagSearch").on("submit", function(e) {
            e.preventDefault();
            var searchKey = $("#cmsSearchKey").val();
            var createdDate = $("#cmsCreatedDate").val();
            var serviceType = $("#service_type").val();



            $.ajax({
                url: '{{ route("pph.bag.cretedBagsSearch") }}',
                type: "POST",
                data: {
                    searchKey: searchKey,
                    createdDate: createdDate,
                    serviceType: serviceType,
                    bag_type: "bagsCreatedForCMS",
                    _token: "{{ csrf_token() }}",
                },

                success: function(response) {

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
    });


    $('#printCMSCreatedBag').on('click', function(e) {

        e.preventDefault();

        var searchKey = $("#cmsSearchKey").val();
        var date = $("#cmsCreatedDate").val();
        $.ajax({

            url: "{{ route('pph.bag.printCMSCreatedBag',['service_type'=>request()->query('service_type')]) }}",
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
</script>

<script>
    $(document).ready(function() {
        $('#add_role_user').on('shown.bs.modal', function() {
            $('#bagAssignedTo').select2({
                dropdownParent: $('#add_role_user')
            });
        });

        $('.edit_role').on('shown.bs.modal', function() {
            var modal = $(this);
            modal.find('select.select2-selection').select2({
                dropdownParent: modal
            });
        });
    });
</script>



@endpush @endsection