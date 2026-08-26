@extends('website.layouts.master')

@section('content')

<!-- Scoped Custom CSS for Services Page -->
<style>
/* ==========================================================================
   SERVICES PAGE SCOPED REDESIGN STYLES
   All rules strictly wrapped inside .services-page-wrapper to guarantee zero global leak
   ========================================================================== */

.services-page-wrapper {
    font-family: 'Montserrat', sans-serif;
    color: #1e293b;
    background-color: #f8fafc;
    overflow-x: hidden;
}

/* 1. Top Hero Banner */
.services-page-wrapper .services-page-top-hero {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
}

.services-page-wrapper .services-hero-bg {
    position: relative;
    width: 100%;
    height: 100%;
}

.services-page-wrapper .services-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 35%;
    filter: brightness(0.8) contrast(1.05);
    transition: transform 0.6s ease;
}

.services-page-wrapper .services-page-top-hero:hover .services-hero-img {
    transform: scale(1.02);
}

.services-page-wrapper .services-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(11, 19, 43, 0.4) 0%, rgba(11, 19, 43, 0.85) 100%);
    display: flex;
    align-items: center;
}

.services-page-wrapper .services-hero-content {
    color: #ffffff;
}

.services-page-wrapper .services-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.services-page-wrapper .services-hero-badge i {
    color: #d9251d;
}

.services-page-wrapper .services-hero-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}

.services-page-wrapper .services-hero-breadcrumb {
    font-size: 14px;
    font-weight: 500;
    color: #cbd5e1;
}

.services-page-wrapper .services-hero-breadcrumb a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.2s ease;
}

.services-page-wrapper .services-hero-breadcrumb a:hover {
    color: #d9251d;
}

.services-page-wrapper .services-hero-breadcrumb i {
    margin: 0 8px;
    color: #94a3b8;
}

.services-page-wrapper .services-hero-breadcrumb .active-crumb {
    color: #d9251d;
    font-weight: 700;
}

/* 2. Welcome Intro Section */
.services-page-wrapper .servwesec {
    padding: 70px 0;
    background-color: #ffffff;
}

.services-page-wrapper .uthead p {
    font-size: 14px;
    font-weight: 800;
    color: #d9251d;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 8px;
}

.services-page-wrapper .mdltxtnrow {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 16px;
}

.services-page-wrapper .main-color {
    color: #d9251d !important;
}

.services-page-wrapper .servwesec p {
    font-size: 15px;
    line-height: 1.75;
    color: #475569;
}

/* 3. Services Cards Section */
.services-page-wrapper .allservsec {
    padding: 75px 0;
    background-color: #f8fafc;
}

.services-page-wrapper .item-service {
    margin-bottom: 30px;
}

.services-page-wrapper .service-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.services-page-wrapper .service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 22px 45px rgba(15, 23, 42, 0.1);
    border-color: rgba(217, 37, 29, 0.25);
}

.services-page-wrapper .service-thumb-wrap {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #0f172a;
}

.services-page-wrapper .service-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.services-page-wrapper .service-card:hover .service-thumb-img {
    transform: scale(1.08);
}

