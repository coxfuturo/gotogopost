@extends('prepaid.layouts.master')

@section('title', 'Profile Management')

@section('content')
<style>
    .profile-view .profile-img-wrap img {
        border-radius: 50%;
        height: 120px;
        width: 120px;
        object-fit: cover;
    }
    .personal-info li .text {
        color: #bb2828;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }
    .tab-content {
        padding-top: 0px;
    }
</style>

<div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="#">
                                <img src="{{ asset('admin/assets/img/profiles/avatar-02.jpg') }}" alt="Profile Image">
                            </a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0">{{ $franchise->name }}</h3>
                                    <h5 class="">Name : {{ $franchise->name }}</h5>
                                    <h5 class="">Mobile : {{ $franchise->mobile }}</h5>
                                    <div class="staff-id">ID : {{ $franchise->generated_id }}</div>
                                    <div class="staff-id">Account Type : Prepaid Customer</div>
                                    <div class="doj">Date of registration : {{ date('d M Y', strtotime($franchise->created_at)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Phone:</div>
                                        <div class="text"><a href="#">{{ $franchise->mobile }}</a></div>
                                    </li>
                                    <li>
                                        <div class="title">Email:</div>
                                        <div class="text"><a href="#">{{ $franchise->email }}</a></div>
                                    </li>
                                    <li>
                                        <div class="title">Account Status:</div>
                                        <div class="text">
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="title">Last Login:</div>
                                        <div class="text">
                                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, h:i A') : 'Today' }}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-edit">
                        <span class="badge bg-info">Prepaid Account</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tab-content">
    <!-- Profile Info Tab -->
    <div id="emp_profile" class="pro-overview tab-pane fade show active">
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-body">
                        <h3 class="card-title">
                            <i class="fas fa-university me-2"></i>Bank Information
                            <span class="badge bg-warning float-end">Limited Access</span>
                        </h3>
                        <ul class="personal-info">
                            <li>
                                <div class="title">Bank name</div>
                                <div class="text">{{ $franchise->kyc->bank_name }}</div>
                            </li>
                            <li>
                                <div class="title">Branch name</div>
                                <div class="text">{{ $franchise->kyc->branch_name }}</div>
                            </li>
                            <li>
                                <div class="title">Bank account No.</div>
                                <div class="text">{{ $franchise->kyc->account_number }}</div>
                            </li>
                            <li>
                                <div class="title">IFSC Code</div>
                                <div class="text">{{ $franchise->kyc->ifsc_code }}</div>
                            </li>
                            <li>
                                <div class="title">PAN No</div>
                                <div class="text">{{ $franchise->kyc->pan_card }}</div>
                            </li>
                            <li>
                                <div class="title">Adhar No</div>
                                <div class="text">{{ $franchise->kyc->adhar_card }}</div>
                            </li>
                        </ul>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> 
                            For updating bank information, please contact customer support.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <div class="card-body">
                        <h3 class="card-title">
                            <i class="fas fa-id-card me-2"></i>Account Details
                        </h3>
                        <ul class="personal-info">
                            <li>
                                <div class="title">Account Type</div>
                                <div class="text">
                                    <span class="badge bg-primary">Prepaid Customer</span>
                                </div>
                            </li>
                            <li>
                                <div class="title">User ID</div>
                                <div class="text">{{ $franchise->generated_id }}</div>
                            </li>
                            <li>
                                <div class="title">Mobile Number</div>
                                <div class="text">{{ $franchise->mobile }}</div>
                            </li>
                            <li>
                                <div class="title">Email Address</div>
                                <div class="text">{{ $franchise->email }}</div>
                            </li>
                            <li>
                                <div class="title">Registration Date</div>
                                <div class="text">{{ date('d M Y', strtotime($franchise->created_at)) }}</div>
                            </li>
                            <li>
                                <div class="title">Account Status</div>
                                <div class="text">
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </li>
                        </ul>
                        
                        <div class="mt-4">
                            <h5>Prepaid Account Features:</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Parcel Booking</li>
                                <li><i class="fas fa-check text-success me-2"></i>Tracking</li>
                                <li><i class="fas fa-check text-success me-2"></i>Reports</li>
                                <li><i class="fas fa-times text-danger me-2"></i>KYC Update (Contact Support)</li>
                                <li><i class="fas fa-times text-danger me-2"></i>Bank Update (Contact Support)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Profile Info Tab -->
</div>

<!-- Information Modal -->
<div id="prepaid_info" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prepaid Account Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Prepaid Account Limitations</h6>
                    <p class="mb-2">As a prepaid customer, you have:</p>
                    <ul class="mb-0">
                        <li>Access to basic profile information</li>
                        <li>Parcel booking and tracking features</li>
                        <li>Limited profile editing capabilities</li>
                    </ul>
                    <p class="mt-2 mb-0">
                        <strong>To update KYC, bank details, or other profile information:</strong><br>
                        Please contact our customer support team.
                    </p>
                </div>
                <div class="text-center">
                    <button class="btn btn-primary" data-bs-dismiss="modal">Got it</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-javascript')
<script>
    $(document).ready(function() {
        // Show prepaid info modal on page load
        $('#prepaid_info').modal('show');
        
        // Disable edit functionality for prepaid users
        $('.edit-icon, #profile_info').on('click', function(e) {
            e.preventDefault();
            $('#prepaid_info').modal('show');
        });
        
        // Add prepaid badge to page header
        $('.page-header .col:first').append(
            '<div class="mt-2"><span class="badge bg-primary">Prepaid Account</span></div>'
        );
    });
</script>
@endpush
@endsection