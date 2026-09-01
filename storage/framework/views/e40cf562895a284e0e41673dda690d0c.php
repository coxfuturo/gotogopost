

<?php $__env->startSection('content'); ?>

<!-- Scoped Custom CSS for About Page -->
<style>
/* ==========================================================================
   ABOUT PAGE SCOPED REDESIGN STYLES
   All rules strictly wrapped inside .about-page-wrapper to guarantee zero global leak
   ========================================================================== */

.about-page-wrapper {
    font-family: 'Montserrat', sans-serif;
    color: #1e293b;
    background-color: #f8fafc;
    overflow-x: hidden;
}

/* 1. Top Hero Banner */
.about-page-wrapper .about-page-top-hero {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
}

.about-page-wrapper .about-hero-bg {
    position: relative;
    width: 100%;
    height: 100%;
}

.about-page-wrapper .about-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 35%;
    filter: brightness(0.85) contrast(1.05);
    transition: transform 0.6s ease;
}

.about-page-wrapper .about-page-top-hero:hover .about-hero-img {
    transform: scale(1.02);
}

.about-page-wrapper .about-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(11, 19, 43, 0.4) 0%, rgba(11, 19, 43, 0.8) 100%);
    display: flex;
    align-items: center;
}

.about-page-wrapper .about-hero-content {
    color: #ffffff;
}

.about-page-wrapper .about-hero-badge {
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

.about-page-wrapper .about-hero-badge i {
    color: #d9251d;
}

.about-page-wrapper .about-hero-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}

.about-page-wrapper .about-hero-breadcrumb {
    font-size: 14px;
    font-weight: 500;
    color: #cbd5e1;
}

.about-page-wrapper .about-hero-breadcrumb a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.2s ease;
}

.about-page-wrapper .about-hero-breadcrumb a:hover {
    color: #d9251d;
}

.about-page-wrapper .about-hero-breadcrumb i {
    margin: 0 8px;
    color: #94a3b8;
}

.about-page-wrapper .about-hero-breadcrumb .active-crumb {
    color: #d9251d;
    font-weight: 700;
}

/* 2. Main About Section */
.about-page-wrapper .about-intro-section {
    padding: 80px 0;
    background-color: #ffffff;
}

.about-page-wrapper .about-intro-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 35px rgba(15, 23, 42, 0.05);
    border: 1px solid #e2e8f0;
}

.about-page-wrapper .about-img-frame {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
    border: 3px solid #ffffff;
}

.about-page-wrapper .about-img-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}

.about-page-wrapper .about-img-frame:hover img {
    transform: scale(1.03);
}

.about-page-wrapper .about-text-content h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 18px;
    position: relative;
    padding-bottom: 12px;
}

.about-page-wrapper .about-text-content h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 55px;
    height: 4px;
    background: #d9251d;
    border-radius: 2px;
}

.about-page-wrapper .about-text-content p {
    font-size: 15.5px;
    line-height: 1.8;
    color: #475569;
    margin-bottom: 18px;
}

.about-page-wrapper .about-text-content p:last-child {
    margin-bottom: 0;
}

/* 3. Team Section */
.about-page-wrapper .about-team-section {
    padding: 85px 0;
    background-color: #f8fafc;
}

.about-page-wrapper .section-header-center {
    text-align: center;
    margin-bottom: 50px;
}

.about-page-wrapper .section-header-center h2 {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    position: relative;
    padding-bottom: 14px;
    margin-bottom: 10px;
    text-transform: uppercase;
}

.about-page-wrapper .section-header-center h2::after {
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

.about-page-wrapper .team-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 36px 26px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.about-page-wrapper .team-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: transparent;
    transition: background 0.3s ease;
}

.about-page-wrapper .team-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 22px 45px rgba(15, 23, 42, 0.1);
    border-color: rgba(217, 37, 29, 0.25);
}

.about-page-wrapper .team-card:hover::before {
    background: #d9251d;
}

