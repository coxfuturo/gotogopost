<?php $__env->startSection('title'); ?> <?php echo e($title); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }

    .table-newdatatable .dataTables_length {
        display: block;
    }

    .table {
        margin-bottom: 0px;
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
                                <th>Services & Barcodes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $franchises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $franchise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i+1); ?></td>
                                <td><?php echo e($franchise['name']); ?></td>
                                <td>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th>Barcode Range</th>
                                                <th>Allotted</th>
                                                <th>Available</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $franchise['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($service['service_name']); ?></td>
                                                <td><?php echo e($service['barcode_range']); ?></td>
                                                <td><?php echo e($service['allotted']); ?></td>
                                                <td><?php echo e($service['available']); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
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

<?php $__env->startPush('page-javascript'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/alloted-barcode/index.blade.php ENDPATH**/ ?>