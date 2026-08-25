<!-- Custom Compact Pure White Footer CSS -->
        <style>
            /* Compact Pure White Corporate Footer Styling */
            .footer-widgets.widgets-area {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                border-top: 3px solid #ff0000 !important;
                position: relative;
                font-family: 'Montserrat', sans-serif, system-ui;
            }

            .footer-sidebars {
                padding: 24px 0 16px 0 !important;
            }

            /* Desktop 4 Columns Equal Flex Layout with Balanced Gaps */
            @media (min-width: 992px) {
                .footer-4col-row {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    justify-content: space-between !important;
                    align-items: flex-start !important;
                    gap: 36px !important;
                }
                .footer-col-item {
                    flex: 1 1 0px !important;
                    width: 25% !important;
                    max-width: 25% !important;
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }
            }

            @media (min-width: 768px) and (max-width: 991px) {
                .footer-4col-row {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 24px 0 !important;
                }
                .footer-col-item {
                    flex: 0 0 50% !important;
                    max-width: 50% !important;
                    margin-bottom: 20px !important;
                }
            }

            @media (max-width: 767px) {
                .footer-4col-row {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 24px !important;
                }
                .footer-col-item {
                    width: 100% !important;
                    margin-bottom: 0 !important;
                }
            }

            /* Section Titles */
            .footer-col-item .widget-title {
                font-size: 16px !important;
                font-weight: 800 !important;
                color: #000000 !important;
                margin-top: 0 !important;
                margin-bottom: 12px !important;
                position: relative !important;
                padding-bottom: 6px !important;
                letter-spacing: 0.5px !important;
                text-transform: uppercase !important;
                line-height: 1.3 !important;
            }

            .footer-col-item .widget-title::after {
                content: '' !important;
                position: absolute !important;
                bottom: 0 !important;
                left: 0 !important;
                width: 32px !important;
                height: 3px !important;
                background: #ff0000 !important;
                border-radius: 2px !important;
            }

            /* 1. About GOTOGO Post */
            .footer-logo-box {
                margin-bottom: 10px !important;
                display: inline-block !important;
            }

            .footer-logo-box img {
                max-height: 44px !important;
                width: auto !important;
                max-width: 100% !important;
                object-fit: contain !important;
                transition: transform 0.3s ease !important;
            }

            .footer-logo-box:hover img {
                transform: scale(1.03) !important;
            }

            .footer-about-text {
                font-size: 13.5px !important;
                line-height: 1.6 !important;
                color: #000000 !important;
                margin-bottom: 12px !important;
            }

            /* Social Links - Cross-Page High Specificity Enforcer */
            .footer-widgets .footer-social-links,
            .footer-widgets .fh-contact-box.type-social ul.footer-social-links {
                display: flex !important;
                gap: 10px !important;
                list-style: none !important;
                padding: 0 !important;
                margin: 12px 0 0 0 !important;
            }

            .footer-widgets .footer-social-links li,
            .footer-widgets .fh-contact-box.type-social ul.footer-social-links li {
                display: inline-block !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .footer-widgets .footer-social-links li a,
            .footer-widgets .fh-contact-box.type-social ul.footer-social-links li a {
                width: 36px !important;
                height: 36px !important;
                border-radius: 50% !important;
                background: #222222 !important;
                border: 1px solid #222222 !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                color: #ffffff !important;
                font-size: 15px !important;
                text-decoration: none !important;
                opacity: 1 !important;
                visibility: visible !important;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }

            .footer-widgets .footer-social-links li a i,
            .footer-widgets .fh-contact-box.type-social ul.footer-social-links li a i {
                color: #ffffff !important;
                font-size: 15px !important;
                line-height: 1 !important;
                margin: 0 !important;
                padding: 0 !important;
                display: inline-block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            .footer-widgets .footer-social-links li a:hover,
            .footer-widgets .fh-contact-box.type-social ul.footer-social-links li a:hover {
                background: #ff0000 !important;
                border-color: #ff0000 !important;
                color: #ffffff !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 4px 12px rgba(255, 0, 0, 0.35) !important;
            }

            .footer-widgets .footer-social-links li a:hover i,
            .footer-widgets .fh-contact-box.type-social ul.footer-social-links li a:hover i {
                color: #ffffff !important;
            }

            /* 2. Useful Links - Iconless Clean Compact List */
            .footer-menu-list {
                list-style: none !important;
                list-style-type: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .footer-menu-list li {
                margin-bottom: 5px !important;
                padding: 0 !important;
                border: none !important;
                line-height: 1.4 !important;
                list-style-type: none !important;
            }

            .footer-menu-list li::before,
            .menu-service-menu-container li::before {
                content: none !important;
                display: none !important;
            }

            .footer-menu-list li a,
            .menu-service-menu-container a {
                color: #000000 !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                text-decoration: none !important;
                border-bottom: none !important;
                box-shadow: none !important;
                outline: none !important;
                display: inline-block !important;
                padding: 1px 0 !important;
                transition: color 0.2s ease !important;
            }

            .footer-menu-list li a i,
            .menu-service-menu-container a i {
                display: none !important;
            }

            .footer-menu-list li a:hover,
            .footer-menu-list li a:focus,
            .footer-menu-list li a:active,
            .menu-service-menu-container a:hover,
            .menu-service-menu-container a:focus {
                color: #ff0000 !important;
                text-decoration: none !important;
                border-bottom: none !important;
                box-shadow: none !important;
                outline: none !important;
            }

            .footer-menu-list,
.footer-menu-list li {
    list-style: none !important;
}

.footer-menu-list li::before,
.footer-menu-list li::after,
.footer-menu-list li a::before,
.footer-menu-list li a::after {
    content: none !important;
    display: none !important;
}

.footer-menu-list li a {
    text-decoration: none !important;
    color: #000000 !important;
    padding-left: 0 !important;
    margin-left: 0 !important;
}

            /* 3. Contact Info */
            .footer-contact-list {
                display: flex !important;
                flex-direction: column !important;
                gap: 10px !important;
            }

            .footer-contact-item {
                display: flex !important;
                align-items: flex-start !important;
                gap: 10px !important;
            }

            .footer-contact-item i {
                color: #ff0000 !important;
                font-size: 14px !important;
                width: 28px !important;
                height: 28px !important;
                border-radius: 6px !important;
                background: rgba(255, 0, 0, 0.08) !important;
                border: 1px solid rgba(255, 0, 0, 0.15) !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                flex-shrink: 0 !important;
                margin-top: 1px !important;
            }

            .footer-contact-item div {
                font-size: 13.5px !important;
                line-height: 1.45 !important;
                color: #000000 !important;
            }

            .footer-contact-item strong {
                display: block !important;
                color: #000000 !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                margin-bottom: 1px !important;
            }

            .footer-contact-item a {
                color: #000000 !important;
                text-decoration: none !important;
                font-weight: 600 !important;
                transition: color 0.2s ease !important;
            }

            .footer-contact-item a:hover {
                color: #ff0000 !important;
            }

            /* 4. Our Newsletter */
            .newsletter-intro {
                font-size: 16px !important;
                line-height: 1.5 !important;
                color: #000000 !important;
                margin-bottom: 10px !important;
            }

            .footer-subscribe-form {
                display: flex !important;
                align-items: center !important;
                margin-bottom: 8px !important;
            }

            .footer-subscribe-form input[type="email"] {
                background: #ffffff !important;
                border: 1.5px solid #cbd5e1 !important;
                border-radius: 6px 0 0 6px !important;
                color: #000000 !important;
                height: 38px !important;
                padding: 0 10px !important;
                font-size: 13px !important;
                flex: 1 !important;
                outline: none !important;
                transition: all 0.2s ease !important;
            }

            .footer-subscribe-form input[type="email"]:focus {
                border-color: #ff0000 !important;
            }

            .footer-subscribe-form input[type="submit"],
            .footer-subscribe-form button[type="submit"] {
                background: #ff0000 !important;
                border: none !important;
                border-radius: 0 6px 6px 0 !important;
                color: #ffffff !important;
                font-weight: 700 !important;
                font-size: 13px !important;
                height: 38px !important;
                padding: 0 14px !important;
                cursor: pointer !important;
                transition: background 0.2s ease !important;
                white-space: nowrap !important;
            }

            .footer-subscribe-form input[type="submit"]:hover,
            .footer-subscribe-form button[type="submit"]:hover {
                background: #cc0000 !important;
            }

            .newsletter-note {
                font-size: 11.5px !important;
                color: #000000 !important;
                margin: 0 !important;
            }

            /* Site Footer / Copyright Bar */
            .site-footer {
                background: #f8fafc !important;
                padding: 12px 0 !important;
                border-top: 1px solid #e2e8f0 !important;
                font-size: 12.5px !important;
            }

            .site-footer .site-info {
                color: #000000 !important;
                font-weight: 500 !important;
            }

            .site-footer .site-info a {
                color: #000000 !important;
                font-weight: 700 !important;
                text-decoration: none !important;
            }

            .site-footer .site-info a:hover {
                color: #ff0000 !important;
            }

            @media (max-width: 767px) {
                .site-footer .text-right {
                    text-align: left !important;
                    margin-top: 4px !important;
                }
            }
        </style>

        <!--footer sec-->
        <div class="footer-widgets widgets-area">
            <div class="footer-sidebars">
                <div class="container">
                    <div class="row footer-4col-row">

                        <!-- Section 1: About GOTOGO Post -->
                        <div class="footer-col-item col-xs-12 col-sm-6 col-md-3">
                            <div class="widget widget_text">
                                <a href="{{route('website.index')}}" class="footer-logo-box">
                                    <img src="{{asset('website/images/logoNoBg.png')}}" onerror="this.onerror=null;this.src='https://via.placeholder.com/150x50?text=GOTOGO+POST';" alt="GOTOGO Post Logo">
                                </a>
                                <h4 class="widget-title">About GOTOGO Post</h4>
                                <div class="textwidget">
                                    <p class="footer-about-text">GOTOGO Post Logistics Services is a global supplier of transport and logistics solutions. We have offices in more than 20 countries and an international network of partners and agents.</p>
                                </div>
                            </div>
                            <div class="fh-contact-box type-social">
                                <ul class="footer-social-links">
                                    <li>
                                        <a href="https://www.facebook.com/share/BVJeAPEcHNBwCnN9/?mibextid=qi2Omg" target="_blank" title="Facebook">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/gotogopost/?utm_source=qr&igsh=MWQ3aDV6NGlkNmZvZg%3D%3D" target="_blank" title="Instagram">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.youtube.com/channel/UCkLQWa6_bv4CnuQZqdh-l4g" target="_blank" title="YouTube">
                                            <i class="fa fa-youtube"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://wa.me/+919810657990" target="_blank" title="WhatsApp">
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Section 2: Useful Links -->
                        <div class="footer-col-item col-xs-12 col-sm-6 col-md-3">
                            <div class="widget widget_nav_menu">
                                <h4 class="widget-title">Useful Links</h4>
                                <div class="menu-service-menu-container">
                                    <ul class="footer-menu-list">
                                        <li><a href="{{ route('website.index') }}">Home</a></li>
                                        <li><a href="{{ route('website.contact') }}">Contact Us</a></li>
                                        <li><a href="{{ route('website.about') }}">About Us</a></li>
                                        <li><a href="{{ route('website.privacy-policy') }}">Privacy Policy</a></li>
                                        <li><a href="{{ route('website.terms') }}">Terms and Conditions</a></li>
                                        <li><a href="{{ route('website.faq') }}">FAQs</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Contact Info -->
                        <div class="footer-col-item col-xs-12 col-sm-6 col-md-3">
                            <div class="widget widget_nav_menu">
                                <h4 class="widget-title">Contact Info</h4>
                                <div class="footer-contact-list">
                                    <div class="footer-contact-item">
                                        <i class="fa fa-map-marker"></i>
                                        <div>
                                            <strong>Address:</strong>
                                            <span>Gaur City Mall Office Space - Sector 4, Greater Noida Uttar Pradesh 201318</span>
                                        </div>
                                    </div>
                                    <div class="footer-contact-item">
                                        <i class="fa fa-phone"></i>
                                        <div>
                                            <strong>Toll Free Number:</strong>
                                            <a href="tel:18001231617">1800 123 1617</a>
                                        </div>
                                    </div>
                                    <div class="footer-contact-item">
                                        <i class="fa fa-clock-o"></i>
                                        <div>
                                            <strong>Opening Hours:</strong>
                                            <span>MON – FRI: 10AM – 7PM</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Our Newsletter -->
                        <div class="footer-col-item col-xs-12 col-sm-6 col-md-3">
                            <div class="widget widget_mc4wp_form_widget">
                                <h4 class="widget-title">Our Newsletter</h4>
                                <p class="newsletter-intro">Sign up today for tips and latest news and exclusive special offers.</p>
                                <form action="#" method="POST" onsubmit="if(typeof event !== 'undefined') event.preventDefault();">
                                    <div class="footform">
                                        <div class="fh-form-field">
                                            <div class="footer-subscribe-form">
                                                <input name="EMAIL" placeholder="Enter Your Email" required="" type="email">
                                                <input value="Sign Up" type="submit">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <p class="newsletter-note"><i class="fa fa-lock"></i> We don’t do spam and your email is confidential.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--footer sec end-->

        <!--copyright sec-->
        <footer class="site-footer">
            <div class="container">
                <div class="row align-items-center">
                    <div class="footer-copyright col-md-6 col-sm-12">
                        <div class="site-info">Copyright © 2026 <a href="{{route('website.index')}}">GOTOGO Post</a>, All Rights Reserved.</div>
                    </div>
                    <!-- <div class="footer-copyright col-md-6 col-sm-12 text-right">
                        <div class="site-info">Designed for Logistics & Transport Excellence</div>
                    </div> -->
                </div>
            </div>
        </footer>
        <!--copyright sec end-->

    </div>

    <!--End pagewrapper-->



    {{-- <!--primary-mobile-nav-->

    <div class="primary-mobile-nav header-v1" id="primary-mobile-nav" role="navigation">

        <a href="#" class="close-canvas-mobile-panel">×</a>

        <ul class="menu">

            <li class=""><a href="/indiapost_web">Home</a>

                

            </li>

            <li class="menu-item-has-children"><a href="services.html" class="dropdown-toggle">Services</a>

                <ul class="sub-menu">

                    <li><a href="services.html">All Services</a></li>

                    <li><a href="road-freight-forwarding.html">Road Freight Forwarding</a></li>

                    <li><a href="ocean-freight-forwarding.html">Ocean Freight Forwarding</a></li>

                    <li><a href="air-freight-forwarding.html">Air Freight Forwarding</a></li>

                    <li><a href="warehousing.html">Warehousing</a></li>

                    <li><a href="door-to-door-delivery.html">Door to Door Delivery</a></li>

                    <li><a href="ground-transport.html">Ground Transport</a></li>

                    <li><a href="worldwide-transport.html">Worldwide Transport</a></li>

                    <li><a href="cargo-service.html">Cargo Service</a></li>

                    <li><a href="packaging-storage.html">Packaging &#038; Storage</a></li>

                </ul>

            </li>

            <li class="menu-item-has-children"><a href="projects.html" class="dropdown-toggle">Pages</a>

                <ul class="sub-menu">

                    <li><a href="about-us.html">About Us</a></li>

                    <li><a href="faqs.html">FAQS</a></li>

                    <li><a href="our-prices.html">Our Prices</a></li>

                    <li><a href="testimonials.html">Testimonials</a></li>

                </ul>

            </li>

            <li><a href="contact_us.php">Contact Us</a></li>

            <li><a href="about_us.php">About Us</a></li>

            <li class="extra-menu-item menu-item-button-link">

                <a href="login.php" class="fh-btn btn">Login</a>

            </li>

        </ul>



    </div>

    <div id="off-canvas-layer" class="off-canvas-layer"></div>

    <!--primary-mobile-nav end--> --}}



    <!--Scroll to top-->

    <a id="scroll-top" class="backtotop" href="#page-top"><i class="fa fa-angle-up"></i></a>



    <!--Color switcher-->

    {{-- <div class="demo_changer" id="demo_changer">

        <div class="demo-icon fa fa-sliders"></div>

        <div class="form_holder">

            <h3 class="demo-title">Customize CargoHub</h3>



            <div class="line"></div>

            <p>Color Scheme</p>

            <div class="predefined_styles" id="styleswitch_area">

                <a href="?default=true" data-rel="default" class="styleswitch" style="background:#ff0000;border-right-color: #0c1239;border-bottom-color: #0c1239"></a>

                <a href="#" data-rel="navy-darkblue" class="styleswitch" style="background:#00baff;border-right-color: #0c1239;border-bottom-color: #0c1239"></a>

                <a href="#" data-rel="orange-black" class="styleswitch" style="background:#ee6012;border-right-color: #202020;border-bottom-color: #202020"></a>

                <a href="#" data-rel="yellow-blue" class="styleswitch" style="background:#f6a604;border-right-color: #0032ab;border-bottom-color: #0032ab"></a>

            </div>

            <div class="line"></div>

        </div>

    </div> --}}



    <!-- jquery Liabrary -->

<script src="{{ asset('website/js/jquery-1.12.4.min.js') }}"></script>

<!-- bootstrap v3.3.6 js -->

<script src="{{ asset('website/js/bootstrap.min.js') }}"></script>

<!-- fancybox js -->

<script src="{{ asset('website/js/jquery.fancybox.pack.js') }}"></script>

<script src="{{ asset('website/js/jquery.fancybox-media.js') }}"></script>

<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

<!-- owl.carousel js -->

<script src="{{ asset('website/js/owl.js') }}"></script>

<!-- counter js -->

<script src="{{ asset('website/js/jquery.appear.js') }}"></script>

<script src="{{ asset('website/js/jquery.countTo.js') }}"></script>

<!-- validate js -->

<script src="{{ asset('website/js/validate.js') }}"></script>

<!-- switcher js -->

<script src="{{ asset('website/js/switcher.js') }}"></script>



<!-- google map -->

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>

<script src="{{ asset('website/js/gmap.js') }}"></script>

<script src="{{ asset('website/js/map-helper.js') }}"></script>



<!-- REVOLUTION JS FILES -->

<script src="{{ asset('website/js/revolution/jquery.themepunch.tools.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/jquery.themepunch.revolution.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.actions.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.carousel.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.kenburn.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.layeranimation.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.migration.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.navigation.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.parallax.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.slideanims.min.js') }}"></script>

<script src="{{ asset('website/js/revolution/extensions/revolution.extension.video.min.js') }}"></script>

<!-- script JS  -->
<script src="{{ asset('website/js/scripts.min.js') }}"></script>

<script src="{{ asset('website/js/script.js') }}"></script>
{{-- my script start --}}

@stack('script')

{{-- my script end --}}
</body>
</html>