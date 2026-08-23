<?php echo $__env->make('website.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header title-area">
    <div class="header-title">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h1 class="page-title">Delete Your Account</h1>
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
                        <span>Delete Account</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Delete Account Section -->
<section class="delete-account-section" style="margin-top: 15px; margin-bottom: 15px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="fh-section-title text-center">
                    <h2>Delete Account</h2>
                </div>
                <form id="delete-account-form" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="fh-form">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your registered phone number" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="text-center">
                            <button type="button" id="verify-button" class="btn btn-warning">Verify</button>
                        </div>
                    </div>
                </form>

                <div id="confirm-delete-section" class="text-center" style="display: none; margin-top: 20px;">
                    <p>Are you sure? Your account will be permanently deleted!</p>
                    <button type="button" id="delete-button" class="btn btn-danger">Delete Account</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('verify-button').addEventListener('click', function() {
        let phone = document.getElementById('phone').value;
        let password = document.getElementById('password').value;

        if (phone.trim() === '' || password.trim() === '') {
            Swal.fire('Error', 'Please enter both phone and password', 'error');
            return;
        }

        // Simulate verification (You should perform actual verification in the backend)
        Swal.fire({
            title: 'Verified Successfully',
            text: 'Click delete to remove your account permanently.',
            icon: 'success'
        }).then(() => {
            document.getElementById('confirm-delete-section').style.display = 'block';
        });
    });

    document.getElementById('delete-button').addEventListener('click', function() {
        let formData = new FormData(document.getElementById('delete-account-form'));

        fetch("<?php echo e(route('website.deleteUserAccount')); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Deleted!', 'Your account has been deleted.', 'success').then(() => {
                    window.location.href = '/';
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'Something went wrong. Please try again later.', 'error');
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/website/user-delete.blade.php ENDPATH**/ ?>