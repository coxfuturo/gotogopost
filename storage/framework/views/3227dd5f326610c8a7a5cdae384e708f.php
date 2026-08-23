<!-- Sidebar -->
  <style>
           .slimScrollBar{
			background: #fc6075  !important;
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
                    <span>Main</span>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.dashboard')); ?>"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
                </li>

                <li class="menu-title">
                    <span>Module</span>
                </li>

                <li class="submenu">
                    <a href="#" class="<?php echo e(request()->url() == route('admin.franchise.index') ? 'active':''); ?> <?php echo e(request()->url() == route('admin.franchise.create') ? 'active':''); ?>"><i class="la la-rocket"></i> <span> Franchise</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="<?php echo e(request()->url() == route('admin.franchise.index') ? 'active':''); ?>"><a href="<?php echo e(route('admin.franchise.index')); ?>">All Franchise</a></li>
                        <li class="<?php echo e(request()->url() == route('admin.franchise.create') ? 'active':''); ?>"><a href="<?php echo e(route('admin.franchise.create')); ?>">Create</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#" class="<?php echo e(request()->url() == route('admin.cms.index') ? 'active':''); ?> <?php echo e(request()->url() == route('admin.cms.create') ? 'active':''); ?>"><i class="la la-cube"></i> <span> CPH</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="<?php echo e(request()->url() == route('admin.cms.index') ? 'active':''); ?>"><a href="<?php echo e(route('admin.cms.index')); ?>">All CPH</a></li>
                        <li class="<?php echo e(request()->url() == route('admin.cms.create') ? 'active':''); ?>"><a href="<?php echo e(route('admin.cms.create')); ?>">Create</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="<?php echo e(request()->url() == route('admin.pph.index') ? 'active':''); ?> <?php echo e(request()->url() == route('admin.pph.create') ? 'active':''); ?>"><i class="la la-user-plus"></i> <span> PPH</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li class="<?php echo e(request()->url() == route('admin.pph.index') ? 'active':''); ?>"><a href="<?php echo e(route('admin.pph.index')); ?>">All PPH</a></li>
                        <li class="<?php echo e(request()->url() == route('admin.pph.create') ? 'active':''); ?>"><a href="<?php echo e(route('admin.pph.create')); ?>">Create</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="<?php echo e(request()->url() == route('admin.deliveryBoy.index') ? 'active':''); ?> <?php echo e(request()->url() == route('admin.deliveryBoy.create') ? 'active':''); ?>"><i class="la la-user-plus"></i> <span> Delivery Boy</span> <span class="menu-arrow"></span></a>
                    <ul>
                    <li class="<?php echo e(request()->url() == route('admin.deliveryBoy.index') ? 'active':''); ?>"><a href="<?php echo e(route('admin.deliveryBoy.index')); ?>">All delivery Boy</a></li>
            </ul>
            </li>

            <li class="submenu">
                <a href="#" class="<?php echo e(in_array(request()->url(), [route('admin.postal-rates.index'), route('admin.parcel.index')]) ? 'active' : ''); ?>">
                    <i class="la la-money"></i>
                    <span>Postal Rates</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.postal-rates.index', ['type' => '1']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.postal-rates.index', ['type' => '1'])); ?>">All Indian Postal Rates</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.india-post-br-rate.index', ['type' => '1']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.india-post-br-rate.index', ['type' => '1'])); ?>">All Indian Post Parcel Contractual</a>
                    </li>


                    <li class="<?php echo e(request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '1']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.gotogo-postal-rates.index',['type'=>'1'])); ?>">Gotogo Speed Packet</a>
                    </li>

                    <li class="<?php echo e(request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '3']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.gotogo-postal-rates.index',['type'=>'3'])); ?>">Gotogo Business Package</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '4']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.gotogo-postal-rates.index',['type'=>'4'])); ?>">Gotogo Legal Document Delivery</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.gotogo-postal-rates.index', ['type' => '9']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.gotogo-postal-rates.index',['type'=>'9'])); ?>">E2H</a>
                    </li>

                </ul>
            </li>


            <li class="submenu">
                <a href="#" class="<?php echo e(in_array(request()->url(), [route('admin.postal-rates.index'), route('admin.parcel.index')]) ? 'active' : ''); ?>">
                    <i class="la la-money"></i>
                    <span>Commission Rates</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>

                    <li class="<?php echo e(request()->fullUrl() == route('admin.commission.index',) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.india-post-commission.index')); ?>">All India Post Commissions</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.commission.index', ['membertype' => 'franchise']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.commission.index',['membertype'=>'franchise'])); ?>">Franchise Commissions</a>
                    </li>

                    <li class="<?php echo e(request()->fullUrl() == route('admin.commission.index', ['membertype' => 'cph']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.commission.index',['membertype'=>'cph'])); ?>">CPH Commissions</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.commission.index', ['membertype' => 'pph']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.commission.index',['membertype'=>'pph'])); ?>">PPH Commissions</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.commission.index', ['membertype' => 'delivery']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.commission.index',['membertype'=>'delivery'])); ?>">Delivery Commissions</a>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="#" class="<?php echo e(in_array(request()->url(), [route('admin.postal-rates.index'), route('admin.parcel.index')]) ? 'active' : ''); ?>">
                    <i class="la la-files-o"></i>
                    <span>Commissions Report</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>

                    <li class="<?php echo e(request()->fullUrl() == route('admin.franchise.commissions', ['membertype' => 'franchise']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.franchise.commissions',['membertype'=>'franchise'])); ?>">Franchise Commissions</a>
                    </li>

                    <li class="<?php echo e(request()->fullUrl() == route('admin.cms.commissions', ['membertype' => 'cph']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.cms.commissions',['membertype'=>'cph'])); ?>">CPH Commissions</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.pph.commissions', ['membertype' => 'pph']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.pph.commissions',['membertype'=>'pph'])); ?>">PPH Commissions</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.deliveryBoy.commissions', ['membertype' => 'delivery']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.deliveryBoy.commissions',['membertype'=>'delivery'])); ?>">Delivery Commissions</a>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="#" class="<?php echo e(in_array(request()->fullUrl(), [
								route('admin.franchise.paymentHistory', ['membertype' => 'franchise']),
								route('admin.cms.paymentHistory', ['membertype' => 'cms']),
								route('admin.pph.paymentHistory', ['membertype' => 'pph']),
								route('admin.web.paymentHistory', ['membertype' => 'web']),
                                route('admin.franchise.paymentHistory', ['membertype' => 'franchise']),
                                route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']),
                                route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise']),
                                route('admin.franchise.payment.create', ['membertype' => 'franchise'])
							]) ? 'active' : ''); ?>">
                    <i class="la la-files-o"></i>
                    <span>Payment Report</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <!-- Franchise Payment Link -->
                    <li class="<?php echo e(request()->fullUrl() == route('admin.payment.amount') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.payment.amount')); ?>">Register Amount</a>
                    </li>

                    <!-- Franchise Payment Link -->
                    <li class="<?php echo e(in_array(request()->fullUrl(), [
                                route('admin.franchise.paymentHistory', ['membertype' => 'franchise']),
                                route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']),
                                route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise']),
                                route('admin.franchise.payment.create', ['membertype' => 'franchise'])
							]) ? 'active' : ''); ?>">
    <a href="#">
        <i class="la la-files-o"></i>
        <span>Franchise Payment</span>
        <span class="menu-arrow"></span>
    </a>
    <ul>
        <li class="<?php echo e(request()->url() == route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.franchise.gotogo.paymentHistory', ['membertype' => 'franchise'])); ?>">Gotogo Payment</a>
        </li>
        <li class="<?php echo e(request()->url() == route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.franchise.india.paymentHistory', ['membertype' => 'franchise'])); ?>">All India Post Payment</a>
        </li>
        <li class="<?php echo e(request()->url() == route('admin.franchise.paymentHistory', ['membertype' => 'franchise']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.franchise.paymentHistory', ['membertype' => 'franchise'])); ?>">Franchise Register</a>
        </li>

        <!-- <li class="<?php echo e(request()->url() == route('admin.franchise.payment.create', ['membertype' => 'franchise']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.franchise.payment.create', ['membertype' => 'franchise'])); ?>">Franchise Credit </a>
        </li> -->
    </ul>
