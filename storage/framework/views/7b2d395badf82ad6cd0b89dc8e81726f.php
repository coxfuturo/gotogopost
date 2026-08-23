<?php echo $__env->make('website.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->startSection('content'); ?>
          <!--Page Header-->
          <div class="page-header title-area">
            <div class="header-title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h1 class="page-title">Logistic Services</h1>
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
                                <span>Logistic Services</span>
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
                            <img src="<?php echo e(asset('website/images/resources/about2.jpg')); ?>" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="abotinforgt">
                            <div class="fh-section-title f30 clearfix  text-left version-dark paddbtm30">
                                <h2>Know Us Better</h2>
                            </div>
                            <p>Highlight the importance of reliable logistics in modern courier services.</p>
                            <p>Emphasize how your company simplifies and accelerates deliveries.</p>
                           
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- about end-->

        <!-- Three steps-->
        <section class="three_steps secpadd graybg">
            <div class="container">
                <div class="">
                    <h2>Services We Offer</h2>
                </div>
              <p>Parcel Delivery: Quick delivery for small to large packages.</p>
              <p>Same-Day Delivery: For urgent shipments that need to arrive on the same day.</p>
              <p>Express Delivery: Fast delivery service with priority handling.</p>
              <p>Bulk Shipments: Tailored solutions for businesses sending high volumes of goods.</p>
              <p>Last-Mile Delivery: Precise and efficient final delivery to the recipient.</p>
              <p>Freight Services: Transport for oversized or heavy shipments.</p>
              <P>Real-Time Tracking: Stay updated on the location and status of your packages.</P>
            </div>
        </section>
        <!-- Three steps end-->
      
<?php $__env->stopSection(); ?>
<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/website/logistic.blade.php ENDPATH**/ ?>