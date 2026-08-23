<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    @page {
      size: 3in 2in; /* width x height ko swap kar do */
       margin: 0;
    }


body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      font-size: 7px;
    }

.label {
  width: 3in;
  height: 2in;
  box-sizing: border-box;
  overflow: hidden;
  page-break-after: always;
  margin: auto;
  padding-top:4px;
}


    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 7px;
    }

    th, td {
      padding: 1px;
      vertical-align: top;
    }

    .logo img {
      width: 100%;
      height: 45px;
      display: block;
      margin-left: auto;
    }

    .qr img {
      width: 30px;
      height: 28px;
      display: block;
      margin: auto;
      margin-top: 4px;
    }

    .barcode img {
      width: 100px;
      height: 12px;
      display: block;
      margin-left: auto;
      margin-right: 30px;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 10px;
      letter-spacing: 1px;
      /* text-align: center; */
      margin-top: 1px;
       margin-right: 30px;
      float: right; 
    }

    .section {
      border: 1px solid #000;
      /* border-top: none; */
      padding: 1px 2px;
      font-size: 7px;
      line-height: 1.1;
    }

    .section:first-of-type {
      border-top: 1px solid #000;
    }

    .parties td {
      border: 1px solid #000;
      width: 50%;
      line-height: 1.3;
    }

    .footer {
      font-size: 6.8px;
      text-align: center;
      line-height: 1.3;
      margin-top:2px;
    }

  </style>
</head>
<body>
<?php
    $chunks = $data->chunk(1);
?>

<?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $__currentLoopData = $page; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="label">

  <!-- Header -->
  <table style="margin-top:3px">
    <tr>
      <th >
        <div class="qr"><img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>"></div>
        
      </th>
      <th style="text-align:center; font-size:8px;text-transform: uppercase;">
           <span style="margin-left:85px;letter-spacing: 2px;font-size:10px;margin-top:3px;">ALL INDIA POST</span><br>
        <span style="margin-left:85px;font-size: 5px">
          
         <?php switch($type):
            case (5): ?>
        <!-- <?php echo e(\App\Models\Admin::INDIA_POST_SPEED); ?> -->
          SPEED POST-INLAND DOCUMENT
         <?php break; ?>

          <?php default: ?>
        <?php echo e(\App\Models\Admin::INDIA_POST_BUSINESS); ?>

         <?php endswitch; ?>
        <br>
      <?php if($parcel->status == 2): ?>
       <span style="color: #de0114;">Cancel Parcel</span>
      <?php endif; ?>
  </span>
        <div class="barcode">
          <!--<img src="data:image/png;base64,<?php echo e($parcel->barcode_image_src); ?>">-->
          <div class="barcode-number" style="margin-right: 45px;"><?php echo e($parcel->barcode_no); ?></div>
        </div>
  </th>
    </tr>
    
    <!-- <tr>
      <td colspan="2">
        <div class="barcode">
          <img src="data:image/png;base64,<?php echo e($parcel->barcode_image_src); ?>">
          <div class="barcode-number"><?php echo e($parcel->barcode_no); ?></div>
        </div>
      </td>
    </tr> -->

  </table>
  <!-- Sections -->
  <!-- <div class="section">Dely Office & Pincode: Air Force SO(122001) <span style="font-size: 8px;float:right"><b>SNo. <?php echo e(str_pad($parcel->id, 6, '0', STR_PAD_LEFT)); ?></b></span></div> -->
  <div class="section" style="margin-top:0px;padding-left:10px">Booking Customer ID: 1580694767, <span style="text-transform: uppercase;"><?php echo e($franchise->city); ?></span> PIN: (110046)
<br>
      Franchise ID: <?php echo e($franchise_no ?? NULL); ?>, <?php echo e($parcel->created_at->format('d-m-y')); ?>, <?php echo e($parcel->created_at->format('H:i:s')); ?>

<br>GST No. <?php echo e($franchise->gst_number); ?> <span style="float:right;margin-right: 20px;font-weight: 700">
<?php if($parcel->parcel_type == 1): ?>
   Surface
<?php else: ?>
     Air
<?php endif; ?>
</span></div>
  <div class="section" style="border-top: none; border-bottom: none;padding-left:10px">
    Weight (gms): <?php echo e($parcel->package_weight ?? 0); ?>

    L: <?php echo e($type == 6 ? ($parcel->package_length ?? 0) : 0); ?>

    B: <?php echo e($type == 6 ? ($parcel->package_width ?? 0) : 0); ?>

    H: <?php echo e($type == 6 ? ($parcel->package_height ?? 0) : 0); ?>

    <?php if($type == 6): ?> (Vol.Wt: 0.00) <?php endif; ?>
<br>

     Amount/Paid:
<?php echo e($parcel->totalOtherAmount ?? $parcel->payment_amount); ?>

(Tax:
<?php
    $amount = $parcel->totalOtherAmount ?? $parcel->payment_amount;
    $tax = $amount - ($amount / 1.18);
?>
<?php echo e(number_format($tax, 2)); ?> (CGST- SGST-))<br>
Mode of Payment: CO 
Contract ID:  41184049</div>

  <!-- Parties -->
  <table class="parties">
    <tr>
      <td style="text-align:center"><b>Sender</b></td>
      <td style="text-align:center"><b>Receiver</b></td>
    </tr>
    <tr>
      <td style="padding-left:10px">
        
        <?php if($parcel->pickup_gst_number): ?> <span style="text-transform: uppercase;"><b><?php echo e($parcel->pickup_gst_number ?? NULL); ?></br></b></span> <?php endif; ?>
        <?php echo e($parcel->pickup_name); ?>-<br>
        Mobile No.<?php echo e($parcel->pickup_mobile); ?><br>
        <?php echo e($parcel->pickup_address); ?><br>
        <?php echo e($parcel->pickup_city); ?><br>
        <?php echo e($parcel->pickup_state); ?>-<?php echo e($parcel->pickup_pincode); ?>

      </td>
      <td style="padding-left:10px">
        <?php echo e($parcel->consignee_name); ?><br>
        Mobile No.<?php echo e($parcel->consignee_mobile); ?><br>
        <?php echo e($parcel->consignee_address); ?><br>
        <?php echo e($parcel->consignee_city); ?><br>
        <?php echo e($parcel->consignee_state); ?>-<?php echo e($parcel->consignee_pincode); ?>

      </td>
    </tr>
  </table>

  
  <div class="section" style="border-top:none;padding-left:10px">
   
    <b>This Is System Generated Document, No Manual Signature Required</b><br>
   </div>

   <div style="display: flex; align-items: center; justify-content: center; font-size: 8px; margin-top:1px;">
  <span>POWERED BY:</span>
  <img src="<?php echo e(asset('website/images/logonewtransparent.png')); ?>" 
       style="width: 15px; height: 15px; margin: 0 5px;" alt="logo">
  <span>GOTOGOPOST</span>
</div>

  </div>

    

</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>
</html>
<?php /**PATH /home/gotogopost/public_html/resources/views/print/indiaPost/prepaidRecipt.blade.php ENDPATH**/ ?>