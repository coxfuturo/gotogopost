<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul class="sidebar-vertical">
				<li class="menu-title">
					<span>Main</span>
				</li>
				<li>
					<a href="{{route('customer.dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
				</li>

				<li class="menu-title">
					<span>Module</span>
				</li>


				<li>
    <a href="{{ route('customer.booking.parcel.cod') }}">
        <i class="la la-box"></i> <span> COD Report</span>
    </a>
</li>

<li>
    <a href="{{ route('customer.booking.parcel.prepaids') }}">
        <i class="la la-box"></i> <span> Prepaid Report</span>
    </a>
</li>


				


			</ul>
		</div>
	</div>
</div>

<!-- /Sidebar -->