	<!-- Sidebar -->
	<div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
			<div id="sidebar-menu" class="sidebar-menu">

				<ul class="sidebar-vertical">
					<li class="menu-title">
						<span>Main</span>
					</li>
					<li>
						<a href="{{route('cms.dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
					</li>

					<li class="menu-title">
						<span>Parcels</span>
					</li>

					{{-- Received Parcel List start --}}
					<li class="submenu">
						<a href="#" class="{{ request()->url() == route('cms.parcel.index', ['id' => 1]) ? 'active' : '' }}">
							<i class="la la-user-secret"></i> <span>Received Parcel List</span> <span class="menu-arrow"></span>
						</a>
						<ul>

							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
							<li class="{{ request()->url() == route('cms.go-speed-post-parcel.index') ? 'active' : '' }}">
								<a href="{{ route('cms.go-speed-post-parcel.index' ) }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
							</li>
							@endif


							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
							<li class="{{ request()->url() == route('cms.go-business-parcel.index') ? 'active' : '' }}">
								<a href="{{ route('cms.go-business-parcel.index') }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
							</li>
							@endif

							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
							<li class="{{ request()->url() == route('cms.go-registered.index') ? 'active' : '' }}">
								<a href="{{ route('cms.go-registered.index', ) }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
							</li>
							@endif




							<li class="submenu">
								<a href="#">
									<span>All Indian Postal Services</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))

									<li class="{{ request()->url() == route('cms.india-post-speed-post.index') ? 'active' : '' }}">
										<a href="{{ route('cms.india-post-speed-post.index') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
									</li>
									@endif
									@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))

									<li class="{{ request()->url() == route('cms.india-post-business.index') ? 'active' : '' }}">
										<a href="{{ route('cms.india-post-business.index') }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a>
									</li>

									@endif

								</ul>
							</li>
						</ul>
					</li>
					{{-- Received Parcel List end --}}


					<li class="menu-title">
						<span>Bags</span>
					</li>

					{{--Bag List start --}}
					<li class="submenu">
						<a href="#" class="{{ request()->fullUrl() == route('cms.go-speed-post-parcel.index', ['id' => 1]) ? 'active' : '' }}">
							<i class="la la-box"></i> <span>Bags</span> <span class="menu-arrow"></span>
						</a>
						<ul style="display: block;">

							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))

							<li class="submenu">
								<a href="#" class="{{ request()->fullUrl() == route('cms.go-speed-post-parcel.index', ['service_type' => 1]) ? 'active' : '' }}">
									<span>{{\App\Models\Admin::GOTOGO_POST_SPEED}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<li class="{{ request()->fullUrl() == route('cms.bag.showCretedBags', ['service_type' => 1]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showCretedBags', ['service_type' => 1]) }}">Bag Dispatched</a>
									</li>
									<li class="{{ request()->fullUrl() == route('cms.bag.showRecievedBags', ['service_type' => 1]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showRecievedBags', ['service_type' => 1]) }}">Bag Received</a>
									</li>
								</ul>
							</li>
							@endif
							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
							<li class="submenu">
								<a href="#" class="{{ request()->fullUrl() == route('cms.go-business-parcel.index', ['service_type' => 3]) ? 'active' : '' }}">
									{{\App\Models\Admin::GOTOGO_POST_BUSINESS}} <span class="menu-arrow"></span>
								</a>
								<ul>
									<li class="{{ request()->fullUrl() == route('cms.bag.showCretedBags', ['service_type' => 3]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showCretedBags', ['service_type' => 3]) }}">Bag Dispatched</a>
									</li>
									<li class="{{ request()->fullUrl() == route('cms.bag.showRecievedBags', ['service_type' => 3]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showRecievedBags', ['service_type' => 3]) }}">Bag Received</a>
									</li>
								</ul>
							</li>


							@endif
							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))

							<li class="submenu">
								<a href="#" class="{{ request()->fullUrl() == route('cms.go-registered.index', ['service_type' => 1]) ? 'active' : '' }}">
									<span>{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<li class="{{ request()->fullUrl() == route('cms.bag.showCretedBags', ['service_type' => 4]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showCretedBags', ['service_type' => 4]) }}">Bag Dispatched</a>
									</li>
									<li class="{{ request()->fullUrl() == route('cms.bag.showRecievedBags', ['service_type' => 4]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showRecievedBags', ['service_type' => 4]) }}">Bag Received</a>
									</li>
								</ul>
							</li>

							@endif
							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
							<li class="submenu">
								<a href="#" class="{{ request()->fullUrl() == route('cms.india-post-speed-post.index', ['service_type' => 1]) ? 'active' : '' }}">
									<span>{{\App\Models\Admin::INDIA_POST_SPEED}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<!-- <li class="{{ request()->fullUrl() == route('cms.bag.showCretedBags', ['service_type' => 5]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showCretedBags', ['service_type' => 5]) }}">Bag Dispatched</a>
									</li> -->
									<li class="{{ request()->fullUrl() == route('cms.bag.showRecievedBags', ['service_type' => 5]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showRecievedBags', ['service_type' => 5]) }}">Bag Received</a>
									</li>
								</ul>
							</li>

							@endif
							@if (App\Models\CMS::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
							<li class="submenu">
								<a href="#" class="{{ request()->fullUrl() == route('cms.india-post-business.index', ['service_type' => 6]) ? 'active' : '' }}">
									<span>{{\App\Models\Admin::INDIA_POST_BUSINESS}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<!-- <li class="{{ request()->fullUrl() == route('cms.bag.showCretedBags', ['service_type' => 6]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showCretedBags', ['service_type' => 6]) }}">Bag Dispatched</a>
									</li> -->
									<li class="{{ request()->fullUrl() == route('cms.bag.showRecievedBags', ['service_type' => 6]) ? 'active' : '' }}">
										<a href="{{ route('cms.bag.showRecievedBags', ['service_type' => 6]) }}">Bag Received</a>
									</li>
								</ul>
							</li>

							@endif
						</ul>
					</li>
					{{-- Bag List end --}}

					<li class="menu-title">
						<span>Commission</span>
					</li>
					{{-- commission service start --}}
					<li class="submenu">
						<a href="#">
							<i class="la la-money"></i> <span>Commission Report </span> <span class="menu-arrow"></span>
						</a>
						<ul style="display: block;">

							<li class="{{ request()->fullUrl() == route('cms.commission.index') ? 'active' : '' }}">
								<a href="{{ route('cms.commission.index') }}">Commission</a>
							</li>

						</ul>
					</li>
					{{-- commission service end --}}
				</ul>
			</div>
		</div>
	</div>

	<!-- /Sidebar -->