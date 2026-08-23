@extends('website.layouts.master')



@section('content')

<!--Page Header-->

<style>
   .team-section {
    background-color: #f9f9f9;
    padding: 60px 0;
    text-align: center;
}

.section-title h2 {
    font-size: 32px;
    font-weight: bold;
    color: #333;
    margin-bottom: 40px;
    text-transform: uppercase;
}

.team-container {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.team-member {
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    max-width: 900px;
    margin: 0 auto;
    transition: transform 0.3s ease-in-out;
}

.team-member:hover {
    transform: scale(1.05);
}

.team-img img {
    width: 250px;
    height: 250px;
    object-fit: cover;
    border-radius: 10px;
}

.team-info {
    padding: 30px;
    text-align: left;
    flex: 1;
}

.team-info h3 {
    font-size: 22px;
    font-weight: 700;
    color: #222;
    margin-bottom: 5px;
}

.designation {
    font-size: 16px;
    color: #007bff;
    font-weight: 600;
    margin-bottom: 10px;
}

.team-info p {
    font-size: 14px;
    color: #555;
}

/* Reverse the layout for alternate rows */
.reverse {
    flex-direction: row-reverse;
}

/* Responsive Design */
@media (max-width: 768px) {
    .team-member, .reverse {
        flex-direction: column;
        text-align: center;
    }

    .team-img img {
        width: 100%;
        height: auto;
        border-radius: 10px 10px 0 0;
    }

    .team-info {
        text-align: center;
    }
}



</style>
<div class="page-header title-area">

    <div class="header-title">

        <div class="container">

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">

                    <h1 class="page-title">About Us</h1>

                </div>

            </div>

        </div>

    </div>

    <div class="breadcrumb-area">

        <div class="container">

            <div class="row">

                <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">

                    <nav class="breadcrumb">

                        <a class="home" href="https://gotogopost.com/"><span>Home</span></a>

                        <i class="fa fa-angle-right" aria-hidden="true"></i>

                        <span>About Us</span>

                    </nav>

                </div>

            </div>

        </div>

    </div>

</div>

<!--Page Header end-->



<!--About us-->

<section class="aboutsec-2 secpaddbig">

    <div class="container">

        <div class="row">

            <div class="col-md-4 col-sm-6">

                <div class="abotimglft">

                    <!-- <img src="{{asset('website/images/resources/cargocar.png')}}" class="img-responsive"> -->
                    {{-- <video width="100%" height="100%" controls autoplay muted>
                       <source src="{{asset('website/video/WhatsApp Video 2024-07-09 at 13.28.59.mp4')}}" type="video/mp4">
                         Your browser does not support the video tag.
                   </video> --}}
                   <img src="{{asset('website/images/resources/r4.jpg')}}" alt=""  class="img-responsive" style="padding-top: 110px; width:500px;">
                   
                </div>

            </div>

            <div class="col-md-8 col-sm-6">

                <div class="abotinforgt">

                    <div class="fh-section-title clearfix  text-left version-dark paddbtm30">

                        <h2>GOTOGO Post</h2>

                    </div>

                    <p>Welcome to Gotogopost, where excellence meets convenience in the world of courier services. As a
                        leading platform, we specialize in providing seamless, reliable, and cost-effective shipping solutions
                        for businesses and individuals worldwide. With our extensive network of trusted partners, cuttingedge technology, and unwavering commitment to customer satisfaction, Gotogopost is your
                        ultimate destination for all your delivery needs. Experience the difference with Gotogopost today
                        and discover a new standard of excellence in courier services. Whether you're sending important
                        documents, parcels, or gifts to your loved ones, we've got you covered with our seamless and
                        hassle-free delivery solutions.</p>

                    <p>What sets us apart is our commitment to excellence in every aspect of our service. With our
                        network of trusted courier partners, state-of-the-art tracking technology, and dedicated customer
                        support team, we ensure that your packages are delivered safely and on time, every time.</p>

                    <img class="paddtop20" src="images/icon/signature-1.png" alt="" />

                </div>

            </div>

        </div>

    </div>

</section>

<section class="team-section">
    <div class="container">
        <div class="section-title">
            <h2>Meet Our Team</h2>
        </div>

        <div class="team-container">
            <!-- Team Member 1 (Image Left, Content Right) -->
            <div class="team-member">
                <div class="team-img">
                    <img style="display:none" src="{{ asset('website/images/resources/sagar.jpeg') }}" alt="Rajesh Kumar Sagar">
                </div>
                <div class="team-info">
                    <h3>Rajesh Kumar Sagar</h3>
                    <p class="designation">Director</p>
                    <p>Rajesh Kumar Sagar is an experienced logistics professional dedicated to ensuring smooth operations and customer satisfaction.</p>
                </div>
            </div>

            <!-- Team Member 2 (Image Right, Content Left) -->
            <div class="team-member reverse">
                <div class="team-info">
                    <h3>Jatinder</h3>
                    <p class="designation">Director</p>
                    <p>With years of expertise in logistics and management, Jatinder is committed to providing top-notch courier solutions.</p>
                </div>
                <div class="team-img">
                    <img style="display:none" src="{{ asset('website/images/resources/partner.jpg') }}" alt="Jatinder">
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

            <div class="col-sm-4">

                <div class="fh-feature-box "><span class="chars">M</span>

                    <h4 class="box-title">Our Mission</h4>

                    <div class="desc">At Gotogopost, our mission is simple – to provide seamless, efficient, and affordable
                        courier solutions for businesses and individuals alike.</div>

                </div>

            </div>

            <div class="col-sm-4">

                <div class="fh-feature-box "><span class="chars">V</span>

                    <h4 class="box-title">Our Vision</h4>

                    <div class="desc">We understand the importance of timely
                        deliveries and the peace of mind that comes with knowing your package is in safe hands.That's
                        why we go above and beyond to ensure that every delivery is handled with care and precision.</div>

                </div>

            </div>

            <div class="col-sm-4">

                <div class="fh-feature-box "><span class="chars">C</span>

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

        <div class="fh-section-title clearfix  text-center version-dark paddbtm30">

            <h2>Why Choose Us</h2>

        </div>

        <div class="row">

            <div class="col-md-4 col-sm-6">

                <div class="fh-icon-box  style-2 version-dark  icon-center">

                    <span class="fh-icon"><i class="flaticon-international-delivery"></i></span>

                    <h4 class="box-title"><span>Extensive Network</span></h4>

                    <div class="desc">

                        <p>We've partnered with a vast network of trusted courier companies to offer
                            you unparalleled coverage and reach. Whether you're sending a package across town or across the
                            country, we've got you covered. Our extensive network ensures that your package reaches its
                            destination safely and on time, every time.
                            </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4 col-sm-6">

                <div class="fh-icon-box  style-2 version-dark  icon-center">

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

            <div class="col-md-4 col-sm-6">

                <div class="fh-icon-box  style-2 version-dark  icon-center">

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

            <div class="col-md-4 col-sm-6 col-md-offset-2">

                <div class="fh-icon-box  style-2 version-dark  icon-center">

                    <span class="fh-icon"><i class="flaticon-open-cardboard-box"></i></span>

                    <h4 class="box-title"><span>Transparent Pricing</span></h4>

                    <div class="desc">

                        <p>We believe in transparency and fairness when it comes to pricing. With
                            Gotogopost, you'll never have to worry about hidden fees or unexpected surcharges. Our pricing is
                            straightforward and competitive, allowing you to plan your shipping budget with confidence.
                            </p>

                    </div>

                </div>

            </div>

            <div class="col-md-4 col-sm-6">

                <div class="fh-icon-box  style-2 version-dark  icon-center">

                    <span class="fh-icon"><i class="flaticon-alarm-clock"></i></span>

                    <h4 class="box-title"><span>Dedicated Support</span></h4>

                    <div class="desc">

                        <p>: Have a question or need assistance with your delivery? Our dedicated
                            customer support team is available around the clock to assist you. Whether you prefer to reach us by
                            phone, email, or live chat, we're here to provide prompt and friendly support whenever you need it.
                            </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- featured sec end -->



<!--Our Team section-->
@endSection