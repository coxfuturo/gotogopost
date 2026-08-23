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

				<li class="submenu">
						<a href="#" class="{{request()->url() == route('customer.booking.parcel.prepaids') ? 'active':''}} {{request()->url() == route('customer.booking.parcel.cod') ? 'active':''}}"><i class="la la-rocket"></i> <span> Booking Parcel</span> <span class="menu-arrow"></span></a>
						<ul>
							<li class="{{request()->url() == route('customer.booking.parcel.prepaids') ? 'active':''}}"><a href="{{ route('customer.booking.parcel.prepaids') }}">Prepaid Report</a></li>

							<li class="{{request()->url() == route('customer.booking.parcel.cod') ? 'active':''}}"><a href="{{ route('customer.booking.parcel.cod') }}">COD Report</a></li>
						</ul>
					</li>

					<li class="submenu">
						<a href="#" class="{{request()->url() == route('customer.payment.index') ? 'active':''}} {{request()->url() == route('customer.payment.index') ? 'active':''}}"><i class="la la-rocket"></i> <span> Payment/Recharge</span> <span class="menu-arrow"></span></a>
						<ul>
							<li class="{{request()->url() == route('customer.payment.index') ? 'active':''}}"><a href="{{ route('customer.payment.index') }}">Recharge</a></li>

							<!-- <li class="{{request()->url() == route('customer.booking.parcel.cod') ? 'active':''}}"><a href="{{ route('customer.booking.parcel.cod') }}">COD Report</a></li> -->
						</ul>
					</li>

                <!-- <li>
                    <a href="{{ route('customer.booking.parcel.cod') }}"> <i class="la la-box"></i> <span> COD Report</span> </a>
                </li> -->

                
            </ul>
        </div>
    </div>
</div>

<!-- /Sidebar -->
