	<!-- Sidebar -->
	<div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
			<div id="sidebar-menu" class="sidebar-menu">

				<ul class="sidebar-vertical">
					<li class="menu-title">
						<span>Main</span>
					</li>
					<li>
						<a href="{{route('pph.dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
					</li>

					<li class="menu-title">
						<span>Parcels</span>
					</li>

					{{-- Received Parcel List start --}}
					<li class="submenu">
						<a href="#" class="">
							<i class="la la-box"></i> <span>Received Parcel List</span> <span class="menu-arrow"></span>
						</a>
						<ul>
							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
							<li class="{{ request()->url() == route('pph.go-speed-post-parcel.index') ? 'active' : '' }}">
								<a href="{{ route('pph.go-speed-post-parcel.index' ) }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
							</li>
							@endif


							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
							<li class="{{ request()->url() == route('pph.go-business-parcel.index') ? 'active' : '' }}">
								<a href="{{ route('pph.go-business-parcel.index') }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
							</li>
							@endif

							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
							<li class="{{ request()->url() == route('pph.go-registered.index') ? 'active' : '' }}">
								<a href="{{ route('pph.go-registered.index', ) }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
							</li>
							@endif

							<li class="submenu">
								<a href="#">
									<span>All Indian Postal Services</span> <span class="menu-arrow"></span>
								</a>
								<ul>

									@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
									<li class="{{ request()->url() == route('pph.india-post-speed-post.index') ? 'active' : '' }}">
										<a href="{{ route('pph.india-post-speed-post.index') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
									</li>
									@endif

									@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
									<li class="{{ request()->url() == route('pph.india-post-business.index') ? 'active' : '' }}">
										<a href="{{ route('pph.india-post-business.index') }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a>
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
						<a href="#" class="">
							<i class="la la-box"></i> <span>Bags</span> <span class="menu-arrow"></span>
						</a>

						<ul style="display: block;">

							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
							<li class="submenu">
								<a href="#" class="">
									<span>{{\App\Models\Admin::GOTOGO_POST_SPEED}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<li class="{{ request()->fullUrl() == route('pph.bag.showCretedBags', ['service_type' => 1]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showCretedBags', ['service_type' => 1]) }}">Bag Dispatched</a>
									</li>
									<li class="{{ request()->fullUrl() == route('pph.bag.showRecievedBags', ['service_type' => 1]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showRecievedBags', ['service_type' => 1]) }}">Bag Received</a>
									</li>
								</ul>
							</li>

							@endif

							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
							<li class="submenu">
								<a href="#" class="">
									{{\App\Models\Admin::GOTOGO_POST_BUSINESS}} <span class="menu-arrow"></span>
								</a>
								<ul>
									<li class="{{ request()->fullUrl() == route('pph.bag.showCretedBags', ['service_type' => 3]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showCretedBags', ['service_type' => 3]) }}">Bag Dispatched</a>
									</li>
									<li class="{{ request()->fullUrl() == route('pph.bag.showRecievedBags', ['service_type' => 3]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showRecievedBags', ['service_type' => 3]) }}">Bag Received</a>
									</li>
								</ul>
							</li>

							@endif

							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
							<li class="submenu">
								<a href="#" class="">
									<span>{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<li class="{{ request()->fullUrl() == route('pph.bag.showCretedBags', ['service_type' => 4]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showCretedBags', ['service_type' => 4]) }}">Bag Dispatched</a>
									</li>
									<li class="{{ request()->fullUrl() == route('pph.bag.showRecievedBags', ['service_type' => 4]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showRecievedBags', ['service_type' => 4]) }}">Bag Received</a>
									</li>
								</ul>
							</li>
							@endif

							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
							<li class="submenu">
								<a href="#" class="">
									<span>IP {{\App\Models\Admin::INDIA_POST_SPEED}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<!-- <li class="{{ request()->fullUrl() == route('pph.bag.showCretedBags', ['service_type' => 5]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showCretedBags', ['service_type' => 5]) }}">Bag Dispatched</a>
									</li> -->
									<li class="{{ request()->fullUrl() == route('pph.bag.showRecievedBags', ['service_type' => 5]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showRecievedBags', ['service_type' => 5]) }}">Bag Received</a>
									</li>
								</ul>
							</li>

							@endif

							@if (App\Models\PPH::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
							<li class="submenu">
								<a href="#" class="">
									<span>IP {{\App\Models\Admin::INDIA_POST_BUSINESS}}</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									<!-- <li class="{{ request()->fullUrl() == route('pph.bag.showCretedBags', ['service_type' => 6]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showCretedBags', ['service_type' => 6]) }}">Bag Dispatched</a>
									</li> -->
									<li class="{{ request()->fullUrl() == route('pph.bag.showRecievedBags', ['service_type' => 6]) ? 'active' : '' }}">
										<a href="{{ route('pph.bag.showRecievedBags', ['service_type' => 6]) }}">Bag Received</a>
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
					{{-- Commission service start --}}
					<li class="submenu">
						<a href="#">
							<i class="la la-cube"></i> <span>Commission Report </span> <span class="menu-arrow"></span>
						</a>
						<ul style="display: block;">

							<li class="{{ request()->fullUrl() == route('pph.commission.index') ? 'active' : '' }}">
								<a href="{{ route('pph.commission.index') }}">Commission</a>
							</li>

						</ul>
					</li>
					{{-- Commission service end --}}

				</ul>

			</div>
		</div>
	</div>

	<!-- /Sidebar -->