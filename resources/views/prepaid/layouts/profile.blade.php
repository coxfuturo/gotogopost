@extends('prepaid.layouts.master')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="fas fa-user me-2"></i>My Profile - Prepaid Account</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Left Column - Profile Info -->
                    <div class="col-md-4">
                        <div class="card profile-card">
                            <div class="card-body text-center">
                                <div class="profile-img mb-3">
                                    <div class="avatar avatar-xxl">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="fas fa-user fa-2x"></i>
                                        </span>
                                    </div>
                                </div>
                                <h4>{{ $user->name ?? 'Prepaid User' }}</h4>
                                <p class="text-muted">Prepaid Customer</p>
                                <span class="badge bg-primary mb-3">Active Account</span>
                                
                                <div class="profile-info mt-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-calendar me-2 text-primary"></i> Joined:</span>
                                        <strong>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><i class="fas fa-user-tag me-2 text-success"></i> Type:</span>
                                        <strong>Prepaid</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-check-circle me-2 text-success"></i> Status:</span>
                                        <strong>Verified</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Details -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-phone-alt me-2"></i>Contact Information
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <label class="form-label">Mobile:</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{ $user->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="form-label">Email:</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">{{ $user->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-id-card me-2"></i>Account Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <label class="form-label">User ID:</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">PREPAID-{{ $user->id ?? '000' }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label class="form-label">Last Login:</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="form-control-static">
                                                    {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, h:i A') : 'Today' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Information Section -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Account Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="fas fa-info-circle fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="alert-heading">Prepaid Account Features</h6>
                                            <p class="mb-2">As a prepaid customer, you have access to:</p>
                                            <ul class="mb-0">
                                                <li>Parcel booking and tracking</li>
                                                <li>Booking reports and history</li>
                                                <li>Download invoices and documents</li>
                                                <li>Basic profile information</li>
                                            </ul>
                                            <p class="mt-2 mb-0">
                                                <strong>Note:</strong> For profile updates, KYC verification, or additional services, 
                                                please contact our customer support team.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('prepaid.dashboard') }}" class="btn btn-primary">
                                        <i class="fas fa-tachometer-alt me-1"></i> Back to Dashboard
                                    </a>
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-edit me-1"></i> Edit Profile
                                    </button>
                                    <a href="#" class="btn btn-outline-info">
                                        <i class="fas fa-headset me-1"></i> Contact Support
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-javascript')
<script>
    $(document).ready(function() {
        // Add any JavaScript for profile page here
        console.log('Profile page loaded');
    });
</script>
@endpush