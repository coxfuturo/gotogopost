@extends('admin.layouts.master')

@section('title') Commission Report @endsection

@section('content')

@push('add-modal-code')

@endpush

<style>
    .custom-button {
        display: inline-block;
        padding: 3px 10px;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        color: #f9f9f9 ! IMPORTANT;
        background-color: #1b64b1;
        border-radius: 4px;
        border: 1px solid transparent;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }

    .custom-button:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
        border-color: #004085;
        color: #fff;
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }

    .table-newdatatable .dataTables_length {
        display: block;
    }

    table.table-new.dataTable>thead .sorting:after,
    table.table-new.dataTable>thead .sorting_asc:after,
    table.table-new.dataTable>thead .sorting_desc:after,
    table.table-new.dataTable>thead .sorting_asc_disabled:after,
    table.table-new.dataTable>thead .sorting_desc_disabled:after {
        right: 0.5em;
        content: "\f0d7";
        font-family: "FontAwesome";
        top: 15px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }

    table.table-new.dataTable>thead .sorting:before,
    table.table-new.dataTable>thead .sorting_asc:before,
    table.table-new.dataTable>thead .sorting_desc:before,
    table.table-new.dataTable>thead .sorting_asc_disabled:before,
    table.table-new.dataTable>thead .sorting_desc_disabled:before {
        right: 0.5em;
        content: "\f0d8";
        font-family: "FontAwesome";
        top: 5px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive table-newdatatable" id="franchise_daily_booking_report">
                    <table class="table table-bordered custom-table datatable table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Sr. No.</th>
                                <th>DeliveryBoy No</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Pincode</th>
                                <th>Wallet Amount</th>
                                <th>Commission</th>
                                <th>Remaining Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $value)
                            <tr> 
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start">{{ $value['delivery_boy_no'] }}</td>
                                <td class="text-start">{{ $value['name'] }}</td>
                                <td class="text-start">{{ $value['mobile'] }}</td>
                                <td class="text-start">{{ $value['email'] }}</td>
                                <td class="text-start">{{ $value['pincode'] }}</td>
                                <td class="text-start">{{ $value['wallet_balance'] }}</td>
                                <td class="text-start">{{ $value['commission'] ?? 'N/A' }}</td>
                                <td class="text-start">{{ $value['remaining_balance'] ?? 0 }}</td>
                                <td class="text-start">
                                    <a class="custom-button" href="{{ route('admin.deliveryBoy.commissionDetail', ['id' => $value['id']]) }}" >
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
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
    function filterDailyBookingsReportByDate() {
        var date = $('.bookingdate').val();

        console.log("reached here", )
        // Construct the URL with query parameters
        var url = "{{ route('admin.daily-booking-report.all') }}";
        url += "?date=" + encodeURIComponent(date);

        $.ajax({
            url: url,
            type: "GET", // Use GET request
            success: function(response) {
                if (response.status == 200) {
                    var data = response.data;


                    // Clear existing table rows
                    $('#franchise_daily_booking_report tbody').empty();

                    // Iterate over the data and create rows

                    $.each(data, function(index, item) {
                        var row = $('<tr>');

                        row.append($('<td>').text(index)); // Adding serial number
                        row.append($('<td>').text(item.service_type));
                        row.append($('<td>').text(item.No_of_article));
                        row.append($('<td>').text(item.total_value));
                        row.append($('<td>').text(item.wallet_balance));
                        row.append($('<td>').text(item.remaining_balance));
                        // Append the row to the table
                        $('#franchise_daily_booking_report tbody').append(row);
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', "Something went wrong", 'error');
            }
        });
    }
</script>

@endpush

@endsection