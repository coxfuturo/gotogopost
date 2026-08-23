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

    .filteredColor {
        background: #d0ffff;
    }
</style>







<div id="cms" class="pro-overview tab-pane fade show">

    <!-- Search Filter -->

    <div class="row">

        <div class="col-lg-8">
            <form id="cmsReceivedBagSearch" method="GET">
                <div class="row">
                    <!-- Search Key Input -->

                    <input type="hidden" id="service_type" name="service_type" value="{{ request()->query('service_type') }}" />
                    <div class="col-lg-4">
                        <div class="input-block mb-3 form-focus">
                            <input type="text" name="searchKey" id="cmsSearchKey" class="form-control floating" value="{{ request('searchKey') }}" />
                            <label class="focus-label">Search</label>
                        </div>
                    </div>

                    <!-- From Date Input -->
                    <div class="col-lg-4">
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
                </div>
            </form>
        </div>


        <div class="col-lg-3 styled-input-div">
            <div class="input-block mb-3 form-focus">
                <input type="text" name="cmsBarcode_no" id="cmsBarcode_no" class="form-control floating" value="{{ request('searchKey') }}" />
                <label class="focus-label">Scan Bag Received From CPH</label>
            </div>
        </div>
    </div>











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

        @if ($bagfromCMS->count()>0) @foreach ( $bagfromCMS as $data)



        <div class="col-lg-2 d-flex" style="position:relative; padding:6px;">
            <div class="profile-widget w-100" style="position: relative; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: #fff; transition: 0.3s;">
                <!-- Full clickable area -->
                <a href="{{ route('pph.bag.viewParcelCMSReceived', ['id' => $data->id, 'service_type' => $data->service_type]) }}"
                    style="position: absolute; inset: 0; z-index: 1;"></a>

                <div class="dash-card-icon">
                    <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                </div>

                <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                    <span id="{{ $data->return_type == 1 ? 'returned-bag' : 'new-bag' }}">
                        {{ $data->return_type == 1 ? 'Return Bag' : 'New Bag' }}
                    </span>
                </h4>

                <h6 class="user-name mt-1 mb-0 text-ellipsis">ID: {{ $data->barcode_no }}</h6>
                <h6 class="user-name mt-1 mb-0 text-ellipsis">PPHCode: {{ $data->pph->pph_no }}</h6>
                <h6 class="user-name mt-1 mb-0 text-ellipsis">City: {{ $data->pph->pincode }}</h6>
            </div>
        </div>



        @endforeach @else

        <p>No result found</p>

        @endif

    </div>

</div>



@php $service_type = request()->query('service_type'); @endphp @push('page-javascript')

<script type="text/javascript">
    function delete_modal(id) {

        const deleteUrl = "{{ route('cms.bag.delete', ['id' => ':id']) }}".replace(':id', id);

        $('#delete_button').attr('href', deleteUrl);

        $('#delete_modal').modal('show');

    }
</script>


<script>
    $(document).ready(function() {
        $("#cmsReceivedBagSearch").on("submit", function(e) {
            e.preventDefault();
            var searchKey = $("#cmsSearchKey").val();
            var createdDate = $("#cmsCreatedDate").val();
            var serviceType = $("#service_type").val();



            $.ajax({
                url: '{{ route("pph.bag.receivedBagsSearch") }}',
                type: "POST",
                data: {
                    searchKey: searchKey,
                    createdDate: createdDate,
                    serviceType: serviceType,
                    bag_type: "bagsReceivedFromCMS",
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
</script>





<script>
    $(document).ready(function() {


        $('#cmsBarcode_no').on('keypress', function(event) {

            if (event.key === 'Enter') {

                let barcode = $(this).val();

                if (barcode.length > 0) {

                    $.ajax({

                        url: "{{ route('pph.bag.scanCMSBagReceived',['service_type' => $service_type]) }}",
                        type: 'POST',

                        data: {

                            barcode: barcode,

                            _token: '{{ csrf_token() }}',

                        },

                        success: function(response) {

                            $(".cmsBagContainer").html(response.html);

                        },

                        error: function(xhr, status, error) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message,
                            });
                        }

                    });

                }

            }

        });

    });
</script>



@endpush @endsection