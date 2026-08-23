<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <style>
    @page {
      size: 7.6cm 5.08cm;
      margin: 0;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    .label {
      width: 7.4cm;
      height: 5.06cm;
      box-sizing: border-box;
      overflow: hidden;
      page-break-after: always;
      margin: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }

    th, td {
      padding: 2px;
      vertical-align: top;
      line-height: 13px;
    }

    .logo img {
      width: 42px;
      height: 20px;
      display: block;
      margin: auto;
    }

    .qr img {
      width: 60px;
      height: 40px;
      display: block;
      margin: auto;
    }

    .qrs img {
      width: 80px;
      height: 70px;
      display: block;
      margin: auto;
      margin-top: 30px;
    }

    .barcode img {
      width: 150px;
      height: 23px;
      display: block;
      margin-left: auto;
      margin-right: 10px;
    }

    .barcode-number {
      font-weight: bold;
      font-size: 10px;
      letter-spacing: 2px;
      margin-top: 1px;
      margin-right: 40px;
      float: right;
    }

    .footer {
      font-size: 8px;
      text-align: left;
      line-height: 1.8;
      margin-top: 0px;
      border: 1px solid #000;
      padding: 2px;
      text-transform: uppercase;
    }

    .footers {
      font-size: 7.8px;
      text-align: center;
      border: 1px solid #000;
      border-top: none;
      padding: 2px;
      letter-spacing: 1px;
      text-transform: uppercase;
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
  <table style="margin-top:10px">
    <tr>
      <th rowspan="2">
        <span style="width:70px; float:right; font-size: 8px; text-transform: uppercase;">
         <b><span style="font-size: 7px"> (LABEL)</span><br>SHIP TO</b><br>
          <span style="font-size: 6px;font-weight: 900"><?php echo e($parcel->consignee_name); ?></span><br>
          PINCODE:-<?php echo e($parcel->consignee_pincode); ?>

        </span>
      </th>
    </tr>

    <tr>
      <td colspan="2">
        <div class="barcode">
          
          <span style="margin-left: 52px; font-size: 8px; text-transform: uppercase;">
            <br>
            <b>
              <?php switch($type):
                  case (5): ?>
                      <span style="display:inline-block; margin-left :40px;">
                    SPEED POST-INLAND DOCUMENT
                </span>
                  <?php break; ?>
                  <?php default: ?>
                      <?php echo e(\App\Models\Admin::INDIA_POST_BUSINESS); ?>

              <?php endswitch; ?>
            </b>
          </span>

          <img src="data:image/png;base64,<?php echo e($parcel->barcode_image_src); ?>">
          <div class="barcode-number"><?php echo e($parcel->barcode_no); ?></div>
        </div>
      </td>
    </tr>
  </table>

  <!-- Footer -->
  <div class="footer">
   
    <table>
    <tr>
    <th style="font-size: 8px; font-weight: 500;font-weight: 600;margin-left: "><span style="margin-left: 20px">Account Details<span></th>
    <th style="font-size: 8px; text-align: center; font-weight: 600;"><span style="border-bottom: 1px solid #000;margin-right: 20px">INDIA POST</span></th>
  </tr>
  <tr>
    <th style="font-size: 8px; font-weight: 500;"><span style="margin-left: 20px">INDIA POST BNPL CODE:</span></th>
    <th style="font-size: 10px; text-align: center; font-weight: 600;"><span style="margin-right: 30px">928-510</span></th>
  </tr>
  <tr>
    <th style="font-size: 8px; font-weight: 500;"><span style="margin-left: 20px">Customer ID:</span></th>
    <th style="font-size: 8px; text-align: center; font-weight: 500;"><span style="margin-right: 22px">1580694767</span></th>
  </tr>
  <tr>
    <th style="font-size: 8px; font-weight: 500;"><span style="margin-left: 20px">Contract ID:</span></th>
    <th style="font-size: 8px; text-align: center; font-weight: 500;"><span style="margin-right: 30px">41184049</span></th>
  </tr>
</table>

  </div>

  <div style="border: 1px solid #000; border-top:none;text-align:center;padding:2px;font-size: 8px;font-weight: 600">
  Have &nbsp;&nbsp;A &nbsp;&nbsp;Nice &nbsp;&nbsp;Day</span>
  </div>

  <!-- <div class="footers">
    <div style="display:flex; align-items:center; justify-content:center; font-size:8px;">
      <span><b>Customer ID: 1580694767</b></span>
      <span style="width:20px;"></span>
      <span><b>Contact ID: 41184049</b></span>
    </div>
  </div> -->

</div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html>
<?php /**PATH /home/gotogopost/public_html/resources/views/print/indiaPost/prepaidLabel3.blade.php ENDPATH**/ ?>