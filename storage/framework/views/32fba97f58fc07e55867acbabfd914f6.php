<?php echo $__env->make('website.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(session('success')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Payment Success',
        text: '<?php echo e(session('success')); ?>',
        confirmButtonText: 'Okay'
    }).then((result) => {
        // Check if the user clicked "Okay"
        if (result.isConfirmed) {
            // Now, trigger the AJAX request after "Okay" click
            $.ajax({
                url: "<?php echo e(route('website.payment.printpaymentHistory')); ?>",
                method: 'GET',
                data: {
                    id: <?php echo e(session('success')); ?>,
                },
                success: function(response) {
                    var iframe = document.createElement('iframe');
                    iframe.style.position = 'absolute';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = 'none';
                    document.body.appendChild(iframe);

                    iframe.contentDocument.open();
                    iframe.contentDocument.write(response.otherPageContent);
                    iframe.contentDocument.close();

                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();

                    document.body.removeChild(iframe);
                    preventDefault();
                }
            });
        }
    });
    </script>
<?php endif; ?>


<?php $__env->startSection('content'); ?>
          <!--Page Header-->
          <div class="page-header title-area">
            <div class="header-title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <!-- <h1 class="page-title">Payment</h1> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="breadcrumb-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">
                            <nav class="breadcrumb">
                                <a class="home" href="#" style="color: #000"><span>Home</span></a>
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                                <span>Payment</span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Page Header end-->

    <!-- Payment -->
    <style>
    .personal-info li .text {
        color: #0c48be;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }

    .form-signin {
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}


.invalid-feedback {
    display: none;
    width: 100%;
    margin-top: .25rem;
    font-size: .875em;
    color: var(--bs-form-invalid-color);
}


input {
    display: block !important;
}
</style>




<section class="whychoose-1 " style="margin-top: 15px;margin-bottom:15px">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6   secpaddlf">
                <h4>Company Account Details</h4>
                <p>NET/RTGS/NetBanking</p>
                <table>
                    <tr>
                        <th>Bank Name </th>
                        <td>Axis Bank Limited</td>
                    </tr>
                    <tr>
                        <th>Branch Name </th>
                        <td>Chittaranjan Park,New Delhi</td>
                    </tr>
                    <tr>
                        <th>Account No. </th>
                        <td>924020029877237</td>
                    </tr>

                    <tr>
                        <th>IFSC Code </th>
                        <td>UTIB0000430</td>
                    </tr>
           
                </table>
                  
            </div>
           
            <div class="col-lg-6 col-md-6 quofrm1  secpaddlf">
                <div class="fh-section-title clearfix  text-left version-dark paddbtm40 text-center">
                    <h2>Payment</h2>
                </div>
                <form id="payment-form" method="POST">
                <?php echo csrf_field(); ?>
                    <div class="fh-form-1 fh-form">
                        <div class="row fh-form-row">
                        
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <p class="field single-field">
                                <span class="input-group-text"><i class="fa fa-money-bill"></i></span>
                                    <input type="number" class="form-control NumberValidate" id="amount" placeholder="Amount*">
                                </p>
                                <div class="invalid-feedback">
                                Please Enter a Valid Amount
                            </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="text-align: -webkit-center;">
                                <p class="field submit">
                                    <input value="Pay" id="pay-button" class="fh-btn" type="submit">
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
    
      <!-- <script src="https://checkout.razorpay.com/v1/checkout.js"></script> -->
   

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const RAZORPAY_KEY = "<?php echo e(env('RAZORPAY_KEY')); ?>";
    console.log("RAZORPAY_KEY =>", RAZORPAY_KEY);
</script>


<script>
    $(document).ready(function() {
        $('#pay-button').on('click', function(e) {
            e.preventDefault(); // Prevent the form from submitting
  
            // Get the amount entered by the user
            const amountInput = $('#amount').val();

            // Validate the amount
            if (!amountInput || amountInput <= 0) {
                alert('Please enter a valid amount');
                return;
            }
            
            // Convert amount to paise (required by Razorpay)
            const amountInPaise = amountInput * 100;
            
            // Create a new Razorpay Checkout instance
            const options = {
                key: "rzp_live_hZ7MLP0RaGm3Dx", // Razorpay key
                amount: amountInPaise, // Amount in paise
                currency: "INR",
                name: "gotogopost.com",
                description: "Franchise Payment",
                image: "https://gotogopost.com/website/images/logonewtransparent.png",
                theme: {
                    color: "#ff7529"
                },
                method: {
                    card: true, // Enable card payments
                    netbanking: true, // Enable netbanking
                    wallet: true, // Enable wallet payments
                    upi: true, // Enable UPI
                },
                handler: function(response) {
                    window.location.href = "<?php echo e(route('website.payment.store')); ?>?razorpay_payment_id=" + response.razorpay_payment_id + "&amount=" + amountInput;
                
                },
                modal: {
                    ondismiss: function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Payment Cancelled',
                            text: 'It looks like you cancelled the payment. Please try again.',
                            confirmButtonText: 'Okay'
                        });
                    }
                }
            };

            console.log(options);
            const rzp = new Razorpay(options);
            rzp.open();
        });
    });
</script>
<?php $__env->stopSection(); ?>
     
    

<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/website/pay.blade.php ENDPATH**/ ?>