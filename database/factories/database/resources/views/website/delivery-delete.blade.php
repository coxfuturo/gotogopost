@include('website.layouts.header')
@extends('website.layouts.master')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Page Header -->
<div class="page-header title-area">
    <div class="header-title">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h1 class="page-title">Delete Your Account</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Section -->
<section class="delete-account-section" style="margin-top: 15px; margin-bottom: 15px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="fh-section-title text-center">
                    <h2>Delete Account</h2>
                </div>
                
                <!-- Form Section -->
                <form id="delete-account-form" method="POST" action="{{ route('website.deleteDeliveryAccount') }}">
                    @csrf
                    <div class="fh-form">
                        <div class="form-group">
                            <label for="email">Email ID</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your registered email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="text-center">
                            <button type="button" id="verify-button" class="btn btn-warning">Verify & Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 for Notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('verify-button').addEventListener('click', function() {
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    if (email.trim() === '' || password.trim() === '') {
        Swal.fire('Error', 'Please enter both email and password', 'error');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch("{{ route('website.deleteDeliveryAccount') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Success', data.message, 'success').then(() => {
                window.location.href = '/';
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Something went wrong. Please try again later.', 'error');
    });
});
</script>
@endsection
