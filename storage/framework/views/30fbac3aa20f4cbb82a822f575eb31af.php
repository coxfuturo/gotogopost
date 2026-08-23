
<?php echo $__env->make('website.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        

<?php echo $__env->yieldContent('content'); ?>


<?php echo $__env->make('website.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php /**PATH /home/gotogopost/public_html/resources/views/website/layouts/master.blade.php ENDPATH**/ ?>