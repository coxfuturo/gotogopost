<style>
  .sidebar {
      background: ;
      color: #94a3b8;
      box-shadow: 6px 0 30px rgba(0, 0, 0, 0.15);
      border-right: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
  }
  .sidebar-inner {
      padding-bottom: 30px;
  }
  .sidebar-menu ul {
      list-style-type: none;
      margin: 0;
      padding: 0 10px;
  }
  .sidebar-menu .menu-title {
      font-size: 0.68rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #475569;
      padding: 20px 14px 8px 14px;
      font-weight: 800;
  }
  .sidebar-menu li a {
      display: flex;
      align-items: center;
      padding: 10px 14px;
      color: #94a3b8;
      font-size: 0.88rem;
      font-weight: 500;
      border-radius: 10px;
      margin-bottom: 4px;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      position: relative;
  }
  .sidebar-menu li a i {
      font-size: 1.2rem;
      margin-right: 12px;
      color: #64748b;
      transition: all 0.25s ease;
  }
  .sidebar-menu li a:hover {
      background: rgba(255, 255, 255, 0.03);
      color: #f8fafc;
      transform: translateX(3px);
  }
  .sidebar-menu li a:hover i {
      color: #38bdf8;
      transform: scale(1.1);
  }
  
  .sidebar-menu li.active > a, 
  .sidebar-menu li a.active {
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.25) 100%) !important;
      color: #38bdf8 !important;
      font-weight: 600;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
      border-left: 3px solid #38bdf8;
      border-top-left-radius: 2px;
      border-bottom-left-radius: 2px;
  }
  .sidebar-menu li.active > a i, 
  .sidebar-menu li a.active i {
      color: #38bdf8 !important;
  }

  .sidebar-menu .submenu ul {
      padding-left: 18px;
      margin-top: 4px;
      margin-bottom: 8px;
      border-left: 1px solid rgba(255, 255, 255, 0.06);
      margin-left: 20px;
  }
  .sidebar-menu .submenu ul li a {
      padding: 8px 12px;
      font-size: 0.83rem;
      color: #64748b;
      background: transparent !important;
      border-left: none !important;
      box-shadow: none !important;
  }
  .sidebar-menu .submenu ul li a:hover {
      color: #f8fafc;
      background: rgba(255, 255, 255, 0.02) !important;
      transform: translateX(2px);
  }
  .sidebar-menu .submenu ul li.active > a,
  .sidebar-menu .submenu ul li a.active {
      color: #38bdf8 !important;
      font-weight: 600;
      background: rgba(56, 189, 248, 0.08) !important;
      border-left: none !important;
      border-radius: 8px;
  }
  .sidebar-menu .menu-arrow {
      margin-left: auto;
      transition: transform 0.25s ease;
      font-size: 0.75rem;
      color: #475569;
  }
  /* Custom SlimScroll bar style */
  .slimScrollBar {
      background: #38bdf8 !important;
      width: 5px !important;
      border-radius: 10px;
      opacity: 0.6 !important;
      right: 2px !important;
  }

  .sidebar-menu li.active > a,
  .sidebar-menu a.active {
    background: linear-gradient(
        135deg,
        rgba(59, 130, 246, 0.15) 0%,
        rgba(37, 99, 235, 0.25) 100%
    ) !important;

    color: #38bdf8 !important;
    font-weight: 600;


}

/* Active icon */
.sidebar-menu li.active > a i,
.sidebar-menu a.active i {
    color: #38bdf8 !important;
}

/* Active top-level item */
.sidebar-menu > ul > li > a.active {
    border-left: 3px solid #38bdf8;
    border-top-left-radius: 2px;
    border-bottom-left-radius: 2px;
}

/* Active submenu item */
.sidebar-menu .submenu ul li.active > a,
.sidebar-menu .submenu ul li a.active {
    color: #38bdf8 !important;
    background: rgba(56, 189, 248, 0.08) !important;
    font-weight: 600;
    border-left: 3px solid #38bdf8 !important;
    border-radius: 8px;
}

/* Parent menu when one of its children is active */
.sidebar-menu li.submenu.parent-active > a {
    background: linear-gradient(
        135deg,
        rgba(59, 130, 246, 0.12) 0%,
        rgba(37, 99, 235, 0.20) 100%
    ) !important;

    color: #38bdf8 !important;

}

