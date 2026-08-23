<?php



namespace App\Http\Controllers\api\franchise;



use App\Http\Controllers\Controller;
use App\Mail\MailToMail;

use App\Models\PickupDetails;
use App\Models\Franchise;
use App\Models\Mail;

use App\Models\MailAttachment;

use App\Models\Recipient;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use  App\Http\Controllers\franchise\RateCalculator;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Config;

use DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\FranchiseCommissionDetail;

class FranchiseMailToFranchiseController extends Controller

{

    public function receivedMails(Request $request)
    {

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $franchiseId = Auth::guard('apifranchise')->user()->id;
        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) {
            // Check service types
            $query->where('service_type', 'mail_to_franchise_bulk')
                ->orWhere('service_type', 'mail_to_franchise_single');
        })
            ->whereDate('created_at', $date)
            ->whereHas('recipients', function ($recipientQuery) use ($franchiseId) {
                // Filter recipients based on franchise email or phone
                $recipientQuery->where('destination_franchise_id', $franchiseId);
            })

            ->with(['recipients', 'attachments', 'franchise'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {
                // Set franchise and user properties if they exist
                $mail->franchise = $mail->franchise_id != 0 ? $mail->franchise : null;
                $mail->user = $mail->user_id != 0 ? $mail->user : null;
                $mail->cms = $mail->cms_id != 0 ? $mail->cms : null;
                $mail->pph = $mail->pph_id != 0 ? $mail->pph : null;;
                return $mail;
            });

        return response()->json(['status' => 200, 'message' => 'data fetch successful', 'data' => $recievedMails]);
    }



    public function sentSingle(Request $request)

    {
        $franchiseId = Auth::guard('apifranchise')->user()->id;
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $sentMails = Mail::where('franchise_id', $franchiseId)
            ->where('service_type', 'mail_to_franchise_single')
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

        return response()->json(['status' => 200, 'message' => 'data fetch successfull', 'data' => $sentMails]);
    }


    public function sentBulk()

    {
        $franchiseId = Auth::guard('apifranchise')->user()->id;
        $sentMails = Mail::where('franchise_id', $franchiseId)
            ->where('service_type', 'mail_to_franchise_bulk')
            ->with(['recipients', 'attachments', 'user', 'franchise'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {
                if ($mail->franchise_id != 0) {
                    $mail->franchise = $mail->franchise;
                } else {
                    $mail->franchise = null;
                }

                if ($mail->franchise_id != 0) {
                    $mail->user = $mail->user;
                } else {
                    $mail->user = null;
                }

                return $mail;
            });


        return response()->json(['status' => 200, 'message' => 'data fetch successfull', 'data' => $sentMails]);
    }



    public function postSingle(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'phone' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400); // You can change the status code if needed
        }

        $franchiseId = Auth::guard('apifranchise')->user()->id;
        $frachiseDetails = Auth::guard('apifranchise')->user();

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        try {


            $mail = Mail::create([
                'franchise_id' => $franchiseId,
                'service_type' => 'mail_to_franchise_single',
                'subject' => $request->input('subject'),
                'body' => $request->input('message'),
            ]);

            // // Store recipients
            Recipient::create([
                'mail_id' => $mail->id,
                'recipient_name' => $request->name,
                'recipient_phone' => $request->phone,
                'recipient_email' => $request->email,
                'destination_franchise_id' => $request->input('destination_franchise_id'),
                'recipient_address' => $request->address,
            ]);


            $attachments = [];
            $payment_amount = 0;
            $commission = 0;
            // // Store attachments
            if ($request->hasFile('files')) {

                foreach ($request->file('files') as $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $newFileName = time() . '_' . $originalName;
                        $destinationPath = public_path('franchise/mailtofranchise/attachments/');
                        $file->move($destinationPath, $newFileName);

                        $filePath = $destinationPath . $newFileName;

                        $fileSize = filesize($filePath);
                        // Initialize PDF parser
                        $parser = new Parser();
                        $pdf = $parser->parseFile($filePath);

                        // Get the number of pages in the PDF
                        $pages = count($pdf->getPages());
                        $zone = 'A';

                        if ($pages > 0) {
                            // Calculate the cost at 3 rupees per page
                            $rateCalculator = new RateCalculator;
                            $payment_amount = $rateCalculator->calculateShippingCostForE2h($pages, $zone, Mail::E2H);
                            $commission += $rateCalculator->calculateCommissionForE2h($pages, 'franchise', Mail::E2H);

                            $attachments[] = [
                                'mail_id' => $mail->id,
                                'file_path' => 'franchise/mailtofranchise/attachments/' . $newFileName,
                                'file_name' => $originalName,
                                'file_size' => $fileSize,
                                'payment_amount' => $payment_amount,
                                'pages' => $pages,
                            ];
                        } else {
                            // Handle error if page count could not be retrieved
                            return response()->json(['status' => false, 'message' => 'Could not retrieve page count for ' . $originalName]);
                        }
                    } catch (\Exception $e) {
                        // Handle exception if something goes wrong during the file upload or parsing process
                        return response()->json(['status' => false, 'message' => 'File upload or parsing error: ' . $e->getMessage()]);
                    }
                }


                MailAttachment::insert($attachments);
            }


            //=====================handling wallet and adding commission start==========//
            $frachiseDetails->decrement('remaining_balance', $payment_amount);
            $frachiseDetails->increment('commission', $commission);
            $frachiseDetails->increment('remaining_balance', $commission);
            $frachiseDetails->save();

            FranchiseCommissionDetail::create([
                "franchise_id" => $franchiseId,
                "service_type" => Mail::E2H,
                "amount" => $payment_amount,
                "commission" => $commission,
            ]);
            //=====================handling wallet and adding commission end==========//


            return response()->json(['status' => true, 'message' => 'Mail sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }





    public function getPrice(Request $request)
    {
        try {
            $payment_amount = 0;
            $file_costs = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    try {
                        $fileSize = $file->getSize();
                        $fileSizeInMB = $fileSize / (1024 * 1024);
                        // 5rupee = 1mb
                        $cost = $fileSizeInMB * 5;
                        $cost = number_format($cost, 2);
                        $payment_amount += $cost;
                        $file_costs[] = [
                            'file_name' => $file->getClientOriginalName(),
                            'cost' => $cost
                        ];
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => 'File size error: ' . $e->getMessage()]);
                    }
                }
            }
            return response()->json([
                'status' => true,
                'file_costs' => $file_costs,
                'total_payment_amount' => number_format($payment_amount, 2)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    public function postBulk(Request $request)
    {

        // Check if the file is uploaded
        if (!$request->hasFile('file')) {
            return response()->json(['status' => true, 'message' => 'no file selected!']);
        }

        // Get the uploaded file
        $file = $request->file('file');

        // Define the base destination path
        $baseDestinationPath = public_path('franchise/mailtofranchisebulk/');

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
                return response()->json(['status' => true, 'message' => 'No inner folder found in the extracted ZIP.']);
            }

            return response()->json(['status' => true, 'message' => 'Mail sent successfully!']);
        } else {
            return response()->json(['status' => false, 'message' => 'Failed to open ZIP file.']);
        }
    }


    public function processExcelFile($fullpath, $extractedFolderName, $basefolder)
    {

        // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
        // Config::set('mail.mailers.smtp.port', 587);
        // Config::set('mail.mailers.smtp.username', 'snehalsharan10@gmail.com');
        // Config::set('mail.mailers.smtp.password', 'aipn fdol xxjb rshv');
        // Config::set('mail.mailers.smtp.encryption', 'tls');
        // Config::set('mail.from.address', 'deferfe1214@gmail.com');
        // Config::set('mail.from.name', 'gotogopost');

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
            $franchiseId = Auth::guard('apifranchise')->user()->id;
            $frachiseDetails = Franchise::findOrFail($franchiseId);


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


                $filePath = public_path('franchise/mailtofranchisebulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document);

                // Create Mail record
                $mail = Mail::create([
                    'franchise_id' => $franchiseId,
                    'service_type' => 'mail_to_franchise_bulk',
                    'subject'      => $subject,
                    'body'         => $body,
                ]);

                // Only insert recipient if the 'Email' field is available in the row

                Recipient::create([
                    'mail_id' => $mail->id,
                    'recipient_name' => $toname,
                    'recipient_phone' => $tomobile,
                    'recipient_email' => $toemail,
                    'recipient_address' => $toaddress,
                    'destination_franchise_id' => $destinationfranchiseId,
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
                    $commission = $rateCalculator->calculateCommissionForE2h($pages, 'franchise', Mail::E2H);

                    MailAttachment::create([
                        'mail_id' => $mail->id,
                        'file_path' => 'franchise/mailtofranchisebulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document,
                        'file_name' => $document,
                        'file_size' => $fileSize,
                        'pages' => $pages,
                        'payment_amount' => $payment_amount,
                    ]);
                }

                $frachiseDetails->decrement('remaining_balance', $payment_amount);
                $frachiseDetails->increment('commission', $commission);
                $frachiseDetails->increment('remaining_balance', $commission);
                $frachiseDetails->save();

                FranchiseCommissionDetail::create([
                    "franchise_id" => $franchiseId,
                    "service_type" => Mail::E2H,
                    "amount" => $payment_amount,
                    "commission" => $commission,
                ]);

                // MailFacade::to($franchiseemail)->send(new MailToFranchise([
                //     'attachments' => [$filePath],
                //     'subject' => $subject,
                //     'emailbody' =>  $body,
                //     'fromFranchiseDetails' =>  $frachiseDetails,
                //     'toPhone' =>  $tomobile,
                //     'toAddress' =>  $toaddress,
                // ]));
            }
        }
    }
}
