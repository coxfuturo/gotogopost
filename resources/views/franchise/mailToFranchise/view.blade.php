@extends('franchise.layouts.master')



@section('title') {{\App\Models\Admin::E2H}} @endsection



@section('content')


<style>
    .card-body {
    flex: 1 1 auto;
    padding: 20px;

}


.rounded-circle {
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
                            <div class="col-sm-3">
                                <div class="mail-view-action">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white btn-sm" data-bs-toggle="tooltip" title="Delete"> <i class="fa-regular fa-trash-can"></i></button>
                                        <button type="button" class="btn btn-white btn-sm" data-bs-toggle="tooltip" title="Reply"> <i class="fa-solid fa-reply"></i></button>
                                        <button type="button" class="btn btn-white btn-sm" data-bs-toggle="tooltip" title="Forward"> <i class="fa fa-share"></i></button>
                                    </div>
                                    <button type="button" class="btn btn-white btn-sm" data-bs-toggle="tooltip" title="Print"> <i class="fa-solid fa-print"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="sender-info">
                            <div class="sender-img">
                                @if($mail->user)
                                @if($mail->user->image)
                                    <img width="40" src="{{ asset('tenancy/assets/admin/User/'.$mail->user->image) }}" alt="User Image" class="rounded-circle">
                                @else
                                    <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
                                @endif
                            @elseif($mail->recipients[0]->destinationFranchiseDetails)
                                @if($mail->recipients[0]->destinationFranchiseDetails->kyc->photo)
                  
                                    <img width="40" style="height:40px;object-fit:cover" src="{{ asset('admin/franchise/'.$mail->recipients[0]->destinationFranchiseDetails->generated_id.'/'.$mail->recipients[0]->destinationFranchiseDetails->kyc->photo) }}" alt="User Image" class="rounded-circle">
                                @else
                                    <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
                                @endif
                            @else
                                <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
                            @endif
                            
                            
                            </div>
                            <div class="receiver-details float-start">
                                <span class="sender-name">
                                        @if($mail->recipients->isNotEmpty())
                                        {{ $mail->recipients[0]->destinationFranchiseDetails->name ?? 'N/A' }}
                                        @else
                                        N/A
                                        @endif
                                </span>
                                <span class="sender-name">
                                    @if($mail->recipients->isNotEmpty())
                                        {{ $mail->recipients[0]->destinationFranchiseDetails->email ?? 'N/A' }}
                                        @else
                                        N/A
                                        @endif
                                    {{ $mail->user ? $mail->user->phone : ($mail->franchise ? $mail->franchise->mobile : 'Unknown') }}
                                </span>
                            
                                <span class="sender-name">
                                    @if($mail->recipients->isNotEmpty())
                                        {{ $mail->recipients[0]->destinationFranchiseDetails->pincode ?? 'N/A' }}
                                        @else
                                        N/A
                                        @endif
                                </span>
                                
                                <span class="receiver-name">
                                    To:-
                                    @foreach($mail->recipients as $recipient)
                                        <span>{{ $recipient->recipient_phone }}</span>{{ !$loop->last ? ',' : '' }}
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


                <div class="mailview-footer">
                    <div class="row">
                        <div class="col-sm-6 left-action">
                            <button type="button" class="btn btn-white"><i class="fa-solid fa-reply"></i> Reply</button>
                            <button type="button" class="btn btn-white"><i class="fa fa-share"></i> Forward</button>
                        </div>
                        <div class="col-sm-6 right-action">
                            <button type="button" class="btn btn-white"><i class="fa-solid fa-print"></i> Print</button>
                            <button type="button" class="btn btn-white"><i class="fa-regular fa-trash-can"></i> Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

