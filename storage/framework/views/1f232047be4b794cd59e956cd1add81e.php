

<?php $__env->startSection('content'); ?>
<section class="whychoose-1 secpadd">
    <div class="container">
        <div class="row quote1forms" style="">
            <div class="col-md-5" style="">
                <div class="abotimglft">
                    <img src="images/resources/about2.jpg" class="img-responsive">
                </div>
            </div>
            <div class="col-md-7" style="">
                <form>
                    <div class="fh-form request-form">
                        <div class="row">
                             <h2 class="mb-2">Register Delivery boy</h2>
                            <div class="field col-md-6">
                                <label>First Name<span class="require">*</span></label>
                                <input placeholder="First Name" type="text">
                            </div>
                            <div class="field col-md-6">
                                <label>Last Name<span class="require">*</span></label>
                                <input placeholder="Last Name" type="text">
                            </div>
                            <div class="field col-md-6">
                                <label>Email Address<span class="require">*</span></label>
                                <input name="your-email" placeholder="Email Address" type="email">
                            </div>
                            <div class="field col-md-6">
                                <label>Password<span class="require">*</span></label>
                                <input name="your-email" placeholder="Password" type="password">
                            </div>
                            <div class="field col-md-6">
                                <label>Confirm Password<span class="require">*</span></label>
                                <input name="your-email" placeholder="Confirm Password" type="password">
                            </div>
                                <p class="field submit">
                                    <input value="Submit" class="fh-btn" type="submit">
                                </p>
                        </div>
                    </div>
                    <p class="mt-2">Already have an account? <a href="login.php">Login</a> </p>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/website/register.blade.php ENDPATH**/ ?>