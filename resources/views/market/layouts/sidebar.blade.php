<!-- Sidebar -->
<div class="sidebar marketOrangeBackground" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul class="sidebar-vertical">
				<li class="menu-title">
					<span>Main</span>
				</li>
				<li>
					<a href="{{route('market.dashboard')}}"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
				</li>

<li class="menu-title">
					<span>Customer & Parcel</span>
				</li>


				<li>
    <a href="{{ route('market.customer.index') }}">
        <i class="la la-box"></i> <span> Customer</span>
    </a>
</li>

<li>
    <a href="{{ route('market.customer.create') }}">
        <i class="la la-box"></i> <span>Create Customer</span>
    </a>
</li>

<li class="menu-title">
					<span>Commission Reports</span>
				</li>

				<li>
    <a href="{{ route('market.commission.index') }}">
        <i class="la la-box"></i> <span> Commission</span>
    </a>
</li>




				


			</ul>
		</div>
	</div>
</div>

<!-- /Sidebar -->