@extends('franchise.layouts.master') 
@section('title') Dashboard @endsection

@section('content')
<style>
    /* Modern Dashboard Custom UI Enhancements */
    .dashboard-wrapper {
        position: relative;
        padding-top: 10px;
    }

    .card {
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        margin-bottom: 24px !important;
        transition: all 0.25s ease-in-out;
        background: #ffffff;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
    }

    .dash-widget .card-body {
        padding: 22px;
        display: flex;
        align-items: center;
    }

    .dash-widget-icon {
        background-color: rgba(255, 155, 68, 0.1) !important;
        color: #ff9b44 !important;
        height: 52px !important;
        width: 52px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px !important;
        margin-right: 16px !important;
        flex-shrink: 0;
    }

    .dash-widget-icon img {
        max-height: 26px;
        width: auto;
    }

    .dash-widget-info {
        text-align: left !important;
        width: 100% !important;
    }

    .dash-widget-info h3 {
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #1e293b;
        margin-bottom: 2px !important;
    }

    .dash-widget-info span {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* Booking & Service Cards UI */
    .service-card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 24px !important;
        height: 100%;
    }

    .booking {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .booking li {
        margin-top: 8px !important;
        background: #f8fafc !important;
        border-radius: 10px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .booking li:hover {
        background: #ef4444 !important;
        border-color: #ef4444 !important;
    }

    .booking li a {
        color: #334155 !important;
        display: block;
        padding: 10px 16px;
        font-weight: 500;
        font-size: 13px;
        text-decoration: none;
        border-radius: 10px;
        transition: color 0.2s;
    }

    .booking li:hover a {
        color: #fff !important;
    }

    /* Filter UI Design */
    .filterOuter {
        position: absolute;
        z-index: 10;
        top: -65px;
        right: 0;
    }

    .filterInner {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
    }

    .form-focus .form-control {
        height: 42px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background-color: #fff;
    }

    /* Table Enhancements */
    .custom-table th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        padding: 14px 16px !important;
        border-bottom: 2px solid #e2e8f0 !important;
    }

    .custom-table td {
        padding: 14px 16px !important;
        vertical-align: middle;
        color: #334155;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }

    .card-title {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 18px;
    }

    @media screen and (max-width: 991px) {
        .filterOuter {
            position: unset;
            margin-bottom: 20px;
        }
        .filterInner {
            justify-content: flex-start;
            flex-wrap: wrap;
        }
    }
</style>

<div class="dashboard-wrapper">
    <div class="row" style="position: relative">

        <!-- Top Date Filter Form -->
        <div class="col-lg-8 filterOuter">
            <form action="{{ route('franchise.dashboard') }}" method="get">
                @csrf
                <div class="row p-0 filterInner">
                    <div class="col-sm-3">
                        <div class="input-block mb-0 form-focus focused">
                            <div class="cal-icon">
                                <input name="start" id="startDate" class="form-control floating datetimepicker fromDate" type="text"
                                    value="{{ request()->query('start') ? \Carbon\Carbon::parse(request()->query('start'))->format('d-m-Y') : '' }}">
                            </div>
                            <label class="focus-label">From</label>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-block mb-0 form-focus focused">
                            <div class="cal-icon">
                                <input name="end" id="endDate" class="form-control floating datetimepicker toDate" type="text"
                                    value="{{ request()->query('end') ? \Carbon\Carbon::parse(request()->query('end'))->format('d-m-Y') : '' }}">
                            </div>
                            <label class="focus-label">To</label>
                        </div>
                    </div>
                    <div class="col-sm-4 filter-button">
                        <button type="submit" class="btn btn-success px-4 py-2 shadow-sm" style="border-radius: 10px; font-weight: 500;">Filter</button>
                        <a href="{{ route('franchise.dashboard') }}" class="btn btn-outline-danger px-3 py-2" style="border-radius: 10px; font-weight: 500;">Reset</a>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Filter -->
         
        <!-- Delivery Boys -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.delboy.index')}}" class="text-decoration-none">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}" alt="Logo"></span>
                        <div class="dash-widget-info">
                            <h3>{{$deliveryboy}}</h3>
                            <span>Delivery Boys</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pickup Enquiry -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.pickup-details.index')}}" class="text-decoration-none">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}" alt="Logo"></span>
                        <div class="dash-widget-info">
                            <h3>{{$pickuplist}}</h3>
                            <span>Pickup Enquiry</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mail E2E Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.mailToMail.receivedMails')}}" class="text-decoration-none">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}" alt="Logo"></span>
                        <div class="dash-widget-info">
                            <h3>{{$MailE2e}}</h3>
                            <span>Mail E2E Report</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mail E2H Report -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <a href="{{route('franchise.mailTofranhchise.receivedMails')}}" class="text-decoration-none">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><img src="{{asset('website/images/icon/gotogo logo3.png')}}" alt="Logo"></span>
                        <div class="dash-widget-info">
                            <h3>{{$MailE2h}}</h3>
                            <span>Mail E2H Report</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Gotogo Post Services -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget h-100">
                <div class="card-body service-card-body">
                    <div class="row w-100 m-0 justify-content-between align-items-center mb-3">
                        <div class="col-6 p-0">
                            <h3>{{$gotogolistList}}</h3>
                            <span>Bookings</span>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <h3 class="text-success">{{$gotogolistAmount}}</h3>
                            <span>Amount</span>
                        </div>
                    </div>
                    <div class="w-100 text-center">
                        <h4 class="card-title mb-2" style="font-size: 14px; color: #475569;">Gotogo Post Booking Services</h4>
                        <ul class="booking">
                            <li><a href="{{ route('franchise.go-speed-post-parcel.create') }}">{{\App\Models\Admin::GOTOGO_POST_SPEED}}</a></li>
                            <li><a href="{{ route('franchise.go-business-parcel.create') }}">{{\App\Models\Admin::GOTOGO_POST_BUSINESS}}</a></li>
                            <li><a href="{{ route('franchise.go-registered.create') }}">{{\App\Models\Admin::GOTOGO_POST_REGISTERED}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- All India Post Services -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget h-100">
                <div class="card-body service-card-body">
                    <div class="row w-100 m-0 justify-content-between align-items-center mb-3">
                        <div class="col-6 p-0">
                            <h3>{{$indiapostlistList}}</h3>
                            <span>Bookings</span>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <h3 class="text-success">{{$gotogolistIndiaAmount}}</h3>
                            <span>Amount</span>
                        </div>
                    </div>
                    <div class="w-100 text-center">
                        <h4 class="card-title mb-2" style="font-size: 14px; color: #475569;">All India Post Booking Services</h4>
                        <ul class="booking">
                            <li><a href="{{ route('franchise.india-post-speed-post.create') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a></li>
                            <li><a href="{{ route('franchise.india-post-business.create') }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a></li>
                            <li><a href="{{ route('franchise.commission.index') }}">Discount</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movin Services Card -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <div class="card dash-widget h-100" style="background: linear-gradient(135deg, #1e293b, #0f172a) !important;">
                <div class="card-body service-card-body">
                    <div class="row w-100 m-0 justify-content-between align-items-center mb-3">
                        <div class="col-6 p-0">
                            <h3 class="text-white">0</h3>
                            <span class="text-white opacity-75">Bookings</span>
                        </div>
                        <div class="col-6 p-0 text-end">
                            <h3 class="text-white">0</h3>
                            <span class="text-white opacity-75">Amount</span>
                        </div>
                    </div>
                    <div class="w-100 text-center">
                        <h4 class="text-white mb-2" style="font-size: 14px; font-weight: 700;">Movin Booking Services</h4>
                        <ul class="booking">
                            <li style="background: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.1) !important;"><a style="color: #fff !important;" href="{{ route('franchise.india-post-speed-post.create') }}">{{\App\Models\Admin::INDIA_POST_SPEED}}</a></li>
                            <li style="background: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.1) !important;"><a style="color: #fff !important;" href="{{ route('franchise.india-post-business.create') }}">{{\App\Models\Admin::INDIA_POST_BUSINESS}}</a></li>
                            <li style="background: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.1) !important;"><a style="color: #fff !important;" href="{{ route('franchise.commission.index') }}">Discount</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- e2E Card -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <a href="{{ route('franchise.mailToMail.create')}}" class="text-decoration-none">
                <div class="card dash-widget h-100">
                    <div class="card-body justify-content-center flex-column py-4">
                        <div class="row w-100 m-0 mb-3">
                            <div class="col-12 p-0">
                                <h3 style="color:#1e3d59; font-weight: 800;"><span style="font-size: 30px; color:#ef4444;">e</span>2E</h3>
                            </div>
                        </div>
                        <div class="row w-100 m-0 text-center">
                            <div class="col-4 p-0">
                                <h4 class="fs-5 fw-bold text-dark mb-1">{{$MailE2e}}</h4>
                                <span style="font-size: 11px;">Bookings</span>
                            </div>
                            <div class="col-4 p-0">
                                <h4 class="fs-5 fw-bold text-dark mb-1">{{$totalEmailAmount}}</h4>
                                <span style="font-size: 11px;">Amount</span>
                            </div>
                            <div class="col-4 p-0 text-end">
                                <h4 class="fs-5 fw-bold text-dark mb-1">{{$totalBalance}}</h4>
                                <span style="font-size: 11px;">Balance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- e2H Card -->
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
            <a href="{{ route('franchise.mailTofranhchise.create')}}" class="text-decoration-none">
                <div class="card dash-widget h-100">
                    <div class="card-body justify-content-center flex-column py-4">
                        <div class="row w-100 m-0 mb-3">
                            <div class="col-12 p-0">
                                <h3 style="color:#1e3d59; font-weight: 800;"><span style="font-size: 30px; color:#ef4444;">e</span>2H</h3>
                            </div>
                        </div>
                        <div class="row w-100 m-0 text-center">
                            <div class="col-4 p-0">
                                <h4 class="fs-5 fw-bold text-dark mb-1">{{$MailE2h}}</h4>
                                <span style="font-size: 11px;">Bookings</span>
                            </div>
                            <div class="col-4 p-0">
                                <h4 class="fs-5 fw-bold text-dark mb-1">{{$totalEmailAmounte2h}}</h4>
                                <span style="font-size: 11px;">Amount</span>
                            </div>
                            <div class="col-4 p-0 text-end">
                                <h4 class="fs-5 fw-bold text-dark mb-1">{{$totalBalance}}</h4>
                                <span style="font-size: 11px;">Balance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <!-- Data Tables Section -->
    <div class="row mt-3">
        <!-- Gotogo Daily Booking Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-start">Gotogo Post Daily Booking Service</h3>
                    <div class="table-responsive">
                        <table class="table table-hover custom-table datatable">
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
                                @foreach($gotogolist as $i => $data)
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td class="text-center font-monospace text-primary fw-semibold">{{$data->barcode_no}}</td>
                                    <td>{{$data->pickup_address}}</td>
                                    <td>{{$data->pickup_state}}</td>
                                    <td>{{$data->pickup_city}}</td>
                                    <td>{{$data->pickup_pincode}}</td>
                                    <td>{{$data->pickup_mobile}}</td>
                                    <td>{{$data->pickup_email}}</td>
                                    <td>{{$data->consignee_address}}</td>
                                    <td>{{$data->consignee_state}}</td>
                                    <td>{{$data->consignee_city}}</td>
                                    <td>{{$data->consignee_pincode}}</td>
                                    <td>{{$data->consignee_mobile}}</td>
                                    <td>{{$data->consignee_email}}</td>
                                    <td>{{$data->package_weight}}</td>
                                    <td class="text-center fw-bold text-success">₹{{$data->payment_amount}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- All India Post Daily Booking Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-start">All India Post Daily Booking Service</h3>
                    <div class="table-responsive">
                        <table class="table table-hover custom-table" id="indiaPostTable">
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
                                    <td>{{ ($indiapostlist->currentPage() - 1) * $indiapostlist->perPage() + $i + 1 }}</td>
                                    <td class="text-center font-monospace text-primary fw-semibold">{{ $data->barcode_no ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($data->pickup_address ?? 'N/A', 20) }}</td>
                                    <td>{{ $data->pickup_state ?? 'N/A' }}</td>
                                    <td>{{ $data->pickup_city ?? 'N/A' }}</td>
                                    <td>{{ $data->pickup_pincode ?? 'N/A' }}</td>
                                    <td>{{ $data->pickup_mobile ?? 'N/A' }}</td>
                                    <td>{{ $data->pickup_email ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($data->consignee_address ?? 'N/A', 20) }}</td>
                                    <td>{{ $data->consignee_state ?? 'N/A' }}</td>
                                    <td>{{ $data->consignee_city ?? 'N/A' }}</td>
                                    <td>{{ $data->consignee_pincode ?? 'N/A' }}</td>
                                    <td>{{ $data->consignee_mobile ?? 'N/A' }}</td>
                                    <td>{{ $data->consignee_email ?? 'N/A' }}</td>
                                    <td>{{ $data->package_weight ?? 'N/A' }}</td>
                                    <td class="text-center fw-bold text-success">₹{{ $data->payment_amount ?? '0' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="16" class="text-center text-muted py-4">No records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination info & links -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div class="pagination-info text-muted small">
                            @if($indiapostlist->total() > 0)
                                Showing {{ $indiapostlist->firstItem() }} to {{ $indiapostlist->lastItem() }} of {{ $indiapostlist->total() }} entries
                            @endif
                        </div>
                        <div class="pagination-container" id="indiaPostPagination">
                            {{ $indiapostlist->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-javascript')
<script>
$(document).ready(function() {
    $('#indiaPostPagination').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var start = $('input[name="start"]').val();
        var end = $('input[name="end"]').val();
        
        if(start || end) {
            url += (url.indexOf('?') > -1 ? '&' : '?') + 'start=' + start + '&end=' + end;
        }
        
        var gotogoPage = getParameterByName('gotogo_page');
        if(gotogoPage) {
            url += '&gotogo_page=' + gotogoPage;
        }
        
        window.location.href = url;
    });
    
    function getParameterByName(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(window.location.search);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    
    $('#indiaPostPagination .pagination a').each(function() {
        var href = $(this).attr('href');
        if(href) {
            var start = $('input[name="start"]').val();
            var end = $('input[name="end"]').val();
            var gotogoPage = getParameterByName('gotogo_page');
            
            var newHref = href;
            if(start || end) {
                newHref += (newHref.indexOf('?') > -1 ? '&' : '?') + 'start=' + start + '&end=' + end;
            }
            if(gotogoPage) {
                newHref += '&gotogo_page=' + gotogoPage;
            }
            $(this).attr('href', newHref);
        }
    });
});
</script>
<script src="{{asset('admin/assets/plugins/morris/morris.min.js')}}"></script>
<script src="{{asset('admin/assets/plugins/raphael/raphael.min.js')}}"></script>
<script src="{{asset('admin/assets/js/chart.js')}}"></script>
@endpush

@endsection