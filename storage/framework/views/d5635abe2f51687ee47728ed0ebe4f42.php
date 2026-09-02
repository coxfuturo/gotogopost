<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <title>GOTOGO POST</title>

    <!-- Stylesheets -->

    <!-- bootstrap v3.3.6 css -->

    <link href="<?php echo e(asset('website/css/bootstrap.css')); ?>" rel="stylesheet">

    <!-- font-awesome css -->

    <link href="<?php echo e(asset('website/css/font-awesome.css')); ?>" rel="stylesheet">

    <!-- flaticon css -->

    <link href="<?php echo e(asset('website/css/flaticon.css')); ?>" rel="stylesheet">

    <!-- factoryplus-icons css -->

    <link href="<?php echo e(asset('website/css/factoryplus-icons.css')); ?>" rel="stylesheet">

    <!-- animate css -->

    <link href="<?php echo e(asset('website/css/animate.css')); ?>" rel="stylesheet">

    <!-- owl.carousel css -->

    <link href="<?php echo e(asset('website/css/owl.css')); ?>" rel="stylesheet">

    <!-- fancybox css -->

    <link href="<?php echo e(asset('website/css/jquery.fancybox.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('website/css/hover.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('website/css/frontend.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('website/css/style.css')); ?>" rel="stylesheet">

    <!-- switcher css -->

    <link href="<?php echo e(asset('website/css/switcher.css')); ?>" rel="stylesheet">

    <link rel='stylesheet' id='factoryhub-color-switcher-css' href="<?php echo e(asset('website/css/switcher/default.css')); ?>" />

    <!-- revolution slider css -->

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/revolution/settings.css')); ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/revolution/layers.css')); ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/revolution/navigation.css')); ?>">



    <!--Favicon-->

    <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.ico')); ?>" type="image/x-icon">

    <link rel="icon" href="<?php echo e(asset('images/favicon.ico')); ?>" type="image/x-icon">

    <!-- Responsive -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="<?php echo e(asset('website/css/responsive.css')); ?>" rel="stylesheet">

    <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>



<style>

    .header-v1 .nav a {

    color: #000;

}



.header-transparent .site-header {

    background-color: white;

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;
    z-index: 999;
    border-bottom: 1px solid rgba(35, 41, 81, 0.1);
}
.site-header {

    background-color: #fff;

    position: relative;

    padding: 12px 0;

}

/* ==========================================================================
   TOP BAR HEADER INFO SECTION
   ========================================================================== */
.top-bar-header-info {
    background-color: #0b132b;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    font-family: 'Montserrat', sans-serif;
}

.top-bar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    width: 100%;
}

.top-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Clean outline/stroke red icon without background square/box wrappers */
.top-info-icon {
    color: #ff0033 !important;
    font-size: 22px !important;
    background: none !important;
    border: none !important;
    border-radius: 0 !important;
    padding: 0 !important;
    width: auto !important;
    height: auto !important;
    min-width: unset !important;
    line-height: 1 !important;
    flex-shrink: 0 !important;
}

.top-info-text {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.top-info-text .address-text {
    color: #ffffff;
    font-size: 13.5px;
    font-style: italic;
    font-weight: 500;
    margin: 0;
    line-height: 1.4;
    white-space: nowrap;
}

.top-info-title {
    color: #94a3b8;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.2;
    margin-bottom: 2px;
}

.top-info-value {
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.2;
}

@media (max-width: 991px) {
    .top-bar-header-info {
        display: none;
    }
}

.fh-btn {
    font-size: 16px;
    font-weight: 400;
    color: #fff;
    text-align: center;
    display: inline-block;
    min-width: 100px;
    min-height: 22px;
    line-height: 42px;
    -webkit-border-radius: 0;
    border-radius: 0;
    background-color: #ff0000;
    text-transform: capitalize;
    border: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    font-family: 'Montserrat', sans-serif;
    -webkit-transition: 0.5s;
    transition: 0.5s;
}

.header-transparent .navbar-icon .navbars-line, .header-transparent .navbar-icon .navbars-line:before, .header-transparent .navbar-icon .navbars-line:after {
    background-color: #222;
}

  .header-v1 .nav li li:hover>ul {
    top: 0;
    left: -webkit-calc(100% + 10px);
    left: calc(-102% + 0px);
}

/* add vikas css  */
/* General Navigation Styling */
.nav-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: #007bff;
    display: flex;
    justify-content: center;
}

.nav-menu > li {
    position: relative;
}

/* Main Menu Links */
.nav-menu a {
    text-decoration: none;
    color: #3d0e0e;
    font-size: 16px;
    padding: 12px 20px;
    display: block;
    transition: all 0.3s ease-in-out;
    text-align: left; /* Text left aligned */
}

.nav-menu a:hover {
    background: #0056b3;
}

/* Sub-Menu Styling */
.sub-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    position: absolute;
    right: 0;  /* Submenu ko right shift kiya */
    top: 100%;
    background: #c77272;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    width: 250px;
    display: none;
    z-index: 100;
}

.sub-menu li {
    width: 100%;
    text-align: left; /* Text left align */
    padding-right: 15px; /* List items right shift */
}

.sub-menu a {
    color: #ffffff;
    background: #818080;
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    transition: all 0.3s ease-in-out;
}

.sub-menu a:hover {
    background: #007bff;
    color: white;
}

/* Show Submenu on Hover */
.has-children:hover > .sub-menu {
    display: block;
}

/* Nested Sub-Menu */
.has-children .sub-menu .sub-menu {
    right: 100%; /* Submenu ko right shift kiya */
    top: 0;
}

/* Responsive Styling */
@media (max-width: 768px) {
    .nav-menu {
        display: block;
    }
    .nav-menu > li {
        display: block;
        width: 100%;
    }
    .sub-menu {
        width: 100%;
        position: relative;
    }
}

/* new Setting */
.nav ul ul {
   background: #ff0000 !important
}

.nav li li:hover {
    background-color: #fff !important;
    color: #000 !important;
}

.nav li li {
     padding: 0px 0px !important;
}

.sub-menu li {
  padding: 0px !important;
}

.sub-menu a:hover {
    color: #000 !important;
}

.sub-menu a {
    background: none !important;
    color: #000000 !important;
}