.sidebar-menu li.submenu.parent-active > a i {
    color: #38bdf8 !important;
}

/* Active parent arrow */
.sidebar-menu li.submenu.parent-active > a .menu-arrow {
    color: #38bdf8 !important;
}

/* Keep active submenu visible */
.sidebar-menu li.submenu.parent-active > ul {
    display: block;
}

/* Small smooth effect */
.sidebar-menu a {
    transition:
    background 0.25s ease,
    color 0.25s ease,
    transform 0.25s ease,
    border 0.25s ease;
}
</style>

<!-- Sidebar HTML Structure -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="menu-title">
                    <span>Admin Dashboard</span>
                </li>
                <li>
                    <a href="{{route('admin.dashboard')}}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li class="menu-title">
                    <span>Module</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{request()->url() == route('admin.franchise.index') ? 'active':''}} {{request()->url() == route('admin.franchise.create') ? 'active':''}}"><i class="la la-rocket"></i> <span>Business Associate</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{request()->url() == route('admin.franchise.index') ? 'active':''}}"><a href="{{route('admin.franchise.index')}}">All Business Associate</a></li>
                        <li class="{{request()->url() == route('admin.franchise.create') ? 'active':''}}"><a href="{{route('admin.franchise.create')}}">Create</a></li>
                    </ul>
                </li>
                 <li class="submenu">
                    <a href="#" class="{{request()->url() == route('admin.franchise.index') ? 'active':''}} {{request()->url() == route('admin.franchise.create') ? 'active':''}}"><i class="la la-rocket"></i> <span>Business Bulk </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{request()->url() == route('admin.e-customer.index') ? 'active':''}}"><a href="{{route('admin.e-customer.index')}}">All Business Bulk</a></li>
                        <li class="{{request()->url() == route('admin.e-customer.create') ? 'active':''}}"><a href="{{route('admin.e-customer.create')}}">Create</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#" class="{{request()->url() == route('admin.m_manager.index') ? 'active':''}} {{request()->url() == route('admin.m_manager.create') ? 'active':''}}"><i class="la la-rocket"></i> <span>Sales Marketing </span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{request()->url() == route('admin.m_manager.index') ? 'active':''}}"><a href="{{route('admin.m_manager.index')}}">All Sales Marketing</a></li>
                        <li class="{{request()->url() == route('admin.m_manager.create') ? 'active':''}}"><a href="{{route('admin.m_manager.create')}}">Create </a></li>
                    </ul>
                </li>
               
                <li class="submenu">
                    <a href="#" class="{{request()->url() == route('admin.cms.index') ? 'active':''}} {{request()->url() == route('admin.cms.create') ? 'active':''}}"><i class="la la-cube"></i> <span>L.P.O/CPH</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{request()->url() == route('admin.cms.index') ? 'active':''}}"><a href="{{route('admin.cms.index')}}">All L.P.O/CPH</a></li>
                        <li class="{{request()->url() == route('admin.cms.create') ? 'active':''}}"><a href="{{route('admin.cms.create')}}">Create</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#" class="{{request()->url() == route('admin.pph.index') ? 'active':''}} {{request()->url() == route('admin.pph.create') ? 'active':''}}"><i class="la la-user-plus"></i> <span>PPH</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{request()->url() == route('admin.pph.index') ? 'active':''}}"><a href="{{route('admin.pph.index')}}">All PPH</a></li>
                        <li class="{{request()->url() == route('admin.pph.create') ? 'active':''}}"><a href="{{route('admin.pph.create')}}">Create</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#" class="{{request()->url() == route('admin.deliveryBoy.index') ? 'active':''}} {{request()->url() == route('admin.deliveryBoy.create') ? 'active':''}}"><i class="la la-user-plus"></i> <span>Delivery Boy</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="{{request()->url() == route('admin.deliveryBoy.index') ? 'active':''}}"><a href="{{route('admin.deliveryBoy.index')}}">All delivery Boy</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ in_array(request()->url(), [route('admin.postal-rates.index'), route('admin.parcel.index')]) ? 'active' : '' }}">
                        <i class="la la-money"></i>
                        <span>Indian Postal Rates</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li class="{{ request()->fullUrl() == route('admin.postal-rates.index', ['type' => '1']) ? 'active' : '' }}">
                            <a href="{{ route('admin.postal-rates.index', ['type' => '1']) }}">1. SP-Document Domestic</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.postal-rates.index', ['type' => '1']) ? 'active' : '' }}">
                            <a href="{{ route('admin.postal-rates.index', ['type' => '1']) }}">2. SP-Parcel Domestic</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.india-post-br-rate.index', ['type' => '1']) ? 'active' : '' }}">
                            <a href="{{ route('admin.india-post-br-rate.index', ['type' => '1']) }}">3. SP-Parcel Contractual</a>
                        </li>
