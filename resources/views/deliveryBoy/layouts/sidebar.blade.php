<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul class="sidebar-vertical">
				<li class="menu-title">
					<span>Main</span>
				</li>
				<li>
					<a href="{{route('deliveryBoy.dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
				</li>

				<li class="menu-title">
					<span>Module</span>
				</li>

				{{-- parcel booking start --}}
				<li class="submenu">
					<a href="#"> <i class="la la-user-secret"></i> <span>Gotogo Post Bookings</span> <span class="menu-arrow"></span> </a>
					<ul>
						@if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))

						<li class="{{ request()->url() == route('deliveryBoy.go-speed-post-parcel.index') ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.go-speed-post-parcel.index' ) }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
						</li>
						@endif @if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))

						<li class="{{ request()->url() == route('deliveryBoy.go-business-parcel.index') ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.go-business-parcel.index') }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
						</li>
						@endif @if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
						<li class="{{ request()->url() == route('deliveryBoy.go-registered.index') ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.go-registered.index', ) }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
						</li>

						@endif

						<li class="submenu">
							<a href="#"> <span>All Indian Postal Services</span> <span class="menu-arrow"></span> </a>
							<ul>
								@if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
								<li class="{{ request()->url() == route('deliveryBoy.india-post-speed-post.index') ? 'active' : '' }}">
									<a href="{{ route('deliveryBoy.india-post-speed-post.index') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
								</li>
								@endif 
								@if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
								<li class="{{ request()->url() == route('deliveryBoy.india-post-business.index') ? 'active' : '' }}">
									<a href="{{ route('deliveryBoy.india-post-business.index') }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a>
								</li>
								@endif 
							</ul>
						</li>
					</ul>
				</li>
				{{-- parcel booking end --}}

				<li>
					<a href="{{route('deliveryBoy.bag.pickupList')}}"><i class="la la-rocket"></i> <span> Pickup</span></a>
				</li>

				<li>
					<a href="{{route('deliveryBoy.bag.showAllParcelToDeliver')}}"><i class="la la-box"></i> <span> Delivery Parcel</span></a>
				</li>

				<li>
					<a href="{{route('deliveryBoy.bag.showAllDeliveredParcel')}}"><i class="la la-crosshairs"></i> <span> Delivered Parcel</span></a>
				</li>

				<li>
					<a href="{{route('deliveryBoy.bag.showRecievedBags')}}"><i class="la la-briefcase"></i> <span> Bags</span></a>
				</li>


				{{-- delivered parcel start --}}
				<li class="submenu">
					<a href="#"> <i class="la la-rocket"></i> <span>Delivered Parcels </span> <span class="menu-arrow"></span> </a>
					<ul>
						@if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
						<li class="{{ request()->fullUrl() == route('deliveryBoy.bag.showAllDeliveredParcel',['service_type'=>1]) ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.bag.showAllDeliveredParcel',['service_type'=>1] ) }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
						</li>

						@endif @if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
						<li class="{{ request()->fullUrl() == route('deliveryBoy.bag.showAllDeliveredParcel',['service_type'=>3]) ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.bag.showAllDeliveredParcel',['service_type'=>3] ) }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
						</li>

						@endif @if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
						<li class="{{ request()->fullUrl() == route('deliveryBoy.bag.showAllDeliveredParcel',['service_type'=>4]) ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.bag.showAllDeliveredParcel',['service_type'=>4] ) }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
						</li>

						@endif

						<li class="submenu">
							<a href="#"> <span>All Indian Postal Services</span> <span class="menu-arrow"></span> </a>
							<ul>
								@if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
								<li class="{{ request()->fullUrl() == route('deliveryBoy.bag.showAllDeliveredParcel', ['service_type' => 5]) ? 'active' : '' }}">
									<a href="{{ route('deliveryBoy.bag.showAllDeliveredParcel', ['service_type' => 5]) }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
								</li>

								@endif @if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))

								<li class="{{ request()->fullUrl() == route('franchise.india-post-business.create', ['service_type' => 6]) ? 'active' : '' }}">
									<a href="{{ route('deliveryBoy.bag.showAllDeliveredParcel', ['service_type' => 6]) }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a>
								</li>

								@endif
							</ul>
						</li>
					</ul>
				</li>
				{{-- delivered parcel end --}}


				{{-- delivered boy commission --}}
				<li class="submenu">
					<a href="#">
						<i class="la la-cube"></i> <span>Commission Report </span> <span class="menu-arrow"></span>
					</a>
					<ul>
						<li class="{{ request()->query('commission_type') == 'pickup' ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.parcel-payment.commissionDetail', ['commission_type' => 'pickup']) }}">Pickup Commission</a>
						</li>
					
						<li class="{{ request()->query('commission_type') == 'delivery' ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.parcel-payment.commissionDetail', ['commission_type' => 'delivery']) }}">Delivery Commission</a>
						</li>
					</ul>
					
				</li>

				{{-- delivered parcel start --}}
				<li class="submenu">
					<a href="#"> <i class="la la-cube"></i> <span>E2H Delivery Report</span> <span class="menu-arrow"></span> </a>
					<ul>
						 @if (App\Models\DeliveryBoy::checkServiceStatus(App\Models\GotogoSpeedPostParcel::E2H)) 
						<li class="{{ request()->fullUrl() == route('deliveryBoy.mailTofranhchise.viewAssignedMailParcel') ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.mailTofranhchise.viewAssignedMailParcel') }}">Assigned Parcels</a>
						</li>

						<li class="{{ request()->fullUrl() == route('deliveryBoy.mailTofranhchise.viewDeliveredMailParcel') ? 'active' : '' }}">
							<a href="{{ route('deliveryBoy.mailTofranhchise.viewDeliveredMailParcel') }}">Delivered Parcels</a>
						</li>
						 @endif 
						 
					</ul>
				</li>
				{{-- delivered parcel end --}}
			</ul>
		</div>
	</div>
</div>

<!-- /Sidebar -->