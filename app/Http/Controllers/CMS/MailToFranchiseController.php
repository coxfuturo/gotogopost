<?php



namespace App\Http\Controllers\CMS;



use App\Http\Controllers\Controller;
use App\Mail\MailToFranchise;
use Smalot\PdfParser\Parser;
use App\Models\SoftCopyParcel;

use App\Models\PickupDetails;
use App\Models\E2HTrackOrder;

use App\Models\Mail;
use Illuminate\Support\Facades\Mail as MailFacade;
use App\Models\Franchise;
use App\Models\MailAttachment;
use App\Models\DeliveryBoy;
use App\Models\Recipient;

use Illuminate\Http\Request;

use Illuminate\Contracts\Support\Renderable;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;

use  App\Http\Controllers\franchise\RateCalculator;
use Illuminate\Support\Facades\Config;

use DB;

use App\Models\FranchiseCommissionDetail;
use App\Models\CMSCommissionDetail;
use Carbon\Carbon;
use App\Models\CMS;

class MailToFranchiseController extends Controller

{


    public function sentMails(Request $request)
    {

        $service_type = $request->service_type;

        $cms_id = Auth::guard('cms')->user()->id;
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $sentMails = Mail::where('cms_id',  $cms_id)
            ->where('service_type', $service_type)
            ->with(['recipients', 'attachments'])
            ->orderBy('created_at', 'desc')
            ->whereDate('created_at', $date)
            ->get()
            ->map(function ($mail) {
                // Franchise details if present
                $mail->franchise = $mail->franchise_id != 0 ? $mail->franchise : null;

                // User details if present
                $mail->user = $mail->user_id != 0 ? $mail->user : null;

                // Calculate total payment amount from attachments
                $cost = $mail->attachments->sum(function ($attachment) {
                    return (float) $attachment->payment_amount;
                });
                $mail->total_payment_amount = number_format($cost, 2);

                // Add destination franchise details for each recipient
                $mail->recipients = $mail->recipients->map(function ($recipient) {
                    // Fetch destination franchise details based on destination_franchise_id
                    if ($recipient->destination_franchise_id) {
                        $recipient->destinationFranchiseDetails = Franchise::find($recipient->destination_franchise_id);
                    } else {
                        $recipient->destinationFranchiseDetails = null;
                    }
                    return $recipient;
                });

                return $mail;
            });


        return view('cms.mailToFranchise.sentMails', ['sentMails' => $sentMails]);
    }


    public function create()

    {
        $allFranchise = Franchise::where('status', 1)->get();
        return view('cms.mailToFranchise.create', compact('allFranchise'));
    }

    public function store(Request $request)
    {

        $cms_id = Auth::guard('cms')->user()->id;
        $request->validate([
            'subject' => 'required|string|max:255',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'attachments.*' => 'mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();
        try {

            if (!$request->hasFile('files')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded. Please upload PDF files.',
                ], 400);
            }

            foreach ($request->file('files') as $file) {
                $extension = strtolower($file->getClientOriginalExtension());

                if ($extension !== 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only PDF files are allowed.',
                    ], 422);
                }
            }
            // Store the mail details
            $mail = Mail::create([
                'cms_id' => $cms_id,
                'mail_code' => Mail::getUniqueCode(),
                'service_type' => 'mail_to_franchise_single',
                'subject' => $request->input('subject'),
                'body' => $request->input('message'),
            ]);

            // Create tracking order
            E2HTrackOrder::create([
                'mail_id' => $mail->id,
                'mail_code' => $mail->mail_code,
                'cms_id' => $cms_id,
                'destination_franchise_id' => $request->input('destination_franchise_id'),
            ]);

            // Store recipients

            Recipient::create([
                'mail_id' => $mail->id,
                'recipient_name' => $request->name,
                'recipient_phone' => $request->phone,
                'recipient_email' => $request->email,
                'destination_franchise_id' => $request->input('destination_franchise_id'),
                'recipient_address' => $request->address,
            ]);

            $attachments = [];
            $total_payment_amount = 0;
            $total_commission = 0;

