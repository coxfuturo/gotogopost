@extends('website.layouts.master')

@section('content')

<!-- Scoped Custom CSS for About Page -->
<style>
/* Page Header / Breadcrumb */
.about-page-header {
    background: linear-gradient(135deg, #0b132b 0%, #1e293b 100%) !important;
    padding: 35px 0 20px 0 !important;
    color: #ffffff;
    border-bottom: 3px solid #ff0000;
}

.about-page-header .breadcrumb {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
    font-size: 14px;
    font-weight: 500;
}

.about-page-header .breadcrumb a {
    color: #cbd5e1 !important;
    text-decoration: none !important;
    transition: color 0.2s ease;
}

.about-page-header .breadcrumb a:hover {
    color: #ff0000 !important;
}

.about-page-header .breadcrumb span.active-crumb {
    color: #ffffff !important;
    font-weight: 600;
}

.about-page-header .breadcrumb i {
    margin: 0 10px;
    color: #64748b;
}

/* About Intro Section */
.aboutsec-2 {
    padding: 70px 0 !important;
    background-color: #ffffff;
}

.abotimglft {
    position: relative;
}

.abotimglft img {
    border-radius: 16px !important;
    box-shadow: 0 14px 35px rgba(0, 0, 0, 0.12) !important;
    border: 1px solid rgba(0, 0, 0, 0.06);
    transition: transform 0.3s ease;
}

.abotimglft img:hover {
    transform: scale(1.02);
}

.abotinforgt .fh-section-title h2 {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 32px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    margin-bottom: 20px !important;
    position: relative;
    padding-bottom: 12px;
}


.abotinforgt .fh-section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 4px;
    background: #ff0000;
    border-radius: 2px;
}

.abotinforgt p {
    font-size: 15px !important;
    line-height: 1.8 !important;
    color: #475569 !important;
    margin-bottom: 20px !important;
}

/* Team Section */
.team-section {
    background-color: #f8fafc !important;
    padding: 75px 0 !important;
}

.team-section .section-title h2 {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 30px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    margin-bottom: 45px !important;
    text-transform: uppercase;
    text-align: center;
    position: relative;
    padding-bottom: 12px;
}

.team-section .section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: #ff0000;
    border-radius: 2px;
}

.team-card-wrap {
    margin-bottom: 30px;
}

.team-member {
    background: #ffffff !important;
    border-radius: 16px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06) !important;
    border: 1px solid rgba(0, 0, 0, 0.06) !important;
    padding: 30px 24px !important;
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.team-member:hover {
    transform: translateY(-6px) !important;
    box-shadow: 0 18px 40px rgba(11, 19, 43, 0.12) !important;
    border-top: 4px solid #ff0000 !important;
}

.team-img {
    margin-bottom: 20px;
}

.team-img img {
    width: 110px !important;
    height: 110px !important;
    object-fit: cover !important;
    border-radius: 50% !important;
    border: 4px solid #f1f5f9 !important;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1) !important;
    transition: transform 0.3s ease !important;
}

.team-member:hover .team-img img {
    transform: scale(1.05);
    border-color: #ff0000 !important;
}

.team-info h3 {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: #0b132b !important;
    margin: 0 0 6px 0 !important;
}

.designation {
    font-size: 14px !important;
    color: #ff0000 !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 14px !important;
    display: inline-block;
    background: rgba(255, 0, 0, 0.08);
    padding: 4px 14px;
    border-radius: 20px;
}

.team-info p {
    font-size: 14px !important;
    line-height: 1.6 !important;
    color: #64748b !important;
    margin: 0 !important;
}

/* Features Section (Mission, Vision, Core Values) */
.features-3.bluebg {
    background: linear-gradient(135deg, #0b132b 0%, #1e293b 100%) !important;
    padding: 65px 0 !important;
    color: #ffffff;
}

.fh-feature-box {
    background: rgba(255, 255, 255, 0.06) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 16px !important;
    padding: 32px 26px !important;
    position: relative;
    height: 100%;
    transition: transform 0.3s ease, background 0.3s ease !important;
}

.fh-feature-box:hover {
    transform: translateY(-5px) !important;
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 0, 0, 0.5) !important;
}

.fh-feature-box .chars {
    font-size: 36px !important;
    font-weight: 800 !important;
    color: #ff0000 !important;
    display: inline-block;
    width: 52px;
    height: 52px;
    line-height: 52px;
    background: rgba(255, 0, 0, 0.15);
    border-radius: 12px;
    text-align: center;
    margin-bottom: 18px;
}

.fh-feature-box .box-title {
    font-size: 20px !important;
    font-weight: 700 !important;
    color: #ffffff !important;
    margin-bottom: 12px !important;
}

.fh-feature-box .desc {
    font-size: 14px !important;
    line-height: 1.7 !important;
    color: #cbd5e1 !important;
}

/* Why Choose Us Section */
.whychoos-3.secpadd {
    padding: 85px 0 !important;
    background-color: #f8fafc;
}

.whychoos-3 .fh-section-title h2 {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 32px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    margin-bottom: 50px !important;
    position: relative;
    padding-bottom: 14px;
    text-align: center;
}

