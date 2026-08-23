<?php $__env->startSection('title'); ?> Pickup Enquiry <?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>


<style>
    table.dataTable>thead .sorting:before,
    table.dataTable>thead .sorting_asc:before,
    table.dataTable>thead .sorting_desc:before,
    table.dataTable>thead .sorting_asc_disabled:before,
    table.dataTable>thead .sorting_desc_disabled:before {
        right: 1em;
        content: "↑";
        top: 3px;
    }

    table.dataTable>thead .sorting:after,
    table.dataTable>thead .sorting_asc:after,
    table.dataTable>thead .sorting_desc:after,
    table.dataTable>thead .sorting_asc_disabled:after,
    table.dataTable>thead .sorting_desc_disabled:after {
        right: .5em;
        content: "↓";
        top: 4px;
    }

    .fixed-width-select {
        width: 200px;
    }

    #pickupDetailsTable_info {
        display: none;
    }

    #pickupDetailsTable_paginate {
        display: none;
    }

    .pagination-container {
        display: flex;
        justify-content: end;
        padding: 5px 10px;
    }

    /* Custom styling for the select box */
    .fixed-width-select {
        width: 180px;
        /* Adjust width */
        padding: 3px 8px;
        /* Reduce padding */
        font-size: 14px;
        /* Set font size */
        height: 30px;
        /* Set height to make it more compact */
        border-radius: 5px;
        /* Rounded corners */
        border: 1px solid #ddd;
        /* Border color */
        background-color: #fff;
        /* Background color */
    }

    /* Adjusting the select2 dropdown container */
    .select2-container {
        width: 180px !important;
        /* Make sure the select2 container matches the width */
    }

    .select2-selection--single {
        height: 30px !important;
        /* Set height of the select box */
        padding: 3px 8px !important;
        /* Reduce padding inside the select box */
    }

    .select2-selection__rendered {
        line-height: 28px !important;
        /* Adjust the text alignment inside the select box */
    }

    .select2-selection__arrow {
        height: 30px !important;
        /* Match the height of the arrow */
    }

    /* Adjust the dropdown list items height */
    .select2-results__option {
        padding: 6px 10px !important;
        /* Adjust padding for list items */
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        cursor: pointer;
        float: right;
        font-weight: bold;
        height: 26px;
        margin-right: 20px;
        padding-right: 0px;
        display: none;
    }

    .dataTables_length {
        display: none;
    }
</style>



<?php $__env->startPush('add-modal-code'); ?>

<div class="col-auto float-end ms-auto">
    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role_user"><i class="fa-solid fa-plus"></i> Add</a>
</div>

<?php $__env->stopPush(); ?>

<!-- Search Filter -->

<form action="<?php echo e(route('franchise.pickup-details.index')); ?>" method="GET">
    <div class="row">
        <!-- Search Key Input -->

        <input type="hidden" name="insert_type" value="<?php echo e(request()->query('insert_type')); ?>">
        <div class="col-sm-3 col-md-3">
            <div class="input-block mb-3 form-focus">
                <input type="text" name="searchKey" class="form-control floating input2" value="<?php echo e(request('searchKey')); ?>">
                <label class="focus-label">Search</label>
            </div>
        </div>


        <!-- From Date Input -->
        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input name="fromDate" class="form-control floating datetimepicker fromDate" type="text" value="<?php echo e(request('fromDate')); ?>">
                </div>
                <label class="focus-label">From</label>
            </div>
        </div>

        <!-- To Date Input -->
        <div class="col-sm-3 col-md-3 col-lg-3 col-xl-2 col-12">
            <div class="input-block mb-3 form-focus">
                <div class="cal-icon">
                    <input name="toDate" class="form-control floating datetimepicker toDate" type="text" value="<?php echo e(request('toDate')); ?>">
                </div>
                <label class="focus-label">To</label>
            </div>
        </div>

        <!-- Search Button -->
        <div class="col-sm-6 col-md-1">
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Search</button>
            </div>
        </div>
    </div>
</form>

<!-- Search Filter -->

<?php if($errors->any()): ?>

<div class="alert alert-danger">

    <ul>

        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <li><?php echo e($error); ?></li>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </ul>

</div>

<?php endif; ?>