            // Store attachments
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $newFileName = time() . '_' . $originalName;
                        $destinationPath = public_path('cms/mailtofranchise/attachments/');
                        $file->move($destinationPath, $newFileName);

                        $filePath = $destinationPath . $newFileName;
                        $fileSize = filesize($filePath);

                        // Initialize PDF parser
                        $parser = new Parser();
                        $pdf = $parser->parseFile($filePath);

                        // Get the number of pages in the PDF
                        $pages = count($pdf->getPages());

                        if ($pages > 0) {
                            // Calculate cost and commission
                            $rateCalculator = new RateCalculator;
                            $amount = $rateCalculator->calculateShippingCostForE2h($pages, Mail::E2H);
                            $commission = $rateCalculator->calculateCommissionForE2h($pages, 'cph', Mail::E2H);

                            // Accumulate total cost and commission
                            $total_payment_amount += $amount;
                            $total_commission += $commission;

                            // Store attachment details
                            $attachments[] = [
                                'mail_id' => $mail->id,
                                'file_path' => 'cms/mailtofranchise/attachments/' . $newFileName,
                                'file_name' => $originalName,
                                'file_size' => $fileSize,
                                'payment_amount' => $amount, // Individual file's amount
                                'pages' => $pages,
                            ];
                        } else {
                            throw new \Exception('Could not retrieve page count for ' . $originalName);
                        }
                    } catch (\Exception $e) {
                        // Log the error and continue processing other files
                        Log::error('File upload error: ' . $e->getMessage());
                        continue;
                    }
                }

                // Insert all attachments at once
                if (!empty($attachments)) {
                    MailAttachment::insert($attachments);
                }
            }

            // Convert attachment paths for email
            $attachmentsPaths = array_map(fn($attachment) => public_path($attachment['file_path']), $attachments);

            // Balance Deduction - Ensure sufficient funds before deduction
            $cmsDetails = CMS::findOrFail($cms_id);
            $wallet_balance = $cmsDetails->remaining_balance;

            if ($wallet_balance < $total_payment_amount) {
                throw new \Exception('Balance Low');
            }

            // Deduct the total payment amount
            $cmsDetails->decrement('remaining_balance', $total_payment_amount);
            $cmsDetails->save();

            // Store commission details
            CMSCommissionDetail::create([
                "cms_id" => $cms_id,
                "service_type" => Mail::E2H,
                "amount" => $total_payment_amount, // Store total amount
                "commission" => $total_commission, // Store total commission
            ]);

            $tofrachiseDetails = Franchise::findOrFail($request->input('destination_franchise_id'));

            MailFacade::to($tofrachiseDetails->email)->send(new MailToFranchise([
                'attachments' => $attachmentsPaths,
                'subject' => $request->input('subject'),
                'emailbody' =>  $request->input('message'),
                'fromFranchiseDetails' =>  $cmsDetails,
                'toPhone' =>  $request->phone,
                'toAddress' =>  $request->address,
                'toEmail' =>  $request->email,
            ]));

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Mail sent successfully!']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on Error
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }



    public function sentview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'franchise'])->findOrFail($id);

        // Loop through each recipient and add the destination franchise details
        $mail->recipients->transform(function ($recipient) {
            if ($recipient->destination_franchise_id) {
                // Fetch the franchise along with its 'kyc' relationship
                $recipient->destinationFranchiseDetails = Franchise::with('kyc')->find($recipient->destination_franchise_id);
            } else {
                $recipient->destinationFranchiseDetails = null;
            }
            return $recipient;
        });

        return view('cms.mailToFranchise.sentview', ['mail' => $mail]);
    }


    public function downloadFormat()

    {

        try {

            $filePath = public_path('admin/assets/file/excelFomatFileForMailToFranchise.xlsx');

            if (!file_exists($filePath)) {

                return back()->with('error', 'File not found');
            }

            return response()->download($filePath, 'excelFomatFile.xlsx');
        } catch (\Exception $e) {

            return back()->with('error', 'Error downloading file: ' . $e->getMessage());
        }
    }


    public function storeByFile(Request $request)
    {

        // Check if the file is uploaded
        if (!$request->hasFile('file')) {
            return back()->with('error', 'No file uploaded.');
        }

        // Get the uploaded file
        $file = $request->file('file');

        // Define the base destination path
        $baseDestinationPath = public_path('cms/mailtofranchisebulk/');

        // Generate a unique filename to avoid overwriting
        $fileName = uniqid() . '_' . $file->getClientOriginalName();

        // Move the uploaded ZIP file to the destination directory
        $file->move($baseDestinationPath, $fileName);

        // Set the full path of the moved ZIP file
        $filePath = $baseDestinationPath . $fileName;

        // Extracted folder name (based on the new unique file name without the .zip extension)
        $extractedFolderName = pathinfo($fileName, PATHINFO_FILENAME);
        $extractedFolderPath = $baseDestinationPath . $extractedFolderName;

        // Create a folder with the unique name
        if (!file_exists($extractedFolderPath)) {
            mkdir($extractedFolderPath, 0777, true);
        }

        // Initialize ZipArchive
        $zip = new \ZipArchive;

        // Check if the ZIP file can be opened
        if ($zip->open($filePath) === TRUE) {
            $zip->extractTo($extractedFolderPath);
            $zip->close();
            $directories = array_filter(glob($extractedFolderPath . '/*'), 'is_dir');
            $fullpath = count($directories) > 0 ? $directories[0] : null;
            if ($fullpath) {
                $this->processExcelFile($fullpath, $extractedFolderName, basename($fullpath));
            } else {
                return back()->with('error', 'No inner folder found in the extracted ZIP.');
            }

            return back()->with('success', 'mail sent successfully');
        } else {
            return back()->with('error', 'Failed to open ZIP file.');
        }
    }


    public function processExcelFile($fullpath, $extractedFolderName, $basefolder)
    {


        // Find the first Excel file inside the folder (.xls or .xlsx)
        $files = glob($fullpath . '/*.xls*'); // This will match both .xls and .xlsx files

        if (count($files) > 0) {
            $excelFilePath = $files[0]; // Take the first Excel file

            // Load the Excel file using PhpSpreadsheet
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excelFilePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Filter out rows where all values are null
            $filteredRows = array_filter($rows, function ($row) {
                // Check if the row is not empty and contains at least one non-null value
                return array_filter($row) !== [];
            });

            // Remove the first row (header) from the filtered result
            array_shift($filteredRows);

            // Reset keys of filtered rows
            $result = array_values($filteredRows);
            $cms_id = Auth::guard('cms')->user()->id;
            // Loop through rows and store them in the database
            foreach ($result as $row) {

                $franchiseemail = isset($row[1]) ? trim($row[1]) : '';
                $destinationFranchise = Franchise::where('email', $franchiseemail)->select('id')->first();
                $destinationfranchiseId = $destinationFranchise ? $destinationFranchise->id : null;

                if (!$destinationfranchiseId) {
                    continue;
                }

                $toname = $row[2] ?? '';
                $tomobile = $row[3] ?? '';
                $toemail = $row[4] ?? '';
                $toaddress = $row[5] ?? '';
                $subject = $row[6] ?? 'Message from Gotogo';
                $body = $row[7] ?? '';
                $document = $row[8] ?? '';


                $filePath = public_path('cms/mailtofranchisebulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document);

                // Create Mail record
                $mail = Mail::create([
                    'cms_id' => $cms_id,
                    'mail_code' => Mail::getUniqueCode(),
                    'service_type' => 'mail_to_franchise_bulk',
                    'subject'      => $subject,
                    'body'         => $body,
                ]);

                // Only insert recipient if the 'Email' field is available in the row

                Recipient::create([
                    'mail_id' => $mail->id,
                    'recipient_name' => $toname,
                    'recipient_phone' => $tomobile,
                    'destination_franchise_id' => $destinationfranchiseId,
                    'recipient_address' => $toaddress,
                    'recipient_email' => $toemail,
                ]);

                $fileSize = filesize($filePath);
                $payment_amount = 0;
                $commission = 0;
                // Initialize PDF parser
                $parser = new Parser();
                $pdf = $parser->parseFile($filePath);
                // Get the number of pages in the PDF
                $pages = count($pdf->getPages());
                $zone = 'A';     //curretly all zone have same price so i can give any zone

                if ($pages > 0) {
                    // Calculate the cost at 3 rupees per page

                    $rateCalculator = new RateCalculator;
                    $payment_amount = $rateCalculator->calculateShippingCostForE2h($pages, $zone, Mail::E2H);
                    $commission = $rateCalculator->calculateCommissionForE2h($pages, 'cph', Mail::E2H);

                    MailAttachment::create([
                        'mail_id' => $mail->id,
                        'file_path' => 'cms/mailtofranchisebulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document,
                        'file_name' => $document,
                        'file_size' => $fileSize,
                        'pages' => $pages,
                        'payment_amount' => $payment_amount,
                    ]);
                }

                $cmsDetails = CMS::findOrFail($cms_id);
                $wallet_balance = $cmsDetails->remaining_balance;
                if ($wallet_balance < $payment_amount) {
                    throw new \Exception('Balance Low');
                }
                $cmsDetails->decrement('remaining_balance', $payment_amount);
                $cmsDetails->save();

                CMSCommissionDetail::create([
                    "cms_id" => $cms_id,
                    "service_type" => Mail::E2H,
                    "amount" => $payment_amount,
                    "commission" => $commission,
                ]);

                // Create tracking order
                E2HTrackOrder::create([
                    'mail_id' => $mail->id,
                    'mail_code' => $mail->mail_code,
                    'cms_id' => $cms_id,
                    'destination_franchise_id' => $destinationfranchiseId,
                ]);

                MailFacade::to($franchiseemail)->send(new MailToFranchise([
                    'attachments' => [$filePath],
                    'subject' => $subject,
                    'emailbody' =>  $body,
                    'fromFranchiseDetails' =>  $cmsDetails,
                    'toPhone' =>  $tomobile,
                    'toAddress' =>  $toaddress,
                ]));
            }
        }
    }


    public function viewDeliveredMailParcel(Request $request)
    {
        $cms_id = Auth::guard('cms')->user()->id;
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();

        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::with(['recipients', 'attachments', 'franchise', 'deliveryBoy', 'cancelDelivery'])
            ->where(function ($query) {
                $query->where('service_type', 'mail_to_franchise_bulk')
                    ->orWhere('service_type', 'mail_to_franchise_single');
            })
            ->whereNotNull('delivery_boy_id')
            ->where('cms_id', $cms_id)
            ->whereDate('created_at', $date)
            ->where(function ($query) use ($date) {
                $query->whereDate('delivered_date', $date)
                    ->orWhere(function ($subQuery) use ($date) {
                        $subQuery->where('otp', '0')
                            ->whereHas('cancelDelivery', function ($cancelQuery) use ($date) {
                                $cancelQuery->whereDate('created_at', $date);
                            });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {
                // Calculate total payment amount from attachments
                $mail->total_payment_amount = number_format(
                    $mail->attachments->sum(fn($attachment) => (float) $attachment->payment_amount),
                    2
                );

                // Set franchise and user properties if they exist
                $mail->franchise = $mail->franchise_id ? $mail->franchise : null;
                $mail->user = $mail->user_id ? $mail->user : null;

                // Add destination franchise details for each recipient
                $mail->recipients = $mail->recipients->map(function ($recipient) {
                    $recipient->destinationFranchiseDetails = $recipient->destination_franchise_id
                        ? Franchise::find($recipient->destination_franchise_id)
                        : null;
                    return $recipient;
                });

                return $mail;
            });


        return view('cms.mailToFranchise.viewDeliveredMailParcel', [
            'recievedMails' => $recievedMails,
        ]);
    }



    public function trackOrder($id)
    {


        $trackingDetails = E2HTrackOrder::where('mail_id', $id)
            ->with([
                'sourceFranchise',
                'cms',
                'pph',
                'user',
                'destinationFranchise',
                'mail' => function ($query) {
                    $query->with('recipients');
                },
                'deliveryBoy'
            ])
            ->first();

        return response()->json([
            'trackingDetails' => $trackingDetails
        ]);
    }
}
