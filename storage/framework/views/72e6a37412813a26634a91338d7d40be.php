<?php $__env->startSection('title'); ?> User Management <?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>





<?php $__env->startPush('add-modal-code'); ?>

<div class="col-auto float-end ms-auto">

    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role_user"><i class="fa-solid fa-plus"></i> Add</a>

</div>

<?php $__env->stopPush(); ?>


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
    div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
    display: block;
}
</style>



<div class="row">

    <div class="col-md-12">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
    
                    <table class="table table-striped custom-table datatable">
        
                        <thead>
        
                            <tr>
        
                                <th>SN#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Pincode</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
        
                            </tr>
        
                        </thead>
        
                        <tbody>
        
                            <?php $__currentLoopData = $roleUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
                            <tr>
        
                                <td><?php echo e($i+1); ?></td>
        
                                <td>
        
                                    <h2 class="table-avatar">
        
                                        <?php if($user->image): ?>
        
                                        <a href="#" class="avatar">
        
                                            <img src="<?php echo e(asset('tenancy/assets/admin/User/' . $user->image)); ?>" alt="">
        
                                        </a>
        
                                        <?php else: ?>
        
                                        <a href="#" class="avatar"><img src="<?php echo e(asset('admin/assets/img/profiles/avatar-19.jpg')); ?>" alt=""></a>
        
                                        <?php endif; ?>
        
                                        <a href="#"><?php echo e($user->name); ?></a>
        
                                    </h2>
        
                                </td>
        
                                <td><?php echo e($user->phone); ?></td>
        
                                <td><?php echo e($user->email); ?></td>
                                <td><?php echo e($user->pincode); ?></td>
                                <td><?php echo e($user->address); ?></td>
        
                                <td>
        
                                    <div class="dropdown action-label">
        
                                        <?php if($user->status == 1): ?>
        
                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>
        
                                        <?php else: ?>
        
                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>
        
                                        <?php endif; ?>
        
                                        <div class="dropdown-menu active-inactive-menu-<?php echo e($user->id); ?>">
        
                                            <a class="dropdown-item" onclick="status_update('<?php echo e($user->id); ?>','1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>
        
                                            <a class="dropdown-item" onclick="status_update('<?php echo e($user->id); ?>','0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
        
                                        </div>
        
                                    </div>
        
                                </td>
        
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
        
                                            <h5 class="modal-title">Update User</h5>
        
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        
                                                <span aria-hidden="true">&times;</span>
        
                                            </button>
        
                                        </div>
        
                                        <div class="modal-body">
        
                                            <form action="<?php echo e(route('admin.user.update',$user->id)); ?>" method="POST" enctype="multipart/form-data">
        
                                                <?php echo csrf_field(); ?>
        
                                                <div class="row">
        
                                                    <div class="col-md-6">
        
                                                        <div class="input-block mb-3">
        
                                                            <label class="col-form-label">Name <span class="text-danger">*</span></label>
        
                                                            <input class="form-control" name="name" placeholder="Enter name.." type="text" value="<?php echo e(old('name',$user->name)); ?>" required>
        
                                                        </div>
        
                                                    </div>
        
                                                    <div class="col-md-6">
        
                                                        <div class="input-block mb-3">
        
                                                            <label class="col-form-label">Email <span class="text-danger">*</span></label>
        
                                                            <input class="form-control floating" name="email" placeholder="Enter email.." type="email" value="<?php echo e(old('name',$user->email)); ?>" required>
        
                                                        </div>
        
                                                    </div>
        
                                                    <div class="col-md-6">
        
                                                        <div class="input-block mb-3">
        
                                                            <label class="col-form-label">Phone <span class="text-danger">*</span></label>
        
                                                            <input class="form-control" name="phone" placeholder="Enter phone.." type="number" value="<?php echo e(old('name',$user->phone)); ?>" required>
        
                                                        </div>
        
                                                    </div>
        
                                                    <div class="col-md-6">
        
                                                        <div class="input-block mb-3">
        
                                                            <label class="col-form-label">pincode <span class="text-danger">*</span></label>
        
                                                            <input class="form-control" name="pincode" placeholder="Enter pincode.." type="text" value="<?php echo e(old('name',$user->pincode)); ?>" required>
        
                                                        </div>
        
                                                    </div>
        
        
                                                    <div class="col-md-6">
        
                                                        <div class="input-block mb-3">
        
                                                            <label class="col-form-label">Address <span class="text-danger">*</span></label>
        
                                                            <input class="form-control" name="address" placeholder="Enter address.." type="text" value="<?php echo e(old('name',$user->address)); ?>" required>
        
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
        
                            <!-- update Client Modal -->
        
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        
        
                        </tbody>
        
                    </table>
        
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

                <h5 class="modal-title">Add User</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <form action="<?php echo e(route('admin.user.store')); ?>" method="POST" enctype="multipart/form-data">

                    <?php echo csrf_field(); ?>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Name <span class="text-danger">*</span></label>

                                <input class="form-control" name="name" placeholder="Enter name.." type="text" value="<?php echo e(old('name')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Email <span class="text-danger">*</span></label>

                                <input class="form-control floating" name="email" placeholder="Enter email.." type="email" value="<?php echo e(old('email')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Phone <span class="text-danger">*</span></label>

                                <input class="form-control" name="phone" placeholder="Enter phone.." type="number" value="<?php echo e(old('phone')); ?>" required>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Pincode <span class="text-danger">*</span></label>

                                <input class="form-control" name="pincode" placeholder="Enter pincode.." type="text" value="<?php echo e(old('pincode')); ?>" required>

                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Address <span class="text-danger">*</span></label>

                                <input class="form-control" name="address" placeholder="Enter address.." type="text" value="<?php echo e(old('address')); ?>" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Password <span class="text-danger">*</span></label>

                                <input class="form-control" name="password" placeholder="Enter password.." type="password" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>

                                <input class="form-control" name="password_confirmation" placeholder="Enter confirm password.." type="password" required>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="input-block mb-3">

                                <label class="col-form-label" for="fileInput1">Image</label>

                                <input type="file" class="form-control" id="fileInput1" onchange="displayFile(1)" name="image">

                                <div id="fileContainer1">

                                </div>

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

        const deleteUrl = "<?php echo e(route('admin.user.delete', ['id' => ':id'])); ?>".replace(':id', id);

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



    function status_update(id, status) {

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

                if (data.success == true) {

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
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/user/index.blade.php ENDPATH**/ ?>