<span class="text-bold text-dark">Gotogo Post Rates</span>                        <li class="{{ request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '1']) ? 'active' : '' }}">
                            <a href="{{route('admin.gotogo-postal-rates.index',['type'=>'1'])}}">1. Gotogo Speed Packet</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '3']) ? 'active' : '' }}">
                            <a href="{{route('admin.gotogo-postal-rates.index',['type'=>'3'])}}">2. Gotogo Business Package</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '4']) ? 'active' : '' }}">
                            <a href="{{route('admin.gotogo-postal-rates.index',['type'=>'4'])}}">3. Gotogo Legal Document</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '9']) ? 'active' : '' }}">
                            <a href="{{route('admin.gotogo-postal-rates.index',['type'=>'9'])}}">4. E2H</a>
                        </li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ in_array(request()->url(), [route('admin.postal-rates.index'), route('admin.parcel.index')]) ? 'active' : '' }}">
                        <i class="la la-money"></i>
                        <span>Commission Rates</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li class="{{ request()->fullUrl() == route('admin.commission.index') ? 'active' : '' }}">
                            <a href="{{route('admin.india-post-commission.index')}}">All India Post/Gotogo Post</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.commission.index', ['membertype' => 'franchise']) ? 'active' : '' }}">
                            <a href="{{route('admin.commission.index',['membertype'=>'franchise'])}}">Business Associate Commissions</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.commission.index', ['membertype' => 'cph']) ? 'active' : '' }}">
                            <a href="{{route('admin.commission.index',['membertype'=>'cph'])}}">CPH Commissions</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.commission.index', ['membertype' => 'pph']) ? 'active' : '' }}">
                            <a href="{{route('admin.commission.index',['membertype'=>'pph'])}}">PPH Commissions</a>
                        </li>
                        <li class="{{ request()->fullUrl() == route('admin.commission.index', ['membertype' => 'delivery']) ? 'active' : '' }}">
                            <a href="{{route('admin.commission.index',['membertype'=>'delivery'])}}">Delivery Commissions</a>
                        </li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ in_array(request()->url(), [route('admin.postal-rates.index'), route('admin.parcel.index')]) ? 'active' : '' }}">
                        <i class="la la-files-o"></i>
                        <span>Commissions Report</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
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
                </li>

                <li class="submenu">
                    <a href="#" class="{{ in_array(request()->fullUrl(), [
                       route('admin.franchise.paymentHistory', ['membertype' => 'franchise']),
                       route('admin.cms.paymentHistory', ['membertype' => 'cms']),
                       route('admin.pph.paymentHistory', ['membertype' => 'pph']),
                       route('admin.web.paymentHistory', ['membertype' => 'web']),
                       route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']),
                       route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise']),
                       route('admin.franchise.payment.create', ['membertype' => 'franchise'])
                       ]) ? 'active' : '' }}">
                       <i class="la la-files-o"></i>
                       <span>Payment Report</span>
                       <span class="menu-arrow"></span>
                   </a>
                   <ul>
                    <li class="{{ request()->fullUrl() == route('admin.payment.amount') ? 'active' : '' }}">
                        <a href="{{ route('admin.payment.amount') }}">Register Amount</a>
                    </li>

                    <li class="{{ in_array(request()->fullUrl(), [
                        route('admin.franchise.paymentHistory', ['membertype' => 'franchise']),
                        route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']),
                        route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise']),
                        route('admin.franchise.payment.create', ['membertype' => 'franchise'])
                        ]) ? 'active' : '' }}">
                        <a href="#">
                            <i class="la la-files-o"></i>
                            <span>Business Associate Payment</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li class="{{ request()->url() == route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']) ? 'active' : '' }}">
                                <a href="{{ route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']) }}">Gotogo Payment</a>
                            </li>
                            <li class="{{ request()->url() == route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise']) ? 'active' : '' }}">
                                <a href="{{ route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise']) }}">All India Post Payment</a>
                            </li>
                            <li class="{{ request()->url() == route('admin.franchise.paymentHistory', ['membertype' => 'franchise']) ? 'active' : '' }}">
                                <a href="{{ route('admin.franchise.paymentHistory', ['membertype' => 'franchise']) }}">Business Associate Register</a>
                            </li>
                            <li class="{{ request()->url() == route('admin.franchise.payment.create', ['membertype' => 'franchise']) ? 'active' : '' }}">
                                <a href="{{ route('admin.franchise.payment.create', ['membertype' => 'franchise']) }}">Business Associate Credit</a>
                            </li>
                        </ul>
                    </li>

                    <li class="{{ request()->fullUrl() == route('admin.cms.paymentHistory', ['membertype' => 'cms']) ? 'active' : '' }}">
                        <a href="{{ route('admin.cms.paymentHistory', ['membertype' => 'cms']) }}">CPH Payment</a>
                    </li>
                    <li class="{{ request()->fullUrl() == route('admin.pph.paymentHistory', ['membertype' => 'pph']) ? 'active' : '' }}">
                        <a href="{{ route('admin.pph.paymentHistory', ['membertype' => 'pph']) }}">PPH Payment</a>
                    </li>
                    <li class="{{ request()->fullUrl() == route('admin.user.paymentHistory', ['membertype' => 'user']) ? 'active' : '' }}">
                        <a href="{{ route('admin.user.paymentHistory', ['membertype' => 'user']) }}">User Payment</a>
                    </li>
                    <li class="{{ request()->fullUrl() == route('admin.web.paymentHistory', ['membertype' => 'web']) ? 'active' : '' }}">
                        <a href="{{ route('admin.web.paymentHistory', ['membertype' => 'web']) }}">Web Payment</a>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="#">
                    <i class="la la-external-link-square"></i>
                    <span>Link</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li class="{{ request()->fullUrl() == route('admin.link.create') ? 'active' : '' }}">
                        <a href="{{route('admin.link.create')}}">Create</a>
                    </li>
                    <li class="{{ request()->fullUrl() == route('admin.link.gotogo') ? 'active' : '' }}">
                        <a href="{{route('admin.link.gotogo')}}">Gotogo Links</a>
                    </li>
                    <li class="{{ request()->fullUrl() == route('admin.link.indiaPost') ? 'active' : '' }}">
                        <a href="{{route('admin.link.indiaPost')}}">All India Post Links</a>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="#">
                    <i class="la la-cube"></i>
                    <span>Parcel Barcode</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li class="{{ request()->fullUrl() == route('admin.alloted-barcode.franchiseParcel') ? 'active' : '' }}">
                        <a href="{{route('admin.alloted-barcode.franchiseParcel')}}">Business Associate</a>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="#">
                    <i class="la la-briefcase"></i>
                    <span>Bag Barcode</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li class="{{ request()->fullUrl() == route('admin.alloted-barcode.franchiseBag') ? 'active' : '' }}">
                        <a href="{{route('admin.alloted-barcode.franchiseBag')}}">Business Associate</a>
                    </li>
                    <li class="{{ request()->fullUrl() == route('admin.alloted-barcode.cphBag') ? 'active' : '' }}">
                        <a href="{{route('admin.alloted-barcode.cphBag')}}">CPH</a>
                    </li>
                    <li class="{{ request()->fullUrl() == route('admin.alloted-barcode.pphBag') ? 'active' : '' }}">
                        <a href="{{route('admin.alloted-barcode.pphBag')}}">PPH</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{route('admin.gotogo.pincode.index')}}"><i class="la la-user-secret"></i> <span>Gotogo Pincodes</span></a>
            </li>
            <li>
                <a href="{{route('admin.user.index')}}"><i class="la la-user-secret"></i> <span>Users</span></a>
            </li>
            <li>
                <a href="{{route('admin.support-ticket.index')}}"><i class="la la-ticket"></i> <span>Support Tickets</span></a>
            </li>
            <li>
                <a href="{{route('admin.barcode-upload.index')}}"><i class="la la-external-link-square"></i> <span>Barcode Upload</span></a>
            </li>
            <li>
                <a href="{{route('indiapostbarcodes.index')}}"><i class="la la-external-link-square"></i> <span>All IndiaPost Barcode</span></a>
            </li>
            <li>
                <a href="{{route('websideMessage.index')}}"><i class="la la-external-link-square"></i> <span>Website Message</span></a>
            </li>
        </ul>
    </div>
