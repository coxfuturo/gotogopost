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

<!-- Scoped Custom CSS for Payment Page -->
<style>
/* Payment Header / Breadcrumb */
.pay-page-header {
    background: linear-gradient(135deg, #0b132b 0%, #1e293b 100%) !important;
    padding: 24px 0 !important;
    color: #ffffff;
    border-bottom: 3px solid #ff0000;
}

.pay-page-header .breadcrumb {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
    font-size: 14px;
}

.pay-page-header .breadcrumb a {
    color: #cbd5e1 !important;
    text-decoration: none !important;
}

.pay-page-header .breadcrumb a:hover {
    color: #ff0000 !important;
}

.pay-page-header .breadcrumb span.active-crumb {
    color: #ffffff !important;
    font-weight: 600;
}

.pay-page-header .breadcrumb i {
    margin: 0 10px;
    color: #64748b;
}

/* Payment Main Section */
.pay-section {
    padding: 60px 0 !important;
    background-color: #f8fafc;
}

/* Left Column: Bank Details Card */
.pay-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
    padding: 32px 28px !important;
    height: 100%;
    margin-bottom: 24px;
}

.pay-card-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f5f9;
}

.pay-card-header i {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(255, 0, 0, 0.08);
    color: #ff0000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.pay-card-header h4 {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    margin: 0 !important;
}

.pay-card-header p {
    font-size: 13px !important;
    color: #64748b !important;
    margin: 0 !important;
}

.bank-details-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 10px;
}

.bank-details-table tr {
    transition: background 0.2s ease;
}

.bank-details-table th {
    width: 40%;
    padding: 14px 16px;
    background: #f8fafc;
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
}

.bank-details-table td {
    padding: 14px 16px;
    background: #ffffff;
    color: #0b132b;
    font-size: 14px;
    font-weight: 700;
    border-bottom: 1px solid #e2e8f0;
}

.bank-details-table tr:first-child th {
    border-top-left-radius: 10px;
}
.bank-details-table tr:first-child td {
    border-top-right-radius: 10px;
}
.bank-details-table tr:last-child th {
    border-bottom-left-radius: 10px;
    border-bottom: none;
}
.bank-details-table tr:last-child td {
    border-bottom-right-radius: 10px;
    border-bottom: none;
}

/* Right Column: Payment Form Card */
.pay-form-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
    padding: 32px 28px !important;
}

.pay-form-card .fh-section-title h2 {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 22px !important;
    font-weight: 800 !important;
    color: #0b132b !important;
    margin-bottom: 20px !important;
    position: relative;
    padding-bottom: 10px;
}

.pay-form-card .fh-section-title h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 4px;
    background: #ff0000;
    border-radius: 2px;
}

.amount-input-group {
    position: relative;
    margin-bottom: 20px;
}

.amount-input-group .currency-symbol {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    font-weight: 700;
    color: #0b132b;
    z-index: 5;
}

.amount-input-group input#amount {
    height: 54px !important;
    padding-left: 42px !important;
    border-radius: 10px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #0b132b !important;
    transition: all 0.2s ease !important;
}

.amount-input-group input#amount:focus {
    border-color: #ff0000 !important;
    box-shadow: 0 0 0 4px rgba(255, 0, 0, 0.1) !important;
}

#pay-button {
    width: 100% !important;
    height: 52px !important;
    background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%) !important;
    border: none !important;
    border-radius: 10px !important;
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 16px !important;
    font-weight: 800 !important;
    letter-spacing: 0.5px !important;
    cursor: pointer !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 8px 20px rgba(255, 0, 0, 0.3) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
}

#pay-button:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 12px 26px rgba(255, 0, 0, 0.4) !important;
}

.pay-security-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 18px;
    font-size: 13px;
    color: #64748b;
}

.pay-security-note i {
    color: #10b981;
}

.invalid-feedback {
    display: none;
    width: 100%;
    margin-top: .4rem;
    font-size: 13px;
    color: #ef4444;
    font-weight: 600;
}
</style>

<!--Page Header-->
<div class="pay-page-header title-area">
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12 site-breadcrumb">
                    <nav class="breadcrumb">
                        <a class="home" href="<?php echo e(route('website.index')); ?>"><span>Home</span></a>
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span class="active-crumb">Payment</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Page Header end-->

<section class="pay-section">
    <div class="container">
        <div class="row">
            <!-- Left Column: Bank Account Details Card -->
            <div class="col-lg-6 col-md-6">
                <div class="pay-card">
                    <div class="pay-card-header">
                        <i class="fa fa-university"></i>
                        <div>
                            <h4>Company Account Details</h4>
                            <p>NEFT / RTGS / NetBanking Information</p>
                        </div>
                    </div>
                    
                    <table class="bank-details-table">
                        <tr>
                            <th>Bank Name</th>
                            <td>Axis Bank Limited</td>
                        </tr>
                        <tr>
                            <th>Branch Name</th>
                            <td>Chittaranjan Park, New Delhi</td>
                        </tr>
                        <tr>
                            <th>Account No.</th>
                            <td>924020029877237</td>
                        </tr>
                        <tr>
                            <th>IFSC Code</th>
                            <td>UTIB0000430</td>
                        </tr>
                    </table>
                </div>
            </div>
           
            <!-- Right Column: Online Payment Card -->
            <div class="col-lg-6 col-md-6">
                <div class="pay-form-card">
                    <div class="fh-section-title clearfix text-left version-dark paddbtm40">
                        <h2>Payment</h2>
                    </div>

                    <form id="payment-form" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="fh-form-1 fh-form">
                            <div class="row fh-form-row">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="amount-input-group">
                                        <span class="currency-symbol">₹</span>
                                        <input type="number" class="form-control NumberValidate" id="amount" placeholder="Enter Amount*">
                                    </div>
                                    <div class="invalid-feedback">
                                        Please Enter a Valid Amount
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <p class="field submit" style="margin-bottom: 0;">
                                        <input value="Pay Now" id="pay-button" class="fh-btn" type="submit">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="pay-security-note">
                        <i class="fa fa-shield"></i> 100% Secure Transaction via Razorpay (UPI, Cards, NetBanking)
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
     
    

<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\coxfuturetech\gotogopost\resources\views/website/pay.blade.php ENDPATH**/ ?>