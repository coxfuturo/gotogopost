<?php $__env->startSection('title'); ?> Commission Report <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>




<?php $__env->startPush('add-modal-code'); ?>


<?php $__env->stopPush(); ?>

<style>
    .custom-button {
        display: inline-block;
        padding: 3px 10px;
        font-size: 13px;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        color: #f9f9f9 ! IMPORTANT;
        background-color: #1b64b1;
        border-radius: 4px;
        border: 1px solid transparent;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }

    .custom-button:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
        border-color: #004085;
        color: #fff;
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }

    .table-newdatatable .dataTables_length {
        display: block;
    }

    table.table-new.dataTable>thead .sorting:after,
    table.table-new.dataTable>thead .sorting_asc:after,
    table.table-new.dataTable>thead .sorting_desc:after,
    table.table-new.dataTable>thead .sorting_asc_disabled:after,
    table.table-new.dataTable>thead .sorting_desc_disabled:after {
        right: 0.5em;
        content: "\f0d7";
        font-family: "FontAwesome";
        top: 15px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }

    table.table-new.dataTable>thead .sorting:before,
    table.table-new.dataTable>thead .sorting_asc:before,
    table.table-new.dataTable>thead .sorting_desc:before,
    table.table-new.dataTable>thead .sorting_asc_disabled:before,
    table.table-new.dataTable>thead .sorting_desc_disabled:before {
        right: 0.5em;
        content: "\f0d8";
        font-family: "FontAwesome";
        top: 5px;
        color: #C1CCDB;
        font-size: 12px;
        opacity: 1;
    }
</style>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive table-newdatatable" id="franchise_daily_booking_report">
                    <table class="table table-new custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Franchise No</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Pincode</th>
                                <th>Commission</th>
                                <th>Gotogo Balance</th>
                                <th>India Post Balance</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
        
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr> 
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($value['franchise_no']); ?></td>
                                <td><?php echo e($value['name']); ?></td>
                                <td><?php echo e($value['mobile']); ?></td>
                                
                                <td><?php echo e($value['pincode']); ?></td>
                                <td><?php echo e($value['commission']); ?></td>
                                <td><?php echo e($value['gotogo_balance']); ?></td>
                                <td><?php echo e($value['indiapost_balance']); ?></td>
                                <td>
                                    <a class="custom-button" href="<?php echo e(route('admin.franchise.commissionDetail', ['id' => $value->id])); ?>" >
                                         Commissions
                                    </a>
                                    <a class="custom-button" href="<?php echo e(route('admin.franchise.creditDetails', ['id' => $value->id])); ?>" >
                                         Credits
                                    </a>
                                    
                                </td>
                            </tr>
        
        
                            <div id="edit_role<?php echo e($value['id']); ?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content modal-md">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add Balance</h5>
                
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                
                                        <?php if($data): ?>
                
                                        <div class="modal-body">
                                            <form action="<?php echo e(route('admin.franchise.commissions', ['id' => $value['id']])); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Add Wallet Balance <span class="text-danger">*</span></label>
                                                    <input class="form-control" value="0" name="wallet_balance" type="text">
                                                </div>
        
                                                <div class="input-block mb-3">
                                                    <label class="col-form-label">Add Commission <span class="text-danger">*</span></label>
                                                    <input class="form-control" value="0" name="commission" type="text">
                                                </div>
                
                                                <div class="submit-section">
                                                    <button class="btn btn-primary submit-btn">Save</button>
                                                </div>
                                            </form>
                                        </div>
                
                                        <?php endif; ?>
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











<?php $__env->startPush('page-javascript'); ?>



<script>
    function filterDailyBookingsReportByDate() {
        var date = $('.bookingdate').val();

        console.log("reached here", )
        // Construct the URL with query parameters
        var url = "<?php echo e(route('admin.daily-booking-report.all')); ?>";
        url += "?date=" + encodeURIComponent(date);

        $.ajax({
            url: url,
            type: "GET", // Use GET request
            success: function(response) {
                if (response.status == 200) {
                    var data = response.data;


                    // Clear existing table rows
                    $('#franchise_daily_booking_report tbody').empty();

                    // Iterate over the data and create rows

                    $.each(data, function(index, item) {
                        var row = $('<tr>');

                        row.append($('<td>').text(index)); // Adding serial number
                        row.append($('<td>').text(item.service_type));
                        row.append($('<td>').text(item.No_of_article));
                        row.append($('<td>').text(item.total_value));
                        row.append($('<td>').text(item.wallet_balance));
                        row.append($('<td>').text(item.remaining_balance));
                        // Append the row to the table
                        $('#franchise_daily_booking_report tbody').append(row);
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', "Something went wrong", 'error');
            }
        });
    }
</script>




<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gotogopost/public_html/resources/views/admin/franchise/commissions.blade.php ENDPATH**/ ?>