.whychoos-3 .fh-section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 65px;
    height: 4px;
    background: #ff0000;
    border-radius: 2px;
}

.whychoose-card-wrap {
    margin-bottom: 30px;
    display: flex;
}

.whychoos-3 .fh-icon-box.style-2 {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-top: 4px solid #e2e8f0 !important;
    border-radius: 16px !important;
    padding: 36px 28px !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03) !important;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.whychoos-3 .fh-icon-box.style-2:hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 18px 42px rgba(11, 19, 43, 0.1) !important;
    border-color: rgba(255, 0, 0, 0.3) !important;
    border-top-color: #ff0000 !important;
}

.whychoos-3 .fh-icon-box .fh-icon {
    width: 68px !important;
    height: 68px !important;
    border-radius: 50% !important;
    background: rgba(255, 0, 0, 0.06) !important;
    border: 1.5px solid rgba(255, 0, 0, 0.15) !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 22px !important;
    transition: all 0.35s ease !important;
}

.whychoos-3 .fh-icon-box .fh-icon i {
    font-size: 28px !important;
    color: #ff0000 !important;
    transition: all 0.35s ease !important;
}

.whychoos-3 .fh-icon-box.style-2:hover .fh-icon {
    background: #ff0000 !important;
    border-color: #ff0000 !important;
    transform: scale(1.08) rotate(4deg);
    box-shadow: 0 8px 20px rgba(255, 0, 0, 0.3) !important;
}

.whychoos-3 .fh-icon-box.style-2:hover .fh-icon i {
    color: #ffffff !important;
}

.whychoos-3 .fh-icon-box .box-title {
    margin-bottom: 14px !important;
}

.whychoos-3 .fh-icon-box .box-title span {
    font-size: 19px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    transition: color 0.3s ease !important;
}

.whychoos-3 .fh-icon-box.style-2:hover .box-title span {
    color: #ff0000 !important;
}

.whychoos-3 .fh-icon-box .desc p {
    font-size: 14px !important;
    line-height: 1.7 !important;
    color: #475569 !important;
    margin: 0 !important;
}

@media (max-width: 767px) {
    .aboutsec-2, .team-section, .features-3.bluebg, .whychoos-3.secpadd {
        padding: 45px 0 !important;
    }
    .abotimglft img {
        margin-bottom: 25px;
    }
}
</style>

<!--Professional Top Section Banner-->
<div class="site-section-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 col-sm-12 banner-text-col">
                <span class="banner-badge">GOTOGO POST</span>
                <h1 class="banner-title">About GOTOGO POST</h1>
                <nav class="banner-breadcrumb">
                    <a href="{{ route('website.index') }}">Home</a>
                    <i class="fa fa-angle-right"></i>
                    <span class="active-crumb">About Us</span>
                </nav>
            </div>
            <div class="col-md-5 col-sm-12 banner-img-col hidden-xs">
                <div class="banner-img-wrap">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=800&q=80" onerror="this.onerror=null;this.src='{{ asset('website/images/resources/r4.jpg') }}';" alt="About GOTOGO POST" class="banner-img">
                </div>
            </div>
        </div>
    </div>
</div>

<!--About us-->
<section class="aboutsec-2 secpaddbig">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="abotimglft">
                    <img src="{{asset('website/images/resources/r4.jpg')}}" alt="GOTOGO Post Logistics" class="img-responsive">
                </div>
            </div>

            <div class="col-md-8 col-sm-6">
                <div class="abotinforgt">
                    <div class="fh-section-title clearfix text-left version-dark paddbtm30">
                        <h2>GOTOGO Post</h2>
                    </div>

                    <p>Welcome to Gotogopost, where excellence meets convenience in the world of courier services. As a
                        leading platform, we specialize in providing seamless, reliable, and cost-effective shipping solutions
                        for businesses and individuals worldwide. With our extensive network of trusted partners, cutting-edge technology, and unwavering commitment to customer satisfaction, Gotogopost is your
                        ultimate destination for all your delivery needs. Experience the difference with Gotogopost today
                        and discover a new standard of excellence in courier services. Whether you're sending important
                        documents, parcels, or gifts to your loved ones, we've got you covered with our seamless and
                        hassle-free delivery solutions.</p>

                    <p>What sets us apart is our commitment to excellence in every aspect of our service. With our
                        network of trusted courier partners, state-of-the-art tracking technology, and dedicated customer
                        support team, we ensure that your packages are delivered safely and on time, every time.</p>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section">
    <div class="container">
        <div class="section-title">
            <h2>Meet Our Team</h2>
        </div>
          
        <div class="row">
            <div class="col-sm-4 team-card-wrap">
                <div class="team-member">
                    <div class="team-img">
                        <img src="{{ asset('website/images/resources/sagar.jpeg') }}" alt="Mr. R.K. Sagar">
                    </div>
                    <div class="team-info text-center">
                        <h3 class="text-center">Mr. R.K. Sagar</h3>
                        <p class="designation text-center">Managing Director</p>
                        <p class="text-center">A Managing Director leads the company, sets strategic direction, oversees operations, ensures profitability, guides teams, and drives long-term growth.</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 team-card-wrap">
                <div class="team-member">
                    <div class="team-img">
                        <img src="{{ asset('website/images/resources/rajender.png') }}" alt="Mr Jatinder Singh">
                    </div>
                    <div class="team-info text-center">
                        <h3 class="text-center">Mr Jatinder Singh</h3>
                        <p class="designation text-center">Director</p>
                        <p class="text-center">A Director sets strategic vision, oversees departments, guides leadership, makes key decisions, ensures goals are met, and drives organizational success.</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 team-card-wrap">
                <div class="team-member">
                    <div class="team-img">
                        <img src="{{ asset('website/images/resources/subhpal.png') }}" alt="Mr Sukh Pal Singh">
                    </div>
                    <div class="team-info text-center">
                        <h3 class="text-center">Mr Sukh Pal Singh</h3>
                        <p class="designation text-center">Director</p>
                        <p class="text-center">A Director sets strategic vision, oversees departments, guides leadership, makes key decisions, ensures goals are met, and drives organizational success.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--About us end-->

