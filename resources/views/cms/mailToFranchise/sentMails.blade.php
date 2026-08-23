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

                                    <a class="btn-white btn" href="{{ route('cms.mailTofranhchise.receivedMails', ['service_type' => 'mail_to_franchise_single']) }}">Inbox</a>

                                    @if(request()->query('service_type') === 'mail_to_franchise_single')
                                    <a class="btn-white btn" href="{{ route('cms.mailTofranhchise.sentMails', ['service_type' => 'mail_to_franchise_bulk']) }}">
                                        Sent Mails Bulk
                                    </a>
                                    @endif

                                    @if(request()->query('service_type') === 'mail_to_franchise_bulk')
                                    <a class="btn-white btn" href="{{ route('cms.mailTofranhchise.sentMails', ['service_type' => 'mail_to_franchise_single']) }}">
                                        Sent Mails Single
                                    </a>
                                    @endif


                                </div>

                                <!-- Search Filter -->
                                <form id="searchForm" action="{{ route('cms.mailTofranhchise.sentMails') }}" method="GET">
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
                                        <th class="text-center">To Franchise Name</th>
                                        <th class="text-center">To Franchise Pincode</th>
                                        <th class="text-center">To Franchise Phone</th>
                                        <th class="text-center">Recipient Phone</th>
                                        <th class="text-center">Recipient Address</th>
                                        <th class="text-center">Subject</th>
                                        <th class="text-center"></th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sentMails as $mail)
                                    <tr>

                                        <!-- Displaying recipient's phone -->
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
                                            {{ $mail->recipients[0]->destinationFranchiseDetails->email ?? 'N/A' }}
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

                                        <!-- Displaying total payment amount -->
                                        <td class="text-center">
                                            ₹ {{ $mail->total_payment_amount ?? '0.00' }}
                                        </td>

                                        <!-- Displaying mail creation date or time if today -->
                                        <td class="text-center">
                                            @if($mail->created_at->isToday())
                                            {{ $mail->created_at->format('H:i') }}
                                            @else
                                            {{ $mail->created_at->format('d-m-Y') }}
                                            @endif
                                        </td>
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
    document.getElementById('searchForm').onsubmit = function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the existing URL
        let existingUrl = "{{ request()->fullUrlWithQuery(['service_type' => request()->query('service_type')]) }}";

        console.log(existingUrl);
        // Get the date value from the input
        let dateValue = this.date.value;

        // Create the new URL with the date parameter appended
        let newUrl = existingUrl + '&date=' + encodeURIComponent(dateValue);

        // Redirect to the new URL
        window.location.href = newUrl;
    };
</script>


@endpush


@endsection