
<?php $__env->startSection('title'); ?> Create Customer  <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo e(session('success')); ?>',
        });
    <?php endif; ?>

    <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '<?php echo e(session('error')); ?>',
        });
    <?php endif; ?>
</script>

<style>

     label{
        background:#FF671F;
        color: #fff;
        width: 100%;
        border-radius: 10px;
    padding-left: 10px;
    } 
</style>

    <div class="card">
    <div class="row card-body">
        <!-- Search Key Input -->

        <!-- Search Button -->
        <div class="col-sm-12 text-end">
                <a href="<?php echo e(route('market.customer.index')); ?>"><button type="submit" class="btn marketGreenBackground" style="width:fit-content;float:right;">Back</button></a>
            
        </div>

        

    </div>
</div>


<form action="<?php echo e(route('market.customer.store')); ?>" method="post">
    <?php echo csrf_field(); ?>
<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="row">

                <div class="col-sm-12 mb-3" >
                      <h3 class="pt-3 marketGreenColor" style="border-bottom: 1px solid #046A38">Customer Registration</h3>
                    
                  </div>

                    <div class="col-sm-6 mb-3">
                         <label for="name" class="form-label">Name</label>
                         <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name." required>
                          <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="mobile" class="form-label">Mobile</label>
                         <input type="number" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile No." required>
                         <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="email" class="form-label">Email</label>
                         <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email id.">
                        
                    </div>

                     <div class="col-sm-6 mb-3">
                         <label for="register_type" class="form-label"> Partner Type</label>
                         <select class="form-select type" name="register_type" id="register_type" required>
                            <option value="proprietor">Proprietorship Firm</option>
                            <option value="partner">Partnership Firm</option>
                            <option value="private">Private Limited</option>
                         </select>
                         <?php $__errorArgs = ['register_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="gst_no" class="form-label">GST No</label>
                         <input type="text" class="form-control" id="gst_no" name="gst_no" placeholder="Enter GST No.">
                         
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="state" class="form-label">State</label>
                         <input type="text" class="form-control" id="state" name="state" placeholder="Enter State." required>
                         <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="city" class="form-label">City</label>
                         <input type="text" class="form-control" id="city" name="city" placeholder="Enter City." required>
                         <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>


                    <div class="col-sm-6 mb-3">
                         <label for="pincode" class="form-label">Pincode</label>
                         <input type="number" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode." required>
                         <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                     <div class="col-sm-6 mb-3">
                         <label for="franchise_id" class="form-label">Franchise</label>
                         <select name="franchise_id" class="form-select location_dropdown" required>
                             <option select disabled>Select Franchise</option>
                         </select>
                         <?php $__errorArgs = ['franchise_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="address" class="form-label">Address</label>
                         <textarea class="form-control" id="address" rows="3" name="address"></textarea>
                         <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                 <small class="text-danger"><?php echo e($message); ?></small>
                          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-sm-12 mb-3 text-center">
            
                         <button type="submit" class="btn marketGreenBackground" style="margin-top: 50px">Create</button>
                    </div>



                </div>
                   

            </div>
        </div>
    </div>
</div>
</form>


<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:700,300);
</style>
<?php 
//   echo"<script>
//     Swal.fire({
//             icon: 'error',
//             title: 'Oops!',
//             text: 'sdafa',
//         });
//   </script>";
?>


<?php $__env->startPush('page-javascript'); ?>
<script type="text/javascript">

            $(document).ready(function () {
                $("#city").on("keyup", function () {
                    let city = $(this).val();
                    var csrfToken = "<?php echo e(csrf_token()); ?>";

                    $.ajax({
                        url: "<?php echo e(route('customer.get.location')); ?>",
                        type: "POST",
                        data: {
                            city: city,
                            _token: csrfToken, // token yahan hai
                        },
                        success: function (response) {
                        
                            $(".location_dropdown").html(response);
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        },
                    });
                });
            });
        <!--End Filter Location -->
</script>




<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('market.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/market/customer/create.blade.php ENDPATH**/ ?>