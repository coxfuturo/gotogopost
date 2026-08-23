@extends('cms.layouts.master')

@section('title'){{\App\Models\Admin::E2H}} @endsection

@section('content')
@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="{{route('cms.mailTofranhchise.create')}}" style="margin-left:10px;" class="btn add-btn"><i class="fa-solid fa-plus"></i>compose</a>
</div>
@endpush

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

<div class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="email-header mb-2">
                        <div class="row">
                            <div class="col top-action-left">
                                <div class="float-start">

                                    <a class="btn-white btn" href="{{ route('cms.mailTofranhchise.sentMails', ['service_type' => 'mail_to_franchise_bulk']) }}">
                                        Sent Mails Bulk
                                    </a>

                                    <a class="btn-white btn" href="{{ route('cms.mailTofranhchise.sentMails', ['service_type' => 'mail_to_franchise_single']) }}">
                                        Sent Mails Single
                                    </a>

                                </div>
                                <!-- Search Filter -->
                                <form action="{{ route('cms.mailTofranhchise.viewAssignedMailParcel') }}" method="GET">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-3 col-12">
                                            <div class="input-block mb-3 form-focus">
                                                <div class="cal-icon">
                                                    <input class="form-control floating datetimepicker bookingdate" type="text" name="date" required>
                                                </div>
                                                <label class="focus-label">Date</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-1">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- /Search Filter -->

                            </div>
                        </div>
                    </div>
                    <div class="email-content">
                        <div class="table-responsive">
                            <table class="table table-inbox table-hover  datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Delivered Date</th>
                                        <th class="text-center">To Franchise Name</th>
                                        <th class="text-center">To Franchise Pincode</th>
                                        <th class="text-center">To Franchise Phone</th>
                                        <th class="text-center">Recipient Phone</th>
                                        <th class="text-center">Recipient Address</th>
                                        <th class="text-center">Recipient Email</th>
                                        <th class="text-center">Subject</th>
                                        <th class="text-center"></th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recievedMails as $mail)
                                    <tr>

                                        @if ($mail->delivered_date)
                                        <td>{{ \Carbon\Carbon::parse($mail->delivered_date)->format('d-M-Y') }}</td>
                                        @else
                                        <td>
                                            {{ $mail->cancelDelivery ? $mail->cancelDelivery->cancel_reason : 'No reason provided' }}
                                        </td>
                                        @endif

                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->destinationFranchiseDetails->name ?? 'N/A' }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->destinationFranchiseDetails->pincode ?? 'N/A' }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->destinationFranchiseDetails->mobile ?? 'N/A' }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->recipient_phone ?? 'N/A' }}
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
                                        
                                        <td class="text-center">
                                            @if($mail->recipients->isNotEmpty())
                                            {{ $mail->recipients[0]->recipient_email ?? 'N/A' }}
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <!-- Displaying mail subject -->
                                        <td class="text-center">
                                            {{ $mail->subject ?? 'No Subject' }}
                                        </td>

                                        <!-- Displaying attachments icon if present -->
                                        <td class="text-center">
                                            @if($mail->attachments->count() > 0)
                                            <i class="fa-solid fa-paperclip"></i>
                                            @endif
                                        </td>

                                        <!-- Displaying mail creation date or time if today -->

                                        <td> <a class="dropdown-item" href="{{route('cms.mailTofranhchise.sentview', ['id' => $mail->id])}}"><i class="fa-regular fa-eye m-r-5"></i> view</a></td>
                                    </tr>
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
                url: "{{ route('cms.mailTofranhchise.assigntoDeliveryBoy') }}",
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



@endpush

@endsection