.services-page-wrapper .service-icon-badge {
    position: absolute;
    bottom: 16px;
    right: 16px;
    width: 42px;
    height: 42px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d9251d;
    font-size: 16px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.services-page-wrapper .service-card:hover .service-icon-badge {
    background: #d9251d;
    color: #ffffff;
    transform: scale(1.1);
}

.services-page-wrapper .service-card-body {
    padding: 28px 24px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.services-page-wrapper .service-card-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 12px;
}

.services-page-wrapper .service-card-title a {
    color: #0f172a;
    text-decoration: none;
    transition: color 0.25s ease;
}

.services-page-wrapper .service-card:hover .service-card-title a {
    color: #d9251d;
}

.services-page-wrapper .service-card-desc {
    font-size: 14px;
    line-height: 1.65;
    color: #64748b;
    margin-bottom: 18px;
    flex-grow: 1;
}

.services-page-wrapper .service-read-more {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    font-weight: 700;
    color: #d9251d;
    text-decoration: none;
    transition: all 0.25s ease;
}

.services-page-wrapper .service-read-more:hover {
    color: #b91c1c;
    transform: translateX(4px);
}

/* 4. Features Section (Fast worldwide, Safe & Secure, 24/7 Support) */
.services-page-wrapper .features-1.bluebg {
    background: linear-gradient(135deg, #0b132b 0%, #0f172a 60%, #1e293b 100%);
    padding: 65px 0;
    color: #ffffff;
}

.services-page-wrapper .feature-item-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    padding: 32px 26px;
    height: 100%;
    transition: all 0.35s ease;
}

.services-page-wrapper .feature-item-card:hover {
    transform: translateY(-6px);
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(217, 37, 29, 0.5);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

.services-page-wrapper .feature-item-card .fh-icon {
    width: 54px;
    height: 54px;
    background: rgba(217, 37, 29, 0.2);
    border: 1.5px solid rgba(217, 37, 29, 0.4);
    color: #d9251d;
    font-size: 24px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.services-page-wrapper .feature-item-card:hover .fh-icon {
    background: #d9251d;
    color: #ffffff;
    border-color: #d9251d;
    transform: scale(1.08);
}

.services-page-wrapper .feature-item-card .box-title span {
    font-size: 20px;
    font-weight: 700;
    color: #ffffff;
}

.services-page-wrapper .feature-item-card .desc p {
    font-size: 14px;
    line-height: 1.7;
    color: #cbd5e1;
    margin-top: 10px;
}

/* 5. Complete Solution About Section */
.services-page-wrapper .aboutsec-3 {
    padding: 85px 0;
    background-color: #ffffff;
}

.services-page-wrapper .aboutsec-3 .about-img-frame {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
    border: 3px solid #ffffff;
}

.services-page-wrapper .aboutsec-3 .about-img-frame img {
    width: 100%;
    display: block;
    transition: transform 0.4s ease;
}

.services-page-wrapper .aboutsec-3 .about-img-frame:hover img {
    transform: scale(1.03);
}

.services-page-wrapper .aboutsec-3 h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 18px;
    position: relative;
    padding-bottom: 12px;
}

.services-page-wrapper .aboutsec-3 h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 55px;
    height: 4px;
    background: #d9251d;
    border-radius: 2px;
}

.services-page-wrapper .aboutsec-3 p {
    font-size: 15px;
    line-height: 1.75;
    color: #475569;
    margin-bottom: 16px;
}

.services-page-wrapper .aboutsec-3 .fh-btn {
    display: inline-block;
    padding: 12px 30px;
    background: #d9251d;
    color: #ffffff;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(217, 37, 29, 0.3);
    transition: all 0.25s ease;
    margin-top: 15px;
}

.services-page-wrapper .aboutsec-3 .fh-btn:hover {
    background: #b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(217, 37, 29, 0.4);
}

/* 6. Three Step Processing Section */
.services-page-wrapper .three_steps {
    padding: 85px 0;
    background-color: #f8fafc;
}

.services-page-wrapper .three_steps .fh-section-title h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    text-align: center;
    position: relative;
    padding-bottom: 14px;
    margin-bottom: 50px;
    text-transform: uppercase;
}

.services-page-wrapper .three_steps .fh-section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: #d9251d;
    border-radius: 2px;
}

.services-page-wrapper .step-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 34px 26px;
    box-shadow: 0 8px 25px rgba(15, 23, 42, 0.04);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.services-page-wrapper .step-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: transparent;
    transition: background 0.3s ease;
}

.services-page-wrapper .step-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 42px rgba(15, 23, 42, 0.1);
    border-color: rgba(217, 37, 29, 0.25);
}

.services-page-wrapper .step-card:hover::before {
    background: #d9251d;
}

