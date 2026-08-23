	<!-- Header -->

	<style>
		[data-layout-mode=orange] body .header {
			background: #ff9b44;
			background: linear-gradient(to right, #ff9b4400 0%, #fc6075 16%);
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

		@php

		if(Auth::guard('franchise')->check())
		{
		$franchise=Auth::guard('franchise')->user();
		}
		if(Auth::guard('franchiseRoleUser')->check())
		{
		$franchise=Auth::guard('franchiseRoleUser')->user();
		}

		@endphp

		<!-- Logo -->
		<div class="header-left">
			<a href="{{route('franchise.dashboard')}}" class="logo">
				<img src="{{asset('website/images/logonewtransparent.png')}}" style="width: 90px;height:50px;" alt="Logo">
			</a>
			<a href="{{route('franchise.dashboard')}}" class="logo2">
				<img src="{{asset('website/images/logonewtransparent.png')}}" style="width: 90px;height:50px;" alt="Logo">
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
						<img style="width: 40px;height:40px;object-fit:cover;" class="inline-block" src="{{isset($franchise->kyc->photo) ? asset('admin/franchise/'.$franchise->generated_id.'/'.$franchise->kyc->photo) : asset('admin/assets/img/profiles/avatar-02.jpg')}}">
						<span class="status online">
						</span>
					</span>

					@if(Auth::guard('franchise')->check())
					<span>{{ Auth::guard('franchise')->user()->name }}</span>
					@elseif(Auth::guard('franchiseRoleUser')->check())
					<span>{{ Auth::guard('franchiseRoleUser')->user()->name }}</span>
					@endif

				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="{{route('franchise.profile')}}">My Profile</a>
					<a class="dropdown-item" href="{{route('franchise.logout')}}">Logout</a>
				</div>
			</li>
		</ul>
		<!-- /Header Menu -->

		<!-- Mobile Menu -->
		<div class="dropdown mobile-user-menu">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="{{route('franchise.profile')}}">My Profile</a>
				<a class="dropdown-item" href="{{route('franchise.logout')}}">Logout</a>
			</div>
		</div>
		<!-- /Mobile Menu -->

	</div>
	<!-- /Header -->