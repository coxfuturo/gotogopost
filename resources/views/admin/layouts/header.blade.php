	<style>
		/* Modern Clean Header Styling (Sidebar Left Untouched) */
		.header {
			background:  !important;
			height: 65px;
			position: fixed;
			top: 0;
			right: 0;
			left: 0;
			z-index: 1000;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
			border-bottom: 1px solid #e2e8f0;
			display: flex;
			align-items: center;
			padding: 0 20px;
			transition: all 0.3s ease;
		}

		.header .header-left {
			background: #ffffff;
			float: left;
			height: 65px;
			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;
			text-align: center;
			width: 240px;
			z-index: 1;
			padding: 0 15px;
			border-right: 1px solid;
			transition: all 0.2s ease;
		}

		.header .header-left .logo img,
		.header .header-left .logo2 img {
			width: 90px;
			height: 45px;
			object-fit: contain;
		}

		/* Toggle Button */
		#toggle_btn {
			background: #f1f5f9;
			border: 1px solid #e2e8f0;
			border-radius: 8px;
			width: 38px;
			height: 38px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-left: 15px;
			transition: all 0.2s ease;
			text-decoration: none;
		}
		#toggle_btn:hover {
			background: #e2e8f0;
		}
		#toggle_btn .bar-icon span {
			background-color: #475569;
			display: block;
			height: 2px;
			margin: 4px 0;
			width: 18px;
		}

		/* Header Title Box */
		.header .page-title-box {
			margin-left: 20px;
		}
		.header .page-title-box h3 {
			color: #1e293b;
			font-size: 1.05rem;
			font-weight: 700;
			margin: 0;
			letter-spacing: 0.5px;
		}

		/* User Menu Profile Pill */
		.header .user-menu {
			margin-left: auto;
			display: flex;
			align-items: center;
			list-style: none;
			margin-bottom: 0;
			padding-left: 0;
		}
		.header .user-menu .nav-link {
			display: flex;
			align-items: center;
			padding: 6px 12px;
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			border-radius: 30px;
			transition: all 0.2s ease;
			text-decoration: none;
		}
		.header .user-menu .nav-link:hover {
			background: #f1f5f9;
		}
		.header .user-menu .user-img {
			position: relative;
			display: inline-block;
		}
		.header .user-menu .user-img img {
			width: 36px !important;
			height: 36px !important;
			border-radius: 50%;
			object-fit: cover;
			border: 2px solid #3b82f6;
		}
		.header .user-menu .user-img .status.online {
			background-color: #22c55e;
			border: 2px solid #ffffff;
			bottom: 0;
			height: 10px;
			position: absolute;
			right: 0;
			width: 10px;
			border-radius: 50%;
		}
		.header .user-menu span:not(.user-img) {
			color: #334155;
			font-weight: 600;
			font-size: 0.88rem;
			margin-left: 10px;
			margin-right: 5px;
		}

		/* Dropdown Menus */
		.header .dropdown-menu {
			background: #ffffff !important;
			border: 1px solid #e2e8f0 !important;
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
			border-radius: 10px !important;
			padding: 8px !important;
			margin-top: 10px !important;
		}
		.header .dropdown-menu .dropdown-item {
			color: #475569 !important;
			font-size: 0.88rem;
			font-weight: 500;
			padding: 9px 14px;
			border-radius: 6px;
			transition: all 0.2s;
		}
		.header .dropdown-menu .dropdown-item:hover {
			background: #eff6ff !important;
			color: #2563eb !important;
		}

		/* Mobile Responsive */
		.header .mobile_btn {
			display: none;
			color: #334155;
			font-size: 1.2rem;
			padding: 8px 12px;
			text-decoration: none;
		}
		.mobile-user-menu {
			display: none;
		}

		@media (max-width: 991px) {
			.header .header-left {
				width: 70px;
			}
			.header .page-title-box {
				display: none;
			}
			.header .mobile_btn {
				display: block;
			}
			.mobile-user-menu {
				display: block;
				margin-left: auto;
			}
			.header .user-menu {
				display: none;
			}
			.mobile-user-menu .nav-link {
				color: #334155;
				font-size: 1.2rem;
				padding: 8px;
			}
		}
	</style>

	<div class="header">
		@php
		$admin=Auth::guard('admin')->user();
		@endphp
		<!-- Logo -->
		<div class="header-left">
			<a href="{{route('admin.dashboard')}}" class="logo">
				<img src="{{asset('website/images/logonewtransparent.png')}}" alt="Logo">
			</a>
			<a href="{{route('admin.dashboard')}}" class="logo2">
				<img src="{{asset('website/images/logonewtransparent.png')}}" alt="Logo">
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
						<img class="inline-block" src="{{isset($admin->image) ? asset('admin/profileImage/'.$admin->image) : asset('admin/assets/img/profiles/avatar-02.jpg')}}" alt="Profile">
						<span class="status online"></span>
					</span>
					@if(Auth::guard('admin')->check())
					<span>{{ Auth::guard('admin')->user()->name }}</span>
					@elseif(Auth::guard('admin')->check())
					<span>{{ Auth::guard('admin')->user()->name }}</span>
					@endif
				</a>
				<div class="dropdown-menu dropdown-menu-end">
					<a class="dropdown-item" href="{{route('admin.profile')}}">My Profile</a>
					<a class="dropdown-item" href="{{route('admin.logout')}}">Logout</a>
				</div>
			</li>
		</ul>
		<!-- Mobile Menu -->
		<div class="dropdown mobile-user-menu">
			<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="{{route('admin.profile')}}">My Profile</a>
				<a class="dropdown-item" href="{{route('admin.logout')}}">Logout</a>
			</div>
		</div>
	</div>