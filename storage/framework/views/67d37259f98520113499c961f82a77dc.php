<?php $__env->startSection('title'); ?> India Post Commission Management <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<?php if($errors->any()): ?>
<div class="alert alert-danger">
    <ul>

        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <li><?php echo e($error); ?></li>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </ul>

</div>

<?php endif; ?>


<style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 4px;
        text-align: center;
    }

    th {
        background-color: #e4e4e4;
    }

    caption {
        caption-side: top;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .add-btn {
        background-color: #ff9b44;
        border: 1px solid #ff9b44;
        color: #ffffff;
        float: right;
        font-weight: 500;
        min-width: 74px;
        border-radius: 50px;
        padding: 3px;
    }
</style>



<div class="row">

    <div class="col-md-12">
       
        <div class="table-responsive">
            <div class="container-fluid">
                <div class="row">
                    <h4 colspan="1"> Commission for booking of inland speed post articals (Document & Parcel)</h4>
                    <div class="col-md-4 table-container">
                        <table class="table-custom w-100">
                            <thead>
                                <tr>
                                    <th colspan="2">Speed Packet 
                                    <?php if($india_commission->count()>0): ?>
                                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#edit_speed">Edit</a>
                                    <?php else: ?>
                                    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_speed">Add</a> 
                                    <?php endif; ?>
                                </th>
                                </tr>
                                <tr>
                                    <td>Monthly revenue</td>
                                    <td>Commission</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $india_commission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rates): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($rates->india_post_monthly_revenue); ?></td>
                                    <td><?php echo e($rates->india_post_commission); ?>%</td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

</div>

<!-- Add speed Modal -->
<div id="add_speed" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Amount For Speed Packet</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="<?php echo e(route('admin.india-post-commission.store')); ?>" method="POST" enctype="multipart/form-data">

                    <?php echo csrf_field(); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">Up To RS. 2,00,000/-  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['upto_2_lakh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="upto_2_lakh" name="upto_2_lakh" placeholder="Up To RS. 2,00,000" value="<?php echo e(old('upto_2_lakh')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 2,00,001 to 5,00,000/- <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['between_2_to_5_lakh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="between_2_to_5_lakh" name="between_2_to_5_lakh" placeholder="RS. 2,00,001 to 5,00,000" value="<?php echo e(old('between_2_to_5_lakh')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 5,00,001 to 10,00,000/-<span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['between_5_to_10_lakh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="between_5_to_10_lakh" name="between_5_to_10_lakh" placeholder="RS. 5,00,001 to 10,00,000" value="<?php echo e(old('between_5_to_10_lakh')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 10,00,001 to and above <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['between_10_to_25_lakh_and_above'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="between_10_to_25_lakh_and_above" name="between_10_to_25_lakh_and_above" placeholder="RS. 10,00,001 to and above" value="<?php echo e(old('between_10_to_25_lakh_and_above')); ?>" required>
                            </div>
                        </div>

                    </div>

                    <div class="submit-section">

                        <button class="btn btn-primary submit-btn">Submit</button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<!-- /Add speed Modal -->

<!-- edit speed Modal -->
<?php if($india_commission->count()>0): ?>

<div id="edit_speed" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Edit Amount For Speed Packet</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">


                <form action="<?php echo e(route('admin.india-post-commission.edit')); ?>" method="POST" enctype="multipart/form-data">

                    <?php echo csrf_field(); ?>

                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">Up To RS. 2,00,000/-  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['upto_2_lakh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="upto_2_lakh" name="upto_2_lakh" placeholder="Up To RS. 2,00,000" value="<?php echo e(old('upto_2_lakh', $india_commission[0]['india_post_commission'])); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 2,00,001 to 5,00,000/- <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['between_2_to_5_lakh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="between_2_to_5_lakh" name="between_2_to_5_lakh" placeholder="RS. 2,00,001 to 5,00,000" value="<?php echo e(old('between_2_to_5_lakh', $india_commission[1]['india_post_commission'])); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-block mb-6">
                                <label class="col-form-label">RS. 5,00,001 to 10,00,000/-<span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['between_5_to_10_lakh'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="between_5_to_10_lakh" name="between_5_to_10_lakh" placeholder="RS. 5,00,001 to 10,00,000" value="<?php echo e(old('between_5_to_10_lakh', $india_commission[2]['india_post_commission'])); ?>" required>
                            </div>
                        </div>
                       
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
<?php endif; ?>
<!-- /edit speed Modal -->


<div class="row">
    <div class="col-sm-6">
        <h4 style="border:1px solid #e4e4e4;padding: 7px;font-size:16px;margin-left:10px">1. If the amount is less than ₹35, a commission of ₹3 will be given.</h4>
       <h4 style="border:1px solid #e4e4e4;padding: 7px;font-size:16px;margin-left:10px">
       2. If the amount is ₹35 or more, a commission of ₹5 per parcel will be given.</h4>
    </div>
</div>


<!-- Delete  Modal -->

<div class="modal custom-modal fade" id="delete_modal" role="dialog">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-body">

                <div class="form-header">

                    <h3>Delete</h3>

                    <p>Are you sure want to delete?</p>

                </div>

                <div class="modal-btn delete-action">

                    <div class="row">

                        <div class="col-6">

                            <a href="" id="delete_button" class="btn btn-primary continue-btn">Delete</a>

                        </div>

                        <div class="col-6">

                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- /Delete  Modal -->



<?php $__env->startPush('page-javascript'); ?>



<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/india-post-commission/index.blade.php ENDPATH**/ ?>