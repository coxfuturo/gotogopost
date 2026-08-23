<?php



namespace App\Http\Controllers\franchise;



use App\Http\Controllers\Controller;
use App\Mail\MailToMail;
use App\Models\Mail;
use Illuminate\Support\Facades\Mail as MailFacade;
use App\Models\Franchise;
use App\Models\MailAttachment;
use App\Models\Recipient;
use App\Models\User;
use Illuminate\Http\Request;
use ZipArchive;
use DB;
use Illuminate\Support\Facades\Http;
use Log;
use Carbon\Carbon;
use App\Notifications\SMSNotification;
use App\Notifications\FranchisePushNotification;
use Config;

class MailToMailController extends Controller

{

    public function receivedMails(Request $request)
    {
        // Fetch franchise details
        $franchiseDetails = Franchise::findOrFail(Franchise::getFranchiseId());
        $franchisePhone = $franchiseDetails->mobile;
        $franchiseEmail = $franchiseDetails->email;

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) use ($franchiseEmail, $franchisePhone) {
            // Check service types
            $query->where('service_type', 'mail_to_mail_bulk')
                ->orWhere('service_type', 'mail_to_mail_single');
        })
            ->whereDate('created_at', $date)
            ->whereHas('recipients', function ($recipientQuery) use ($franchiseEmail, $franchisePhone) {
                // Filter recipients based on franchise email or phone
                $recipientQuery->where('recipient_email', $franchiseEmail)
                    ->orWhere('recipient_phone', $franchisePhone);
            })
            ->with(['recipients', 'attachments', 'cms', 'pph', 'user', 'franchise'])
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


        return view('franchise.mailToMail.receivedMail', ['recievedMails' => $recievedMails]);
    }


    public function sentMails(Request $request)

    {

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $service_type = $request->service_type;
        // return  Mail::where('franchise_id', Franchise::getFranchiseId())->get();
        $sentMails = Mail::where('franchise_id', Franchise::getFranchiseId())
            ->where('service_type', $service_type)
            ->with(['recipients', 'attachments'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {
                if ($mail->franchise_id != 0) {
                    $mail->franchise = $mail->franchise;
                } else {
                    $mail->franchise = null;
                }

                if ($mail->user_id != 0) {
                    $mail->user = $mail->user;
                } else {
                    $mail->user = null;
                }

                $cost = $mail->attachments->sum(function ($attachment) {
                    return (float) $attachment->payment_amount;
                });
                $mail->total_payment_amount = number_format($cost, 2);
                return $mail;
            });

        return view('franchise.mailToMail.sentMails', ['sentMails' => $sentMails]);
    }


    public function attachmentReport(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();

        $mail_to_mail_single = "mail_to_mail_single";
        $mail_to_mail_bulk = "mail_to_mail_bulk";

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


        return view('franchise.mailToMail.attachmentReport', ['recipients' => $formattedData]);
    }



    function shortURL($longUrl)
    {
        $response = Http::get('https://tinyurl.com/api-create.php', [
            'url' => $longUrl
        ]);

        return $response->body();
    }


    public function create()

    {

        // $notification = new SMSNotification(9536970222, 'ETOE', ['1234', "1234", "https://gotogopost.com"]);
        // $notification->sendMessage();

        // dd('jjjjj');


        // $mail_code = 'E846D0C7890C2E';
        // $phone = '5454565677';
        // $downloadUrl = env('APP_URL') . "/franchise/downloadAttachmentView/{$mail_code}?phone={$phone}";
        // $shortUrl = $this->shortURL($downloadUrl);

        // dd($shortUrl);

        // $shortUrl = $this->shortURL($downloadUrl);

        // $otp = 'donwload';
        // $notification = new SMSNotification(9536970222, 'OTP', [$otp]);
        // $notification->sendMessage();

        // dd('jjjjjj');
        // dd($shortUrl);

        // return view('franchise.mail.mailtomailview');
        return view('franchise.mailToMail.create');
    }


    public function sendE2ENotificationToUser($parcel, $user, $franchise)
    {
        try {

            $token = $user->fcm_token;

            if (!$token) {
                \Log::error("FCM token not found for user", ['user_id' => $user->id]);
                return;
            }

            // Get sender (franchise) name
            $fromName = $franchise->name ?? $franchise->username ?? 'Unknown Sender';

            $title = "E2E Mail Received";
            // Title & Body for Notification
            $body = "Hello {$user->name},\n\n"
                . "You have received an E2E Mail from *{$fromName}*.\n\n"
                . "📌 Subject: {$parcel->subject}\n\n"
                . "📅 Delivered On: " . Carbon::now()->format('d M, Y') . "\n\n"
                . "Thank you for using our service! 🚀";

            \Log::info("Preparing to send push notification", [
                'title' => $title,
                'body' => $body,
                'user_id' => $user->id,
                'parcel_id' => $parcel->id
            ]);
            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $response = $notification->sendPushNotification();

            if ($response != 1) {
                \Log::info("Push notification could not sent");
                return;
            }

            \Log::info("Push notification sent successfully", ['response' => $response]);

            // Save notification in DB
            DB::table('user_notifications')->insert([
                'user_id' => $user->id,
                'service_type' => 8,
                'message' => "E2E Mail Received",
                'barcode_no' => $parcel->mail_code,
                'body' => $body,
                'current_location' => $user->address,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Log::info("User notification record created successfully");
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToUser: " . $e->getMessage(), [
                'parcel_id' => $parcel->id ?? null,
                'user_phone' => $userPhone ?? null,
                'exception' => $e
            ]);
        }
    }


    public function store(Request $request)
    {
      
        $request->validate([
            'subject' => 'required|string|max:255',
            // 'files' => 'required|file|mimes:jpg,png,pdf|max:2048'
        ]);

        DB::beginTransaction(); // Start Transaction

        try {
            // Store the mail details
            $mail = Mail::create([
                'franchise_id' => Franchise::getFranchiseId(),
                'mail_code' => Mail::getUniqueCode(),
                'service_type' => 'mail_to_mail_single',
                'subject' => $request->input('subject'),
                'body' => $request->input('message'),
            ]);

            // Store recipients
            $recipients = [];
            foreach ($request->input('to-phone', []) as $phone) {
                $recipients[] = ['mail_id' => $mail->id, 'recipient_phone' => $phone];
            }
            foreach ($request->input('to-email', []) as $email) {
                $recipients[] = ['mail_id' => $mail->id, 'recipient_email' => $email];
            }

            Recipient::insert($recipients); // Insert all recipients in one go

            $attachments = [];
            $payment_amount = 0;

            // Store attachments
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $newFileName = time() . '_' . $originalName;
                    $destinationPath = public_path('franchise/mailtomail/attachments/');
                    $file->move($destinationPath, $newFileName);

                    $filePath = $destinationPath . $newFileName;
                    $fileSize = filesize($filePath);

                    if ($fileSize === false) {
                        throw new \Exception('Could not retrieve file size for ' . $originalName);
                    }

                    // 1 rupee per MB
                    $fileSizeInMB = $fileSize / (1024 * 1024);
                    $cost = number_format($fileSizeInMB * 1, 2);

                    $payment_amount += $cost;
                    $attachments[] = [
                        'mail_id' => $mail->id,
                        'file_path' => 'franchise/mailtomail/attachments/' . $newFileName,
                        'file_name' => $originalName,
                        'file_size' => $fileSize,
                        'payment_amount' => $cost,
                    ];
                }
                MailAttachment::insert($attachments);
            }

            $franchise = Franchise::findOrFail(Franchise::getFranchiseId());
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

            // Send Emails
            $emails = $request->input('to-email', []);
            $phones = $request->input('to-phone', []);
            $attachmentsPaths = array_map(fn($attachment) => public_path($attachment['file_path']), $attachments);

            foreach ($emails as $email) {
                MailFacade::to($email)->send(new MailToMail([
                    'attachments' => $attachmentsPaths,
                    'subject' => $request->input('subject'),
                    'emailbody' =>  $request->input('message'),
                ]));

                $user = User::where("email", $email)->first();
                if ($user) {
                    $this->sendE2ENotificationToUser($mail, $user, $franchise);
                }
            }


            foreach ($phones as $phone) {
                // Construct download URL correctly
                $downloadUrl = env('APP_URL') . "/?/{$mail->mail_code}/{$phone}";

                $user = User::where("phone", $phone)->first();
                if ($user) {
                    $user_email = $user->email;
                    // MailFacade::to($user_email)->send(new MailToMail([
                    //     'attachments' => $attachmentsPaths,
                    //     'subject' => $request->input('subject'),
                    //     'emailbody' =>  $request->input('message'),
                    //     'donwloadLink' =>  $downloadUrl
                    // ]));

                    $this->sendE2ENotificationToUser($mail, $user, $franchise);
                }

                // Uncomment this when ready to send the SMS
                $notification = new SMSNotification($phone, 'ETOE', [$mail->mail_code, $franchise->name, $downloadUrl]);
                $notification->sendMessage();
            }


            DB::commit(); // Commit Transaction

            return response()->json(['status' => true, 'message' => 'Mail sent successfully!', 'service_type' => 'mail_to_mail_single']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback Transaction on error
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function downloadAttachmentView()
    {
        return view('franchise.mailToMail.downloadAttachmentView');
    }

    public function downloadAttachment($mail_code, $phone)
    {

        $mail = DB::table('mails')
            ->where('mails.mail_code', $mail_code)
            ->leftJoin('users', 'users.id', '=', 'mails.user_id') // Join with users
            ->leftJoin('franchises', 'franchises.id', '=', 'mails.franchise_id') // Join with franchises
            ->select('mails.id', 'users.phone as user_phone', 'franchises.mobile as franchise_phone')
            ->first(); // Get first result

        // Agar mail nahi mila toh error response bhejo
        if (!$mail) {
            return response()->json(['message' => 'Mail not found.'], 404);
        }

        // Attachments fetch karo
        $attachments = MailAttachment::where('mail_id', $mail->id)->get();

        if ($attachments->isEmpty()) {
            return response()->json(['message' => 'No files found.'], 404);
        }

        Recipient::where('mail_id', $mail->id)
            ->where('recipient_phone', $phone)
            ->update(['view_date' => Carbon::now()]);


        $userPhone = $mail->user_phone;
        $franchisePhone = $mail->franchise_phone;

        if ($franchisePhone) {
            $notification = new SMSNotification($franchisePhone, 'OTP', [123456]);
            $notification->sendMessage();
        } else if ($userPhone) {
            $notification = new SMSNotification($franchisePhone, 'OTP', [123456]);
            $notification->sendMessage();
        }

        // File URLs ka array banao
        $fileUrls = $attachments->map(fn($attachment) => asset($attachment->file_path))->toArray();

        return response()->json(['file_urls' => $fileUrls]);
    }




    public function sendMail(Request $request)
    {
        Mail::to('test@YOPmail.com')->send(new MailToMail());
        return "Email sent successfully!";
    }



    public function receivedview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'franchise'])
            ->where('mail_code', $id)
            ->first();


        // Loop through each recipient and add the destination franchise details
        // $mail->recipients->transform(function ($recipient) {
        //     if ($recipient->destination_franchise_id) {
        //         // Fetch the franchise along with its 'kyc' relationship
        //         $recipient->destinationFranchiseDetails = Franchise::with('kyc')->find($recipient->destination_franchise_id);
        //     } else {
        //         $recipient->destinationFranchiseDetails = null;
        //     }
        //     return $recipient;
        // });

        // return $mail;

        return view('franchise.mailToMail.receivedview', ['mail' => $mail]);
    }


    public function sentview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'franchise'])
            ->where('mail_code', $id)
            ->first();

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

        return view('franchise.mailToMail.sentview', ['mail' => $mail]);
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
        $baseDestinationPath = public_path('franchise/mailtomailbulk/');

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
                // Directly use indices based on your header structure
                $email = $row[1] ?? ''; // Email index
                $mobile = $row[2] ?? ''; // Mobile index
                $subject = $row[3] ?? 'Message from Gotogo'; // Subject index
                $body = $row[4] ?? ''; // MessageBody index
                $document = $row[5] ?? ''; // Document index

                $filePath = public_path('franchise/mailtomailbulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document);

                // Create Mail record
                $mail = Mail::create([
                    'franchise_id' => Franchise::getFranchiseId(),
                    'mail_code' => Mail::getUniqueCode(),
                    'service_type' => 'mail_to_mail_bulk',
                    'subject'      => $subject,
                    'body'         => $body,
                ]);

                // Only insert recipient if the 'Email' field is available in the row
                if (!empty($email)) {
                    Recipient::create([
                        'mail_id' => $mail->id,
                        'recipient_email' => $email,
                        'recipient_phone' => $mobile,
                    ]);
                }

                // Create Mail Attachment
                $fileSize = filesize($filePath);
                $payment_amount = 0;
                if ($fileSize !== false) {

                    // 2 rupee for 1 mb
                    $fileSizeInMB = $fileSize / (1024 * 1024);
                    $cost = $fileSizeInMB  * 1;
                    $payment_amount = number_format($cost, 2);
                }
                if (!empty($document)) {
                    MailAttachment::create([
                        'mail_id' => $mail->id,
                        'file_path' => 'franchise/mailtomailbulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document,
                        'file_name' => $document,
                        'file_size' => $fileSize, // You may want to calculate this based on the actual file size
                        'payment_amount' => $payment_amount,
                    ]);
                }

                MailFacade::to($email)->send(new MailToMail([
                    'attachments' => [$filePath],
                    'subject' => $subject,
                    'emailbody' =>  $body,
                ]));



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

                $user_by_email = User::where("email", $email)->first();

                if ($user_by_email) {
                    $this->sendE2ENotificationToUser($mail, $user_by_email, $franchise);
                }

                // Construct download URL correctly
                $downloadUrl = env('APP_URL') . "/?/{$mail->mail_code}/{$mobile}";


                $user_by_phone = User::where("phone", $mobile)->first();


                if ($user_by_phone) {
                    $user_email = $user_by_phone->email;

                    if ($user_email) {
                        MailFacade::to($user_email)->send(new MailToMail([
                            'attachments' => [$filePath],
                            'subject' => $subject,
                            'emailbody' =>  $body,
                            'donwloadLink' =>  $downloadUrl
                        ]));
                    }

                    $this->sendE2ENotificationToUser($mail, $user_by_phone, $franchise);
                }

                // Uncomment this when ready to send the SMS
                $notification = new SMSNotification($mobile, 'ETOE', [$mail->mail_code, $franchise->name, $downloadUrl]);
                $notification->sendMessage();
            }

            return back()->with('success', 'Data extracted and saved to the database.');
        } else {
            return back()->with('error', 'No Excel file found in the folder.');
        }
    }
    public function receivedDate(Request $request, $id)
    {
        $mail = Recipient::findOrFail($id);
        $mail->view_date = Carbon::now();
        $mail->save();

        return $mail;
    }
}
