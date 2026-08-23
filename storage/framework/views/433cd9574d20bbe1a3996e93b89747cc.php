<?php $__env->startSection('title'); ?> Website Message <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startPush('add-modal-code'); ?>
<div class="col-auto float-end ms-auto">
</div>
<?php $__env->stopPush(); ?>
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
                                <th class="text-center">Name</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Services</th>
                                <th class="text-center">Message</th>
                                <th class="text-end">Action</th>  <!-- Missing header added -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $website_message; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td class="text-center"><?php echo e($data->name); ?></td>
                                <td class="text-center"><?php echo e($data->phone); ?></td>
                                <td class="text-center"><?php echo e($data->email); ?></td>
                                <td class="text-center"><?php echo e($data->option); ?></td>
                                <td><?php echo e(\Illuminate\Support\Str::limit($data->message, 100)); ?></td>
                                <td class="text-end">
                                    <a href="javascript:void(0);" class="text-danger" onclick="delete_modal(<?php echo e($data->id); ?>)">
                                        <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                                    </a>   
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
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
<script type="text/javascript">
    function delete_modal(id) {
        const deleteUrl = "<?php echo e(route('websideMessage.delete', ['id' => ':id'])); ?>".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/websiteMessage/index.blade.php ENDPATH**/ ?>