</div>
</div>
<script> document.addEventListener('DOMContentLoaded', function () { const sidebar = document.querySelector('#sidebar-menu'); if (!sidebar) { return; } /* * Current browser URL */ const currentUrl = new URL(window.location.href); /* * Normalize URL so that: * /admin/users * /admin/users/ * * are treated as the same URL. */ function normalizeUrl(url) { const parsedUrl = new URL(url, window.location.origin); let pathname = parsedUrl.pathname.replace(/\/+$/, ''); if (pathname === '') { pathname = '/'; } return pathname + parsedUrl.search; } const currentPath = normalizeUrl(currentUrl.href); /* * Remove previously added active classes. */ sidebar.querySelectorAll('a.active').forEach(function (link) { link.classList.remove('active'); }); sidebar.querySelectorAll('li.parent-active').forEach(function (item) { item.classList.remove('parent-active'); }); /* * Check every sidebar link. */ sidebar.querySelectorAll('a[href]').forEach(function (link) { const href = link.getAttribute('href'); /* * Ignore: * # * javascript: * empty links */ if ( !href || href === '#' || href.startsWith('javascript:') ) { return; } try { const linkUrl = new URL(href, window.location.origin); const linkPath = normalizeUrl(linkUrl.href); /* * Exact current URL match */ if (linkPath === currentPath) { link.classList.add('active'); /* * Find parent <li> */ const currentLi = link.closest('li'); if (!currentLi) { return; } /* * Find parent submenu */ const parentSubmenu = currentLi.closest('li.submenu'); if (parentSubmenu) { /* * Highlight parent menu */ parentSubmenu.classList.add('parent-active'); const parentLink = parentSubmenu.querySelector(':scope > a'); if (parentLink) { parentLink.classList.add('active'); } /* * Keep submenu open */ const submenu = parentSubmenu.querySelector(':scope > ul'); if (submenu) { submenu.style.display = 'block'; } } /* * Handle nested submenu * * Example: * Payment Report * Business Associate Payment * Gotogo Payment */ let nestedParent = currentLi.parentElement?.closest('li.submenu'); while (nestedParent) { nestedParent.classList.add('parent-active'); const nestedParentLink = nestedParent.querySelector(':scope > a'); if (nestedParentLink) { nestedParentLink.classList.add('active'); } const nestedSubmenu = nestedParent.querySelector(':scope > ul'); if (nestedSubmenu) { nestedSubmenu.style.display = 'block'; } nestedParent = nestedParent.parentElement?.closest('li.submenu'); } } } catch (error) { /* * Ignore invalid URLs. */ } }); /* * When user clicks a sidebar link: * immediately show it as active. * * Backend/navigation remains unchanged. */ sidebar.querySelectorAll('a[href]').forEach(function (link) { const href = link.getAttribute('href'); if ( !href || href === '#' || href.startsWith('javascript:') ) { return; } link.addEventListener('click', function () { /* * Remove active state from all links */ sidebar.querySelectorAll('a.active').forEach(function (item) { item.classList.remove('active'); }); sidebar.querySelectorAll('li.parent-active').forEach(function (item) { item.classList.remove('parent-active'); }); /* * Set clicked link active */ this.classList.add('active'); /* * Highlight parent submenu */ const currentLi = this.closest('li'); if (currentLi) { const parentSubmenu = currentLi.closest('li.submenu'); if (parentSubmenu) { parentSubmenu.classList.add('parent-active'); const parentLink = parentSubmenu.querySelector(':scope > a'); if (parentLink) { parentLink.classList.add('active'); } const submenu = parentSubmenu.querySelector(':scope > ul'); if (submenu) { submenu.style.display = 'block'; } } } }); }); }); </script>