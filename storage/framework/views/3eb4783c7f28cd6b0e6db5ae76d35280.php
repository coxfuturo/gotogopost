<!DOCTYPE html>

<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
    <head>
        <meta charset="utf-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta name="description" content="Smarthr - Bootstrap Admin Template" />

        <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects" />

        <meta name="author" content="Dreamguys - Bootstrap Admin Template" />

        <title>Franchise|Register</title>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" type="text/css" />

        <!-- Favicon -->

        <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('admin/assets/img/favicon.png')); ?>" />

        <!-- Bootstrap CSS -->

        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>" />

        <!-- Fontawesome CSS -->

        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css')); ?>" />

        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/plugins/fontawesome/css/all.min.css')); ?>" />

        <!-- Lineawesome CSS -->

        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/line-awesome.min.css')); ?>" />

        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/material.css')); ?>" />

        <!-- Main CSS -->

        <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/style.css')); ?>" />
    </head>


    <body class="account-page">



        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>

        <!-- jQuery -->

        <script src="<?php echo e(asset('admin/assets/js/jquery-3.7.0.min.js')); ?>"></script>

        <!-- Bootstrap Core JS -->

        <script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>

        <!-- Custom JS -->

        <!-- Slimscroll JS -->
        <script src="<?php echo e(asset('admin/assets/js/jquery.slimscroll.min.js')); ?>"></script>
        <!-- <script src="<?php echo e(asset('admin/assets/js/select2.min.js')); ?>"></script> -->

        <script src="<?php echo e(asset('admin/assets/js/moment.min.js')); ?>"></script>
        <script src="<?php echo e(asset('admin/assets/js/bootstrap-datetimepicker.min.js')); ?>"></script>

        <script src="<?php echo e(asset('admin/assets/js/jquery.dataTables.min.js')); ?>"></script>
        <script src="<?php echo e(asset('admin/assets/js/dataTables.bootstrap4.min.js')); ?>"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
        <!-- Tagsinput JS -->

        <!-- Select2 JS -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

        <script src="<?php echo e(asset('admin/assets/js/app.js')); ?>"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

        <!-- Include SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>


        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <?php if(empty($payment)): ?>
<script>
// Razorpay Payment Handling
const amountInPaise = <?php echo json_encode($amount, 15, 512) ?>; 
const franchise_id = <?php echo json_encode($franchise_id, 15, 512) ?>; 

const options = {
    // key: "<?php echo e(env('RAZORPAY_KEY')); ?>", // Razorpay key
    key: "rzp_live_hZ7MLP0RaGm3Dx",
    // key: "rzp_test_hJW9oH6mPsYkla",
    amount: amountInPaise, // Amount in paise
    currency: "INR",
    name: "gotogopost.com",
    description: "Register Payment",
    image: "https://gotogopost.com/website/images/logonewtransparent.png",
    theme: {
        color: "#ff7529",
    },
    handler: function (response) {
        const paymentData = {
            razorpay_payment_id: response.razorpay_payment_id,
            final_amount: amountInPaise,
            franchise_id: franchise_id,
        };

        // Sending payment data using $.ajax()
        $.ajax({
            url: "<?php echo e(route('franchise.payment.store')); ?>", // Replace with your endpoint
            type: "POST",
            contentType: "application/json", // Set content type to JSON
            data: JSON.stringify(paymentData),
            headers: {
                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
            },
            success: function (paymentResponse) {

                console.log(paymentResponse)
                if (paymentResponse.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Payment Successful",
                        text: `${paymentResponse.message}`,
                        confirmButtonText: "Okay",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "<?php echo e(route('franchise.login')); ?>"; // Redirect to login
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Payment Failed",
                        text: `${paymentResponse.message || "An error occurred while processing the payment."}\nID: ${paymentResponse.data.id}\nPassword: ${paymentResponse.data.password}`,
                        confirmButtonText: "Okay",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "<?php echo e(route('franchise.login')); ?>"; // Redirect to login
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An error occurred while processing the payment.",
                    confirmButtonText: "Okay",
                });
                console.error("Payment AJAX request failed:", error);
            },
        });
    },
    modal: {
        ondismiss: function () {
            Swal.fire({
                icon: "error",
                text: "Payment Cancelled.",
                confirmButtonText: "Okay",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?php echo e(route('franchise.login')); ?>"; // Redirect to login
                }
            });
        },
    },
};

// Initialize Razorpay instance
const rzp = new Razorpay(options);
rzp.open();
</script>
<?php endif; ?>

    </body>
</html>
<?php /**PATH /home/gotogopost/public_html/resources/views/franchise/auth/makePayment.blade.php ENDPATH**/ ?>