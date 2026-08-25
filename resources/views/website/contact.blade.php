@extends('website.layouts.master')
@section('content')

<!-- Scoped Custom CSS for Contact Page -->
<style>
/* Contact Banner */
.ban_sec {
    width: 100%;
    margin-top: 0;
    overflow: hidden;
}

.contact-banner {
    width: 100%;
    height: 380px;
    object-fit: cover;
    object-position: center;
    filter: brightness(0.9);
}

/* Contact Breadcrumb Header */
.contact-page-header {
    background: linear-gradient(135deg, #0b132b 0%, #1e293b 100%) !important;
    padding: 24px 0 !important;
    color: #ffffff;
    border-bottom: 3px solid #ff0000;
}

.contact-page-header .breadcrumb {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
    font-size: 14px;
}

.contact-page-header .breadcrumb a {
    color: #cbd5e1 !important;
    text-decoration: none !important;
}

.contact-page-header .breadcrumb a:hover {
    color: #ff0000 !important;
}

.contact-page-header .breadcrumb span.active-crumb {
    color: #ffffff !important;
    font-weight: 600;
}

.contact-page-header .breadcrumb i {
    margin: 0 10px;
    color: #64748b;
}

/* Contact Details Section */
.contactpagesec {
    padding: 65px 0 !important;
    background-color: #ffffff;
}

.contactpagesec .fh-section-title h2 {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    margin-bottom: 16px !important;
    position: relative;
    padding-bottom: 10px;
}

.contactpagesec .fh-section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 45px;
    height: 4px;
    background: #ff0000;
    border-radius: 2px;
}

.contactpagesec p.margbtm30 {
    font-size: 15px !important;
    color: #64748b !important;
    line-height: 1.7 !important;
    margin-bottom: 35px !important;
}

/* Contact Cards */
.fh-contact-box {
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 24px 20px !important;
    margin-bottom: 24px !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
    transition: all 0.25s ease !important;
}

.fh-contact-box:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08) !important;
    border-color: #ff0000 !important;
}

.fh-contact-box i.flaticon-pin,
.fh-contact-box i.flaticon-business,
.fh-contact-box i.flaticon-phone-call,
.fh-contact-box i.flaticon-share {
    font-size: 24px !important;
    color: #ff0000 !important;
    margin-bottom: 10px !important;
    display: inline-block;
}

.fh-contact-box .box-title {
    font-size: 17px !important;
    font-weight: 700 !important;
    color: #0b132b !important;
    margin-bottom: 8px !important;
}

.fh-contact-box .desc p {
    font-size: 14px !important;
    color: #475569 !important;
    margin: 0 !important;
    line-height: 1.6 !important;
}

.fh-contact-box.type-social ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 12px 0 0 0 !important;
    display: flex !important;
    gap: 10px !important;
}

.fh-contact-box.type-social ul li a {
    width: 36px !important;
    height: 36px !important;
    border-radius: 50% !important;
    background: #0b132b !important;
    color: #ffffff !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-decoration: none !important;
    font-size: 14px !important;
    transition: all 0.25s ease !important;
}

.fh-contact-box.type-social ul li a:hover {
    background: #ff0000 !important;
    transform: translateY(-2px) !important;
}

/* Contact Form & Map Section */
.contactpagform {
    padding: 65px 0 !important;
    background-color: #f8fafc !important;
    border-top: 1px solid #e2e8f0;
}

.contactpagform .fh-section-title h2 {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    margin-bottom: 12px !important;
    text-align: center;
    position: relative;
    padding-bottom: 10px;
}

.contactpagform .fh-section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 4px;
    background: #ff0000;
    border-radius: 2px;
}

.contact-form-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 35px 30px !important;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05) !important;
    border: 1px solid #e2e8f0 !important;
}

.contact-form-card .form-control {
    height: 48px !important;
    border-radius: 8px !important;
    border: 1px solid #cbd5e1 !important;
    padding: 0 16px !important;
    font-size: 14px !important;
    color: #1e293b !important;
    box-shadow: none !important;
    transition: all 0.2s ease !important;
}

.contact-form-card textarea.form-control {
    height: 140px !important;
    padding: 14px 16px !important;
}

.contact-form-card .form-control:focus {
    border-color: #ff0000 !important;
    box-shadow: 0 0 0 3px rgba(255, 0, 0, 0.1) !important;
}

.contact-form-card button[type="submit"] {
    background: #ff0000 !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 15px !important;
    padding: 12px 36px !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 6px 18px rgba(255, 0, 0, 0.25) !important;
}

.contact-form-card button[type="submit"]:hover {
    background: #cc0000 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 24px rgba(255, 0, 0, 0.35) !important;
}

.map-container {
    height: 100% !important;
    min-height: 400px;
    border-radius: 16px !important;
    overflow: hidden !important;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05) !important;
    border: 1px solid #e2e8f0 !important;
}

.map-container iframe {
    width: 100% !important;
    height: 100% !important;
    min-height: 420px;
}

