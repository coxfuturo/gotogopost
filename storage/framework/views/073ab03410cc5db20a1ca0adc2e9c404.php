
<?php $__env->startSection('content'); ?>
 <style>
.glow-text h4 {
    color: #fff;
    animation: glow 1.5s infinite alternate;
    font-weight: bold;
}

@keyframes glow {
    0% {
        text-shadow: 0 0 5px #ff0000, 0 0 10px #ff0000, 0 0 15px #ff0000;
    }
    100% {
        text-shadow: 0 0 5px blue, 0 0 10px blue, 0 0 15px blue;
    }
}
</style>
<div class="welcomesec secpadd">
    <div class="container">
        <div class="fh-section-title clearfix  text-center version-dark paddbtm20">
            <h2>GOTOGO <span class="main-color">POST</span> SINGLE REGISTATION</h2>
        </div>
        <h2 class="text-center" style="margin-top:50px; margin-bottom: 20px">GOTOGO <span class="main-color"> POST</span> Single Register</h2>
        <div class="welservices row" style="margin-top:40px">
            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <div class="row">
                        <div class="col-sm-4">
                            <img  src="<?php echo e(asset('website/images/logonew.jpg')); ?>" alt="CargoHub" class="logo-light show-logo">

                        </div>

                        <div class="col-sm-8">
                             <a href="<?php echo e(route('franchise.register')); ?>" class="glow-text"><h4>Franchise Registation</h4></a>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <div class="row">
                        <div class="col-sm-4">
                            <img  src="<?php echo e(asset('website/images/logonew.jpg')); ?>" alt="CargoHub" class="logo-light show-logo">

                        </div>

                        <div class="col-sm-8">
                             <a href="<?php echo e(route('cms.register')); ?>" class="glow-text"><h4>CPH Registation</h4></a>
                        </div>
                    </div>
                    
                </div>
            </div>


            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <div class="row">
                        <div class="col-sm-4">
                            <img  src="<?php echo e(asset('website/images/logonew.jpg')); ?>" alt="CargoHub" class="logo-light show-logo">

                        </div>

                        <div class="col-sm-8">
                             <a href="<?php echo e(route('pph.register')); ?>" class="glow-text"><h4>PPH Registation</h4></a>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <div class="row">
                        <div class="col-sm-4">
                            <img  src="<?php echo e(asset('website/images/logonew.jpg')); ?>" alt="CargoHub" class="logo-light show-logo">

                        </div>

                        <div class="col-sm-8">
                             <a href="<?php echo e(route('deliveryBoy.register')); ?>" class="glow-text"><h4>Delivery Boy Registation</h4></a>
                        </div>
                    </div>
                    
                </div>
            </div>
           

            <!-- <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="<?php echo e(asset('website/images/pracel.png')); ?>" style="height:100px;"></span>
                    <h4 class="box-title"><a href="#">Integrated E-Commerce Logistics</a></h4>
                    <div class="desc">
                        <p>Tailored for the booming e-commerce sector, our vertical supports aggregators, D2C brands, and B2C customers. We offer solutions for both time-sensitive e-commerce shipments and cost-effective deliveries for lower-value B2C orders.</p>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>

   <!-- Combo  -->
<div class="welcomesec secpadd">
    <div class="container">
       
        <h2 class="text-center" style="margin-top:50px; margin-bottom: 20px">GOTOGO <span class="main-color"> POST</span> Combo Register</h2>
        <div class="welservices row" style="margin-top:40px">
            <div class="col-md-6 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <div class="row">
                        <div class="col-sm-4">
                            <img  src="<?php echo e(asset('website/images/logonew.jpg')); ?>" alt="CargoHub" class="logo-light show-logo">

                        </div>

                        <div class="col-sm-8">
                             <a href="<?php echo e(route('franchise.combo.index')); ?>" class="glow-text">
                                <h4>Franchise And CPH 
                                    <br>
                                    Registation
                                </h4>
                            
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>

            
           

            <!-- <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="<?php echo e(asset('website/images/pracel.png')); ?>" style="height:100px;"></span>
                    <h4 class="box-title"><a href="#">Integrated E-Commerce Logistics</a></h4>
                    <div class="desc">
                        <p>Tailored for the booming e-commerce sector, our vertical supports aggregators, D2C brands, and B2C customers. We offer solutions for both time-sensitive e-commerce shipments and cost-effective deliveries for lower-value B2C orders.</p>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/website/package.blade.php ENDPATH**/ ?>