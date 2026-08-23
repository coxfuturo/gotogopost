@extends('franchise.layouts.master')



@section('title') {{\App\Models\Admin::E2H}} @endsection


@push('add-modal-code')

<div class="col-auto float-end ms-auto">
    <button style="margin-right:10px;" class="btn add-btn" id="trackOrder" data-bs-toggle="modal" data-bs-target="#track_order">Track Order</button>
</div>

@endpush
@section('content')


<style>
    .card-body {
    flex: 1 1 auto;
    padding: 20px;

}


.profile-image{
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50% !important;
    border: 2px solid #d49a4f;
}
</style>


<div class="row">
    <div class="col-sm-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="mailview-content">
                    <div class="mailview-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="text-ellipsis m-b-10">
                                    <span class="mail-view-title">{{ $mail->subject }}</span>
                                </div>
                            </div>
                           
                        </div>
                        <div class="sender-info">
                            <div class="sender-img">
                                @if($mail->user)
                                @if($mail->user->image)
                                    <img class="profile-image" src="{{ asset('tenancy/assets/admin/User/'.$mail->user->image) }}" alt="User Image" >
                                @else
                                    <img class="profile-image" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" >
                                @endif
                                @elseif($mail->franchise)
                                @if($mail->franchise->kyc->photo)
                
                                    <img class="profile-image" style="height:40px;object-fit:cover" src="{{ asset('admin/franchise/'.$mail->franchise->generated_id.'/'.$mail->franchise->kyc->photo) }}" alt="User Image" >
                                @else
                                    <img class="profile-image" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" >
                                @endif
                                @elseif($mail->cms)
                                @if($mail->cms->kyc->photo)
                
                                    <img class="profile-image" style="height:40px;object-fit:cover" src="{{ asset('admin/cms/'.$mail->cms->generated_id.'/'.$mail->cms->kyc->photo) }}" alt="User Image" >
                                @else
                                    <img class="profile-image" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" >
                                @endif
                                @elseif($mail->pph)
                                @if($mail->pph->kyc->photo)
                
                                    <img class="profile-image" style="height:40px;object-fit:cover" src="{{ asset('admin/pph/'.$mail->pph->generated_id.'/'.$mail->pph->kyc->photo) }}" alt="User Image" >
                                @else
                                    <img class="profile-image" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" >
                                @endif
                                @else
                                    <img class="profile-image" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" >
                                @endif

                            </div>
                            <div class="receiver-details float-start">
                                <span class="sender-name">
                                    {{ $mail->user ? $mail->user->name : ($mail->franchise ? $mail->franchise->name : ($mail->cms ? $mail->cms->name : ($mail->pph ? $mail->pph->name : 'Unknown'))) }}
                                </span>
                                <span class="sender-name">
                                    {{ $mail->user ? $mail->user->mobile : ($mail->franchise ? $mail->franchise->mobile : ($mail->cms ? $mail->cms->mobile : ($mail->pph ? $mail->pph->mobile : 'Unknown'))) }}
                                </span>
                                <span class="sender-name">
                                    {{ $mail->user ? $mail->user->email : ($mail->franchise ? $mail->franchise->email : ($mail->cms ? $mail->cms->email : ($mail->pph ? $mail->pph->email : 'Unknown'))) }}
                                </span>
                                <span class="sender-name">
                                    {{ $mail->user ? $mail->user->pincode : ($mail->franchise ? $mail->franchise->pincode : ($mail->cms ? $mail->cms->pincode : ($mail->pph ? $mail->pph->pincode : 'Unknown'))) }}
                                </span>
                                                               
                                <span class="receiver-name">
                                    To :-
                                    @foreach($mail->recipients as $recipient)
                                        <span>{{ $recipient->recipient_phone }}</span>{{ !$loop->last ? ',' : '' }}
                                        <br>
                                        <span>{{ $recipient->recipient_address }}</span>{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </span>


                            </div>	
                            <div class="mail-sent-time">
                                <span class="mail-time">{{ $mail->created_at->format('d M Y h:i A') }}</span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="mailview-inner">
                        {!! $mail->body !!}
                    </div>
                </div>
                @if($mail->attachments->count() > 0)
                <div class="mail-attachments">
                    <p><i class="fa-solid fa-paperclip"></i> {{ $mail->attachments->count() }} Attachments</p>
                    <ul class="attachments clearfix">
                        @foreach($mail->attachments as $attachment)
                            <li>
                                @if(str_contains($attachment->file_path, '.pdf'))
                                    <div class="attach-file"><i class="fa-regular fa-file-pdf"></i></div>
                                @elseif(str_contains($attachment->file_path, '.xlsx') || str_contains($attachment->file_path, '.xls'))
                                    <div class="attach-file"><i class="fa-regular fa-file-excel"></i></div>
                                @else
                                    <div class="attach-file"><img src="{{ asset($attachment->file_path) }}" alt="Attachment"></div>
                                @endif
                                <div class="attach-info">
                                    <a href="{{ asset($attachment->file_path) }}" class="attach-filename" target="_blank">{{ $attachment->file_name }}</a>
                                    <div class="attach-file-size">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif


            </div>
        </div>
    </div>
</div>
<!-- Track Order Modal -->

<div id="track_order" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Tracking Order Details</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div>
                <div class="tracking-container">
                    <ul class="tracking-steps row">

                    </ul>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Track Order Modal -->

@push('page-javascript')



<script>
    $(document).ready(function() {

        

        $('#trackOrder').on('click', function() {
            console.log("Reached here");

            $.ajax({
                url: "{{ route('franchise.mail-to-franchise.trackOrder', ['id' => $mail->id]) }}",
                method: 'GET',
                success: function(response) {
                    console.log(response);

                    const tracking = response.trackingDetails;

                    // Clear existing data in the modal
                    $('.tracking-steps').empty();

                    const steps = [];

                    // Source Franchise (Order Placed)
                    if (tracking.source_franchise) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">&#10003;</div>
                                    <div class="step-details">
                                        <p class="step-title">Sender </p>
                                        <p class="step-date">${formatDate(tracking.created_at)}</p>
                                        <p class="step-location">Location: ${tracking.source_franchise.address || '-'}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Source Franchise (Order Placed)
                    if (tracking.user) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">&#10003;</div>
                                    <div class="step-details">
                                        <p class="step-title">Sender </p>
                                        <p class="step-date">${formatDate(tracking.created_at)}</p>
                                        <p class="step-location">Location: ${tracking.user.address || '-'}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // Source Franchise (Order Dispatched)
                    if (tracking.destination_franchise) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">&#128640;</div>
                                    <div class="step-details">
                                        <p class="step-title">Reciver</p>
                                        <p class="step-date">${formatDate(tracking.created_at)}</p>
                                        <p class="step-location">Location: ${tracking.destination_franchise.address || '-'}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // delivery_boy_assigned_datetime
                    if (tracking.delivery_boy_assigned_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title"> Assign To Delivery Boy </p>
                                        <p class="step-date">${formatDate(tracking.delivery_boy_assigned_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.destination_franchise.address || '-'}</p>
                                    </div>
                                </li>
                            `);
                    }

                    // delivery_datetime
                    if (tracking.delivery_datetime) {
                        steps.push(`
                                <li class="tracking-item in-progress col-md-6">
                                    <div class="step-icon">🏢</div>
                                    <div class="step-details">
                                        <p class="step-title">Delivered</p>
                                        <p class="step-date">${formatDate(tracking.delivery_datetime)}</p>
                                        <p class="step-location">Location: ${tracking.mail.recipients[0].recipient_address || '-'}</p>
                                    </div>
                                </li>
                            `);
                    }

                 
                    // Append all steps to the modal
                    $('.tracking-steps').html(steps.join(''));
                },
                error: function(error) {
                    console.error('Failed to fetch tracking details:', error);
                }
            });
        });

        // Helper function to format date
        function formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

    });
</script>

@endpush


@endsection

