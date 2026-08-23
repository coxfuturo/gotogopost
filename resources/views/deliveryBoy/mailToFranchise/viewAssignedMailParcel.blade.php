@extends('deliveryBoy.layouts.master')

@section('title') {{\App\Models\Admin::E2H}} @endsection

@section('content')


<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }

    .mail-important {
        color: gold;
    }

    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 100% !important;
    }
</style>


<style>
    .page-wrapper .content .page-header {
        margin-bottom: 0.675rem;
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }

    .verify-btn {
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

<div class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-body">
                    
                    <div class="email-content">
                        <div class="table-responsive">
                            <table class="table table-inbox table-hover  datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Verify</th>
                                        <th class="text-center">Cancel</th>
                                        <th class="text-center">Recipient Phone</th>
                                        <th class="text-center">Recipient Email</th>
                                        <th class="text-center">Recipient Address</th>
                                        <th class="text-center">Subject</th>
                                        <th class="text-center">Payment Amount</th>
                                        <!-- <th class="text-center"></th> -->

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recievedMails as $mail)
                                    <tr>

                                        <td>
                                            <a class="verify-btn" href="{{ route('deliveryBoy.mailTofranhchise.verifyOtp', ['id'=>$mail->id]) }}">Verify OTP</a>
                                        </td>
                                        <td>
                                            <a class="verify-btn" data-bs-toggle="modal" data-bs-target="#edit_role{{$mail->id}}" href="">Cancle</a>
                                        </td>

                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->recipient_phone ?? 'N/A' }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->recipient_email ?? 'N/A' }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <!-- Displaying recipient's email -->
                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->recipient_address ?? 'N/A' }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <!-- Displaying mail subject -->
                                        <td class="text-center">
                                            {{ $mail->subject ?? 'No Subject' }}
                                        </td>

                                        <td class="text-center">
                                            ₹ {{ $mail->total_payment_amount ?? '0.00' }}
                                        </td>
                                    </tr>

                                    <div id="edit_role{{$mail->id}}" class="modal custom-modal fade" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-md">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cancel Delivery</h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                @if($mail)

                                                <div class="modal-body">
                                                    <form action="{{ route('deliveryBoy.mailTofranhchise.cancelDelivery', ['id' => $mail->id]) }}" method="POST">
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
    </div>
</div>







@push('page-javascript')

<script>
    $(document).ready(function() {

        console.log("reached here started")
        let selectedMails = [];

        // Handle select all checkboxes
        $('#selectAll').on('click', function() {
            if ($(this).is(':checked')) {
                // Select all checkboxes
                $('.checkmail').prop('checked', true);

                // Clear the array first to avoid duplicates
                selectedMails = [];

                // Add all mail IDs to the array
                $('.checkmail').each(function() {
                    let mailId = $(this).data('id');
                    selectedMails.push(mailId);
                });
            } else {
                // Deselect all checkboxes
                $('.checkmail').prop('checked', false);
                // Clear the selectedMails array when deselecting all
                selectedMails = [];
            }

            console.log(selectedMails); // For debugging
        });

        // Handle individual checkbox click
        $('.checkmail').on('change', function() {
            let mailId = $(this).data('id');
            if ($(this).is(':checked')) {
                if (!selectedMails.includes(mailId)) {
                    selectedMails.push(mailId);
                }
            } else {
                selectedMails = selectedMails.filter(id => id !== mailId);
            }
            console.log(selectedMails); // Debugging to see selected IDs
        });

        // Add an event listener if you need to use the selected mail IDs (e.g., for deletion)
        $('#transferToDeliveryBoyForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var csrfToken = "{{ csrf_token() }}"; // Get CSRF token
            var selectedMails = []; // Define the array of selected mail IDs

            // Capture selected mail IDs
            $('.checkmail:checked').each(function() {
                let mailId = $(this).data('id');
                selectedMails.push(mailId); // Append checked mail IDs
            });

            // Make sure the array is not empty
            if (selectedMails.length === 0) {
                alert('Please select at least one mail.');
                return;
            }

            // Create a FormData object to append form fields and selected mail IDs
            var formData = new FormData(this);

            // Append the selected mail IDs to the form data
            formData.append('selectedMails', JSON.stringify(selectedMails)); // Convert array to string

            // Send the AJAX request
            $.ajax({
                type: 'POST',
                url: "{{ route('franchise.mailTofranhchise.assigntoDeliveryBoy') }}",
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in the headers
                },
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                data: formData, // Send the form data with selected mail IDs
                success: function(data) {
                    if (data.success) {
                        Swal.fire('Success!', "Status updated", 'success').then(() => {
                            window.location.reload(); // Reload the page after the alert is confirmed
                        });
                    } else {
                        Swal.fire('Error!', "Something went wrong", 'error').then(() => {
                            window.location.reload(); // Reload the page after the alert is confirmed
                        });
                    }
                }
            });
        });



    });
</script>



<script>
    function filterParcelsByDate() {
        var date = $('.date').val();

        // Construct the URL with query parameters
        var url = "{{ route('franchise.mailTofranhchise.viewAssignedMailParcel') }}";
        url += "?date=" + encodeURIComponent(date) + "&type=parcel";

        $.ajax({
            url: url,
            type: "GET", // Use GET request
            success: function(response) {
                if (response.status == 200) {
                    var data = response.html;

                    $('#parcel tbody').empty();

                    $('#parcel tbody').html(data);

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