<!-- Feature sec -->
<section class="features-3 bluebg">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 mb-4">
                <div class="fh-feature-box">
                    <span class="chars">M</span>
                    <h4 class="box-title">Our Mission</h4>
                    <div class="desc">At Gotogopost, our mission is simple – to provide seamless, efficient, and affordable courier solutions for businesses and individuals alike.</div>
                </div>
            </div>

            <div class="col-sm-4 mb-4">
                <div class="fh-feature-box">
                    <span class="chars">V</span>
                    <h4 class="box-title">Our Vision</h4>
                    <div class="desc">We understand the importance of timely deliveries and the peace of mind that comes with knowing your package is in safe hands. That's why we go above and beyond to ensure that every delivery is handled with care and precision.</div>
                </div>
            </div>

            <div class="col-sm-4 mb-4">
                <div class="fh-feature-box">
                    <span class="chars">C</span>
                    <h4 class="box-title">Core Values</h4>
                    <div class="desc">Procedures, values and attitudes are crucial to our reputation – not to mention the success we enjoy.</div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Feature sec end -->

<!-- featured sec -->
<section class="whychoos-3 secpadd">
    <div class="container">
        <div class="fh-section-title clearfix text-center version-dark paddbtm30">
            <h2>Why Choose Us</h2>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6 whychoose-card-wrap">
                <div class="fh-icon-box style-2 version-dark icon-center">
                    <span class="fh-icon"><i class="flaticon-international-delivery"></i></span>
                    <h4 class="box-title"><span>Extensive Network</span></h4>
                    <div class="desc">
                        <p>We've partnered with a vast network of trusted courier companies to offer
                            you unparalleled coverage and reach. Whether you're sending a package across town or across the
                            country, we've got you covered. Our extensive network ensures that your package reaches its
                            destination safely and on time, every time.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 whychoose-card-wrap">
                <div class="fh-icon-box style-2 version-dark icon-center">
                    <span class="fh-icon"><i class="flaticon-people"></i></span>
                    <h4 class="box-title"><span>Advanced Tracking Technology</span></h4>
                    <div class="desc">
                        <p>With our state-of-the-art tracking technology, you can
                            monitor the progress of your delivery every step of the way. From pickup to final delivery, you'll
                            have real-time visibility into the whereabouts of your package, giving you peace of mind and
                            eliminating any guesswork.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 whychoose-card-wrap">
                <div class="fh-icon-box style-2 version-dark icon-center">
                    <span class="fh-icon"><i class="flaticon-route"></i></span>
                    <h4 class="box-title"><span>Customized Solutions</span></h4>
                    <div class="desc">
                        <p>We understand that every delivery is unique, which is why we offer
                            customized solutions tailored to your specific needs. Whether you require express delivery, special
                            handling instructions, or multiple drop-off points, our team is here to accommodate your
                            requirements and ensure a smooth delivery process.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-md-offset-2 whychoose-card-wrap">
                <div class="fh-icon-box style-2 version-dark icon-center">
                    <span class="fh-icon"><i class="flaticon-open-cardboard-box"></i></span>
                    <h4 class="box-title"><span>Transparent Pricing</span></h4>
                    <div class="desc">
                        <p>We believe in transparency and fairness when it comes to pricing. With
                            Gotogopost, you'll never have to worry about hidden fees or unexpected surcharges. Our pricing is
                            straightforward and competitive, allowing you to plan your shipping budget with confidence.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 whychoose-card-wrap">
                <div class="fh-icon-box style-2 version-dark icon-center">
                    <span class="fh-icon"><i class="flaticon-alarm-clock"></i></span>
                    <h4 class="box-title"><span>Dedicated Support</span></h4>
                    <div class="desc">
                        <p>: Have a question or need assistance with your delivery? Our dedicated
                            customer support team is available around the clock to assist you. Whether you prefer to reach us by
                            phone, email, or live chat, we're here to provide prompt and friendly support whenever you need it.</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<!-- featured sec end -->

@endSection
