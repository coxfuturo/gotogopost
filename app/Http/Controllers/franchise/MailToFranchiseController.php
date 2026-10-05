<?php



namespace App\Http\Controllers\franchise;



use App\Http\Controllers\Controller;
use App\Mail\MailToFranchise;
use Smalot\PdfParser\Parser;
use App\Models\SoftCopyParcel;

use App\Models\PickupDetails;

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
use App\Models\E2HTrackOrder;
use Carbon\Carbon;

class MailToFranchiseController extends Controller

{


    public function receivedMails(Request $request)
    {

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $franchiseId = Franchise::getFranchiseId();
        $deliveryBoy = DeliveryBoy::where('franchise_id', Franchise::getFranchiseId())->get();
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

        return view('franchise.mailToFranchise.receivedMail', ['recievedMails' => $recievedMails, 'deliveryBoy' => $deliveryBoy]);
    }


    public function sentMails(Request $request)
    {

        $service_type = $request->service_type;
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $sentMails = Mail::where('franchise_id', Franchise::getFranchiseId())
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


        return view('franchise.mailToFranchise.sentMails', ['sentMails' => $sentMails]);
    }

    public function attachmentReport(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();

        $mail_to_mail_single = "mail_to_franchise_single";
        $mail_to_mail_bulk = "mail_to_franchise_bulk";

        $sentMails = Mail::where('franchise_id', Franchise::getFranchiseId())
            ->where(function ($query) use ($mail_to_mail_single, $mail_to_mail_bulk) {
                $query->where('service_type', $mail_to_mail_single)
                    ->orWhere('service_type', $mail_to_mail_bulk);
            })
            ->whereDate('created_at', $date)
            ->with([
                'recipients' => function ($query) use ($date) {
                    $query->whereDate('view_date', $date)
                        ->select('id', 'mail_id', 'recipient_name', 'recipient_phone', 'recipient_email', 'view_date');
                },
                'attachments:id,mail_id,file_name,file_path' // Sirf necessary attachment fields
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // **📌 New Array with Required Data**
        $formattedData = [];

        foreach ($sentMails as $mail) {
            foreach ($mail->recipients as $recipient) {
                $formattedData[] = [
                    'recipient_name' => $recipient->recipient_name ?? 'N/A',
                    'recipient_phone' => $recipient->recipient_phone ?? 'N/A',
                    'recipient_email' => $recipient->recipient_email ?? 'N/A',
                    'view_date' => $recipient->view_date ?? 'N/A',
                    'mail_code' => $mail->mail_code ?? 'N/A',
                    'mail_subject' => $mail->subject ?? 'N/A',
                    'attachments' => $mail->attachments->map(function ($attachment) {
                        return [
                            'file_name' => $attachment->file_name,
                            'file_path' => $attachment->file_path
                        ];
                    })->toArray()
                ];
            }
        }


        return view('franchise.mailToFranchise.attachmentReport', ['recipients' => $formattedData]);
    }

    public function create()

    {
        $allFranchise = Franchise::where('status', 1)->get();
        return view('franchise.mailToFranchise.create', compact('allFranchise'));
    }

    function shortURL($longUrl)
    {
        $response = Http::get('https://tinyurl.com/api-create.php', [
            'url' => $longUrl
        ]);

        return $response->body();
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'phone' => 'required',
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'attachments.*' => 'mimes:pdf',
        ]);

        return DB::transaction(function () use ($request) {
            if (!$request->hasFile('files')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded. Please upload PDF files.',
                ], 400);
            }

            foreach ($request->file('files') as $file) {
                if (strtolower($file->getClientOriginalExtension()) !== 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only PDF files are allowed.',
                    ], 422);
                }
            }

            // Store the mail details
            $mail = Mail::create([
                'franchise_id' => Franchise::getFranchiseId(),
                'mail_code' => Mail::getUniqueCode(),
                'service_type' => 'mail_to_franchise_single',
                'subject' => $request->input('subject'),
                'body' => $request->input('message'),
            ]);

            // Create tracking order
            E2HTrackOrder::create([
                'mail_id' => $mail->id,
                'mail_code' => $mail->mail_code,
                'source_franchise_id' => Franchise::getFranchiseId(),
                'destination_franchise_id' => $request->input('destination_franchise_id'),
            ]);

            // Store recipient details
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

            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $newFileName = time() . '_' . $originalName;
                $destinationPath = public_path('franchise/mailtofranchise/attachments/');
                $file->move($destinationPath, $newFileName);

                $filePath = $destinationPath . $newFileName;
                $fileSize = filesize($filePath);

                if ($fileSize === false) {
                    throw new \Exception('Could not retrieve file size for ' . $originalName);
                }

                // Parse PDF for number of pages
                $parser = new Parser();
                $pdf = $parser->parseFile($filePath);
                $pages = count($pdf->getPages());

                if ($pages > 0) {
                    $rateCalculator = new RateCalculator;
                    $amount = $rateCalculator->calculateShippingCostForE2h($pages, Mail::E2H);
                    // $commission = $rateCalculator->calculateCommissionForE2h($pages, 'franchise', Mail::E2H);
                    $commission = $amount * 0.05;
                    // Total amount aur commission increment karo
                    $total_payment_amount += $amount;
                    $total_commission += $commission;

                    $attachments[] = [
                        'mail_id' => $mail->id,
                        'file_path' => 'franchise/mailtofranchise/attachments/' . $newFileName,
                        'file_name' => $originalName,
                        'file_size' => $fileSize,
                        'payment_amount' => $amount, // Individual file ka amount
                        'pages' => $pages,
                    ];
                } else {
                    throw new \Exception('Could not retrieve page count for ' . $originalName);
                }
            }

            // Deduct balance from franchise account
            $franchise = Franchise::findOrFail(Franchise::getFranchiseId());
            $gotogo_balance = $franchise->gotogo_balance;
            $credit_balance = $franchise->credit_balance;
            $total_balance = $gotogo_balance + $credit_balance;

            if ($total_balance < $total_payment_amount) {
                throw new \Exception('Balance Low');
            }

            $deduct_from_gotogo = min($gotogo_balance, $total_payment_amount);
            $franchise->decrement('gotogo_balance', $deduct_from_gotogo);
            $remaining_amount = $total_payment_amount - $deduct_from_gotogo;

            if ($remaining_amount > 0) {
                $franchise->decrement('credit_balance', $remaining_amount);
            }

            // Store attachments
            MailAttachment::insert($attachments);

          // Franchise-specific commission
