

<?php $__env->startSection('content'); ?>
<section class="whychoose-1 secpadd">
    <div class="container">
        <div class="row quote1forms" style="">
            <div class="col-md-6">
                <div class="abotimglft">
                    <img src="images/resources/about2.jpg" class="img-responsive">
                </div>
            </div>
            <div class="col-md-6" style="">
                <form>
                    <div class="fh-form request-form">
                        <div class="row " style="margin:0px">
                            <h2 class="mb-2">Login to Delivery boy</h2>
                            <div class="field col-md-12">
                                <label>Email Address<span class="require">*</span></label>
                                <input name="your-email" placeholder="Email Address" type="email">
                            </div>
                            <div class="field col-md-12">
                                <label>Password<span class="require">*</span></label>
                                <input name="your-email" placeholder="Password" type="email">
                            </div>
                                <p class="field submit">
                                    <input value="Submit" class="fh-btn" type="submit">
                                </p>
                        </div>
                    </div>
                </form>
                <p class="mt-2">Create an account <a href="register.php">Register</a> </p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

     
                

<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/website/login.blade.php ENDPATH**/ ?>