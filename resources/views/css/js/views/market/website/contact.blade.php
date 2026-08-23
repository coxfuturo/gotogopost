@extends('website.layouts.master')
@section('content')
<style>
    .ban_sec {
        width: 100%;
        margin-top: 88px;
    }

    .ban_img {
        width: 100%;
        position: relative;
    }

    .ban_img img {
        width: 100%;
    }

    .contact-banner {
        max-width: 100%;
        height: auto;
        height: 420px;
    }

    @media only screen and (max-width: 778px) {

        .contact-banner {
            height: auto;
        }
    }
</style>


<section class="ban_sec">

    <div class="ban_img">
        <img class="contact-banner" src="{{asset('website/images/contactBanner2.jpg')}}" alt="banner" border="0" />
    </div>

</section>

<!--Page Header-->
<div class="page-header title-area">
    {{-- <div class="header-title">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h1 class="page-title">Contact</h1>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">
                    <nav class="breadcrumb">
                        <a class="home" href="#"><span>Home</span></a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span>Contact</span>
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
            <div class="col-md-8">
                <div class="fh-section-title clearfix f25 text-left version-dark paddbtm40">
                    <h2>Contact Details</h2>
                </div>
                <p class="margbtm30">If you have any questions about what we offer for consumers or for business, you
                    can always email us or call us via the below details. We’ll reply within 24 hours.</p>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="fh-contact-box type-address "><i class="flaticon-pin"></i>
                            <h4 class="box-title">Visit our office</h4>
                            <div class="desc">
                                <p>
                                    <!-- Office No. 955,9th Floor,Gaur City Mall CO1,BHG,Sec-4,Greater  -->  
                                  Noida West,Gautam Bddha Nagar,(U.P)-201318</p>
                            </div>
                        </div>
                        <div class="fh-contact-box type-email "><i class="flaticon-business"></i>
                            <h4 class="box-title">Mail Us at</h4>
                            <div class="desc">
                             <p>corp.office@gotogopost.in</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="fh-contact-box type-phone "><i class="flaticon-phone-call "></i>
                            <h4 class="box-title">Call us on</h4>
                            <div class="desc">

                                <p>1800 123 1617
                                    {{-- &amp; +44 123 456789 --}}

                                    {{-- <br> Customer Care: 1800-123-45-6789</p> --}}
                            </div>
                        </div>
                        <div class="fh-contact-box type-social "><i class="flaticon-share"></i>
                            <h4 class="box-title">We are social</h4>
                            <ul class="clearfix">
                                <li class="facebook">
                                    <a href="https://www.facebook.com/share/BVJeAPEcHNBwCnN9/?mibextid=qi2Omg"
                                        target="_blank">

                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                <li class="twitter">
                                    <a href="https://www.instagram.com/gotogopost/?utm_source=qr&igsh=MWQ3aDV6NGlkNmZvZg%3D%3D"
                                        target="_blank">

                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                                <li class="googleplus">
                                    <a href="https://www.youtube.com/channel/UCkLQWa6_bv4CnuQZqdh-l4g" target="_blank">
                                        <i class="fa fa-youtube"></i>
                                    </a>
                                </li>
                                <li class="pinterest">

                                    <a href="https://wa.me/+91 9810657990" target="_blank">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                </li>
                                {{-- <li class="linkedin">

                                    <a href="#" target="_blank">

                                        <i class="fa fa-linkedin"></i>

                                    </a>

                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="opening-hours vc_opening-hours">
                    <h3>WORKING HOURS</h3>
                    <ul>
                        <li>Mon to Fri<span class="hour">10:00:AM – 18:00:PM</span></li>
                        <li>Sat &amp; Sunday <span class="hour main-color">Closed</span></li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</section>

<!--contact end-->
{{-- <section class="aboutsec-3 secpadd">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="abotimglft">
                    <img src="{{asset('website/images/resources/sagar.jpeg')}}" class="img-responsive">
                </div>
                <h5>NAME- RAJESH KUMAR SAGAR</h5>
                <h5>DESIGNATION- DIRECTOR</h5>
                <h5>RESIDENT- UTTAR PRADESH</h5>
            </div>
            <div class="col-md-6">
                <div class="abotinforgt">
                    <div class="abotimglft">
                        <img src="{{asset('website/images/resources/partner.jpg')}}" class="img-responsive">
                    </div>
                    <h5>NAME- JATINDER</h5>
                    <h5>DESIGNATION- DIRECTOR</h5>
                    <h5>RESIDENT- DELHI</h5>
                </div>
            </div>
        </div>
    </div>
</section> --}}


<!--contact form -->

<section class="contactpagform graybg secpadd">

    <div class="container">

        <div class="fh-section-title clearfix f25 text-center version-dark paddbtm40">

            <h2>Leave Your Message</h2>

        </div>

        <p class="paddbtm40 text-center">Any Question About What we offer for Consumers And for Business?<br>Please
            Email us or call at below given details.We will be Glad to reply within 24 hours.</p>

        {{-- <form method="post" action="{{route('websiteMessage.store')}}" id="contact-form">
             @csrf
            <div class="fh-form fh-form-3">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <p class="field">
                            <input name="name" placeholder="Your Name*" type="text">
                        </p>
                        <p class="col-md-6 col-sm-6 col-xs-12">
                            <input name="email" placeholder="Email Address*" type="email">
                        </p>
                        <p class="col-md-6 col-sm-6 col-xs-12">
                            <input name="phone" placeholder="Phone" type="text">
                        </p>
                        <p class="col-md-6 col-sm-6 col-xs-12">
                            <select name="option" placeholder="Please choose an option">
                                <option value="PPH">Prime Processing Hub(PPH)</option>
                                <option value="CPH">Collection Processing Hub(CPH)</option>
                                <option value="DELIVERY">DELIVERY/PICKUP BOY</option>
                                <option value="FRANCHISE">FRANCHISE</option>
                            </select>
                        </p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <p class="field single-field">
                            <textarea name="message" cols="40" rows="10" placeholder="Your Message..."></textarea>
                        </p>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p class="field submit">
                            <button class="fh-btn" type="submit">Submit</button>
                        </p>
                    </div>
                </div>
            </div>
        </form> --}}
        <div class="container">
            <div class="row">
                <!-- Left Side: Form -->
                <div class="col-lg-6">
                    <form method="post" action="{{ route('websiteMessage.store') }}" id="contact-form">
                        @csrf
                        <div class="fh-form fh-form-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="field">
                                        <input name="name" placeholder="Your Name*" type="text" class="form-control">
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="field">
                                        <input name="email" placeholder="Email Address*" type="email" class="form-control">
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="field">
                                        <input name="phone" placeholder="Phone" type="text" class="form-control">
                                    </p>
                                </div>
                                <div class="col-md-6">
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
                                <div class="col-md-12">
                                    <p class="field">
                                        <textarea name="message" cols="40" rows="5" placeholder="Your Message..." class="form-control"></textarea>
                                    </p>
                                </div>
                                <div class="col-md-12">
                                    <p class="field submit">
                                        <button class="fh-btn btn btn-primary pt-4" type="submit">Submit</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
        
                <!-- Right Side: Google Map -->
                <div class="col-lg-6">
                    <div class="map-container" style="height: 400px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d112089.35736709561!2d77.34723109193371!3d28.606003593214258!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1742545950106!5m2!1sen!2sin" width="590" height="370" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--contact form  end -->



<!--google map-->

{{-- <div class="google-map-area">

    <div class="google-map" id="contact-google-map" data-map-lat="51.545527" data-map-lng="-0.133272"
        data-icon-path="images/icon/maker3.png" data-map-title="Chester" data-map-zoom="13" data-markers='{

                                "marker-1": [51.545527, -0.133272, "<h4>Cargo Hub</h4><p>Camden Borough </p>"]

                            }' style="height:500px;width:100%;">

    </div>

</div> --}}

<!--google map end-->

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



    