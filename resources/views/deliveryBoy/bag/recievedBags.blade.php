@extends('deliveryBoy.layouts.master') @section('title') {{$title}} @endsection @section('content')



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
        margin-bottom: 12px;
    }

    .tab-content {
        padding-top: 0px;
    }

    .form-focus {
        height: 35px;
        position: relative;
    }
</style>


<div id="franchise" class="pro-overview tab-pane fade show active">

    <!-- Search Filter -->

    <div class="row">

        <div class="col-lg-8">
            <form id="franchiseReceivedBagSearch" method="GET">
                <div class="row">
                    <!-- Search Key Input -->

                    <input type="hidden" id="service_type" name="service_type" value="{{ request()->query('id') }}" />
                    <div class="col-lg-4">
                        <div class="input-block mb-3 form-focus">
                            <input type="text" name="searchKey" id="franchiseSearchKey" class="form-control floating" value="{{ request('searchKey') }}" />
                            <label class="focus-label">Search</label>
                        </div>
                    </div>

                    <!-- From Date Input -->
                    <div class="col-lg-3">
                        <div class="input-block mb-3 form-focus">
                            <div class="cal-icon">
                                <input name="fromDate" id="franchiseCreatedDate" class="form-control floating datetimepicker fromDate" type="text" value="{{ request('fromDate') }}" />
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



    <div class="row staff-grid-row franchiseBagContainer">

        @if ($bagfromfranchise->count()>0) @foreach ( $bagfromfranchise as $data)



        <div class="col-lg-2 d-flex" style="position:relative">

            <a href="{{route('deliveryBoy.bag.viewParcelfranchiseReceived',['id'=>$data->id,'service_type'=>$data->service_type])}}">

                <div class="profile-widget w-100" style="padding: 10px;">

                    <div class="dash-card-icon">

                        <i class="fa-solid fa-suitcase" style="color: #666363; font-size: 30px;"></i>

                    </div>

                    <h5 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">ID: {{$data->bag_id}}</a></h5>
                    <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">delboyCode: {{$data->deliveryBoy->delivery_boy_no}}</a></h6>
                    <h6 class="user-name mt-1 mb-0 text-ellipsis"><a href="#">City: {{$data->deliveryBoy->pincode}}</a></h6>

                </div>

            </a>

        </div>

        @endforeach @else

        <p>No result found</p>

        @endif

    </div>

</div>

@php $service_type = request()->query('id'); @endphp @push('page-javascript')

<script type="text/javascript">
    function delete_modal(id) {

        const deleteUrl = "{{ route('cms.bag.delete', ['id' => ':id']) }}".replace(':id', id);

        $('#delete_button').attr('href', deleteUrl);

        $('#delete_modal').modal('show');

    }
</script>


<script>
    $(document).ready(function() {
        $("#franchiseReceivedBagSearch").on("submit", function(e) {
            e.preventDefault();
            // Collect form data
            var searchKey = $("#franchiseSearchKey").val();
            var createdDate = $("#franchiseCreatedDate").val();
            var serviceType = $("#service_type").val();

            $.ajax({
                url: '{{ route("deliveryBoy.bag.receivedBagsSearch") }}',
                type: "POST",
                data: {
                    searchKey: searchKey,
                    createdDate: createdDate,
                    serviceType: serviceType,
                    bag_type: "bagsReceivedFromFranchise",
                    _token: "{{ csrf_token() }}",
                },

                success: function(response) {
                    $(".franchiseBagContainer").html(response.html);
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


@endpush @endsection