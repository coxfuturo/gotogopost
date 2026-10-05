@extends('admin.layouts.master')

@section('title') Dashboard @endsection

@section('content')

<div class="dashboard-wrapper">
    <div class="row position-relative">

        <!-- Filter Form -->
        <div class="col-lg-8 filterOuter">
            <form action="{{ route('admin.dashboard') }}" method="get">
                @csrf
                <div class="filter-glass-box">
                    <div class="row align-items-center g-2">
                        <div class="col-sm-4">
                            <div class="input-block mb-0 form-focus focused">
                                <div class="cal-icon">
                                    <input name="start" id="startDate" class="form-control floating datetimepicker fromDate" type="text"
                                    value="{{ request()->query('start') ? \Carbon\Carbon::parse(request()->query('start'))->format('d-m-Y') : '' }}" placeholder="From Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-block mb-0 form-focus focused">
                                <div class="cal-icon">
                                    <input name="end" id="endDate" class="form-control floating datetimepicker toDate" type="text"
                                    value="{{ request()->query('end') ? \Carbon\Carbon::parse(request()->query('end'))->format('d-m-Y') : '' }}" placeholder="To Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill font-weight-bold shadow-sm">Filter</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light text-danger px-3 py-2 rounded-pill font-weight-bold ms-1 border">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

      
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('admin.franchise.index')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative; top: 0;" class="dash-widget-icon">
                            <img src="{{ asset('website/images/icon/gotogo logo3.png') }}" style="height: 20px; width: auto;">
                        </span>

                        <div class="dash-widget-info">
                            <h3>{{$franchise_count}}</h3>
                            <span>Business Associate</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

       
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('admin.cms.index')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative; top: 0;" class="dash-widget-icon">
                            <img src="{{ asset('website/images/icon/gotogo logo3.png') }}" style="height: 20px; width: auto;">
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{$cms_count}}</h3>
                            <span>L.P.O/CPH</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

       
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('admin.pph.index')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative; top: 0;" class="dash-widget-icon">
                            <img src="{{ asset('website/images/icon/gotogo logo3.png') }}" style="height: 20px; width: auto;">
                        </span>
                        <div class="dash-widget-info">
                            <h3>{{$pph_count}}</h3>
                            <span> PPH</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

      
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('admin.deliveryBoy.index')}}">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span style="background:none !important; position: relative;" class="dash-widget-icon">
                            <img src="{{ asset('website/images/icon/gotogo logo3.png') }}" style="height: 20px; width: auto; margin-bottom:8px;">
                        </span>
                        <!-- <span style="background:none !important;" class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}"></span> -->
                        <div class="dash-widget-info">
                            <h3>{{$deliveryboy_count}}</h3>
                            <span> Delivery Boy</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Section Row 2: Service Cards -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="row align-items-center w-100 m-0 pb-3 border-bottom">
                        <div class="col-6 ps-0">
                            <h3 class="stat-value mb-0">{{$gotogolistList}}</h3>
                            <span class="stat-label">Total Bookings</span>
                        </div>
                        <div class="col-6 text-end pe-0">
                            <h3 class="stat-value text-success mb-0">{{$gotogolistAmount}}</h3>
                            <span class="stat-label">Total Amount</span>
                        </div>
                    </div>
                    <div class="w-100 pt-3">
                        <h4 class="font-weight-bold text-dark mb-3 text-center">Gotogo Post Booking Services</h4>
                        <ul class="booking text-center">
                            <li>
                                <a href="{{ route('cms.go-speed-post-parcel.index') }}">
                                    {{ \App\Models\Admin::GOTOGO_POST_SPEED }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.go-business-parcel.index') }}">
                                    {{ \App\Models\Admin::GOTOGO_POST_BUSINESS }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.go-registered.index') }}">
                                    {{ \App\Models\Admin::GOTOGO_POST_REGISTERED }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="row align-items-center w-100 m-0 pb-3 border-bottom">
                        <div class="col-6 ps-0">
                            <h3 class="stat-value mb-0">{{$indiapostlistList}}</h3>
                            <span class="stat-label">Total Bookings</span>
                        </div>
                        <div class="col-6 text-end pe-0">
                            <h3 class="stat-value text-success mb-0">{{$gotogolistIndiaAmount}}</h3>
                            <span class="stat-label">Total Amount</span>
                        </div>
                    </div>
                    <div class="w-100 pt-3">
                        <h4 class="font-weight-bold text-dark mb-3 text-center">Indian Post Booking Services</h4>
                        <ul class="booking text-center">
                            <li>
                                <a href="{{ route('cms.india-post-speed-post.index') }}">
                                    {{ \App\Models\Admin::INDIA_POST_SPEED }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.india-post-business.index') }}">
                                    {{ \App\Models\Admin::INDIA_POST_BUSINESS }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cms.india-post-business.index') }}">
                                    SP-Parcal Contractual
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commissions Card -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center pb-3 border-bottom">
                        <!-- <img src="{{ asset('website/images/logonewtransparent.png') }}" alt="" style="height:45px; width:45px; object-fit: cover; border-radius: 10px;" class="me-3 shadow-sm border"> -->
                        <div>
                            <h3 class="stat-value mb-0">{{$commissionList}}</h3>
                            <span class="stat-label">Total Commission Amount</span>
                        </div>
                    </div>
                    <div class="w-100 pt-3 text-center">
                        <ul class="booking">
                            <li class="{{ request()->fullUrl() == route('admin.franchise.commissions', ['membertype' => 'franchise']) ? 'active' : '' }}">
                                <a href="{{route('admin.franchise.commissions',['membertype'=>'franchise'])}}">Business Associate Commissions</a>
                            </li>
                            <li class="{{ request()->fullUrl() == route('admin.cms.commissions', ['membertype' => 'cph']) ? 'active' : '' }}">
                                <a href="{{route('admin.cms.commissions',['membertype'=>'cph'])}}">CPH Commissions</a>
                            </li>
                            <li class="{{ request()->fullUrl() == route('admin.pph.commissions', ['membertype' => 'pph']) ? 'active' : '' }}">
                                <a href="{{route('admin.pph.commissions',['membertype'=>'pph'])}}">PPH Commissions</a>
                            </li>
                            <li class="{{ request()->fullUrl() == route('admin.deliveryBoy.commissions', ['membertype' => 'delivery']) ? 'active' : '' }}">
                                <a href="{{route('admin.deliveryBoy.commissions',['membertype'=>'delivery'])}}">Delivery Commissions</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mail Reports Card -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4 mb-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="row align-items-center w-100 m-0 pb-3 border-bottom">
                        <div class="col-6 ps-0">
                            <h3 class="stat-value mb-0">{{$totalMailCount}}</h3>
                            <span class="stat-label">Bookings Report</span>
                        </div>
                        <div class="col-6 text-end pe-0">
                            <h3 class="stat-value text-success mb-0">{{$totalEmailAmount}}</h3>
                            <span class="stat-label">Amount</span>
                        </div>
                    </div>
                    <div class="w-100 pt-3 text-center">
                        <h6 class="font-weight-bold text-dark mb-3">Mail Report</h6>
                        <ul class="booking">
                            <li>
                                <a href="">{{\App\Models\Admin::E2E}}</a>
                            </li>
                            <li>
                                <a href="">{{\App\Models\Admin::E2H}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Data Tables Section -->
    <div class="row mt-3">
        <!-- Table 1 -->
        <div class="col-md-12 mb-4">
            <div class="modern-dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="section-title mb-0">Gotogo Post Daily Booking Service</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table modern-table datatable">
                            <thead>
                                <tr>
                                    <th>SN#</th>
                                    <th class="text-center">Barcode</th>
                                    <th class="text-center">From Address</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">City</th>
                                    <th class="text-center">Pincode</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">To Address</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">City</th>
                                    <th class="text-center">Pincode</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Weight</th>
                                    <th class="text-center">Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gotogoList as $i => $data)
                                <tr>
                                    <img style="display: none;" src="data:image/png;base64,{{ $data->barcode_image_src }}" alt="Barcode" />
                                    <td>{{$i+1}}</td>
                                    <td class="text-center fw-semibold text-primary">{{$data->barcode_no}}</td>
                                    <td class="text-center">{{$data->pickup_address}}</td>
                                    <td class="text-center">{{$data->pickup_state}}</td>
                                    <td class="text-center">{{$data->pickup_city}}</td>
                                    <td class="text-center">{{$data->pickup_pincode}}</td>
                                    <td class="text-center">{{$data->pickup_mobile}}</td>
                                    <td class="text-center">{{$data->pickup_email}}</td>
                                    <td class="text-center">{{$data->consignee_address}}</td>
                                    <td class="text-center">{{$data->consignee_state}}</td>
                                    <td class="text-center">{{$data->consignee_city}}</td>
                                    <td class="text-center">{{$data->consignee_pincode}}</td>
                                    <td class="text-center">{{$data->consignee_mobile}}</td>
                                    <td class="text-center">{{$data->consignee_email}}</td>
                                    <td class="text-center">{{$data->package_weight}}</td>
                                    <td class="text-center fw-bold text-success">₹{{$data->payment_amount}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table 2: India Post Speed Post -->
        <div class="col-md-12 mb-4">
            <div class="modern-dashboard-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="section-title mb-0">Indian Post Speed Post Daily Booking Service</h3>
                    </div>

                    <!-- Pagination Info Header -->
                    <div class="row mb-3 align-items-center text-muted small">
                        <div class="col-md-6 text-start">
                            Showing <strong>{{ $indiapostlist->firstItem() }}</strong> to <strong>{{ $indiapostlist->lastItem() }}</strong> of <strong>{{ $indiapostlist->total() }}</strong> records
                        </div>
                        <div class="col-md-6 text-end">
                            <span>Page {{ $indiapostlist->currentPage() }} of {{ $indiapostlist->lastPage() }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>SN#</th>
                                    <th class="text-center">Barcode</th>
                                    <th class="text-center">From Address</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">City</th>
                                    <th class="text-center">Pincode</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">To Address</th>
                                    <th class="text-center">State</th>
                                    <th class="text-center">City</th>
                                    <th class="text-center">Pincode</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Weight</th>
                                    <th class="text-center">Amount (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($indiapostlist as $i => $data)
                                <tr>
                                    <td>{{ $indiapostlist->firstItem() + $i }}</td>
                                    <td class="text-center fw-semibold text-primary">{{$data->barcode_no}}</td>
                                    <td class="text-center">{{$data->pickup_address}}</td>
                                    <td class="text-center">{{$data->pickup_state}}</td>
                                    <td class="text-center">{{$data->pickup_city}}</td>
                                    <td class="text-center">{{$data->pickup_pincode}}</td>
                                    <td class="text-center">{{$data->pickup_mobile}}</td>
                                    <td class="text-center">{{$data->pickup_email}}</td>
                                    <td class="text-center">{{$data->consignee_address}}</td>
                                    <td class="text-center">{{$data->consignee_state}}</td>
                                    <td class="text-center">{{$data->consignee_city}}</td>
                                    <td class="text-center">{{$data->consignee_pincode}}</td>
                                    <td class="text-center">{{$data->consignee_mobile}}</td>
                                    <td class="text-center">{{$data->consignee_email}}</td>
                                    <td class="text-center">{{$data->package_weight}}</td>
                                    <td class="text-center fw-bold text-success">₹{{$data->payment_amount}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="16" class="text-center py-4 text-muted">No records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="row mt-4">
                        <div class="col-md-12 d-flex justify-content-center">
                            {{ $indiapostlist->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-javascript')
<script src="{{asset('admin/assets/plugins/morris/morris.min.js')}}"></script>
<script src="{{asset('admin/assets/plugins/raphael/raphael.min.js')}}"></script>
<script src="{{asset('admin/assets/js/chart.js')}}"></script>
<style>
    /* Ultra-Modern Dashboard Theme Styles */
    .dashboard-wrapper {
        padding: 10px 0;
        background-color: #f8fafc;
    }

    /* Modern Card Layout */
    .modern-dashboard-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 24px;
        overflow: hidden;
    }

    .modern-dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -4px rgba(0, 0, 0, 0.08);
    }

    /* Stat Widgets Styling */
    .stat-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1);
    }

    .stat-card .card-body {
        padding: 22px;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.025em;
        line-height: 1.2;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 4px;
    }

    /* Sleek Links & Booking Lists */
    .booking {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .booking li, .booking a {
        padding: 8px 12px;
        color: #334155;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        display: block;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 4px;
        background: #f8fafc;
    }

    .booking li:hover, .booking a:hover {
        background-color: #eff6ff;
        color: #2563eb;
        padding-left: 16px;
    }

    .booking li.active > a, .booking .active {
        background-color: #dbeafe !important;
        color: #1d4ed8 !important;
        font-weight: 600;
    }

    /* Filter Bar Styling */
    .filterOuter {
        position: absolute;
        z-index: 10;
        top: -75px;
        right: 0;
    }

    .filter-glass-box {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 14px;
        padding: 8px 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .form-focus .form-control {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 0.9rem;
        background-color: #fff;
        transition: all 0.2s;
    }

    .form-focus .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* Modern Table Design */
    .modern-table {
        width: 100%;
        margin-bottom: 0;
        white-space: nowrap;
    }

    .modern-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px 14px;
    }

    .modern-table tbody td {
        padding: 14px;
        vertical-align: middle;
        color: #1e293b;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .modern-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .section-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.2rem;
        letter-spacing: -0.01em;
    }

    @media screen and (max-width: 991px) {
        .filterOuter {
            position: unset;
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@endsection