</li>


                    <!-- CPH Payment Link -->
                    <li class="<?php echo e(request()->fullUrl() == route('admin.cms.paymentHistory', ['membertype' => 'cms']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.cms.paymentHistory', ['membertype' => 'cms'])); ?>">CPH Payment</a>
                    </li>

                    <!-- PPH Payment Link -->
                    <li class="<?php echo e(request()->fullUrl() == route('admin.pph.paymentHistory', ['membertype' => 'pph']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.pph.paymentHistory', ['membertype' => 'pph'])); ?>">PPH Payment</a>
                    </li>

                    <!-- user Payment Link -->
                    <li class="<?php echo e(request()->fullUrl() == route('admin.user.paymentHistory', ['membertype' => 'user']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.user.paymentHistory', ['membertype' => 'user'])); ?>">User Payment</a>
                    </li>
                    <!-- Web Payment Link -->
                    <li class="<?php echo e(request()->fullUrl() == route('admin.web.paymentHistory', ['membertype' => 'web']) ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.web.paymentHistory', ['membertype' => 'web'])); ?>">Web Payment</a>
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
                    <li class="<?php echo e(request()->fullUrl() == route('admin.link.create') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.link.create')); ?>">Create</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.link.gotogo') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.link.gotogo')); ?>">Gotogo Links</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.link.indiaPost') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.link.indiaPost')); ?>">All India Post Links</a>
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
                    <li class="<?php echo e(request()->fullUrl() == route('admin.alloted-barcode.franchiseParcel') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.alloted-barcode.franchiseParcel')); ?>">Franchise</a>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="#">
                    <i class="la la la-briefcase"></i>
                    <span>Bag Barcode</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.alloted-barcode.franchiseBag') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.alloted-barcode.franchiseBag')); ?>">Franchise</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.alloted-barcode.cphBag') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.alloted-barcode.cphBag')); ?>">CPH</a>
                    </li>
                    <li class="<?php echo e(request()->fullUrl() == route('admin.alloted-barcode.pphBag') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.alloted-barcode.pphBag')); ?>">PPH</a>
                    </li>
                </ul>
            </li>
           
            <li>
                <a href="<?php echo e(route('admin.gotogo.pincode.index')); ?>"><i class="la la-user-secret"></i> <span>Gotogo Pincodes</span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.user.index')); ?>"><i class="la la-user-secret"></i> <span>Users</span></a>
            </li>

            <li>
                <a href="<?php echo e(route('admin.support-ticket.index')); ?>"><i class="la la-ticket"></i> <span>Support Tickets</span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin.barcode-upload.index')); ?>"><i class="la la-external-link-square"></i> <span>Barcode Upload</span></a>
            </li>
            <li>
                <a href="<?php echo e(route('indiapostbarcodes.index')); ?>"><i class="la la-external-link-square"></i> <span>All IndiaPost Barcode</span></a>
            </li>
            <li>
                <a href="<?php echo e(route('websideMessage.index')); ?>"><i class="la la-external-link-square"></i> <span>Website Message</span></a>
            </li>

            </ul>

        </div>
    </div>
</div>

<!-- /Sidebar --><?php /**PATH /home/gotogopost/public_html/resources/views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>