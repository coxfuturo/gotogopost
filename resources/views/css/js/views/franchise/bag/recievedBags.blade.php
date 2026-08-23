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
        margin-bottom: 10px;
    }

    .tab-content {
        padding-top: 0px;
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







<!-- franchise start -->



<div id="cms" class="pro-overview tab-pane fade show">

    <!-- Search Filter -->

    <div class="row">

        <div class="col-lg-8">
            <form action="" id="receivedBagSearch" method="GET">
                <div class="row">
                    <!-- Search Key Input -->
                    <input type="hidden" id="service_type" name="service_type" value="{{ request()->query('service_type') }}" />
                    <div class="col-lg-4">
                        <div class="input-block mb-3 form-focus">
                            <input type="text" name="searchKey" id="searchKey" class="form-control floating" value="{{ request('searchKey') }}" />
                            <label class="focus-label">Search</label>
                        </div>
                    </div>

                    <!-- From Date Input -->
                    <div class="col-lg-4">
                        <div class="input-block mb-3 form-focus">
                            <div class="cal-icon">
                                <input name="fromDate" id="createdDate" class="form-control floating datetimepicker fromDate" type="text" value="{{ request('fromDate') }}" />
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
                <label class="focus-label">Scan</label>
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



    <div class="row staff-grid-row BagContainer">

        @if ($bagfromCMS->count()>0) @foreach ( $bagfromCMS as $data)

        <div class="col-lg-2 d-flex" style="position:relative; padding:6px;">
            <div class="profile-widget w-100 bagCard" style="position: relative;">
                <a href="{{ route('franchise.bag.viewParcelCMSReceived', ['id' => $data->id, 'service_type' => $data->service_type]) }}" 
                   style="position: absolute; inset: 0; z-index: 1;"></a>
        
                <div class="dash-card-icon">
                    <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>
                </div>
        
                <h4 class="user-name mt-1 mb-0 text-ellipsis" style="font-size: 15px; color: #333;">
                    <span id="{{ $data->return_type == 1 ? 'returned-bag' : 'new-bag' }}">
                        {{ $data->return_type == 1 ? 'Return Bag' : 'New Bag' }}
                    </span>
                </h4>
        
                <h5 class="user-name mt-1 mb-0 text-ellipsis">ID: {{ $data->barcode_no }}</h5>
        
                @if(isset($data->cms))
                    <h6 class="user-name mt-1 mb-0 text-ellipsis">CMSCode: {{ $data->cms->cms_no }}</h6>
                    <h6 class="user-name mt-1 mb-0 text-ellipsis">City: {{ $data->cms->pincode }}</h6>
                @endif
            </div>
        </div>
        
        @endforeach @else

        <p>No result found</p>

        @endif

    </div>

</div>



<!-- franchise start -->





@push('page-javascript')


<script>
    $(document).ready(function() {
        $("#receivedBagSearch").on("submit", function(e) {
            e.preventDefault();

            // Collect form data
            var searchKey = $("#searchKey").val();
            var createdDate = $("#createdDate").val();
            var serviceType = $("#service_type").val();

            $.ajax({
                url: '{{ route("franchise.bag.receivedBagsSearch") }}',
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
                    $(".BagContainer").html(response.html);
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

        $('#cmsBarcode_no').focus();

        $('#cmsBarcode_no').on('keypress', function(event) {

            if (event.key === 'Enter') {

                let barcode = $(this).val();

                if (barcode.length > 0) { // Make sure there is data to send

                    $.ajax({

                        url: "{{ route('franchise.bag.scanBagReceived',['id' => $service_type]) }}",

                        type: 'POST',

                        data: {

                            barcode: barcode,

                            _token: '{{ csrf_token() }}',

                        },

                        success: function(response) {

                            $(".BagContainer").html(response.html);

                        },

                        error: function(xhr, status, error) {

                            console.log('Error:', error);

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