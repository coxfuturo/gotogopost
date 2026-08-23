	<!-- Header -->

	<style>
		[data-layout-mode=orange] body .header {
			background: #ff9b44;
			background: linear-gradient(to right, #ff9b4400 0%, #fc6075 16%);
		}

		.header .header-left {
			background: white;
			float: left;
			height: 60px;
			position: relative;
			text-align: center;
			width: 230px;
			z-index: 1;
			padding: 0 20px;
			-webkit-transition: all 0.2s ease;
			-ms-transition: all 0.2s ease;
			transition: all 0.2s ease;
		}

		@media (max-width: 991px) {
			.header .mobile_btn {
				background: #fc6075;
			}

			[data-layout-mode=orange] body .header {
				background: #ff9b44;
				background: linear-gradient(to right, #ff9b4400 0%, #fc6075 16%);
			}

			.header {
				background: #ff9b44;
				background: linear-gradient(to right, #ff9b4400 0%, #fc6075 16%);
			}
		}
	</style>
	<div class="header">

		<?php
		$data=Auth::guard('delboy')->user();
		?>

		<!-- Logo -->
		<div class="header-left">
			<a href="<?php echo e(route('customer.dashboard')); ?>" class="logo">
				<img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>" style="width: 90px;height:50px;" alt="Logo">
			</a>
			<a href="<?php echo e(route('customer.dashboard')); ?>" class="logo2">
				<img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>" style="width: 90px;height:50px;" alt="Logo">
			</a>
		</div>
		<!-- /Logo -->

		<a id="toggle_btn" href="javascript:void(0);">
			<span class="bar-icon">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</a>

		<!-- Header Title -->
		<div class="page-title-box">
			<h3>GOTOGO POST</h3>
		</div>
		<!-- /Header Title -->

		<a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa-solid fa-bars"></i></a>

		<!-- Header Menu -->
		<ul class="nav user-menu">


			<li class="nav-item dropdown has-arrow main-drop">
				<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
					<span class="user-img">

						<img style="width: 40px;height:40px;object-fit:cover;"
							src="<?php echo e(isset($data->kyc->photo) ? asset('tenancy/assets/delboy/'.$data->generated_id.'/'.$data->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg')); ?>"
							alt="Profile Image">

						<span class="status online">
						</span>
					</span>
					<span><?php echo e(auth()->user()->name); ?></span>
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?php echo e(route('prepaid.profile')); ?>">My Profile</a>
					<a class="dropdown-item" href="<?php echo e(route('customer.logout')); ?>">Logout</a>
				</div>
			</li>
		</ul>
		<!-- /Header Menu -->

		<!-- Mobile Menu -->
		<div class="dropdown mobile-user-menu">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="<?php echo e(route('customer.profile')); ?>">My Profile</a>
				<a class="dropdown-item" href="<?php echo e(route('customer.logout')); ?>">Logout</a>
			</div>
		</div>
		<!-- /Mobile Menu -->

	</div>
	<!-- /Header --><?php /**PATH /home/gotogopost/public_html/resources/views/prepaid/layouts/header.blade.php ENDPATH**/ ?>