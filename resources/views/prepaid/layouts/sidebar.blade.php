<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul class="sidebar-vertical">
				<li class="menu-title">
					<span>Main</span>
				</li>
				<li>
					<a href="{{route('prepaid.dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
					
				</li>

				<li class="menu-title">
					<span>Booking Report</span>
				</li>
       

				<li>
    <!-- <a href="{{ route('customer.booking.parcel.cod') }}">
        <i class="la la-box"></i> <span> COD Report</span>
    </a>
</li> -->

           <li><a href="{{ route('prepaid.booking.index',1) }}"><i class="la la-box"></i> <span> {{\App\Models\Admin::GOTOGO_POST_SPEED}}</span></a></li>
           <li><a href="{{route('prepaid.booking.index',2)}}"><i class="la la-dashboard"></i> <span> {{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</span></a></li>
		   <li><a href="{{route('prepaid.booking.index',3)}}"><i class="la la-dashboard"></i> <span> {{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</span></a></li>
		   <li><a href="{{route('prepaid.booking.index',4)}}"><i class="la la-dashboard"></i> <span> {{\App\Models\Admin::INDIA_POST_SPEED}}</span></a></li>
		   <li><a href="{{route('prepaid.booking.index',5)}}"><i class="la la-dashboard"></i> <span> {{\App\Models\Admin::INDIA_POST_BUSINESS}}</span></a></li>
			</ul>
		</div>
	</div>
</div>

<!-- /Sidebar -->