.services-page-wrapper .step-icon-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #fff5f5;
    border: 1.5px solid rgba(217, 37, 29, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 22px;
    transition: all 0.35s ease;
}

.services-page-wrapper .step-icon-circle i {
    font-size: 30px;
    color: #d9251d;
    transition: all 0.35s ease;
}

.services-page-wrapper .step-card:hover .step-icon-circle {
    background: #d9251d;
    border-color: #d9251d;
    transform: scale(1.08) rotate(4deg);
    box-shadow: 0 8px 20px rgba(217, 37, 29, 0.35);
}

.services-page-wrapper .step-card:hover .step-icon-circle i {
    color: #ffffff;
}

.services-page-wrapper .step-title {
    font-size: 19px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
    transition: color 0.3s ease;
}

.services-page-wrapper .step-card:hover .step-title {
    color: #d9251d;
}

.services-page-wrapper .step-desc {
    font-size: 14px;
    line-height: 1.7;
    color: #64748b;
    margin: 0;
}

/* Responsive Rules */
@media (max-width: 991px) {
    .services-page-wrapper .services-page-top-hero {
        height: 240px;
    }
    .services-page-wrapper .services-hero-title {
        font-size: 26px;
    }
    .services-page-wrapper .servwesec,
    .services-page-wrapper .allservsec,
    .services-page-wrapper .features-1.bluebg,
    .services-page-wrapper .aboutsec-3,
    .services-page-wrapper .three_steps {
        padding: 55px 0;
    }
    .services-page-wrapper .mdltxtnrow {
        font-size: 24px;
    }
}

@media (max-width: 576px) {
    .services-page-wrapper .services-page-top-hero {
        height: 200px;
    }
    .services-page-wrapper .services-hero-title {
        font-size: 22px;
    }
    .services-page-wrapper .servwesec,
    .services-page-wrapper .allservsec,
    .services-page-wrapper .features-1.bluebg,
    .services-page-wrapper .aboutsec-3,
    .services-page-wrapper .three_steps {
        padding: 40px 0;
    }
    .services-page-wrapper .mdltxtnrow {
        font-size: 20px;
    }
    .services-page-wrapper .service-card-body,
    .services-page-wrapper .feature-item-card,
    .services-page-wrapper .step-card {
        padding: 24px 20px;
    }
}
</style>