/* --- Login & Register Main Button Styling (Logo Blue) --- */
.header-v1 .nav .fh-btn.btn {
    background: linear-gradient(135deg, #183b9e, #102c80) !important;
    color: #ffffff !important;
    font-size: 14px;
    font-weight: 600;
    line-height: normal;
    padding: 9px 22px;
    border-radius: 30px; /* Rounded pill design */
    border: none;
    box-shadow: 0 4px 12px rgba(16, 44, 128, 0.25);
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-transform: capitalize;
}

/* Hover on Login & Register Button */
.header-v1 .nav .fh-btn.btn:hover {
    background: linear-gradient(135deg, #102c80, #0c2263) !important;
    box-shadow: 0 6px 16px rgba(16, 44, 128, 0.35);
    transform: translateY(-1px);
}

/* --- Dropdown Menu Container --- */
.header-v1 .nav li.has-children .sub-menu {
    background: #ffffff !important;
    min-width: 250px;
    padding: 8px;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    top: 100%;
    margin-top: 8px;
}

/* --- Dropdown Links List --- */
.header-v1 .nav li.has-children .sub-menu li {
    padding: 0 !important;
    margin: 2px 0;
    background: transparent !important;
}

/* Link Items */
.header-v1 .nav li.has-children .sub-menu a,
.header-v1 .nav li .sub-menu a,
.nav .sub-menu li a {
    color: #000000 !important;
    background: transparent !important;
    font-size: 15.5px;
    font-weight: 500;
    padding: 10px 14px;
    border-radius: 6px;
    border: none !important;
    display: block;
    transition: all 0.2s ease-in-out;
}

/* Link Hover Effect */
.header-v1 .nav li.has-children .sub-menu a:hover {
    background: #f1f5f9 !important;
    color: #102c80 !important;
    padding-left: 18px; /* Smooth slide effect */
}
</style>



<body class="home header-transparent  header-sticky header-v1 hide-topbar-mobile">

    <div id="page">

        <!-- Preloader-->

        



        <!-- top bar info section -->
        <div class="top-bar-header-info hidden-xs hidden-sm">
            <div class="container">
                <div class="top-bar-inner">
                    
                    <!-- Address Item -->
                    <div class="top-info-item">
                        <i class="flaticon-signs top-info-icon"></i>
                        <div class="top-info-text">
                            <p class="address-text">Gaur City Mall Office Space - Sector 4, Greater Noida Uttar Pradesh 201318</p>
                        </div>
                    </div>

                    <!-- Toll Free Item -->
                    <div class="top-info-item">
                        <i class="flaticon-phone-call top-info-icon"></i>
                        <div class="top-info-text">
                            <span class="top-info-title">Toll Free Number :</span>
                            <span class="top-info-value">1800 123 1617</span>
                        </div>
                    </div>

                    <!-- Opening Hours Item -->
                    <div class="top-info-item">
                        <i class="flaticon-clock-1 top-info-icon"></i>
                        <div class="top-info-text">
                            <span class="top-info-title">Opening Hours :</span>
                            <span class="top-info-value">MON – FRI: 10AM – 7PM</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <header id="masthead" class="site-header clearfix">



            <div class="header-main clearfix">

                <div class="container mobile_relative">

                    <div class="row">

                        <div class="site-logo col-md-2 col-sm-6 col-xs-6">

                            <a href="<?php echo e(route('website.index')); ?>" class="logo">

                                <img  src="<?php echo e(asset('website/images/logonew.jpg')); ?>" style="width: 50%;height: 100%;" alt="CargoHub" class="logo-light show-logo">

                                <img src=" <?php echo e(asset('website/images/logonew.jpg')); ?> " style="width: 50%;height: 100%;" alt="CargoHub" class="logo-dark hide-logo">

                            </a>

                        </div>

                        <div class="site-menu col-md-10 col-sm-6 col-xs-6">

                            <nav id="site-navigation" class="main-nav primary-nav nav" style="display:flex; justify-content:flex-end;">

                                <ul class="menu">

                                    <li class=""><a href="<?php echo e(route('website.index')); ?>" class="dropdown-toggle">Home</a>

                                    </li>

                                    
                                    <li class="has-children">
                                        <a href="<?php echo e(route('website.services')); ?>" class="dropdown-toggle">Services</a>
                                        <ul class="sub-menu">
                                    
                                           
                                            <li class="has-children">
                                                <a href="#">Courier Booking Services</a>
                                                <ul class="sub-menu">
                                                    <li class="has-children">
                                                    <li><a href="#">GOTOGO Super Speed Packet</a></li>
                                                    <li><a href="#">GOTOGO Express Package</a></li>
                                                    <li><a href="#">GOTOGO Secure Packet</a></li>
                                                       
                                                    </li>
                                                </ul>
                                            </li>
                                    
                                           
                                            <li class="has-children">
                                                <a href="#">GOTOGO Digital Courier Services</a>
                                                <ul class="sub-menu">
                                                    <li class="has-children">
                                                        <a href="#">E2E</a>
                                                        <a href="#">E2H</a>
                                                       
                                                    </li>

                                                </ul>
                                            </li>
                                            <li class="has-children">
                                                <a href="#">GOTOGO All India Post Services</a>
                                                <ul class="sub-menu">
                                                    <li class="has-children">
                                                    <li><a href="#">GOTOGO Speed Packet</a></li>
                                                    <li><a href="#">GOTOGO Business Package</a></li>
                                                        
                                                    </li>

                                                </ul>
                                            </li>
                                    
                                            <li class="has-children">
                                                <a href="#">International Service</a>
                                                <ul class="sub-menu">
                                                    <li class="has-children">
                                                        <a href="#">B2B</a>
                                                        <a href="#">B2C</a>
                                                        
                                                    </li>

                                                </ul>
                                            </li>
                                           
                                        </ul>
                                    </li>
                                    

                                    

                                    <li><a href="<?php echo e(route('website.contact')); ?>">Contact</a></li>

                                    <li><a href="<?php echo e(route('website.about')); ?>">About Us</a></li>
                                    <li><a href="<?php echo e(route('website.pay')); ?>">Payment</a></li>

                                    <li class="has-children"><a href="#" class="dropdown-toggle fh-btn btn" style="color:white">Login</a>

                                        <ul class="sub-menu">  
                                            <li><a href="<?php echo e(route('franchise.login')); ?>">Bussiness Associate</a></li>
                                            
                                            <li><a href="<?php echo e(route('cms.login')); ?>">City Process Hub</a></li>
                                            
                                            <li><a href="<?php echo e(route('pph.login')); ?>">Prime Process Hub</a></li>
                                            
                                            <li><a href="<?php echo e(route('deliveryBoy.login')); ?>">Delivery | Pickup Boy</a></li>
                                            <li><a href="<?php echo e(route('customer.login')); ?>">Bussiness Bulk Customer</a></li>
                                            <li><a href="<?php echo e(route('market.login')); ?>">Sales Marketing Manager</a></li>
                                        </ul>

                                    </li>

                                    <li class="has-children"><a href="#" class="dropdown-toggle fh-btn btn" style="color:white">Register</a> 

                                        <ul class="sub-menu">

                                            <li><a href="<?php echo e(route('franchise.register')); ?>">Bussiness Associate</a>
                                           
                                            </li>

                                            <li><a href="<?php echo e(route('cms.register')); ?>">City Process Hub</a></li>

                                            <li><a href="<?php echo e(route('pph.register')); ?>">Prime Process Hub</a></li>
                                               <li><a href="<?php echo e(route('deliveryBoy.register')); ?>">Delivery | Pickup Boy</a></li>
                                               <li><a href="<?php echo e(route('franchise.combo.index')); ?>"> Associate Hub Center</a></li>
                                               <li><a href="<?php echo e(route('customer.register')); ?>">Bussiness Bulk Customer</a></li>
                                               <li><a href="<?php echo e(route('market.register')); ?>">Sales Marketing Manager</a></li>

                                        </ul>

                                    </li>
                                 

                                </ul>

                            </nav>

                        </div>

                    </div>

                    <a href="#" class="navbar-toggle">

                        <span class="navbar-icon">

                            <span class="navbars-line"></span>

                        </span>

                    </a>

                </div>

            </div>

        </header>

        


        <!--mobile view-->

    <div class="primary-mobile-nav header-v1" id="primary-mobile-nav" role="navigation">

        <a href="#" class="close-canvas-mobile-panel">×</a>

        <ul class="menu">

            <li class=""><a href="<?php echo e(route('website.index')); ?>">Home</a>
            </li>

            <li class="menu-item-has-children"><a href="#" class="dropdown-toggle">Services</a>

                <!-- <ul class="sub-menu">

                    <li><a href="<?php echo e(route('website.services')); ?>">Gotogo Speed Packet</a></li>

                    <li><a href="<?php echo e(route('website.roadFreight')); ?>">Gotogo Business Package</a></li>

                    <li><a href="ocean-freight-forwarding.html">Gotogo Legal Document Delivery</a></li>

                    <li><a href="air-freight-forwarding.html">Gotogo Digital Courier</a></li>

                </ul> -->

                <ul class="sub-menu">

                                          <li><a href="<?php echo e(route('website.services')); ?>">Courier Booking Services</a></li>
                                          <li><a href="<?php echo e(route('website.services')); ?>">GOTOGO Speed Packet </a></li>
                                          <li><a href="<?php echo e(route('website.roadFreight')); ?>">GOTOGO Express </a></li>
                                         
                                          <li><a href="<?php echo e(route('website.insta')); ?>"> GOTOGO Secure Packet</a></li>
                                          <!-- <li><a href="<?php echo e(route('website.logistic')); ?>">Logistic Services </a></li> -->
                                          <li><a href="<?php echo e(route('website.ecommerce')); ?>">B2B Service </a></li>
                                          <li><a href="#">Digital Courier Service  </a></li>
                                          <li><a href="#">Gotogo Post All India Post Service  </a>
                                            <ul>
                                                <li><a href="#">Gotogo Speed Packaet</a></li>
                                                <li><a href="#">GOTOGO Business Package</a></li>
                                            </ul>
                                          </li>
                                        </ul>

            </li>

            <li class="menu-item-has-children"><a href="#" class="dropdown-toggle">Pages</a>

                <ul class="sub-menu">

                    <li><a href="about-us.html">About Us</a></li>

                    <li><a href="faqs.html">FAQS</a></li>

                    <li><a href="our-prices.html">Our Prices</a></li>

                    <li><a href="testimonials.html">Testimonials</a></li>

                </ul>

            </li>

            <li><a href="<?php echo e(route('website.contact')); ?>">Contact</a></li>

            <li><a href="<?php echo e(route('website.about')); ?>">About Us</a></li>
            <li><a href="<?php echo e(route('website.pay')); ?>">Payment</a></li>

            <li class="menu-item-has-children extra-menu-item menu-item-button-link">  <a href="#" class="">Login</a>

                <ul class="sub-menu">  
                    <li><a class="fh-btn" href="<?php echo e(route('deliveryBoy.login')); ?>">Delivery Boy</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('franchise.login')); ?>">Franchise</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('cms.login')); ?>">CPH</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('cms.login')); ?>">PPH</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('customer.login')); ?>">Premium E-Customer Services</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('market.login')); ?>">Marketing Manager</a></li>
                </ul>

            </li>

            <li class="menu-item-has-children extra-menu-item menu-item-button-link">  <a href="#" class="">Register</a>

                <ul class="sub-menu">  
                    <li><a class="fh-btn" href="<?php echo e(route('deliveryBoy.register')); ?>">Delivery Boy</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('franchise.register')); ?>">Franchise</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('cms.register')); ?>">CPH</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('cms.register')); ?>">PPH</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('franchise.combo.index')); ?>">Hub Center</a></li>
                    <li><a class="fh-btn" href="<?php echo e(route('customer.register')); ?>">Premium E-Customer Services</a></li>
                    <li><a href="<?php echo e(route('market.register')); ?>">Marketing Manager</a></li>
                </ul>

            </li>

        </ul>



    </div>

    

    <!--mobile view--><?php /**PATH D:\coxfuturetech\gotogopost\resources\views/website/layouts/header.blade.php ENDPATH**/ ?>