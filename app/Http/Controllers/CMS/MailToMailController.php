<?php



namespace App\Http\Controllers\CMS;



use App\Http\Controllers\Controller;
use App\Mail\MailToMail;



use App\Models\Mail;
use Illuminate\Support\Facades\Mail as MailFacade;
use App\Models\Franchise;

use App\Models\MailAttachment;



use App\Models\Recipient;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;

use GuzzleHttp\Client;


use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Config;
use ZipArchive;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Log;
use finfo;
use Carbon\Carbon;
use App\Models\CMS;
use App\Notifications\SMSNotification;

class MailToMailController extends Controller

{

    public function receivedMails(Request $request)
    {

        $cmsDetails = CMS::find(Auth::guard('cms')->user()->id);
        $cmsPhone = $cmsDetails->mobile;
        $cmsEmail = $cmsDetails->email;

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) {
            // Check service types
            $query->where('service_type', 'mail_to_mail_bulk')
                ->orWhere('service_type', 'mail_to_mail_single');
        })
            ->whereDate('created_at', $date)
            ->whereHas('recipients', function ($recipientQuery) use ($cmsEmail, $cmsPhone) {
                // Filter recipients based on franchise email or phone
                $recipientQuery->where('recipient_email', $cmsEmail)
                    ->orWhere('recipient_phone', $cmsPhone);
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

        // return $recievedMails;
        return view('cms.mailToMail.receivedMail', ['recievedMails' => $recievedMails]);
    }


    public function sentMails(Request $request)

    {

        $cms_id = Auth::guard('cms')->user()->id;
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $service_type = $request->service_type;

        $sentMails = Mail::where('cms_id', $cms_id)
            ->where('service_type', $service_type)
            ->with(['recipients', 'attachments'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {

                $cost = $mail->attachments->sum(function ($attachment) {
                    return (float) $attachment->payment_amount;
                });
                $mail->total_payment_amount = number_format($cost, 2);
                return $mail;
            });

        // return $sentMails;

        return view('cms.mailToMail.sentMails', ['sentMails' => $sentMails]);
    }


    public function create()

    {
        // return view('cms.mail.mailtomailview');
        return view('cms.mailToMail.create');
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

        $cms_id = Auth::guard('cms')->user()->id;
        $request->validate([
            'subject' => 'required|string|max:255',
        ]);

        try {
            // Store the mail details
            $mail = Mail::create([
                'cms_id' => $cms_id,
                'mail_code' => Mail::getUniqueCode(),
                'service_type' => 'mail_to_mail_single',
                'subject' => $request->input('subject'),
                'body' => $request->input('message'),
            ]);

            // Store recipients
            $recipients = [];

            // Add recipients with phone numbers
            foreach ($request->input('to-phone', []) as $phone) {
                $recipients[] = [
                    'mail_id' => $mail->id,
                    'recipient_phone' => $phone,
                ];
            }

            // Add recipients with email addresses
            foreach ($request->input('to-email', []) as $email) {
                $recipients[] = [
                    'mail_id' => $mail->id,
                    'recipient_email' => $email,
                ];
            }

            // Now create each recipient and collect the created instances
            $createdRecipients = [];
            foreach ($recipients as $recipientData) {
                $createdRecipients[] = Recipient::create($recipientData);
            }

            $attachments = [];
            $payment_amount = 0;
            // // Store attachments
            if ($request->hasFile('files')) {

                foreach ($request->file('files') as $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $newFileName = time() . '_' . $originalName;
                        $destinationPath = public_path('cms/mailtomail/attachments/');
                        $file->move($destinationPath, $newFileName);

                        $filePath = $destinationPath . $newFileName;
                        $fileSize = filesize($filePath);

                        if ($fileSize !== false) {

                            // 2 rupee for 1 mb
                            $fileSizeInMB = $fileSize / (1024 * 1024);
                            $cost = $fileSizeInMB  * 2;
                            $cost = number_format($cost, 2);

                            $payment_amount += $cost;
                            $attachments[] = [
                                'mail_id' => $mail->id,
                                'file_path' => 'cms/mailtomail/attachments/' . $newFileName,
                                'file_name' => $originalName,
                                'file_size' => $fileSize,
                                'payment_amount' => $cost,
                            ];
                        } else {
                            // Handle error if file size could not be retrieved
                            return response()->json(['status' => false, 'message' => 'Could not retrieve file size for ' . $originalName]);
                        }
                    } catch (\Exception $e) {
                        // Handle exception if something goes wrong during the file upload process
                        return response()->json(['status' => false, 'message' => 'File upload error: ' . $e->getMessage()]);
                    }
                }
                MailAttachment::insert($attachments);
            }


            $attachmentsPaths = array_map(function ($attachment) {
                return public_path($attachment['file_path']);
            }, $attachments);


            $emails = $request->input('to-email', []);
            $phones = $request->input('to-phone', []);
            $attachmentsPaths = array_map(function ($attachment) {
                return public_path($attachment['file_path']); // Convert to absolute path
            }, $attachments);


            foreach ($emails as $email) {
                MailFacade::to($email)->send(new MailToMail([
                    'attachments' => $attachmentsPaths,
                    'subject' => $request->input('subject'),
                    'emailbody' =>  $request->input('message'),
                ]));
            }

            $downloadUrl = env('APP_URL') . '/franchise/downloadAttachmentView/' . $mail->mail_code;
            $shortUrl = $this->shortURL($downloadUrl);

            foreach ($phones as $phone) {
                $shortUrl = $this->shortURL($downloadUrl);
                // $notification = new SMSNotification($phone, 'OTP', [$shortUrl]);
                // $notification->sendMessage();
            }

            $cmsDetails = cms::findOrFail($cms_id);
            $cmsDetails->decrement('remaining_balance', $payment_amount);
            return response()->json(['status' => true, 'message' => 'Mail sent successfully!', 'service_type' => 'mail_to_mail_single']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function receivedview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'cms'])->findOrFail($id);


        return view('cms.mailToMail.receivedview', ['mail' => $mail]);
    }


    public function sentview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'cms'])->findOrFail($id);

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

        return view('cms.mailToMail.sentview', ['mail' => $mail]);
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
        $baseDestinationPath = public_path('cms/mailtomailbulk/');

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
                // dd('kkkkkkkkkkkk');
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

            // dd($result);

            $cms_id = Auth::guard('cms')->user()->id;
            // Loop through rows and store them in the database
            foreach ($result as $row) {
                // Directly use indices based on your header structure
                $email = $row[1] ?? ''; // Email index
                $mobile = $row[2] ?? ''; // Mobile index
                $subject = $row[3] ?? 'Message from Gotogo'; // Subject index
                $body = $row[4] ?? ''; // MessageBody index
                $document = $row[5] ?? ''; // Document index

                $filePath = public_path('cms/mailtomailbulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document);

                // Create Mail record
                $mail = Mail::create([
                    'cms_id' => $cms_id,
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
                    $cost = $fileSizeInMB  * 2;
                    $payment_amount = number_format($cost, 2);
                }
                if (!empty($document)) {
                    MailAttachment::create([
                        'mail_id' => $mail->id,
                        'file_path' => 'cms/mailtomailbulk/' . $extractedFolderName . '/' . $basefolder . '/' . $document,
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

                $cmsDetails = CMS::findOrFail($cms_id);
                $cmsDetails->decrement('remaining_balance', $payment_amount);

                $downloadUrl = env('APP_URL') . '/franchise/downloadAttachmentView/' . $mail->mail_code;
                $shortUrl = $this->shortURL($downloadUrl);
                
                // $notification = new SMSNotification($mobile, 'OTP', [$shortUrl]);
                // $notification->sendMessage();
            }

            return back()->with('success', 'Data extracted and saved to the database.');
        } else {
            return back()->with('error', 'No Excel file found in the folder.');
        }
    }
}
