	<!-- Header -->

	<style>
		[data-layout-mode=orange] body .header {
			background: #de0114;
			background: linear-gradient(to right, #ff9b4400 0%, #1e3d59 16%);
		}

	
.form-select:focus
 {
    border-color: none !important;
    outline: 0;
    box-shadow: none !important;
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

		#sidebar {
			background: white
		}

		/* Overlay to cover the whole viewport with a semi-transparent background */
		.overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			background: rgba(0, 0, 0, 0.5);
			/* Semi-transparent black */
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 1000;
			/* Higher z-index to overlay the entire page */
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
			border-color: #1e3d59;
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

		@media (max-width: 991px) {
			.header .mobile_btn {
				background: #1e3d59;
			}

			[data-layout-mode=orange] body .header {
				background: #de0114;
				background: linear-gradient(to right, #ff9b4400 0%, #1e3d59 16%);
			}

			.header {
				background: #de0114;
				background: linear-gradient(to right, #ff9b4400 0%, #1e3d59 16%);
			}
		}

		.card .card-title {
    font-size: 18px !important;
}

.page-item.active .page-link {
    background-color: #de0114 !important;
    border-color: #de0114 !important;
    color: #fff !important;
}

.pagination > .active > a, .pagination > .active > span {
    color: #de0114 !important;
}

.btn-success {
    background-color: #1e3d59 !important;
    border: 1px solid #1e3d59 !important;
}

.btn-success:hover, .btn-success:focus .btn-success.active, .btn-success:active {
    background-color: #1e3d59 !important;
    border: 1px solid #1e3d59 !important;
    color: #ffffff !important;
}
.table-responsive::-webkit-scrollbar-thumb {
  background:  #1e3d59 !important;
  border-radius: 10px !important;
}
.settings-icon span {
    width: 45px;
    height: 45px;
    border-radius: 45px;
    cursor: pointer;
    color: #ffffff;
    font-size: 24px;
    background-color:  #de0114 !important;
}
.pagination > li > a, .pagination > li > span {
    color:  #de0114 !important;
}
 .btn-danger {
    background-color: #de0114 !important;
    border: 1px solid #de0114 !important;
}
	</style>


	<div class="header">

		<?php

		if(Auth::guard('franchise')->check())
		{
		$franchise=Auth::guard('franchise')->user();
		}
		if(Auth::guard('franchiseRoleUser')->check())
		{
		$franchise=Auth::guard('franchiseRoleUser')->user();
		}

		?>

		<!-- Logo -->
		<div class="header-left">
			<a href="<?php echo e(route('franchise.dashboard')); ?>" class="logo">
				<img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>" style="width: 90px;height:50px;" alt="Logo">
			</a>
			<a href="<?php echo e(route('franchise.dashboard')); ?>" class="logo2">
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
						<img style="width: 40px;height:40px;object-fit:cover;" class="inline-block" src="<?php echo e(isset($franchise->kyc->photo) ? asset('admin/franchise/'.$franchise->generated_id.'/'.$franchise->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg')); ?>">
						<span class="status online">
						</span>
					</span>

					<?php if(Auth::guard('franchise')->check()): ?>
					<span><?php echo e(Auth::guard('franchise')->user()->name); ?></span>
					<?php elseif(Auth::guard('franchiseRoleUser')->check()): ?>
					<span><?php echo e(Auth::guard('franchiseRoleUser')->user()->name); ?></span>
					<?php endif; ?>

				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="<?php echo e(route('franchise.profile')); ?>">My Profile</a>
					<a class="dropdown-item" href="<?php echo e(route('franchise.logout')); ?>">Logout</a>
				</div>
			</li>
		</ul>
		<!-- /Header Menu -->

		<!-- Mobile Menu -->
		<div class="dropdown mobile-user-menu">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="<?php echo e(route('franchise.profile')); ?>">My Profile</a>
				<a class="dropdown-item" href="<?php echo e(route('franchise.logout')); ?>">Logout</a>
			</div>
		</div>
		<!-- /Mobile Menu -->

	</div>
	<!-- /Header --><?php /**PATH /home/gotogopost/public_html/resources/views/franchise/layouts/header.blade.php ENDPATH**/ ?>