$franchiseId = Franchise::getFranchiseId();
$serviceType = Mail::E2H;

$commissionData = $rateCalculator->calculateFranchiseCommission(
    $franchiseId,
    $serviceType,
    $total_payment_amount
);

$commissionRate = $commissionData['rate'];
$total_commission = $commissionData['commission'];

// Store commission details
FranchiseCommissionDetail::create([
    'franchise_id' => $franchiseId,
    'service_type' => $serviceType,
    'amount' => $total_payment_amount,
    'commission' => $total_commission,
    'commission_rate' => $commissionRate,
]);

            // Send email
            $attachmentsPaths = array_map(fn($attachment) => public_path($attachment['file_path']), $attachments);
            $toFranchiseDetails = Franchise::findOrFail($request->input('destination_franchise_id'));

            // MailFacade::to($toFranchiseDetails->email)->queue(new MailToFranchise([
            //     'attachments' => $attachmentsPaths,
            //     'subject' => $request->input('subject'),
            //     'emailbody' => $request->input('message'),
            //     'fromFranchiseDetails' => $franchise,
            //     'toPhone' => $request->phone,
            //     'toAddress' => $request->address,
            //     'toEmail' => $request->email,
            // ]));


            MailFacade::to($toFranchiseDetails->email)->send(new MailToFranchise([
                'attachments' => $attachmentsPaths,
                'subject' => $request->input('subject'),
                'emailbody' => $request->input('message'),
                'fromFranchiseDetails' => $franchise,
                'toPhone' => $request->phone,
                'toAddress' => $request->address,
                'toEmail' => $request->email,
            ]));



            $phones = $request->input('to-phone', []);
            foreach ($phones as $phone) {
                // Construct download URL correctly
                $downloadUrl = env('APP_URL') . "/franchise/downloadAttachmentView/{$mail->mail_code}?phone={$phone}";
                $shortUrl = $this->shortURL($downloadUrl);


                // Uncomment this when ready to send the SMS
                // $notification = new SMSNotification($phone, 'OTP', [$shortUrl]);
                // $notification->sendMessage();
            }

            return response()->json(['status' => true, 'message' => 'Mail sent successfully!']);
        }, 5); // Transaction with 5 retry attempts
    }


    public function sendMail(Request $request)
    {

        // Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
        // Config::set('mail.mailers.smtp.port', 587);
        // Config::set('mail.mailers.smtp.username', 'snehalsharan10@gmail.com');
        // Config::set('mail.mailers.smtp.password', 'aipn fdol xxjb rshv');
        // Config::set('mail.mailers.smtp.encryption', 'tls');
        // Config::set('mail.from.address', 'deferfe1214@gmail.com');
        // Config::set('mail.from.name', 'gotogopost');

        Mail::to('test@YOPmail.com')->send(new MailToMail());
        return "Email sent successfully!";
    }


    public function receivedview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'franchise', 'deliveryBoy', 'cms', 'pph', 'user'])
            ->where('mail_code', $id)
            ->firstOrFail();

        return view('franchise.mailToFranchise.receivedview', ['mail' => $mail]);
    }


    public function sentview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'franchise'])
            ->where('mail_code', $id)
            ->firstOrFail();

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

        return view('franchise.mailToFranchise.sentview', ['mail' => $mail]);
    }


    public function downloadFormat()

    {

        try {

            $filePath = public_path('admin/assets/file/excelFomatFileForMailToMail.xlsx');

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
            $franchise = Franchise::findOrFail(Franchise::getFranchiseId());

            // Loop through rows and store them in the database
            foreach ($result as $row) {

                // 0 => "1"
                // 1 => "shri@gmail.com"
                // 2 => "rahul"
                // 3 => "2345672345"
                // 4 => "rahul@gmail.com"
                // 5 => "Fortis La Femme S-549, Part-2, Greater Kailash, New Delhi, Delhi 110048, India"
                // 6 => "this is test subject 1 "
                // 7 => "this is message body 1"
                // 8 => "10023 Invoice of swam pneumatics (2).pdf"

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
                    'franchise_id' => Franchise::getFranchiseId(),
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


                if ($pages > 0) {
                    // Calculate the cost at 3 rupees per page

                    $rateCalculator = new RateCalculator;
                    $payment_amount = $rateCalculator->calculateShippingCostForE2h($pages, Mail::E2H);
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

                // Deduct balance from franchise account

                $gotogo_balance = $franchise->gotogo_balance;
                $credit_balance = $franchise->credit_balance;
                $total_balance = $gotogo_balance + $credit_balance;

                if ($total_balance < $payment_amount) {
                    throw new \Exception('Balance Low');
                }

                $deduct_from_gotogo = min($gotogo_balance, $payment_amount);
                $franchise->decrement('gotogo_balance', $deduct_from_gotogo);
                $remaining_amount = $payment_amount - $deduct_from_gotogo;

                if ($remaining_amount > 0) {
                    $franchise->decrement('credit_balance', $remaining_amount);
                }

                FranchiseCommissionDetail::create([
                    "franchise_id" => Franchise::getFranchiseId(),
                    "service_type" => Mail::E2H,
                    "amount" => $payment_amount,
                    "commission" => $commission,
                ]);

                MailFacade::to($franchiseemail)->send(new MailToFranchise([
                    'attachments' => [$filePath],
                    'subject' => $subject,
                    'emailbody' =>  $body,
                    'fromFranchiseDetails' =>  $franchise,
                    'toPhone' =>  $tomobile,
                    'toAddress' =>  $toaddress,
                ]));


                // Construct download URL correctly
                $downloadUrl = env('APP_URL') . "/franchise/downloadAttachmentView/{$mail->mail_code}?phone={$tomobile}";
                $shortUrl = $this->shortURL($downloadUrl);

                // $notification = new SMSNotification($mobile, 'OTP', [$shortUrl]);
                // $notification->sendMessage();
            }
        }
    }



    public function assigntoDeliveryBoy(Request $request)
    {
        // Decode the JSON string for selectedMails into an array
        $selectedMailIds = json_decode($request->input('selectedMails'), true);

        // Retrieve the delivery boy ID from the request
        $deliveryBoyId = $request->input('mailAssignedTodelboy'); // assuming this is the delivery boy ID

        // Check if selectedMailIds is a valid array and if deliveryBoyId is present
        if (is_array($selectedMailIds) && !empty($selectedMailIds) && !empty($deliveryBoyId)) {
            // Directly update all the selected mail records in a single query
            Mail::whereIn('id', $selectedMailIds)
                ->update(['delivery_boy_id' => $deliveryBoyId]);

            E2HTrackOrder::whereIn('mail_id', $selectedMailIds)
                ->update([
                    'delivery_boy_id' => $deliveryBoyId,
                    'delivery_boy_assigned_datetime' => now(),
                ]);


            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Delivery Boy assigned successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid mail selection or missing delivery boy ID.'
            ], 400);
        }
    }



    public function viewAssignedMailParcel(Request $request)
    {


        $franchiseId = Franchise::getFranchiseId();

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();

        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) {
            // Check service types
            $query->where('service_type', 'mail_to_franchise_bulk')
                ->orWhere('service_type', 'mail_to_franchise_single');
        })
            ->whereNotNull('delivery_boy_id')
            ->whereDate('created_at', $date)
            ->whereHas('recipients', function ($recipientQuery) use ($franchiseId) {
                // Filter recipients based on franchise email or phone
                $recipientQuery->where('destination_franchise_id', $franchiseId);
            })
            ->with(['recipients', 'attachments', 'franchise', 'deliveryBoy', 'cms', 'pph', 'user'])
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


        return view('franchise.mailToFranchise.viewAssignedMailParcel', [
            'recievedMails' => $recievedMails,
        ]);
    }

    public function viewUnAssignedMailParcel(Request $request)
    {
        $franchiseId = Franchise::getFranchiseId();

        $deliveryBoy = DeliveryBoy::where('franchise_id', Franchise::getFranchiseId())->get();
        $recievedMails = Mail::where(function ($query) {
            $query->where('service_type', 'mail_to_franchise_bulk')
                ->orWhere('service_type', 'mail_to_franchise_single');
        })
            ->where(function ($query) {
                $query->whereNull('delivery_boy_id')
                    ->orWhere('delivery_boy_id', 0);
            })
            ->whereHas('recipients', function ($recipientQuery) use ($franchiseId) {
                $recipientQuery->where('destination_franchise_id', $franchiseId);
            })
            ->with(['recipients', 'attachments', 'franchise', 'cms', 'pph', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {
                $mail->franchise = $mail->franchise_id != 0 ? $mail->franchise : null;
                $mail->user = $mail->user_id != 0 ? $mail->user : null;
                $mail->cms = $mail->cms_id != 0 ? $mail->cms : null;
                $mail->pph = $mail->pph_id != 0 ? $mail->pph : null;
                return $mail;
            });

        return view('franchise.mailToFranchise.receivedMail', ['recievedMails' => $recievedMails, 'deliveryBoy' => $deliveryBoy]);
    }


    public function viewDeliveredMailParcel(Request $request)
    {

        $franchiseId = Franchise::getFranchiseId();

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();

        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) {
            // Check service types
            $query->where('service_type', 'mail_to_franchise_bulk')
                ->orWhere('service_type', 'mail_to_franchise_single');
        })
            ->whereNotNull('delivery_boy_id')
            ->whereDate('created_at', $date)
            ->whereHas('recipients', function ($recipientQuery) use ($franchiseId) {
                // Filter recipients based on franchise email or phone
                $recipientQuery->where('destination_franchise_id', $franchiseId);
            })
            ->with(['recipients', 'attachments', 'franchise', 'deliveryBoy', 'cms', 'pph', 'user'])
            ->whereDate('delivered_date', $date)
            ->orWhere(function ($subQuery) use ($date) {
                $subQuery->where('otp', '0') // Check for otp = 0
                    ->whereHas('cancelDelivery', function ($query) use ($date) {
                        $query->whereDate('created_at', $date); // Match cancelDelivery created_at date with the requested date
                    });
            })
            ->with('cancelDelivery')
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

        return view('franchise.mailToFranchise.viewDeliveredMailParcel', [
            'recievedMails' => $recievedMails,
        ]);
    }

    public function trackOrder($id)
    {


        $trackingDetails = E2HTrackOrder::where('mail_id', $id)
            ->with([
                'sourceFranchise',
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
