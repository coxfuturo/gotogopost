<?php $__env->startSection('title'); ?> Rate Calculator <?php $__env->stopSection(); ?>



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





<div class="row staff-grid-row">

    <div class="row justify-content-center">

        <div class="col-lg-9 col-12 rate-calculator-left">

            <div class="assets-info">

                <h2>Shipping Details</h2>

                <form>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label for="pincode">Origin Pincode<span class="text-danger">*</span></label>

                            <div class="input-group mt-1">

                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                <input type="text" class="form-control NumberValidate " maxlength="6" id="originPincode" name="originPincode" value="" placeholder="Pincode.">

                                <div class="invalid-feedback">

                                    Please provide pincode

                                </div>

                                <div class="invalid-feedback validerror-origin">

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label for="pincode">Destination Pincode<span class="text-danger">*</span></label>

                            <div class="input-group mt-1">

                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                <input type="text" class="form-control NumberValidate " maxlength="6" id="destinationPincode" name="destinationPincode" value="" placeholder="Pincode.">

                                <div class="invalid-feedback">

                                    Please provide pincode

                                </div>

                                <div class="invalid-feedback validerror-destination">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label for="package_weight">Package Weight</label>

                            <div class="input-group mt-1">

                                <span class="input-group-text"><i class="fa fa-box"></i></span>

                                <input type="number" class="form-control NumberValidate " id="packageWeight" name="packageWeight" value="" placeholder="Package Weight">

                                <div class="invalid-feedback">

                                    Please provide weight

                                </div>



                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="text-dark mb-1">Service<span class="text-danger ">*</span></label>

                            <select name="paymentMethod" id="serviceType" class="select" data-tags="true" data-placeholder="Select an option">
                                <option value="">Select an option</option>
                                <option value="1"><?php echo e(\App\Models\Admin::GOTOGO_POST_SPEED); ?></option>
                                <option value="3"><?php echo e(\App\Models\Admin::GOTOGO_POST_BUSINESS); ?></option>
                                <option value="4"><?php echo e(\App\Models\Admin::GOTOGO_POST_REGISTERED); ?></option>
                                <option value="5"><?php echo e(\App\Models\Admin::INDIA_POST_SPEED); ?></option>
                                <option value="6"><?php echo e(\App\Models\Admin::INDIA_POST_BUSINESS); ?></option>
                            </select>

                            <div class="invalid-feedback select-error">

                                Please provide payment Method

                            </div>



                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3" id="toggle-element" style="display:none">

                            <label for="package_weight">Cod Amount</label>

                            <div class="input-group mt-1">

                                <span class="input-group-text"><i class="fa fa-building"></i></span>

                                <input type="text" class="form-control NumberValidate " id="codAmount" name="codAmount" value="" placeholder="Cod Amount">

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

        </div>

        <div class="col-lg-5 col-12 rate-details" style="display: none">

            <div class="assets-info">

                <h2>Price Details</h2>

                <ul>

                    <li class="d-flex flex-wrap">

                        <span>Zone</span>

                        <p class="zonep"></p>

                    </li>

                    <li class="d-flex flex-wrap">

                        <span>Price</span>

                        <p class="price"></p>

                    </li>

                    <li>

                        <span>GST</span>

                        <p class="gstp">345</p>

                    </li>

                    <li>

                        <span>COD Amount</span>

                        <p class="codAmountp">₹ 1,200</p>

                    </li>

                    <li>

                        <span>Total</span>

                        <p class="totalp">₹ 24324</p>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</div>





<?php $__env->startPush('page-javascript'); ?>





<script>


    $('form').on('submit', function(e) {

        e.preventDefault();

        if ($('#originPincode').val() === '') {

            $('#originPincode').next().css('display', 'block');

            // return;

        } else {

            $('#originPincode').next().css('display', 'none');

            $('.validerror-origin').css('display', 'none');

        }

        if ($('#destinationPincode').val() === '') {

            $('#destinationPincode').next().css('display', 'block');

            // return;

        } else {

            $('#destinationPincode').next().css('display', 'none');

            $('.validerror-destination').css('display', 'none');

        }

        if ($('#packageWeight').val() === '') {

            $('#packageWeight').next().css('display', 'block');

            return;

        } else {

            $('#packageWeight').next().css('display', 'none');

        }

        if ($('#paymentMethod').val() === '') {

            $('.select-error').css('display', 'block');

            return;

        }

        var csrfToken = "<?php echo e(csrf_token()); ?>";

        let data = {

            originPincode: $('#originPincode').val(),

            destinationPincode: $('#destinationPincode').val(),

            packageWeight: $('#packageWeight').val(),

            service_type: $('#serviceType').val(),

        }



        $.ajax({

            type: 'POST',

            url: "<?php echo e(route('franchise.rateCalculator.calculate')); ?>",

            headers: {

                'X-CSRF-TOKEN': csrfToken

            },

            data: data,

            success: function(data) {

                $('.validerror-destination').css('display', 'none');

                if (data.status == 'success') {

                    $('.zonep').text(data.zone)

                    $('.gstp').text(`₹ ${data.gst}`)
                    $('.price').text(`₹ ${data.price}`)

                    if (data.codAmount) {

                        $('.codAmountp').text(`₹ ${data.codAmount}`)

                        $('.codAmountp').closest('li').show()

                    } else {

                        $('.codAmountp').closest('li').hide()

                    }

                    $('.totalp').text(`₹ ${data.total}`)

                    $('.rate-calculator-left').removeClass('col-lg-9').addClass('col-lg-7');

                    $('.rate-details').css('display', 'block');



                } else {

                    if (data.message == 'fill valid distination pincode') {

                        $('.validerror-destination').text('Please enter a valid pincode');

                        $('.validerror-destination').css('display', 'block');

                    }

                    if (data.message == 'fill valid origin pincode') {

                        $('.validerror-origin').text('Please enter a valid pincode');

                        $('.validerror-origin').css('display', 'block');

                    }



                }

            },

            error: function(jqXHR, textStatus, errorThrown) {

                $('.error-message').text("An error occurred. Please try again.");

            }

        });

    });

</script>



<?php $__env->stopPush(); ?>





<?php $__env->stopSection(); ?>
<?php echo $__env->make('franchise.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/franchise/rateCalculator/index.blade.php ENDPATH**/ ?>