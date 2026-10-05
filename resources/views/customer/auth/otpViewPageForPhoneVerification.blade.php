<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <title>Business Bulk|Register </title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" type="text/css" />
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/assets/img/favicon.png')}}">

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.min.css')}}">

		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    	<link rel="stylesheet" href="{{asset('admin/assets/plugins/fontawesome/css/all.min.css')}}">

		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="{{asset('admin/assets/css/line-awesome.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin/assets/css/material.css')}}">

		<!-- Main CSS -->
        <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">

    </head>

	<style>
		.account-page .main-wrapper .account-content .account-logo img {
    width: 140px !important;
	}
	</style>


<style>

	/* Overlay to cover the whole viewport with a semi-transparent background */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000; /* Higher z-index to overlay the entire page */
}

.hidden {
    display: none;
}

/* Loader animation styles */
.loader {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    position: relative;
    animation: rotate 1s linear infinite;
}

.loader::before,
.loader::after {
    content: "";
    box-sizing: border-box;
    position: absolute;
    inset: 0px;
    border-radius: 50%;
    border: 5px solid #FFF;
    animation: prixClipFix 2s linear infinite;
}

.loader::after {
    border-color: #FF3D00;
    animation: prixClipFix 2s linear infinite, rotate 0.5s linear infinite reverse;
    inset: 6px;
}

/* Keyframes for loader rotation */
@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

@keyframes prixClipFix {
    0% {
        clip-path: polygon(50% 50%, 0 0, 0 0, 0 0, 0 0, 0 0);
    }
    25% {
        clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 0, 100% 0, 100% 0);
    }
    50% {
        clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 100% 100%, 100% 100%);
    }
    75% {
        clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 100%);
    }
    100% {
        clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 0);
    }
}

.account-page .main-wrapper .account-content .account-box .otp-wrap .otp-input {
    background-color: #ffffff;
    border: 1px solid #e3e3e3;
    display: inline-block;
    font-size: 24px;
    font-weight: 500;
    height: 60px;
    line-height: 29px;
    margin-right: 15px;
    text-align: center;
    width: 60px;
    border-radius: 4px;
}
</style>
    <body class="account-page">
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
				<div class="container">
					<!-- Account Logo -->
					<div class="account-logo">
						<a href="#"><img src="{{asset('website/images/logonewtransparent.png')}}" alt="IndiaPost"></a>
					</div>
					<!-- /Account Logo -->
                    <div class="account-box">
                        <div class="account-wrapper">
                            <h3 class="account-title">OTP</h3>
                            <p class="account-subtitle">Verification your account</p>
    
                            {{-- ==============loader================= --}}
                            <div id="overlay" class="overlay hidden">
                                <div class="loader"></div>
                            </div>
						    {{-- ==============loader================= --}}
                            <!-- Account Form -->
                            <form action="{{route('customer.verifyPhoneNumberOtp',['mobile'=>$mobile])}}" class="needs-validation" method="POST"  novalidate>
                                @csrf
                                <div class="otp-wrap">
                                    <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                    <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                    <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                    <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                    <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                </div>
                                <div class="input-block mb-4 text-center">
                                    <button class="btn btn-primary account-btn" type="submit">Enter</button>
                                </div>
                            </form>

                            <form action="{{ route('customer.resendPhoneNumberOtp', ['mobile' => $mobile]) }}" method="post">
                                @csrf
                                <!-- Your form fields go here -->
                            
                                <div class="account-footer">
                                    <p>Not yet received? <a href="javascript:void(0)" onclick="resendOtp()">Resend OTP</a></p>
                                </div>
                            </form>
                            
                            <!-- /Account Form -->
    
                        </div>
                    </div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>

        @if(Session::has('success'))
    <script>
        Swal.fire('Yay!!!', "{{ Session::get('success')}}", 'success')
    </script>
    @endif
    @if(Session::has('error'))
    <script>
        Swal.fire('Oops!!!', "{{ Session::get('error')}}", 'error')
    </script>
    @endif
		<!-- jQuery -->

       <script src="{{asset('admin/assets/js/jquery-3.7.0.min.js')}}"></script>

		<!-- Bootstrap Core JS -->
        <script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>

		<!-- Custom JS -->
		<script src="{{asset('admin/assets/js/app.js')}}"></script>
		<script src="{{asset('franchise/js/custom.js')}}"></script>	


<script>
    function resendOtp() {
    // Create a form element dynamically
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('customer.resendPhoneNumberOtp') }}"; // Adjusted to your route

    // Add the CSRF token for Laravel's security
    var csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    // Add the username if necessary
    var usernameInput = document.createElement('input');
    usernameInput.type = 'hidden';
    usernameInput.name = 'mobile';
    usernameInput.value = '{{ $mobile ?? old("mobile") }}'; // Ensure username is set
    form.appendChild(usernameInput);

    // Append the form to the body and submit it
    document.body.appendChild(form);
    form.submit();
}

</script>

    </body>
</html>
