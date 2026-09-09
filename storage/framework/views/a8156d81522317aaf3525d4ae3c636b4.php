<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <title>Admin|Login </title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" type="text/css" />
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('admin/assets/img/favicon.png')); ?>">

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>">

		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css')); ?>">
    	<link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/all.min.css')); ?>">

		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/line-awesome.min.css')); ?>">
		<link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/material.css')); ?>">

		<!-- Lineawesome CSS -->
		<link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/line-awesome.min.css')); ?>">

		<!-- Main CSS -->
        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/style.css')); ?>">

    </head>
    <body class="account-page">

		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
				<div class="container">
					<!-- Account Logo -->
					<div class="account-logo">
						<a href="#"><img src="<?php echo e(asset('admin/assets/img/logo2.png')); ?>"></a>
					</div>
					<!-- /Account Logo -->

					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Login</h3>
							<p class="account-subtitle">Access to our dashboard</p>

							<!-- Account Form -->
							<form action="<?php echo e(route('admin.login')); ?>" method="POST" class="needs-validation" novalidate>
                                <?php echo csrf_field(); ?>
								<div class="input-block mb-4">
									<label class="col-form-label">Email Address <span class="text-danger">*</span></label>
									<input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="email" placeholder="Enter Email" name="email" value="<?php echo e(old('email')); ?>" required>
                                    <div class="invalid-feedback">
                                            Please provide email address
                                    </div>
								</div>
								<div class="input-block mb-4">
									<div class="row align-items-center">
										<div class="col">
											<label class="col-form-label">Password <span class="text-danger">*</span></label>
										</div>
										<div class="col-auto">
											<a class="text-muted" href="">
												Forgot password?
											</a>
										</div>
									</div>
									<div class="position-relative">
										<input class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter Password" type="password" name="password" id="password" required>
										<span class="fa-solid fa-eye-slash" id="toggle-password"></span>
                                        <div class="invalid-feedback">
                                                Please provide password
                                        </div>
									</div>
								</div>
								<div class="input-block mb-4 text-center">
									<button class="btn btn-primary account-btn" type="submit">Login</button>
								</div>
								<!-- <div class="account-footer">
									<p>Don't have an account yet? <a href="register.html">Register</a></p>
								</div> -->
							</form>
							<!-- /Account Form -->

						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>

        <?php if(Session::has('success')): ?>
    <script>
        Swal.fire('Yay!!!', "<?php echo e(Session::get('success')); ?>", 'success')
    </script>
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
    <script>
        Swal.fire('Oops!!!', "<?php echo e(Session::get('error')); ?>", 'error')
    </script>
    <?php endif; ?>
		<!-- jQuery -->

       <script src="<?php echo e(asset('admin/assets/js/jquery-3.7.0.min.js')); ?>"></script>

		<!-- Bootstrap Core JS -->
        <script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>

		<!-- Custom JS -->
		<script src="<?php echo e(asset('admin/assets/js/app.js')); ?>"></script>
		<script src="<?php echo e(asset('admin/assets/js/custom.js')); ?>"></script>


    </body>
</html>
<?php /**PATH C:\xampp\htdocs\GotogoPost\gotogopost\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>