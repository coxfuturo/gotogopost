<?php $__env->startSection('title'); ?> <?php echo e($title); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<?php $__env->startPush('add-modal-code'); ?>
<div class="col-auto float-end ms-auto">
    <?php if($rates->count()>0): ?>
    <a href="<?php echo e(route('admin.postal-rates.edit',['type'=>request()->query('type')])); ?>" class="btn add-btn"><i class="fa-solid fa-pen"></i> edit</a>
    <?php else: ?>
    <a href="<?php echo e(route('admin.postal-rates.create',['type'=>request()->query('type')])); ?>" class="btn add-btn"><i class="fa-solid fa-plus"></i>Add</a>
    <?php endif; ?>

</div>
<?php $__env->stopPush(); ?>

<div class="row">
    <div class="col-md-12">

        <?php if(request()->query('type')==1): ?>
        <h4>Inland Speed Post Rates</h4>
        <p>EMS speed post is a guaranteed and fast mail service for letters,Parcel and Packets deliverable in a specified time,between specified stations in India.(Rate In INR)</p>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0">
                <thead>
                    <tr>
                        <th>weight</th>
                        <th>Local</th>
                        <th>51 to 200 kms</th>
                        <th>201 to 500 kms</th>
                        <th>501 to 1000 kms</th>
                        <th>1001 to 2000 kms</th>
                        <th>above 2000 kms</th>
                    </tr>
                </thead>
                <?php $__currentLoopData = $rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($rate->weight); ?></td>
                    <td><?php echo e($rate['Local']); ?></td> <!-- Use square brackets with quotes -->
                    <td><?php echo e($rate['upto_200_kms']); ?></td>
                    <td><?php echo e($rate['rate_201_to_500_kms']); ?></td> 
                    <td><?php echo e($rate['rate_501_to_1000_kms']); ?></td>
                    <td><?php echo e($rate['rate_1001_to_2000_kms']); ?></td>
                    <td><?php echo e($rate['above_2000_kms']); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>

        <?php if(request()->query('type')==1): ?>

        <p class="mt-2">The above tariff is exclusive of taxes.The taxes have to be paid extra as notified by the central government from time to time.(Rates effective from 1st october 2012)</p>

        <p class="mt-2">source
            <a href="https://www.indiapost.gov.in" target="_blank">www.indiapost.gov.in</a>
            <span>|</span>
            <a href="https://www.maharashtra.gov.in" target="_blank">www.maharashtra.gov.in</a>
        </p>

        <p class="mt-2">The information provided in this webpage has been compipled to provide general infomation to the public with the utmost care.For accuracy and completeness of the information in the question,Please verify details with any post office or India post website.</p>

        <?php endif; ?>
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
        const deleteUrl = "<?php echo e(route('admin.parcel.delete', ['id' => ':id'])); ?>".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/postal-rates/index.blade.php ENDPATH**/ ?>