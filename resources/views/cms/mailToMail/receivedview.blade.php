@extends('cms.layouts.master')



@section('title') {{\App\Models\Admin::E2E}} @endsection



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
                        </div>
                        <div class="sender-info">
                            <div class="sender-img">
                                @if($mail->user)
                                @if($mail->user->image)
                                <img width="40" src="{{ asset('tenancy/assets/admin/User/'.$mail->user->image) }}" alt="User Image" class="rounded-circle">
                                @else
                                <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
                                @endif
                                @elseif($mail->franchise)
                                @if($mail->franchise->kyc->photo)

                                <img width="40" style="height:40px;object-fit:cover" src="{{ asset('admin/franchise/'.$mail->franchise->generated_id.'/'.$mail->franchise->kyc->photo) }}" alt="User Image" class="rounded-circle">
                                @else
                                <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
                                @endif
                                @elseif($mail->cms)
                                @if($mail->cms->kyc->photo)

                                <img width="40" style="height:40px;object-fit:cover" src="{{ asset('admin/cms/'.$mail->cms->generated_id.'/'.$mail->cms->kyc->photo) }}" alt="User Image" class="rounded-circle">
                                @else
                                <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
                                @endif
                                @elseif($mail->pph)
                                @if($mail->pph->kyc->photo)

                                <img width="40" style="height:40px;object-fit:cover" src="{{ asset('admin/pph/'.$mail->pph->generated_id.'/'.$mail->pph->kyc->photo) }}" alt="User Image" class="rounded-circle">
                                @else
                                <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
                                @endif
                                @else
                                <img width="40" src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}" alt="User Image" class="rounded-circle">
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
                                    <span>{{ $recipient->recipient_email }}</span>{{ !$loop->last ? ',' : '' }}
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


@endsection