.about-page-wrapper .team-avatar-wrap {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin-bottom: 22px;
    padding: 5px;
    background: #ffffff;
    border: 3px solid #e2e8f0;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.35s ease;
}

.about-page-wrapper .team-card:hover .team-avatar-wrap {
    border-color: #d9251d;
    transform: scale(1.05);
}

.about-page-wrapper .team-avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.about-page-wrapper .team-name {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 8px 0;
}

.about-page-wrapper .team-designation {
    font-size: 13px;
    font-weight: 700;
    color: #d9251d;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    background: #fff5f5;
    border: 1px solid rgba(217, 37, 29, 0.15);
    padding: 5px 16px;
    border-radius: 50px;
    display: inline-block;
    margin-bottom: 16px;
}

.about-page-wrapper .team-bio {
    font-size: 14px;
    line-height: 1.65;
    color: #64748b;
    margin: 0;
}

/* 4. Features Section (Mission, Vision, Core Values) */
.about-page-wrapper .about-features-section {
    background: linear-gradient(135deg, #0b132b 0%, #0f172a 60%, #1e293b 100%);
    padding: 75px 0;
    color: #ffffff;
}

.about-page-wrapper .feature-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    padding: 34px 28px;
    height: 100%;
    transition: all 0.35s ease;
}