<div class="services-page-wrapper">

    <!-- 1. Top Section Banner (Exclusive to Services Page) -->
    <div class="services-page-top-hero">
        <div class="services-hero-bg">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=2000&q=85" alt="GOTOGO POST Logistics Services Banner" class="services-hero-img">
            <div class="services-hero-overlay">
                <div class="container">
                    <div class="services-hero-content">
                        <span class="services-hero-badge"><i class="fa fa-truck"></i> LOGISTICS & FREIGHT SERVICES</span>
                        <h1 class="services-hero-title">Our Premium Services</h1>
                        <nav class="services-hero-breadcrumb">
                            <a href="{{ route('website.index') }}">Home</a>
                            <i class="fa fa-angle-right"></i>
                            <span class="active-crumb">Services</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Services Intro Section -->
    <div class="servwesec secpadd">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="uthead lftredbrdr">
                        <p>Unbeatable Trucking and Transport Services</p>
                    </div>
                    <div class="mdltxtnrow">Cargo Hub is world leading logistic service company and <span class="main-color">provide #1 Solution.</span></div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <p>Cargo Hub is the world’ s leading logistic service company, We have a wide experience in overland industry specific logistic solutions like pharmaceutical logistics, retail and automotive logistics by train or road.</p>
                    <p>We bring your goods safely to worldwide destinations with our great sea fright services.We offer LLC and FLC shipments that are fast and effective with no delays.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. All Services Cards Grid -->
    <div class="allservsec">
        <div class="container">
            <div class="fh-service style-bordered">
                <div class="service-list row">
                    
                    <!-- Warehousing -->
                   <div class="item-service col-xs-12 col-sm-6 col-md-4">
    <div class="service-card">
        <div class="service-thumb-wrap">
            <img 
                src="https://plus.unsplash.com/premium_photo-1661302828763-4ec9b91d9ce3?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8aW5kdXN0cmlhbCUyMHdhcmVob3VzZXxlbnwwfHwwfHx8MA%3D%3D"
                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1580674684081-7617fbf3d745?auto=format&fit=crop&w=800&q=80';"
                alt="Warehousing"
                class="service-thumb-img"
            >

            <div class="service-icon-badge">
                <i class="fa fa-long-arrow-right"></i>
            </div>
        </div>

        <div class="service-card-body">
            <h3 class="service-card-title">
                <a href="#">Warehousing</a>
            </h3>

            <p class="service-card-desc">
                Package and store your things effectively and securely to make sure them in storage, have certified warehouse.
            </p>

            <a href="#" class="service-read-more">
                Read More <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</div>

                    <!-- Door to Door Delivery -->
                    <div class="item-service col-xs-12 col-sm-6 col-md-4">
                        <div class="service-card">
                            <div class="service-thumb-wrap">
                                <img src="{{ asset('website/images/services/serv-5.jpg') }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1580674684081-7617fbf3d745?auto=format&fit=crop&w=800&q=80';" alt="Door to Door Delivery" class="service-thumb-img">
                                <div class="service-icon-badge">
                                    <i class="fa fa-long-arrow-right"></i>
                                </div>
                            </div>
                            <div class="service-card-body">
                                <h3 class="service-card-title"><a href="#">Door to Door Delivery</a></h3>
                                <p class="service-card-desc">Our expertise in transport management and planning allows us to design a solution. hand over the parcel at your door.</p>
                                <a href="#" class="service-read-more">Read More <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Ground Transport -->
                    <div class="item-service col-xs-12 col-sm-6 col-md-4">
    <div class="service-card">
        <div class="service-thumb-wrap">
            <img 
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQt--ngrXWSJEwtKKR7iPgAktF4fkOo9SUAGTNBUAjypEKwBERXiyWc-FY&s=10"
                alt="Ground Transport"
                class="service-thumb-img"
            >

            <div class="service-icon-badge">
                <i class="fa fa-long-arrow-right"></i>
            </div>
        </div>

        <div class="service-card-body">
            <h3 class="service-card-title">
                <a href="#">Ground Transport</a>
            </h3>

            <p class="service-card-desc">
                Ground transportation options for all visitors, no matter your needs, schedule or destination.
            </p>

            <a href="#" class="service-read-more">
                Read More <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</div>

                    <!-- Worldwide Transport -->
                    <div class="item-service col-xs-12 col-sm-6 col-md-4">
                        <div class="service-card">
                            <div class="service-thumb-wrap">
                                <img src="{{ asset('website/images/services/serv-7.jpg') }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1616401784845-180882ba9ba8?auto=format&fit=crop&w=800&q=80';" alt="Worldwide Transport" class="service-thumb-img">
                                <div class="service-icon-badge">
                                    <i class="fa fa-long-arrow-right"></i>
                                </div>
                            </div>
                            <div class="service-card-body">
                                <h3 class="service-card-title"><a href="#">Worldwide Transport</a></h3>
                                <p class="service-card-desc">Specialises in international freight forwarding of merchandise and associated general and all logistic services.</p>
                                <a href="#" class="service-read-more">Read More <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Cargo Service -->
                    <div class="item-service col-xs-12 col-sm-6 col-md-4">
    <div class="service-card">
        <div class="service-thumb-wrap">
            <img 
                src="https://airborneinternational.in/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Finternational-cargo-services2.4f83509e.jpg&w=3840&q=75"
                alt="Cargo Service"
                class="service-thumb-img"
            >

            <div class="service-icon-badge">
                <i class="fa fa-long-arrow-right"></i>
            </div>
        </div>

        <div class="service-card-body">
            <h3 class="service-card-title">
                <a href="#">Cargo Service</a>
            </h3>

            <p class="service-card-desc">
                Delivery of any freight from one place to another place quickly to save your cost and save your valuable time.
            </p>

            <a href="#" class="service-read-more">
                Read More <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</div>

                    <!-- Packaging & Storage -->
                   <div class="item-service col-xs-12 col-sm-6 col-md-4">
    <div class="service-card">
        <div class="service-thumb-wrap">
            <img 
                src="https://5.imimg.com/data5/SELLER/Default/2021/6/QL/EW/XC/93185467/packaging-and-storage-500x500.jpeg"
                alt="Packaging & Storage"
                class="service-thumb-img"
            >

            <div class="service-icon-badge">
                <i class="fa fa-long-arrow-right"></i>
            </div>
        </div>

        <div class="service-card-body">
            <h3 class="service-card-title">
                <a href="#">Packaging & Storage</a>
            </h3>

            <p class="service-card-desc">
                Package and store your things effectively and securely to make sure them in storage, We guranteed for 100%.
            </p>

            <a href="#" class="service-read-more">
                Read More <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>

    <!-- 4. Features Highlights -->
    <section class="features-1 bluebg">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="feature-item-card">
                        <div class="fh-icon"><i class="flaticon-internet"></i></div>
                        <h4 class="box-title"><span>Fast worldwide delivery</span></h4>
                        <div class="desc">
                            <p>There are many variations of passages of available, but the majority have suffered alteration in some form, by or randomised slightly believable.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="feature-item-card">
                        <div class="fh-icon"><i class="flaticon-shield"></i></div>
                        <h4 class="box-title"><span>Safe and Secure Services</span></h4>
                        <div class="desc">
                            <p>There are many variations of passages of available, but the majority have suffered alteration in some form, by or randomised slightly believable.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="feature-item-card">
                        <div class="fh-icon"><i class="flaticon-technology"></i></div>
                        <h4 class="box-title"><span>24/7 customer support</span></h4>
                        <div class="desc">
                            <p>There are many variations of passages of available, but the majority have suffered alteration in some form, by or randomised slightly believable.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Complete Solution About Section -->
    <section class="aboutsec-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="about-img-frame">
                        <img src="{{asset('website/images/resources/about2.jpg')}}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80';" class="img-responsive" alt="The Complete Solution">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="abotinforgt">
                        <h2>The Complete Solution</h2>
                        <p>Our warehousing services are known nationwide to be one of the most reliable, safe and affordable, because we take pride in delivering the best of warehousing services, at the most reasonable prices.</p>
                        <p>Pleasure and praising pain was born and I will give you a complete account of system, and expound actual teachings occasionally circumstances.</p>
                        <a href="#" class="fh-btn">More About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Three Step Processing Section -->
    <section class="three_steps">
        <div class="container">
            <div class="fh-section-title">
                <h2>Three Step processing</h2>
            </div>
            <div class="row">
                <div class="col-sm-4 col-xs-12 mb-4">
                    <div class="step-card">
                        <div class="step-icon-circle"><i class="flaticon-box-1"></i></div>
                        <h4 class="step-title">Receive From Shipper</h4>
                        <p class="step-desc">Pursues or desires to obtain sed pain of it because it is pain circumstances.</p>
                    </div>
                </div>

                <div class="col-sm-4 col-xs-12 mb-4">
                    <div class="step-card">
                        <div class="step-icon-circle"><i class="flaticon-delivery-truck"></i></div>
                        <h4 class="step-title">Safe & Secure Shipment</h4>
                        <p class="step-desc">Except to obtain some advantage from it but who has any rights too find fault with enjoy.</p>
                    </div>
                </div>

                <div class="col-sm-4 col-xs-12 mb-4">
                    <div class="step-card">
                        <div class="step-icon-circle"><i class="flaticon-box-2"></i></div>
                        <h4 class="step-title">Handover to Receiver</h4>
                        <p class="step-desc">At vero eos et accusamus et iusto sed odio dignissimos ducimus ut blanditiis</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection