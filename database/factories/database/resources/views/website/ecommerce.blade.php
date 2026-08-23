@include('website.layouts.header')
@extends('website.layouts.master')

@section('content')
          <!--Page Header-->
          <div class="page-header title-area">
            <div class="header-title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h1 class="page-title">Services</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">
                            <nav class="breadcrumb">
                                <a class="home" href="#"><span>Home</span></a>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                                <span>Services</span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Page Header end-->


        <!-- featured sec end -->

        <!-- about sec-->
        <section class="aboutsec-3 secpadd">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="abotimglft">
                            <img src="{{asset('website/images/resources/about2.jpg')}}" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="abotinforgt">
                            <div class="fh-section-title f30 clearfix  text-left version-dark paddbtm30">
                                <h2>Know Us Better</h2>
                            </div>
                            <p>GOTOGO POST Logistic is a technology-oriented logistics solutions provider based in India, offering businesses the means to enhance their supply chain efficiency via comprehensive services encompassing Warehousing, First Mile, Mid Mile, and Last Mile operations.</p>
                            <p>We have firmly established ourselves as the preeminent ‘Knowledge Pioneer’ and ‘Market Leader’ within India’s logistics sector. Our repertoire includes a diverse array of inventive logistics services and value-added solutions tailored to various business verticals.</p>
                           
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- about end-->

        <!-- Three steps-->
        <section class="three_steps secpadd graybg">
            <div class="container">
                <div class="fh-section-title clearfix f30  text-center version-dark margbtm40">
                    <h2>Three Step processing</h2>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <div class="fh-icon-box  style-2 version-dark  icon-center  service-process">
                            <span class="fh-icon"><i class="flaticon-box-1"></i></span>
                            <h4 class="box-title"><span>Receive From Shipper</span></h4>
                            <div class="desc">
                                <p>Pursues or desires to obtain sed pain of it because it is pain circumstances.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="fh-icon-box  style-2 version-dark  icon-center  service-process">
                            <span class="fh-icon"><i class="flaticon-delivery-truck"></i></span>
                            <h4 class="box-title"><span>Safe & Secure Shipment</span></h4>
                            <div class="desc">
                                <p>Except to obtain some advantage from it but who has any rights too find fault with enjoy.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="fh-icon-box  style-2 version-dark  icon-center  service-process">
                            <span class="fh-icon"><i class="flaticon-box-2"></i></span>
                            <h4 class="box-title"><span>Handover to Receiver </span></h4>
                            <div class="desc">
                                <p>At vero eos et accusamus et iusto sed odio dignissimos ducimus ut blanditiis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Three steps end-->
      
@endsection