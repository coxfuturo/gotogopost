<?php $__env->startSection('title'); ?> IndiaPost Barcode <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startPush('add-modal-code'); ?>
<div class="col-auto float-end ms-auto">
    <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#barcodeModal" onclick="resetModal()">
        <i class="fa-solid fa-plus"></i> Add
    </button>
</div>
<?php $__env->stopPush(); ?>

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }
    .table-newdatatable .dataTables_length {
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
                                <th class="text-center">Service Type</th>
                                <th class="text-center">State</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Range From</th>
                                <th class="text-center">Range To</th>
                                <th class="text-center">Available</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $barcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i+1); ?></td>
                                <td class="text-center">
                                <?php if($data->service_type == 5): ?>
                                  <?php echo e(\App\Models\Admin::INDIA_POST_SPEED); ?>

                                <?php else: ?>
                                  <?php echo e(\App\Models\Admin::INDIA_POST_BUSINESS); ?>

                                <?php endif; ?>
                               </td>
                                <td class="text-center"><?php echo e($data->state); ?></td>
                                <td class="text-center"><?php echo e($data->code); ?></td>
                                <td class="text-center"><?php echo e($data->prefix . $data->range_from . $data->postfix); ?></td>
                                <td class="text-center"><?php echo e($data->prefix . $data->range_to . $data->postfix); ?></td>
                                <td class="text-center"><?php echo e($data->availables); ?></td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" onclick="editModal(<?php echo e(json_encode($data)); ?>)">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="deleteBarcode('<?php echo e($data->id); ?>')">
                                                <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                                            </a>
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

<!-- Create/Edit Modal -->
<div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            
            <form id="barcodeForm" action="<?php echo e(route('indiapostbarcodes.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="id" id="barcode_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeModalLabel">Add New Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="state" class="form-label">Service</label>
                        <select class="form-select" name="service_type" id="service_type" required>
                            <option selected disabled>Select Service</option>
                            <option value="5">SP-Inland Document</option>
                            <option value="6">Seed Post-Parcel Domestic</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" name="state" id="state" required>
                            <option selected disabled>Select State</option>
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($state); ?>"><?php echo e($state); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" name="code" id="code" required maxlength="2">
                    </div>
                    <div class="mb-3">
                        <label for="prefix" class="form-label">Prefix</label>
                        <input type="text" class="form-control" name="prefix" id="prefix" required maxlength="3">
                    </div>
                    <div class="mb-3">
                        <label for="range_from" class="form-label">Range From</label>
                        <input type="number" class="form-control" name="range_from" id="range_from" required>
                    </div>
                    <div class="mb-3">
                        <label for="range_to" class="form-label">Range To</label>
                        <input type="number" class="form-control" name="range_to" id="range_to" required>
                    </div>
                    <div class="mb-3">
                        <label for="postfix" class="form-label">Postfix</label>
                        <input type="text" class="form-control" name="postfix" id="postfix" required maxlength="3">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<<script>
    function resetModal() {
        $('#barcodeForm').trigger("reset");
        $('#formMethod').remove(); // Remove any existing _method input
        $('#barcodeModalLabel').text('Add New Barcode');
        $('#barcodeForm').attr('action', '<?php echo e(route('indiapostbarcodes.store')); ?>');
        $('#barcodeForm').append('<input type="hidden" name="_method" value="POST" id="formMethod">');
    }

    function editModal(data) {
        $('#barcode_id').val(data.id);
        $('#state').val(data.state);
        $('#code').val(data.code);
        $('#prefix').val(data.prefix);
        $('#range_from').val(data.range_from);
        $('#range_to').val(data.range_to);
        $('#postfix').val(data.postfix);
        
        $('#formMethod').remove(); // Remove existing _method input to avoid duplication
        $('#barcodeForm').append('<input type="hidden" name="_method" value="POST" id="formMethod">');
        
        $('#barcodeModalLabel').text('Edit Barcode');
        $('#barcodeForm').attr('action', 'indiapostbarcodes/' + data.id + '/update');
        $('#barcodeModal').modal('show');
    }
</script>

<script>
function deleteBarcode(id) {
    if (confirm('Are you sure you want to delete this barcode?')) {
        let form = $('<form>', {
            'method': 'GET',
            'action': '<?php echo e(route("indiapostbarcodes.destroy", ":id")); ?>'.replace(':id', id)
        });

        let methodInput = $('<input>', {
            'type': 'hidden',
            'name': '_method',
            'value': 'GET'
        });

        let csrfInput = $('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': '<?php echo e(csrf_token()); ?>'
        });

        form.append(methodInput, csrfInput).appendTo('body').submit();
    }
}
</script>    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/indiapost-barcode/index.blade.php ENDPATH**/ ?>