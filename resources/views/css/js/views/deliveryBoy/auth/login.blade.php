<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Smarthr - Bootstrap Admin Template">
	<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
	<meta name="author" content="Dreamguys - Bootstrap Admin Template">
	<title>Delivery|Login </title>
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
						<h3 class="account-title">Delivery Login</h3>
						<!-- <p class="account-subtitle">Access to our dashboard</p> -->

						<!-- Account Form -->
						<form action="{{route('deliveryBoy.login')}}" class="needs-validation" method="POST" novalidate>
							@csrf
							<div class="input-block mb-4">
								<label class="col-form-label">Username <span class="text-danger">*</span></label>
								<input class="form-control @error('username') is-invalid @enderror" type="email" placeholder="Enter username" name="username" value="{{old('username')}}" required>
								<div class="invalid-feedback">
									Please provide username
								</div>
							</div>
							<div class="input-block mb-4">
								<div class="row align-items-center">
									<div class="col">
										<label class="col-form-label">Password <span class="text-danger">*</span></label>
									</div>
									<div class="col-auto">
										<a class="text-muted" href="{{ route('deliveryBoy.sendOtp') }}">
											Forgot password?
										</a>
									</div>
								</div>
								<div class="position-relative">
									<input class="form-control " placeholder="Enter Password" type="password" name="password" id="password" required>
									<span class="fa-solid fa-eye-slash" id="toggle-password"></span>
									<div class="invalid-feedback">
										Please provide password
									</div>
								</div>
							</div>
							<div class="input-block mb-4 text-center">
								<button class="btn btn-primary account-btn" type="submit">Login</button>
							</div>
							<div class="account-footer">
								<p>Don't have an account yet? <a href="{{route('deliveryBoy.register')}}">Register</a></p>
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


</body>

</html>