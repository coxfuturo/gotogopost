	<!-- Sidebar -->
	 <style>
    .slimScrollBar{
	background: #fff !important;
    width: 12px !important;
    position: absolute;
    top: 13px;
    opacity: 5.0 !important;
    display: none;
    border-radius: 7px;
    z-index: 99;
    right: 1px;
    height: 44.763px;
		   }
	 </style>
	<div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
			<div id="sidebar-menu" class="sidebar-menu">

				<ul class="sidebar-vertical">
					<li class="menu-title">
						<span>Main Module</span>
					</li>
					<li>
						<a href="{{route('franchise.dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
					</li>

					<li class="submenu">
						@php
						use Spatie\Permission\Models\Role;
						$roles = Role::where('guard_name', 'franchise')
						->orderBy('name', 'asc')
						->first();
						$role = $roles ? $roles->id : 1;
						@endphp
						<a href="#" class="noti-dot {{request()->url() == route('franchise.role.index',$role) ? 'active':''}} {{request()->url() == route('franchise.role.user') ? 'active':''}}"><i class="la la-user"></i> <span> Role & Users</span> <span class="menu-arrow"></span></a>
						<ul>
							<li class="{{request()->url() == route('franchise.role.index',$role) ? 'active':''}}"><a class="" href="{{route('franchise.role.index',$role)}}">Roles</a></li>
							<li class="{{request()->url() == route('franchise.role.user') ? 'active':''}}"><a href="{{route('franchise.role.user')}}">Users</a></li>
						</ul>
					</li>

					<li class="submenu">
						<a href="#" class="{{request()->url() == route('franchise.delboy.index') ? 'active':''}} {{request()->url() == route('franchise.delboy.create') ? 'active':''}}"><i class="la la-rocket"></i> <span> Pickup/Delivery Boy</span> <span class="menu-arrow"></span></a>
						<ul>
							<li class="{{request()->url() == route('franchise.delboy.index') ? 'active':''}}"><a href="{{route('franchise.delboy.index')}}">All Delivery Boy</a></li>
							<li class="{{request()->url() == route('franchise.delboy.create') ? 'active':''}}"><a href="{{route('franchise.delboy.create')}}">Create</a></li>
						</ul>
					</li>


					<li class="{{request()->url() == route('franchise.pickup-details.index') ? 'active':''}}">
						<a href="{{route('franchise.pickup-details.index')}}"><i class="la la-user"></i> <span>Pickup Enquiry</span></a>
					</li>

					{{-- parcel booking start --}}
					<li class="submenu">
						<a href="#" class="{{request()->url() == route('franchise.go-speed-post-parcel.create') ? 'active':''}} {{request()->url() == route('franchise.go-business-parcel.create') ? 'active':''}} {{request()->url() == route('franchise.go-registered.create') ? 'active':''}} {{request()->url() == route('franchise.india-post-speed-post.create') ? 'active':''}} {{request()->url() == route('franchise.india-post-business.create') ? 'active':''}} {{request()->url() == route('franchise.mailToMail.create') ? 'active':''}} {{request()->url() == route('franchise.mailTofranhchise.create') ? 'active':''}}">
							<i class="la la-crosshairs"></i> <span>Gotogo Post Bookings</span> <span class="menu-arrow"></span>
						</a>
						<ul>

							@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
							<li class="{{ request()->url() == route('franchise.go-speed-post-parcel.create') ? 'active' : '' }}">
								<a href="{{ route('franchise.go-speed-post-parcel.create') }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
							</li>
							@endif

							@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
							<li class="{{ request()->url() == route('franchise.go-business-parcel.create') ? 'active' : '' }}">
								<a href="{{ route('franchise.go-business-parcel.create') }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
							</li>
							@endif

							@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
							<li class="{{ request()->url() == route('franchise.go-registered.create') ? 'active' : '' }}">
								<a href="{{ route('franchise.go-registered.create', ) }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
							</li>
							@endif


							<li class="submenu">
								<a href="#" class="{{request()->url() == route('franchise.india-post-speed-post.create') ? 'active':''}} {{request()->url() == route('franchise.india-post-business.create') ? 'active':''}}">
									<span>All Indian Postal Services</span> <span class="menu-arrow"></span>
								</a>
								<ul>
									@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
									<li class="{{ request()->url() == route('franchise.india-post-speed-post.create') ? 'active' : '' }}">
										<a href="{{ route('franchise.india-post-speed-post.create') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
									</li>
									@endif
									@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
									<li class="{{ request()->url() == route('franchise.india-post-business.create') ? 'active' : '' }}">
										<a href="{{ route('franchise.india-post-business.create') }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a>
									</li>
									@endif
						</ul>
					</li>

					<li class="submenu">
						<a href="#" class="{{request()->url() == route('franchise.mailToMail.create') ? 'active':''}} {{request()->url() == route('franchise.mailTofranhchise.create') ? 'active':''}}">
							<span>Email Post Services</span> <span class="menu-arrow"></span>
						</a>
						<ul>

							@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::E2E))
							<li class="{{ request()->url() == route('franchise.mailToMail.create') ? 'active' : '' }}">
								<a href="{{ route('franchise.mailToMail.create') }}">{{\App\Models\Admin::E2E}}</a>
							</li>
							@endif

							@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::E2H))
							<li class="{{ request()->url() == route('franchise.mailTofranhchise.create') ? 'active' : '' }}">
								<a href="{{ route('franchise.mailTofranhchise.create') }}">{{\App\Models\Admin::E2H}}</a>
							</li>
							@endif
						</ul>
					</li>
				</ul>
				</li>
				{{-- parcel booking end --}}


				{{-- parcel booking List start --}}
				<li class="submenu">
					<a href="#" class="{{ request()->url() == route('franchise.go-speed-post-parcel.index', ['id' => 1]) ? 'active' : '' }}  { request()->url() == route('franchise.go-speed-post-parcel.index', ['insert_type' => 1]) ? 'active' : '' }} { request()->url() == route('franchise.go-speed-post-parcel.index', ['insert_type' => 2]) ? 'active' : '' }}  { request()->url() == route('franchise.go-speed-post-parcel.index', ['insert_type' => 2]) ? 'active' : '' }} { request()->url() == route('franchise.go-speed-post-parcel.index', ['insert_type' => 3]) ? 'active' : '' }}">
						<i class="la la-user-secret"></i> <span>Bookings Report</span> <span class="menu-arrow"></span>
					</a>
					<ul>
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
						<li class="submenu">
							<a href="#" class="{{ request()->url() == route('franchise.go-speed-post-parcel.index', ['id' => 1]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::GOTOGO_POST_SPEED}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.go-speed-post-parcel.index', ['insert_type' => 1]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-speed-post-parcel.index', ['insert_type' => 1]) }}">Booking List</a>
								</li>
								<!-- <li class="{{ request()->fullUrl() == route('franchise.go-speed-post-parcel.index', ['insert_type' => 2]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-speed-post-parcel.index', ['insert_type' => 2]) }}">Bulk Uploads</a>
								</li> -->
								<li class="{{ request()->fullUrl() == route('franchise.go-speed-post-parcel.index', ['insert_type' => 3]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-speed-post-parcel.index', ['insert_type' => 3]) }}">From App</a>
								</li>
							</ul>

						</li>
						@endif
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
						<li class="submenu">
							<a href="#" class="{{ request()->url() == route('franchise.go-business-parcel.index', ['id' => 3]) ? 'active' : '' }}">
								{{\App\Models\Admin::GOTOGO_POST_BUSINESS}} <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.go-business-parcel.index', ['insert_type' => 1]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-business-parcel.index', ['insert_type' => 1, 'type'=> 'cod', 'type'=> 'cod']) }}">COD Booking List</a>
								</li>

								<li class="{{ request()->fullUrl() == route('franchise.go-business-parcel.index', ['insert_type' => 1, 'type'=> 'prepaid']) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-business-parcel.index', ['insert_type' => 1, 'type'=> 'prepaid']) }}">Prepaid Booking List</a>
								</li>

								<!-- <li class="{{ request()->fullUrl() == route('franchise.go-business-parcel.index', ['insert_type' => 2]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-business-parcel.index', ['insert_type' => 2]) }}">Bulk Uploads</a>
								</li> -->
								<li class="{{ request()->fullUrl() == route('franchise.go-business-parcel.index', ['insert_type' => 3]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-business-parcel.index', ['insert_type' => 3]) }}">From App</a>
								</li>
							</ul>
						</li>
						@endif
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
						<li class="submenu">
							<a href="#" class="{{ request()->url() == route('franchise.go-registered.index', ['id' => 1]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.go-registered.index', ['insert_type' => 1]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-registered.index', ['insert_type' => 1]) }}">Booking List</a>
								</li>
								<!-- <li class="{{ request()->fullUrl() == route('franchise.go-registered.index', ['insert_type' => 2]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-registered.index', ['insert_type' => 2]) }}">Bulk Uploads</a>
								</li> -->
								<li class="{{ request()->fullUrl() == route('franchise.go-registered.index', ['insert_type' => 3]) ? 'active' : '' }}">
									<a href="{{ route('franchise.go-registered.index', ['insert_type' => 3]) }}">From App</a>
								</li>
							</ul>
						</li>
						
						@endif
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
						<li class="submenu">
							<a href="#" class="{{ request()->url() == route('franchise.india-post-speed-post.index', ['id' => 1]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::INDIA_POST_SPEED}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<!-- <li class="{{ request()->fullUrl() == route('franchise.india-post-speed-post.index', ['insert_type' => 1,'type'=> 'cod', 'type'=> 'cod']) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-speed-post.index', ['insert_type' => 1,'type'=> 'cod', 'type'=> 'cod']) }}">COD Booking List</a>
								</li> -->

								<li class="{{ request()->fullUrl() == route('franchise.india-post-speed-post.index', ['insert_type' => 1,'type'=> 'prepaid', 'type'=> 'prepaid']) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-speed-post.index', ['insert_type' => 1,'type'=> 'prepaid', 'type'=> 'prepaid']) }}">Prepaid Booking List</a>
								</li>

								<li class="{{ request()->fullUrl() == route('franchise.india-post-speed-post.cancel.index') ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-speed-post.cancel.index') }}">Cancel Parecl</a>
								</li>

								<!-- <li class="{{ request()->fullUrl() == route('franchise.india-post-speed-post.index', ['insert_type' => 2]) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-speed-post.index', ['insert_type' => 2]) }}">Bulk Uploads</a>
								</li> -->
								<li class="{{ request()->fullUrl() == route('franchise.india-post-speed-post.index', ['insert_type' => 3]) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-speed-post.index', ['insert_type' => 3]) }}">From App</a>
								</li>
							</ul>
						</li>
						@endif
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
						<li class="submenu">
							<a href="#" class="{{ request()->url() == route('franchise.india-post-business.index', ['id' => 6]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::INDIA_POST_BUSINESS}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.india-post-business.index', ['insert_type' => 1,'type'=> 'prepaid', 'type'=> 'prepaid']) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-business.index', ['insert_type' => 1,'type'=> 'prepaid', 'type'=> 'prepaid']) }}">Prepaid Booking List</a>
								</li>

								<li class="{{ request()->fullUrl() == route('franchise.india-post-business.index', ['insert_type' => 1,'type'=> 'cod', 'type'=> 'cod']) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-business.index', ['insert_type' => 1,'type'=> 'cod', 'type'=> 'cod']) }}">COD Booking List</a>
								</li>
								<li class="{{ request()->fullUrl() == route('franchise.india-post-speed-post.cancel.index') ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-business.cancel.index') }}">Cancel Parecl</a>
								</li>
								<!-- <li class="{{ request()->fullUrl() == route('franchise.india-post-business.index', ['insert_type' => 2]) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-business.index', ['insert_type' => 2]) }}">Bulk Uploads</a>
								</li> -->
								<li class="{{ request()->fullUrl() == route('franchise.india-post-business.index', ['insert_type' => 3]) ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-business.index', ['insert_type' => 3]) }}">From App</a>
								</li>
							</ul>
						</li>
						@endif

					</ul>
				</li>
				{{-- parcel booking List end --}}


				{{-- Mail Report List Start --}}
				<li class="submenu">
					<a href="#" class="{{ request()->url() == route('franchise.go-speed-post-parcel.index', ['id' => 1]) ? 'active' : '' }}">
						<i class="la la-crosshairs"></i> <span>Mail Report</span> <span class="menu-arrow"></span>
					</a>
					<ul>
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::E2E))
						<li class="submenu">
							<a href="#" class="{{ request()->url() == route('franchise.mailToMail.receivedMails') ? 'active' : '' }}">
								<span>E2E</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->url() == route('franchise.mailToMail.receivedMails') ? 'active' : '' }}">
									<a href="{{ route('franchise.mailToMail.receivedMails') }}">Received mails</a>
								</li>
								<li class="{{ request()->url() == route('franchise.mailToMail.sentMails') ? 'active' : '' }}">
									<a href="{{ route('franchise.mailToMail.sentMails', ['service_type' => 'mail_to_mail_single']) }}">Sent Mails</a>
								</li>
								<li class="{{ request()->url() == route('franchise.mailToMail.attachmentReport') ? 'active' : '' }}">
									<a href="{{ route('franchise.mailToMail.attachmentReport') }}">Attachment Report</a>
								</li>
							</ul>
						</li>
						@endif

						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::E2H))
						<li class="submenu">
							<a href="#" class="{{ request()->url() == route('franchise.mailTofranhchise.receivedMails') ? 'active' : '' }}">
								<span>E2H</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->url() == route('franchise.mailTofranhchise.receivedMails') ? 'active' : '' }}">
									<a href="{{ route('franchise.mailTofranhchise.receivedMails') }}">Received mails</a>
								</li>
								<li class="{{ request()->url() == route('franchise.mailTofranhchise.sentMails') ? 'active' : '' }}">
									<a href="{{ route('franchise.mailTofranhchise.sentMails', ['service_type' => 'mail_to_franchise_single']) }}">Sent Mails</a>
								</li>
								<li class="{{ request()->url() == route('franchise.mailTofranhchise.attachmentReport') ? 'active' : '' }}">
									<a href="{{ route('franchise.mailTofranhchise.attachmentReport') }}">Attachment Report</a>
								</li>
							</ul>
						</li>
						@endif
					</ul>
				</li>
				{{-- Mail Report List End --}}


				{{-- E2H Delivery Report Start --}}
				@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::E2H))
				<li class="submenu">
					<a href="#">
						<i class="la la-box"></i> <span>E2H Delivery Report</span> <span class="menu-arrow"></span>
					</a>
					<ul>
						<li class="{{ request()->url() == route('franchise.mailTofranhchise.viewAssignedMailParcel') ? 'active' : '' }}">
							<a href="{{ route('franchise.mailTofranhchise.viewAssignedMailParcel') }}">Transferred To</a>
						</li>
						<li class="{{ request()->url() == route('franchise.mailTofranhchise.viewDeliveredMailParcel') ? 'active' : '' }}">
							<a href="{{ route('franchise.mailTofranhchise.viewDeliveredMailParcel') }}">Delivery Report</a>
						</li>
					</ul>
				</li>
				@endif
				{{-- E2H Delivery Report End --}}



				{{-- Received Parcel List start --}}
				<li class="submenu">
					<a href="#">
						<i class="la la-box"></i> <span>Received Parcel List</span> <span class="menu-arrow"></span>
					</a>
					<ul>
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
						<li class="{{ request()->url() == route('franchise.go-speed-post-parcel.showReceivedParcelList') ? 'active' : '' }}">
							<a href="{{ route('franchise.go-speed-post-parcel.showReceivedParcelList') }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
						</li>
						@endif

						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
						<li class="{{ request()->url() == route('franchise.go-business-parcel.showReceivedParcelList') ? 'active' : '' }}">
							<a href="{{ route('franchise.go-business-parcel.showReceivedParcelList') }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
						</li>
						@endif

						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
						<li class="{{ request()->url() == route('franchise.go-registered.showReceivedParcelList') ? 'active' : '' }}">
							<a href="{{ route('franchise.go-registered.showReceivedParcelList') }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
						</li>
						@endif

						<li class="submenu">
							<a href="#">
								<span>All Indian Postal Services</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
								<li class="{{ request()->url() == route('franchise.india-post-speed-post.showReceivedParcelList') ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-speed-post.showReceivedParcelList') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
								</li>
								@endif

								@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
								<li class="{{ request()->url() == route('franchise.india-post-business.showReceivedParcelList') ? 'active' : '' }}">
									<a href="{{ route('franchise.india-post-business.showReceivedParcelList') }}">Business Parcel</a>
								</li>
								@endif

								{{-- @if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED))
										<li class="{{ request()->url() == route('franchise.india-post-registered.showReceivedParcelList') ? 'active' : '' }}">
								<a href="{{ route('franchise.india-post-registered.showReceivedParcelList') }}">Registered Letter</a>
						</li>
						@endif --}}
					</ul>
				</li>
				</ul>
				</li>
				{{-- Received Parcel List end --}}

				{{--Bag List start --}}
				<li class="submenu">
					<a href="#" class="{{ request()->fullUrl() == route('franchise.go-speed-post-parcel.index', ['service_type' => 1]) ? 'active' : '' }}">
						<i class="la la-briefcase"></i> <span>Bags</span> <span class="menu-arrow"></span>
					</a>
					<ul>
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
						<li class="submenu">
							<a href="#" class="{{ request()->fullUrl() == route('franchise.go-speed-post-parcel.index', ['service_type' => 1]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::GOTOGO_POST_SPEED}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showCretedBags', ['service_type' => 1]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showCretedBags', ['service_type' => 1]) }}">Bag Dispatched</a>
								</li>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showRecievedBags', ['service_type' => 1]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showRecievedBags', ['service_type' => 1]) }}">Bag Received</a>
								</li>
							</ul>
						</li>
						@endif

						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
						<li class="submenu">
							<a href="#" class="{{ request()->fullUrl() == route('franchise.go-business-parcel.index', ['service_type' => 3]) ? 'active' : '' }}">
								{{\App\Models\Admin::GOTOGO_POST_BUSINESS}} <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showCretedBags', ['service_type' => 3]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showCretedBags', ['service_type' => 3]) }}">Bag Dispatched</a>
								</li>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showRecievedBags', ['service_type' => 3]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showRecievedBags', ['service_type' => 3]) }}">Bag Received</a>
								</li>
							</ul>
						</li>
						@endif

						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
						<li class="submenu">
							<a href="#" class="{{ request()->fullUrl() == route('franchise.go-registered.index', ['service_type' => 1]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showCretedBags', ['service_type' => 4]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showCretedBags', ['service_type' => 4]) }}">Bag Dispatched</a>
								</li>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showRecievedBags', ['service_type' => 4]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showRecievedBags', ['service_type' => 4]) }}">Bag Received</a>
								</li>
							</ul>
						</li>
						@endif


						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
						<li class="submenu">
							<a href="#" class="{{ request()->fullUrl() == route('franchise.india-post-speed-post.index', ['service_type' => 1]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::INDIA_POST_SPEED}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showCretedBags', ['service_type' => 5]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showCretedBags', ['service_type' => 5]) }}">Bag Dispatched</a>
								</li>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showRecievedBags', ['service_type' => 5]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showRecievedBags', ['service_type' => 5]) }}">Bag Received</a>
								</li>
							</ul>
						</li>

						@endif
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))
						<li class="submenu">
							<a href="#" class="{{ request()->fullUrl() == route('franchise.india-post-business.index', ['service_type' => 6]) ? 'active' : '' }}">
								<span>{{\App\Models\Admin::INDIA_POST_BUSINESS}}</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showCretedBags', ['service_type' => 6]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showCretedBags', ['service_type' => 6]) }}">Bag Dispatched</a>
								</li>
								<li class="{{ request()->fullUrl() == route('franchise.bag.showRecievedBags', ['service_type' => 6]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showRecievedBags', ['service_type' => 6]) }}">Bag Received</a>
								</li>
							</ul>
						</li>

						@endif
						{{-- @if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED))
							<li class="submenu">
								<a href="#" class="{{ request()->fullUrl() == route('franchise.india-post-registered.index', ['service_type' => 7]) ? 'active' : '' }}">
						<span>IP Registered Letter</span> <span class="menu-arrow"></span>
						</a>
						<ul>
							<li class="{{ request()->fullUrl() == route('franchise.bag.showCretedBags', ['service_type' => 7]) ? 'active' : '' }}">
								<a href="{{ route('franchise.bag.showCretedBags', ['service_type' => 7]) }}">Bag Dispatched</a>
							</li>
							<li class="{{ request()->fullUrl() == route('franchise.bag.showRecievedBags', ['service_type' => 7]) ? 'active' : '' }}">
								<a href="{{ route('franchise.bag.showRecievedBags', ['service_type' => 7]) }}">Bag Received</a>
							</li>
						</ul>
				</li>
				@endif --}}
				</ul>
				</li>
				{{-- Bag List end --}}


				{{-- delivered parcel start --}}
				<li class="submenu">
					<a href="#">
						<i class="la la-cube"></i> <span>Delivered Parcels </span> <span class="menu-arrow"></span>
					</a>
					<ul>
						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_SPEED))
						<li class="{{ request()->fullUrl() == route('franchise.bag.showAllDeliveredParcel',['service_type'=>1]) ? 'active' : '' }}">
							<a href="{{ route('franchise.bag.showAllDeliveredParcel',['service_type'=>1] ) }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a>
						</li>
						@endif

						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_BUSINESS_PARCEL))
						<li class="{{ request()->fullUrl() == route('franchise.bag.showAllDeliveredParcel',['service_type'=>3]) ? 'active' : '' }}">
							<a href="{{ route('franchise.bag.showAllDeliveredParcel',['service_type'=>3] ) }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a>
						</li>
						@endif

						@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_GOTO_POST_REGISTERED))
						<li class="{{ request()->fullUrl() == route('franchise.bag.showAllDeliveredParcel',['service_type'=>4]) ? 'active' : '' }}">
							<a href="{{ route('franchise.bag.showAllDeliveredParcel',['service_type'=>4] ) }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a>
						</li>
						@endif


						<li class="submenu">
							<a href="#">
								<span>All Indian Postal Services</span> <span class="menu-arrow"></span>
							</a>
							<ul>
								@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_SPEED))
								<li class="{{ request()->fullUrl() == route('franchise.bag.showAllDeliveredParcel', ['service_type' => 5]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showAllDeliveredParcel', ['service_type' => 5]) }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a>
								</li>
								@endif
								@if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_BUSINESS))

								<li class="{{ request()->fullUrl() == route('franchise.india-post-business.create', ['service_type' => 6]) ? 'active' : '' }}">
									<a href="{{ route('franchise.bag.showAllDeliveredParcel', ['service_type' => 6]) }}">Business Parcel</a>
								</li>
								@endif
								{{-- @if (App\Models\Franchise::checkServiceStatus(App\Models\GotogoSpeedPostParcel::SERVICE_TYPE_INDIA_POST_REGISTERED))
									<li class="{{ request()->fullUrl() == route('franchise.bag.showAllDeliveredParcel', ['service_type' => 7]) ? 'active' : '' }}">
								<a href="{{ route('franchise.bag.showAllDeliveredParcel', ['service_type' => 7]) }}">Registered Letter</a>
						</li>
						@endif --}}
					</ul>

				</li>
				</ul>
				</li>
				{{-- delivered parcel end --}}


				<li class="{{ request()->fullUrl() == route('franchise.commission.index') ? 'active' : '' }}">
					<a href="{{ route('franchise.commission.index') }}"><i class="la la-pie-chart"></i> <span>Discount</span></a>
				</li>
				<li class="{{ request()->fullUrl() == route('franchise.support-ticket.index') ? 'active' : '' }}">
					<a href="{{ route('franchise.support-ticket.index') }}"><i class="la la-ticket"></i> <span>Support Tickets</span></a>
				</li>
				<li class="{{ request()->fullUrl() == route('franchise.rateCalculator.index') ? 'active' : '' }}">
					<a href="{{ route('franchise.rateCalculator.index') }}"><i class="la la-money"></i> <span>Rate Calculator</span></a>
				</li>
				<li class="{{ request()->fullUrl() == route('franchise.availability.index') ? 'active' : '' }}">
					<a href="{{ route('franchise.availability.index') }}"><i class="la la-money"></i><span>Service Availability</span></a>
				</li>

				<li class="submenu {{ request()->is('franchise/franchise-payment*') ? 'active' : '' }}">
					<a href="#"><i class="la la-pie-chart"></i> <span> Payment </span> <span class="menu-arrow"></span></a>
					<ul>
						<li class="{{ request()->fullUrl() == route('franchise.franchise-payment.index', ['type' => 'gotogo']) ? 'active' : '' }}">
							<a href="{{ route('franchise.franchise-payment.index', ['type' => 'gotogo']) }}">GOTOGO Payment</a>
						</li>
						<li class="{{ request()->fullUrl() == route('franchise.franchise-payment.index', ['type' => 'indiapost']) ? 'active' : '' }}">
							<a href="{{ route('franchise.franchise-payment.index', ['type' => 'indiapost']) }}">All India Post Payment</a>
						</li>
						<li class="{{ request()->fullUrl() == route('franchise.franchise-payment.paymentHistory') ? 'active' : '' }}">
							<a href="{{ route('franchise.franchise-payment.paymentHistory') }}"> Payments Report </a>
						</li>

						<li class="{{ request()->fullUrl() == route('franchise.franchise-payment.credit') ? 'active' : '' }}">
							<a href="{{ route('franchise.franchise-payment.credit') }}">Credit Payment Report </a>
						</li>
					</ul>
				</li>



				</ul>

			</div>
		</div>
	</div>

	<!-- /Sidebar -->