<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable" id="pickupDetailsTable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>AssignTo</th>
                                <th>Address</th>
                                <th>Pincode</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pickupdetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i + 1); ?></td>
                                <td>
                                    <h2 class="table-avatar"><a href="#"><?php echo e($user->name); ?></a></h2>
                                </td>
                                <td><?php echo e($user->email); ?></td>
                                <td><?php echo e($user->phone); ?></td>
                                <td>
                                    <form action="<?php echo e(route('franchise.pickup-details.updateStatus', $user->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <select name="status" class="form-select fixed-width-select" onchange="this.form.submit()">
                                            <option value="0" <?php echo e($user->status == 0 ? 'selected' : ''); ?>>Pending</option>
                                            <option value="1" <?php echo e($user->status == 1 ? 'selected' : ''); ?>>Confirmed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('franchise.pickup-details.assignDeliveryBoy', $user->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <select name="deliveryboy_id" class="form-select fixed-width-select" onchange="this.form.submit()">
                                            <option value="">Not Assigned</option>
                                            <?php $__currentLoopData = $deliveryBoys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($boy->id); ?>" <?php echo e($user->deliveryboy_id == $boy->id ? 'selected' : ''); ?>>
                                                <?php echo e($boy->name); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </form>
                                </td>
                                <td><?php echo e($user->address); ?></td>
                                <td><?php echo e($user->pincode); ?></td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role_user<?php echo e($user->id); ?>"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="#" onclick="delete_modal('<?php echo e($user->id); ?>')"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <!-- update Client Modal -->

                            <div id="edit_role_user<?php echo e($user->id); ?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">Update Pickup Details</h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                                                <span aria-hidden="true">&times;</span>

                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <form action="<?php echo e(route('franchise.pickup-details.update',$user->id)); ?>" method="POST" enctype="multipart/form-data">

                                                <?php echo csrf_field(); ?>

                                                <div class="row">

                                                    <div class="col-md-4">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Name <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="name" placeholder="Enter name" type="text" value="<?php echo e(old('name',$user->name)); ?>" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Email <span class="text-danger">*</span></label>

                                                            <input class="form-control floating" name="email" placeholder="Enter email" type="email" value="<?php echo e(old('email',$user->email)); ?>" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Phone <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="phone" placeholder="Enter phone" type="number" value="<?php echo e(old('phone',$user->phone)); ?>" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">City <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="city" placeholder="Enter city" type="text" value="<?php echo e(old('city',$user->city)); ?>" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">State <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="state" placeholder="Enter State" type="text" value="<?php echo e(old('state',$user->state)); ?>" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Pincode <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="pincode" placeholder="Enter Pincode" type="text" value="<?php echo e(old('pincode',$user->pincode)); ?>" required>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="input-block mb-3">

                                                            <label class="col-form-label">Address <span class="text-danger">*</span></label>

                                                            <input class="form-control" name="address" placeholder="Enter Address" type="text" value="<?php echo e(old('address',$user->address)); ?>" required>

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

                            <!-- update Client Modal -->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <!-- Pagination links -->
                    <div class="pagination-container">
                        <?php echo e($pickupdetails->appends(request()->query())->links()); ?>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<!-- Add Client Modal -->

<div id="add_role_user" class="modal custom-modal fade" role="dialog">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Pickup Details</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="<?php echo e(route('franchise.pickup-details.store')); ?>" method="POST" enctype="multipart/form-data">

                    <?php echo csrf_field(); ?>

                    <div class="row">

                        <div class="col-md-4">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Name <span class="text-danger">*</span></label>

                                <input class="form-control" name="name" placeholder="Enter name" type="text" value="<?php echo e(old('name')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Email <span class="text-danger">*</span></label>

                                <input class="form-control floating" name="email" placeholder="Enter email" type="email" value="<?php echo e(old('email')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Phone <span class="text-danger">*</span></label>

                                <input class="form-control" name="phone" placeholder="Enter phone" type="number" value="<?php echo e(old('phone')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">City <span class="text-danger">*</span></label>

                                <input class="form-control" name="city" placeholder="Enter city" type="text" value="<?php echo e(old('city')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">State <span class="text-danger">*</span></label>

                                <input class="form-control" name="state" placeholder="Enter City" type="text" value="<?php echo e(old('city')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Pincode <span class="text-danger">*</span></label>

                                <input class="form-control" name="pincode" placeholder="Enter Pincode" type="text" value="<?php echo e(old('pincode')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Address <span class="text-danger">*</span></label>

                                <input class="form-control" name="address" placeholder="Enter Address" type="text" value="<?php echo e(old('address')); ?>" required>

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

<!-- /Add Client Modal -->




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

<script type="text/javascript">
    function delete_modal(id) {

        console.log("reached here", id)

        const deleteUrl = "<?php echo e(route('franchise.pickup-details.delete', ['id' => ':id'])); ?>".replace(':id', id);

        $('#delete_button').attr('href', deleteUrl);

        $('#delete_modal').modal('show');

    }



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



    function status_update(id, status)

    {

        var className = "active-inactive-menu-" + id;

        $.ajax({

            url: "<?php echo e(route('admin.user.status')); ?>",

            type: "POST",

            data: {

                "_token": "<?php echo e(csrf_token()); ?>",

                id: id,

                status: status,

            },

            success: function(data) {

                console.log("reached here", data)

                if (data.success == true)

                {

                    Swal.fire('Success!', "Status updated", 'success');

                    if (data.status == 0) {

                        var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>');

                        $('.' + className).prev().replaceWith(newElement);

                    } else {

                        var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>');

                        $('.' + className).prev().replaceWith(newElement);

                    }

                } else {

                    Swal.fire('Error!', "Something went wrong", 'error')

                }



            }



        });

    }
</script>



<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('franchise.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/franchise/pickup-details/index.blade.php ENDPATH**/ ?>