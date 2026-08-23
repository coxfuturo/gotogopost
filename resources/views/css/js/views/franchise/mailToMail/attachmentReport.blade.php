@extends('franchise.layouts.master')

@section('title') {{ \App\Models\Admin::E2E }} Attachment Report @endsection




@section('content')
@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="{{route('franchise.mailToMail.create')}}" style="margin-left:10px;" class="btn add-btn"><i class="fa-solid fa-plus"></i>compose</a>
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
                        
                                    <a class="btn-white btn" href="{{ route('franchise.mailToMail.receivedMails', ['service_type' => 'mail_to_mail_single']) }}">Inbox</a>

                                    @if(request()->query('service_type') === 'mail_to_mail_single')
                                    <a class="btn-white btn" href="{{ route('franchise.mailToMail.attachmentReport', ['service_type' => 'mail_to_mail_bulk']) }}">
                                        Sent Mails Bulk
                                    </a>
                                    @endif

                                    @if(request()->query('service_type') === 'mail_to_mail_bulk')
                                    <a class="btn-white btn" href="{{ route('franchise.mailToMail.attachmentReport', ['service_type' => 'mail_to_mail_single']) }}">
                                        Sent Mails Single
                                    </a>
                                    @endif
                                </div>

                                <!-- Search Filter -->
                                <form id="searchForm" action="{{ route('franchise.mailToMail.attachmentReport') }}" method="GET">
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
                    <table class="table table-inbox table-hover datatable">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Download Date</th>
                                <th>Mail Code</th>
                                <th>Mail Subject</th>
                                <th>Attachments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recipients as $index => $recipient)
                            <tr>
                                <td>{{ $index + 1 }}</td>  {{-- Serial Number --}}
                                <td>{{ $recipient['recipient_phone'] }}</td>
                                <td>{{ $recipient['recipient_email'] }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($recipient['view_date'])->format('d M Y, h:i A') }}
                                </td>                                
                                <td>{{ $recipient['mail_code'] }}</td>
                                <td>{{ $recipient['mail_subject'] }}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @if(count($recipient['attachments']) > 0)
                                                <div class="dropdown-divider"></div>
                                                <h6 class="dropdown-header">Attachments</h6>
                                                @foreach($recipient['attachments'] as $attachment)
                                                    <a class="dropdown-item" href="{{ asset($attachment['file_path']) }}" target="_blank">
                                                        <i class="fa-solid fa-file m-r-5"></i> {{ $attachment['file_name'] }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
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