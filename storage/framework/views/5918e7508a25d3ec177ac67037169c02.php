<?php $__env->startSection('title'); ?> Support Ticket Management <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startPush('add-modal-code'); ?>

<?php $__env->stopPush(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card-group m-b-30">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="d-block">New Tickets</span>
                        <h3 class="mb-3"><?php echo e($datas->count()); ?></h3>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="d-block">Solved Tickets</span>
                        <h3 class="mb-3"><?php echo e($datas->where('status',2)->count()); ?></h3>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="d-block">Open Tickets</span>
                        <h3 class="mb-3"><?php echo e($datas->where('status',1)->count()); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card">
    <div class="card-body">
        <!-- Search Filter -->
        <form action="<?php echo e(route('admin.support-ticket.index')); ?>" method="GET">
            <div class="row">
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                    <div class="input-block mb-3 form-focus">
                        <div class="cal-icon">
                            <!-- Add 'name' attribute to input field -->
                            <input class="form-control floating datetimepicker start_date" value="<?php echo e(request()->query('start_date')); ?>" type="text" name="start_date">
                        </div>
                        <label class="focus-label">Start Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                    <div class="input-block mb-3 form-focus">
                        <div class="cal-icon">
                            <!-- Add 'name' attribute to input field -->
                            <input class="form-control floating datetimepicker end_date" value="<?php echo e(request()->query('end_date')); ?>" type="text" name="end_date">
                        </div>
                        <label class="focus-label">End Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-1">
                    <div class="d-grid d-flex">
                        <button type="submit" class="btn btn-success">Search</button> &nbsp;&nbsp;
                        <?php if(request()->query('end_date') == 'start_date' || request()->query('end_date')): ?>
                        <a href="<?php echo e(route('admin.support-ticket.index')); ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" data-original-title="Reset">
                            <i class="fa-regular fa-trash-can m-r-5 mt-2"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        </form>
        <!-- /Search Filter -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ticket Id</th>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Subject</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i+1); ?></td>
                                <td><a href="ticket-view.html">#TKT-<?php echo e($model->id); ?></a></td>
                                <td><?php echo e(date('d M Y h:i A',strtotime($model->created_at))); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.support-ticket.get_details',['id' => $model->id])); ?>" class="btn btn-primary btn-xs text-white">
                                        <i class="fa fa-edit-1 mr-1"></i> Reply</a>
                                    <span class="badge badge-danger badge-pill"> <?php echo e($model->message()->where('messageable_type',\App\Models\Franchise::class)->where('is_read',0)->count()); ?></span>

                                </td>
                                <td><?php echo e($model->status == 1 ? 'Open' : 'Close'); ?></td>
                                <td><?php echo e($model->title); ?></td>

                                
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role_user<?php echo e($model->id); ?>"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="#" onclick="delete_modal('<?php echo e($model->id); ?>')"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <div id="edit_role_user<?php echo e($model->id); ?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-md">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Support Tickets</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo e(route('admin.support-ticket.update', ['id' => $model->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Ticket Name <span class="text-danger">*</span></label>
                                                    <input name="name" class="form-control" value="<?php echo e($model->title); ?>" type="text">
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="input-block mb-3">
                                                            <label class="col-form-label">Description</label>
                                                            <textarea class="form-control" name="description"><?php $__currentLoopData = $model->message; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($message->body); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="text">
                                                            <select name="status" class="select" data-tags="true" data-placeholder="Select an option" required>   
                                                                    <option value="1" <?php echo e($model->status == 1 ? 'selected':''); ?>>Open</option>
                                                                    <option value="2" <?php echo e($model->status == 2 ? 'selected':''); ?>>Close</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="text">
                                                            <select name="read_unread" class="select" data-tags="true" data-placeholder="Select an option" required>   
                                                                    <option value="1" <?php echo e($model->is_read == 1 ? 'selected':''); ?>>read</option>
                                                                    <option value="0" <?php echo e($model->is_read == 0 ? 'selected':''); ?>>un-read</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="submit-section">
                                                    <button class="btn btn-primary submit-btn">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Add Role Modal -->
<div id="add_support_tickets" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Support Tickets</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('admin.support-ticket.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="input-block mb-3">
                        <label class="col-form-label">Ticket Name<span class="text-danger">*</span></label>
                        <input class="form-control" name="name" type="text" required>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-block mb-3">
                                <label class="col-form-label">Description</label>
                                <textarea class="form-control" name="description"></textarea>
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
<!-- /Add Role Modal -->

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
        const deleteUrl = "<?php echo e(route('admin.support-ticket.delete', ['id' => ':id'])); ?>".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }  
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/support-ticket/index.blade.php ENDPATH**/ ?>