.about-page-wrapper .feature-card:hover {
    transform: translateY(-6px);
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(217, 37, 29, 0.5);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

.about-page-wrapper .feature-icon-badge {
    width: 54px;
    height: 54px;
    background: rgba(217, 37, 29, 0.2);
    border: 1.5px solid rgba(217, 37, 29, 0.4);
    color: #ffffff;
    font-size: 22px;
    font-weight: 800;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.about-page-wrapper .feature-card:hover .feature-icon-badge {
    background: #d9251d;
    border-color: #d9251d;
    transform: scale(1.08);
}

.about-page-wrapper .feature-title {
    font-size: 21px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 14px;
}

.about-page-wrapper .feature-desc {
    font-size: 14.5px;
    line-height: 1.7;
    color: #cbd5e1;
    margin: 0;
}

/* 5. Why Choose Us Section */
.about-page-wrapper .about-whychoose-section {
    padding: 85px 0;
    background-color: #ffffff;
}

.about-page-wrapper .whychoose-card {
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

.about-page-wrapper .whychoose-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: transparent;
    transition: background 0.3s ease;
}

.about-page-wrapper .whychoose-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 42px rgba(15, 23, 42, 0.1);
    border-color: rgba(217, 37, 29, 0.25);
}

.about-page-wrapper .whychoose-card:hover::before {
    background: #d9251d;
}

.about-page-wrapper .whychoose-icon-circle {
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

.about-page-wrapper .whychoose-icon-circle i {
    font-size: 30px;
    color: #d9251d;
    transition: all 0.35s ease;
}

.about-page-wrapper .whychoose-card:hover .whychoose-icon-circle {
    background: #d9251d;
    border-color: #d9251d;
    transform: scale(1.08) rotate(4deg);
    box-shadow: 0 8px 20px rgba(217, 37, 29, 0.35);
}

.about-page-wrapper .whychoose-card:hover .whychoose-icon-circle i {
    color: #ffffff;
}

.about-page-wrapper .whychoose-title {
    font-size: 19px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
    transition: color 0.3s ease;
}

.about-page-wrapper .whychoose-card:hover .whychoose-title {
    color: #d9251d;
}

.about-page-wrapper .whychoose-desc {
    font-size: 14px;
    line-height: 1.7;
    color: #64748b;
    margin: 0;
}

/* Responsive Rules */
@media (max-width: 991px) {
    .about-page-wrapper .about-page-top-hero {
        height: 240px;
    }
    .about-page-wrapper .about-hero-title {
        font-size: 26px;
    }
    .about-page-wrapper .about-intro-section,
    .about-page-wrapper .about-team-section,
    .about-page-wrapper .about-features-section,
    .about-page-wrapper .about-whychoose-section {
        padding: 60px 0;
    }
    .about-page-wrapper .about-text-content h2,
    .about-page-wrapper .section-header-center h2 {
        font-size: 26px;
    }
}

@media (max-width: 576px) {
    .about-page-wrapper .about-page-top-hero {
        height: 200px;
    }
    .about-page-wrapper .about-hero-title {
        font-size: 22px;
    }
    .about-page-wrapper .about-intro-section,
    .about-page-wrapper .about-team-section,
    .about-page-wrapper .about-features-section,
    .about-page-wrapper .about-whychoose-section {
        padding: 45px 0;
    }
    .about-page-wrapper .about-intro-card {
        padding: 24px 18px;
    }
    .about-page-wrapper .team-card,
    .about-page-wrapper .whychoose-card,
    .about-page-wrapper .feature-card {
        padding: 26px 20px;
    }
}
</style>

<div class="about-page-wrapper">

    <!-- 1. Top Section Banner (Exclusive to About Page) -->
    <div class="about-page-top-hero">
        <div class="about-hero-bg">
            <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=2000&q=85" alt="GOTOGO POST About Banner" class="about-hero-img">
            <div class="about-hero-overlay">
                <div class="container">
                    <div class="about-hero-content">
                        <span class="about-hero-badge"><i class="fa fa-building"></i> GOTOGO POST</span>
                        <h1 class="about-hero-title">About GOTOGO POST</h1>
                        <nav class="about-hero-breadcrumb">
                            <a href="<?php echo e(route('website.index')); ?>">Home</a>
                            <i class="fa fa-angle-right"></i>
                            <span class="active-crumb">About Us</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. About Main Section -->
    <section class="about-intro-section">
        <div class="container">
            <div class="about-intro-card">
                <div class="row align-items-center">
                    <div class="col-md-5 col-sm-12 mb-4 mb-md-0">
                        <div class="about-img-frame">
                            <img src="<?php echo e(asset('website/images/resources/r4.jpg')); ?>" alt="GOTOGO Post Logistics" class="img-responsive">
                        </div>
                    </div>

                    <div class="col-md-7 col-sm-12">
                        <div class="about-text-content">
                            <h2>GOTOGO Post</h2>
                            <p>Welcome to Gotogopost, where excellence meets convenience in the world of courier services. As a leading platform, we specialize in providing seamless, reliable, and cost-effective shipping solutions for businesses and individuals worldwide. With our extensive network of trusted partners, cutting-edge technology, and unwavering commitment to customer satisfaction, Gotogopost is your ultimate destination for all your delivery needs. Experience the difference with Gotogopost today and discover a new standard of excellence in courier services. Whether you're sending important documents, parcels, or gifts to your loved ones, we've got you covered with our seamless and hassle-free delivery solutions.</p>

                            <p>What sets us apart is our commitment to excellence in every aspect of our service. With our network of trusted courier partners, state-of-the-art tracking technology, and dedicated customer support team, we ensure that your packages are delivered safely and on time, every time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Team Section -->
    <section class="about-team-section">
        <div class="container">
            <div class="section-header-center">
                <h2>Meet Our Team</h2>
            </div>
              
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar-wrap">
                            <img src="<?php echo e(asset('website/images/resources/sagar.jpeg')); ?>" alt="Mr. R.K. Sagar" class="team-avatar-img">
                        </div>
                        <h3 class="team-name">Mr. R.K. Sagar</h3>
                        <span class="team-designation">Managing Director</span>
                        <p class="team-bio">A Managing Director leads the company, sets strategic direction, oversees operations, ensures profitability, guides teams, and drives long-term growth.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar-wrap">
                            <img src="<?php echo e(asset('website/images/resources/rajender.png')); ?>" alt="Mr Jatinder Singh" class="team-avatar-img">
                        </div>
                        <h3 class="team-name">Mr Jatinder Singh</h3>
                        <span class="team-designation">Director</span>
                        <p class="team-bio">A Director sets strategic vision, oversees departments, guides leadership, makes key decisions, ensures goals are met, and drives organizational success.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="team-card">
                        <div class="team-avatar-wrap">
                            <img src="<?php echo e(asset('website/images/resources/subhpal.png')); ?>" alt="Mr Sukh Pal Singh" class="team-avatar-img">
                        </div>
                        <h3 class="team-name">Mr Sukh Pal Singh</h3>
                        <span class="team-designation">Director</span>
                        <p class="team-bio">A Director sets strategic vision, oversees departments, guides leadership, makes key decisions, ensures goals are met, and drives organizational success.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Features Section (Mission, Vision, Core Values) -->
    <section class="about-features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-badge">M</div>
                        <h4 class="feature-title">Our Mission</h4>
                        <p class="feature-desc">At Gotogopost, our mission is simple – to provide seamless, efficient, and affordable courier solutions for businesses and individuals alike.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-badge">V</div>
                        <h4 class="feature-title">Our Vision</h4>
                        <p class="feature-desc">We understand the importance of timely deliveries and the peace of mind that comes with knowing your package is in safe hands. That's why we go above and beyond to ensure that every delivery is handled with care and precision.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-badge">C</div>
                        <h4 class="feature-title">Core Values</h4>
                        <p class="feature-desc">Procedures, values and attitudes are crucial to our reputation – not to mention the success we enjoy.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Why Choose Us Section -->
    <section class="about-whychoose-section">
        <div class="container">
            <div class="section-header-center">
                <h2>Why Choose Us</h2>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="whychoose-card">
                        <div class="whychoose-icon-circle">
                            <i class="flaticon-international-delivery"></i>
                        </div>
                        <h4 class="whychoose-title">Extensive Network</h4>
                        <p class="whychoose-desc">We've partnered with a vast network of trusted courier companies to offer you unparalleled coverage and reach. Whether you're sending a package across town or across the country, we've got you covered. Our extensive network ensures that your package reaches its destination safely and on time, every time.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="whychoose-card">
                        <div class="whychoose-icon-circle">
                            <i class="flaticon-people"></i>
                        </div>
                        <h4 class="whychoose-title">Advanced Tracking Technology</h4>
                        <p class="whychoose-desc">With our state-of-the-art tracking technology, you can monitor the progress of your delivery every step of the way. From pickup to final delivery, you'll have real-time visibility into the whereabouts of your package, giving you peace of mind and eliminating any guesswork.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="whychoose-card">
                        <div class="whychoose-icon-circle">
                            <i class="flaticon-route"></i>
                        </div>
                        <h4 class="whychoose-title">Customized Solutions</h4>
                        <p class="whychoose-desc">We understand that every delivery is unique, which is why we offer customized solutions tailored to your specific needs. Whether you require express delivery, special handling instructions, or multiple drop-off points, our team is here to accommodate your requirements and ensure a smooth delivery process.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4 offset-md-2">
                    <div class="whychoose-card">
                        <div class="whychoose-icon-circle">
                            <i class="flaticon-open-cardboard-box"></i>
                        </div>
                        <h4 class="whychoose-title">Transparent Pricing</h4>
                        <p class="whychoose-desc">We believe in transparency and fairness when it comes to pricing. With Gotogopost, you'll never have to worry about hidden fees or unexpected surcharges. Our pricing is straightforward and competitive, allowing you to plan your shipping budget with confidence.</p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="whychoose-card">
                        <div class="whychoose-icon-circle">
                            <i class="flaticon-alarm-clock"></i>
                        </div>
                        <h4 class="whychoose-title">Dedicated Support</h4>
                        <p class="whychoose-desc">Have a question or need assistance with your delivery? Our dedicated customer support team is available around the clock to assist you. Whether you prefer to reach us by phone, email, or live chat, we're here to provide prompt and friendly support whenever you need it.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\coxfuturetech\gotogopost\resources\views/website/about.blade.php ENDPATH**/ ?>