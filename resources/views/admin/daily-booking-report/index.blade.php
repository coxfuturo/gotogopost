@extends('admin.layouts.master')

@section('title') Daily Booking Report @endsection

@section('content')



@push('add-modal-code')


@endpush







<!-- Search Filter -->
<div class="row">

    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
        <div class="input-block mb-3 form-focus">
            <div class="cal-icon">
                <input class="form-control floating datetimepicker bookingdate" type="text">
            </div>
            <label class="focus-label">Date</label>
        </div>
    </div>
    <div class="col-sm-6 col-md-1">
        <div class="d-grid">
            <button type="submit" onclick="filterDailyBookingsReportByDate()" class="btn btn-success">Search</button>
        </div>
    </div>
</div>
<!-- /Search Filter -->





<div class="row">

    <div class="col-md-12">

        <div class="table-responsive table-newdatatable" id="franchise_daily_booking_report">

            <table class="table table-new custom-table mb-0 datatable">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Service</th>

                        <th>No of Articles</th>

                        <th>Total Value</th>

                        <th>Wallet Amount</th>

                        <th>Wallet Balalnce</th>

                    </tr>

                </thead>

                <tbody>



                    @foreach($data as $value)
                    <tr>
                        <td>{{ $value['serial_no'] }}</td>
                        <td>{{ $value['service_type'] }}</td>
                        <td>{{ $value['No_of_article'] }}</td>
                        <td>{{ $value['total_value'] }}</td>
                        <td>{{ $value['wallet_balance'] }}</td>
                        <td>{{ $value['remaining_balance'] }}</td>
                    </tr>
                    @endforeach



                </tbody>

            </table>

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