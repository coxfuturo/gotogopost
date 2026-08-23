<?php
/**
 * CORE PHP BULK UPLOAD – FULL FEATURE
 */

session_start();

/* ================= LARAVEL BOOTSTRAP ================= */

require __DIR__ . '/../bootstrap/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/* ================= AUTH CHECK ================= */

$franchiseUser = Auth::guard('franchise')->user();
if (!$franchiseUser) {
    die('Unauthorized');
}

$franchiseId = $franchiseUser->franchise_id;

/* ================= HANDLE FILE UPLOAD ================= */

$success = 0;
$failedRows = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'File upload failed';
        header('Location: /franchise/india-post-speed-post');
        exit;
    }

    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if (!in_array($ext, ['xls', 'xlsx'])) {
        $_SESSION['error'] = 'Only Excel files allowed';
        header('Location: /franchise/india-post-speed-post');
        exit;
    }

    /* ================= SAVE FILE ================= */

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $filePath = $uploadDir . uniqid() . '_' . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

    /* ================= READ EXCEL ================= */

    $sheet = IOFactory::load($filePath)->getActiveSheet();
    $header = [];

    foreach ($sheet->getRowIterator() as $rowIndex => $row) {
        $cells = [];
        foreach ($row->getCellIterator() as $cell) $cells[] = trim((string)$cell->getValue());

        if ($rowIndex == 1) { $header = $cells; continue; }
        if (empty(array_filter($cells))) continue;

        $data = array_combine($header, array_pad($cells, count($header), null));

        try {
            /* ================= REQUIRED ================= */
            if (empty($data['Pickup Pincode']) || empty($data['Consignee Pincode'])) {
                throw new Exception('Pincode missing');
            }

            /* ================= DISTANCE AUTO CALC ================= */
            $distanceRow = DB::table('pincode_distance')
                ->where('from_pincode', $data['Pickup Pincode'])
                ->where('to_pincode', $data['Consignee Pincode'])
                ->first();

            $distance = $distanceRow->distance ?? 0;

            /* ================= AUTO BARCODE ================= */
            $barcode = 'JF' . mt_rand(100000000, 999999999) . 'IN';
            while (DB::table('india_post_speed_post_parcels')->where('barcode_no', $barcode)->exists()) {
                $barcode = 'JF' . mt_rand(100000000, 999999999) . 'IN';
            }

            /* ================= INSERT ================= */
            DB::table('india_post_speed_post_parcels')->insert([
                'franchise_id' => $franchiseId,
                'pickup_name' => $data['Pickup Name'],
                'pickup_mobile' => $data['Pickup Phone'],
                'pickup_pincode' => $data['Pickup Pincode'],
                'consignee_name' => $data['Consignee Name'],
                'consignee_mobile' => $data['Consignee Phone'],
                'consignee_pincode' => $data['Consignee Pincode'],
                'package_weight' => (float)$data['Package Weight'],
                'distance' => $distance,
                'barcode_no' => $barcode,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $success++;

        } catch (Exception $e) {
            $data['Error Reason'] = $e->getMessage();
            $failedRows[] = $data;
        }
    }

    /* ================= FAILED EXCEL GENERATE ================= */
    $failedFile = null;
    if (!empty($failedRows)) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(array_keys($failedRows[0]), null, 'A1');

        $rowNum = 2;
        foreach ($failedRows as $row) $sheet->fromArray(array_values($row), null, 'A' . $rowNum++);

        $failedFile = 'failed_rows_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($uploadDir . $failedFile);
    }

    unlink($filePath); // remove original uploaded file

    $_SESSION['success'] = "Bulk upload done. Success: $success, Failed: " . count($failedRows);
    $_SESSION['failed_file'] = $failedFile;

    header('Location: /franchise/india-post-speed-post');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bulk Upload</title>
</head>
<body>

<h2>Bulk Upload Excel</h2>

<?php if (!empty($_SESSION['success'])): ?>
    <div style="color:green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form action="/india-post-bulk-upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>

<?php if (!empty($_SESSION['failed_file'])): ?>
    <br>
    <a href="/uploads/<?= $_SESSION['failed_file']; ?>" download>
        📊 Download Failed Rows
    </a>
    <?php unset($_SESSION['failed_file']); ?>
<?php endif; ?>

</body>
</html>
