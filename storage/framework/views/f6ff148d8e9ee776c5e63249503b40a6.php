<?php $__env->startSection('title'); ?> PPH Management <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('add-modal-code'); ?>
<div class="col-auto float-end ms-auto">
    <a href="<?php echo e(route('admin.pph.create')); ?>" class="btn add-btn"><i class="fa-solid fa-plus"></i> Add</a>

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
                                <th class="text-center">Created at</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i+1); ?></td>
                                <td>
                                    <h2 class="table-avatar">
                                        <?php
                                            $photos = isset($data->kyc->photo) ? explode(',', $data->kyc->photo) : [];
                                            $firstPhoto = !empty($photos[0]) ? asset('admin/pph/' . $data->generated_id . '/' . trim($photos[0])) : asset('admin/assets/img/profiles/avatar-02.jpg');
                                        ?>
                                        <a href="#" class="avatar"><img src="<?php echo e($firstPhoto); ?>" alt=""></a>
                                        <a href="#"><?php echo e($data->name); ?></a>
                                    </h2>
                                </td>
                                <td><?php echo e($data->mobile); ?></td>
                                <td><?php echo e($data->email); ?></td>
                                <td><?php echo e(date('d M Y h:i A',strtotime($data->created_at))); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-<?php echo e($data->status == 1 ? 'success' : 'danger'); ?>"><?php echo e($data->status == 1 ? 'Active' : 'Inactive'); ?></button>
                                </td>

                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="<?php echo e(route('admin.pph.edit',$data->id)); ?>"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="<?php echo e(route('admin.pph.view',$data->id)); ?>"><i class="fa-regular fa-eye m-r-5"></i> view</a>
                                            <a class="dropdown-item" href="#" onclick="delete_modal('<?php echo e($data->id); ?>')"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
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
        const deleteUrl = "<?php echo e(route('admin.pph.delete', ['id' => ':id'])); ?>".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }

    function status_update(id, status) {
        if (status === 1) {
            var update_status = 0;
        } else {
            var update_status = 1;
        }
        $.ajax({
            url: "<?php echo e(route('admin.pph.status')); ?>",
            type: "POST",
            data: {
                "_token": "<?php echo e(csrf_token()); ?>",
                id: id,
                status: update_status,
            },
            success: function(data) {
                if (data.success == true) {
                    Swal.fire('Success!', "Status updated", 'success');
                } else {
                    Swal.fire('Error!', "Something went wrong", 'error')
                }

            }

        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/pph/index.blade.php ENDPATH**/ ?>