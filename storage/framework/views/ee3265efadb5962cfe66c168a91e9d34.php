<?php $__env->startSection('title'); ?> <?php echo e(\App\Models\Admin::INDIA_POST_SPEED); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<!-- Row -->

<div class="row">

    <div class="col-sm-12">



        <!-- Custom Boostrap Validation -->

        <div class="card">

            <form action="<?php echo e(route('franchise.india-post-speed-post.edit', $data->id)); ?>" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>

                <?php echo csrf_field(); ?>

                <div class="card-header">

                    <h5 class="card-title mb-0">Pickup Details</h5>

                </div>

                <div class="card-body">

                    <?php echo $__env->make('admin.layouts.error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="row">

                        <div class="col-sm">

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label class="text-dark">Select Pickup details<span class="text-danger">*</span></label>

                                    <select name="pickup-details" id="pickup-details" class="select" data-tags="true" data-placeholder="Select an option">

                                        <option value="">Select an option</option>

                                        <?php $__currentLoopData = $pickupDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> </option>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="PickupName">Name <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-user"></i></span>

                                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="PickupName" name="PickupName" placeholder="Name" value="<?php echo e(old('PickupName', $data ? $data->pickup_name : '')); ?>" required>

                                        <div class="invalid-feedback">

                                            Please provide your name

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="PickupMobile">Mobile <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="10" id="PickupMobile" name="PickupMobile" value="<?php echo e(old('PickupMobile', $data ? $data->pickup_mobile : '')); ?>" placeholder="Mobile." required>

                                        <div class="invalid-feedback">

                                            Please provide mobile number

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label for="PickupEmail">Email <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="PickupEmail" name="PickupEmail" placeholder="Email ID." value="<?php echo e(old('PickupEmail', $data ? $data->pickup_email : '')); ?>" >

                                        <div class="invalid-feedback">

                                            Please provide emailId

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="PickupPincode">Pincode <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="6" id="PickupPincode" name="PickupPincode" value="<?php echo e(old('PickupPincode', $data ? $data->pickup_pincode : '')); ?>" placeholder="Pincode." required>

                                        <div class="invalid-feedback">

                                            Please provide pincode

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="PickupCity">City <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <input type="text" class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="PickupCity" name="PickupCity" placeholder="City." value="<?php echo e(old('PickupCity', $data ? $data->pickup_city : '')); ?>" required>

                                        <div class="invalid-feedback">

                                            Please provide city

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label for="PickupState">State <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                        <input type="text"  class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="PickupState" name="PickupState" value="<?php echo e(old('PickupState', $data ? $data->pickup_state : '')); ?>" required>

                                        <div class="invalid-feedback">

                                            Please provide state

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="PickupAddress">Address <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="PickupAddress" name="PickupAddress" placeholder="Type address..." required><?php echo e(old('PickupAddress', $data ? $data->pickup_address : '')); ?></textarea>

                                        <input type="hidden" name="latitude" value="<?php echo e(old('PickupLatitude', $data ? $data->pickup_latitude : '')); ?>" id="latitude">

                                        <input type="hidden" name="longitude" value="<?php echo e(old('PickupLongitude', $data ? $data->pickup_longitude : '')); ?>" id="longitude">

                                        <div class="invalid-feedback">

                                            Please provide address

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="card-header">

                    <h5 class="card-title mb-0">Consignee Details</h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-sm">

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label for="ConsigneeName">Name <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-user"></i></span>

                                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ConsigneeName" name="ConsigneeName" placeholder="Name" value="<?php echo e(old('ConsigneeName', $data ? $data->consignee_name : '')); ?>" required>

                                        <div class="invalid-feedback">

                                            Please provide your name

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="ConsigneeMobile">Mobile <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="10" id="ConsigneeMobile" name="ConsigneeMobile" value="<?php echo e(old('ConsigneeMobile', $data ? $data->consignee_mobile : '')); ?>" placeholder="Mobile." >

                                        <div class="invalid-feedback">

                                            Please provide mobile number

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="ConsigneeEmail">Email <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ConsigneeEmail" name="ConsigneeEmail" placeholder="Email ID." value="<?php echo e(old('ConsigneeEmail', $data ? $data->consignee_email : '')); ?>" >

                                        <div class="invalid-feedback">

                                            Please provide emailId

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label for="ConsigneePincode">Pincode <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="6" id="ConsigneePincode" name="ConsigneePincode" value="<?php echo e(old('ConsigneePincode', $data ? $data->consignee_pincode : '')); ?>" placeholder="Pincode." required>

                                        <div class="invalid-feedback">

                                            Please provide pincode

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="ConsigneeCity">City <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <input type="text" class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ConsigneeCity" name="ConsigneeCity" placeholder="City." value="<?php echo e(old('ConsigneeCity', $data ? $data->consignee_city : '')); ?>" required>

                                        <div class="invalid-feedback">

                                            Please provide city

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="ConsigneeState">State <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>

                                        <input type="text"  class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ConsigneeState" name="ConsigneeState" value="<?php echo e(old('ConsigneeState', $data ? $data->consignee_state : '')); ?>" required>

                                        <div class="invalid-feedback">

                                            Please provide state

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label for="ConsigneeAddress">Address <span class="text-danger">*</span></label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-building"></i></span>

                                        <textarea class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ConsigneeAddress" name="ConsigneeAddress" placeholder="Type address..." required><?php echo e(old('ConsigneeAddress', $data ? $data->consignee_address : '')); ?></textarea>

                                        <input type="hidden" name="latitude" value="<?php echo e(old('ConsigneeLatitude', $data ? $data->consignee_latitude : '')); ?>" id="latitude">

                                        <input type="hidden" name="longitude" value="<?php echo e(old('ConsigneeLongitude', $data ? $data->consignee_longitude : '')); ?>" id="longitude">

                                        <div class="invalid-feedback">

                                            Please provide address

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label class="text-dark">Payment Method<span class="text-danger">*</span></label>

                                    <select name="payment_method" id="paymentMethod" class="select" data-tags="true" data-placeholder="Select an option" required>

                                        <option value="">Select an option</option>

                                        <option <?php echo e($data && $data->payment_method == 'Cod' ? 'selected' : ''); ?> value="cod">Cash on delivery</option>

                                        <option <?php echo e($data && $data->payment_method == 'Prepaid' ? 'selected' : ''); ?> value="prepaid">Prepaid</option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-header">

                    <h5 class="card-title mb-0">Parcel Details</h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-sm">

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label for="package_weight">Package Weight</label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-box"></i></span>

                                        <input type="text" class="form-control NumberValidate <?php $__errorArgs = ['package_weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" maxlength="12" id="package_weight" name="package_weight" value="<?php echo e(old('package_weight', $data ? $data->package_weight : '')); ?>" placeholder="Package Weight">

                                        <?php $__errorArgs = ['package_weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                        <div class="invalid-feedback"><?php echo e($message); ?></div>

                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="package_length">Package Length</label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-box"></i></span>

                                        <input type="text" class="form-control <?php $__errorArgs = ['package_length'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="package_length" name="package_length" value="<?php echo e(old('package_length', $data ? $data->package_length : '')); ?>" placeholder="Package Length">

                                        <?php $__errorArgs = ['package_length'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                        <div class="invalid-feedback"><?php echo e($message); ?></div>

                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                    </div>

                                </div>

                                <div class="col-md-4 mb-3">

                                    <label for="package_width">Package Width</label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-box"></i></span>

                                        <input type="text" class="form-control <?php $__errorArgs = ['package_width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="package_width" name="package_width" value="<?php echo e(old('package_width', $data ? $data->package_width : '')); ?>" placeholder="Package Width">

                                        <?php $__errorArgs = ['package_width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                        <div class="invalid-feedback"><?php echo e($message); ?></div>

                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">

                                    <label for="package_height">Package Height</label>

                                    <div class="input-group">

                                        <span class="input-group-text"><i class="fa fa-box"></i></span>

                                        <input type="text" class="form-control <?php $__errorArgs = ['package_height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="package_height" name="package_height" value="<?php echo e(old('package_height', $data ? $data->package_height : '')); ?>" placeholder="Package Height">

                                        <?php $__errorArgs = ['package_height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                                        <div class="invalid-feedback"><?php echo e($message); ?></div>

                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-sm">

                            <div class="text-center">

                                <button class="btn btn-primary" type="submit">Submit</button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

        <!-- /Custom Boostrap Validation -->



    </div>

</div>

<!-- /Row -->



<?php $__env->startPush('page-javascript'); ?>





<script>

    $('#pincode').on('change', function() {

        var pincode = $('#pincode').val();

        var name = $('#name').val();

        var firstThreeNumbers = pincode.substring(0, 3);

        var generateID = name + firstThreeNumbers + '@post.in';

        $.ajax({

            type: 'GET',

            url: "<?php echo e(url('admin/city-state')); ?>" + '/' + pincode,

            success: function(data) {

                if (data.success === true) {

                    $('.customer_error').empty();

                    $('#district').empty().val(data.district);

                    $('#state').empty().val(data.state);

                    $('#generated_id').empty().val(generateID);

                } else {

                    $('.validerror').text('Please enter valid pincode');

                }

            }

        });

    });





    $('#pickup-details').on('change', function(e) {

        var csrfToken = "<?php echo e(csrf_token()); ?>";



        $.ajax({

            type: 'POST',

            url: "<?php echo e(route('franchise.pickup-details.details')); ?>",

            headers: {

                'X-CSRF-TOKEN': csrfToken

            },

            data: {

                id: e.target.value

            },

            success: function(data) {

                if (data) {

                    $('#PickupName').val(data.name);

                    $('#PickupMobile').val(data.phone);

                    $('#PickupEmail').val(data.email);

                    $('#PickupPincode').val(data.pincode);

                    $('#PickupCity').val(data.city);

                    $('#PickupState').val(data.state);

                    $('#PickupAddress').val(data.address);



                } else {

                    $('.validerror').text('Please enter a valid pincode');

                }

            }

        });

    });



    function displayFile(id) {

        const fileInput = document.getElementById(`fileInput${id}`);

        const fileContainer = document.getElementById(`fileContainer${id}`);



        // Clear any previous content

        fileContainer.innerHTML = "";



        // Check if a file is selected

        if (fileInput.files.length === 0) {

            fileContainer.innerHTML = "<p>No file selected.</p>";

            return;

        }

        const file = fileInput.files[0];

        const fileType = file.type;



        if (fileType === "application/pdf") {

            // Display PDF

            const reader = new FileReader();

            reader.onload = function(e) {

                const pdfData = e.target.result;

                const embed = document.createElement("embed");

                embed.setAttribute("src", pdfData);

                embed.setAttribute("type", "application/pdf");

                embed.style.width = "285px";

                embed.style.height = "130px";

                fileContainer.appendChild(embed);

            };

            reader.readAsDataURL(file);

        } else if (fileType.startsWith("image/")) {

            // Display image

            const img = document.createElement("img");

            img.setAttribute("src", URL.createObjectURL(file));

            img.style.width = "100%";

            img.style.height = "100%";

            fileContainer.appendChild(img);

        } else {

            fileContainer.innerHTML = "<p>Unsupported file type.</p>";

        }

    }



    function forceUpper(strInput) {

        strInput.value = strInput.value.toUpperCase();

    }



    var bankIfsc = $('#ifsc_code');

    var bankIfscError = $('#bank_ifsc_error');

    var bankName = $('#bank_name');

    var bankBranch = $('#branch_name');



    bankIfsc.on('input', function() {

        var ifscCode = bankIfsc.val();

        if (ifscCode.length === 11) {

            $.ajax({

                'url': 'https://ifsc.razorpay.com/' + ifscCode,

                'success': function(res, status, xhr) {

                    if (xhr.status === 200) {

                        bankName.val(res.BANK);

                        bankBranch.val(res.BRANCH);

                        bankIfscError.html('');



                    }

                },

                'error': function(xhr, status, error) {

                    if (xhr.status === 404) {

                        bankIfscError.siblings('.backendError').remove();

                        bankIfscError.html('Please enter a valid IFSC Code');

                    }

                }

            })

        } else {

            bankName.val('');

            bankBranch.val('');

            bankIfscError.html('');

        }

    });

</script>



<script src="https://maps.google.com/maps/api/js?key=AIzaSyARf505VVJ_bn-5BnQ5qFbyKqWGF4DRn9U&libraries=places&callback=initAutocomplete" type="text/javascript"></script>



<script>

    google.maps.event.addDomListener(window, 'load', initialize);



    function initialize() {

        var input = document.getElementById('address');

        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {

            var place = autocomplete.getPlace();

            $('#latitude').val(place.geometry['location'].lat());

            $('#longitude').val(place.geometry['location'].lng());

        });

    }

</script>

<?php $__env->stopPush(); ?>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('franchise.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/franchise/indiaPost-speedPost/edit.blade.php ENDPATH**/ ?>