@media (max-width: 991px) {
    .map-container {
        margin-top: 30px;
        min-height: 350px;
    }
}
</style>

<section class="ban_sec">
    <div class="ban_img">
        <img class="contact-banner" src="{{asset('website/images/contactBanner2.jpg')}}" alt="banner" />
    </div>
</section>

<!--Page Header-->
<div class="contact-page-header title-area">
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">
                    <nav class="breadcrumb">
                        <a class="home" href="{{ route('website.index') }}"><span>Home</span></a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span class="active-crumb">Contact</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Page Header end-->

<!--contact pagesec-->
<section class="contactpagesec secpadd">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="fh-section-title clearfix f25 text-left version-dark paddbtm40">
                    <h2>Contact Details</h2>
                </div>
                <p class="margbtm30">If you have any questions about what we offer for consumers or for business, you
                    can always email us or call us via the below details. We’ll reply within 24 hours.</p>
                
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="fh-contact-box type-address">
                            <i class="flaticon-pin"></i>
                            <h4 class="box-title">Visit our office</h4>
                            <div class="desc">
                                <p>Gaur City Mall Office Space - Sector 4, Greater Noida Uttar Pradesh 201318</p>
                            </div>
                        </div>
                        <div class="fh-contact-box type-email">
                            <i class="flaticon-business"></i>
                            <h4 class="box-title">Mail Us at</h4>
                            <div class="desc">
                                <p>corp.office@gotogopost.in</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="fh-contact-box type-phone">
                            <i class="flaticon-phone-call"></i>
                            <h4 class="box-title">Call us on</h4>
                            <div class="desc">
                                <p>1800 123 1617</p>
                            </div>
                        </div>

                        <div class="fh-contact-box type-social">
                            <i class="flaticon-share"></i>
                            <h4 class="box-title">We are social</h4>
                            <ul class="clearfix">
                                <li class="facebook">
                                    <a href="https://www.facebook.com/share/BVJeAPEcHNBwCnN9/?mibextid=qi2Omg" target="_blank" title="Facebook">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                <li class="twitter">
                                    <a href="https://www.instagram.com/gotogopost/?utm_source=qr&igsh=MWQ3aDV6NGlkNmZvZg%3D%3D" target="_blank" title="Instagram">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                                <li class="googleplus">
                                    <a href="https://www.youtube.com/channel/UCkLQWa6_bv4CnuQZqdh-l4g" target="_blank" title="YouTube">
                                        <i class="fa fa-youtube"></i>
                                    </a>
                                </li>
                                <li class="pinterest">
                                    <a href="https://wa.me/+919810657990" target="_blank" title="WhatsApp">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--contact end-->

<!--contact form -->
<section class="contactpagform graybg secpadd">
    <div class="container">
        <div class="fh-section-title clearfix f25 text-center version-dark paddbtm40">
            <h2>Leave Your Message</h2>
        </div>

        <p class="paddbtm40 text-center" style="font-size: 15px; color: #64748b;">Any Question About What we offer for Consumers And for Business?<br>Please Email us or call at below given details. We will be Glad to reply within 24 hours.</p>

        <div class="row">
            <!-- Left Side: Form -->
            <div class="col-lg-6">
                <div class="contact-form-card">
                    <form method="post" action="{{ route('websiteMessage.store') }}" id="contact-form">
                        @csrf
                        <div class="fh-form fh-form-3">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <p class="field">
                                        <input name="name" placeholder="Your Name*" type="text" class="form-control" required>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="field">
                                        <input name="email" placeholder="Email Address*" type="email" class="form-control" required>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="field">
                                        <input name="phone" placeholder="Phone" type="text" class="form-control">
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="field">
                                        <select name="option" class="form-control">
                                            <option value="">Please choose an option</option>
                                            <option value="PPH">Prime Processing Hub(PPH)</option>
                                            <option value="CPH">Collection Processing Hub(CPH)</option>
                                            <option value="DELIVERY">DELIVERY/PICKUP BOY</option>
                                            <option value="FRANCHISE">FRANCHISE</option>
                                        </select>
                                    </p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <p class="field">
                                        <textarea name="message" cols="40" rows="5" placeholder="Your Message..." class="form-control" required></textarea>
                                    </p>
                                </div>
                                <div class="col-md-12 text-left">
                                    <p class="field submit" style="margin-bottom: 0;">
                                        <button class="fh-btn btn btn-primary" type="submit">Submit</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side: Google Map -->
            <div class="col-lg-6">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d112089.35736709561!2d77.34723109193371!3d28.606003593214258!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1742545950106!5m2!1sen!2sin" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<!--contact form end -->

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $("#contact-form").submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('websiteMessage.store') }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your form has been submitted successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    $("#contact-form")[0].reset();
                },
                error: function(jqXHR) {
                    let errorMessage = "Something went wrong! Please try again.";

                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        errorMessage = Object.values(jqXHR.responseJSON.errors).flat().join("\n");
                    } else if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    }

                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection
    