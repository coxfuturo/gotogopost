<?php



namespace App\Http\Controllers\api\franchise;



use App\Http\Controllers\Controller;
use App\Mail\MailToMail;

use App\Models\PickupDetails;

use App\Models\Mail;

use App\Models\MailAttachment;

use App\Models\Recipient;

use Illuminate\Http\Request;



use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Config;

use DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class FranchiseMailToMailController extends Controller

{


    public function receivedMails(Request $request)
    {
        
        $franchiseEmail = Auth::guard('apifranchise')->user()->email;

        $franchisePhone = Auth::guard('apifranchise')->user()->phone;

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) {
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

        return response()->json(['status' => 200, 'message' => 'data fetch successful', 'data' => $recievedMails]);
    }
    
    
    public function outBoxReceivedMails(Request $request)
    {
     
        $franchiseEmail = Auth::guard('apifranchise')->user()->email;

        $franchisePhone = Auth::guard('apifranchise')->user()->phone;

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) {
            // Check service types
            $query->where('service_type', 'mail_to_mail_bulk')
                ->orWhere('service_type', 'mail_to_mail_single')
                ->orWhere('franchise_id', auth()->user()->id);
        })
            ->whereDate('created_at', $date)
            ->whereHas('recipients', function ($recipientQuery)  {
                // Filter recipients based on franchise email or phone
                // $recipientQuery->where('recipient_email', $franchiseEmail)
                //     ->orWhere('recipient_phone', $franchisePhone);
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

        return response()->json(['status' => 200, 'message' => 'data fetch successful', 'data' => $recievedMails]);
    }



    public function sentSingle()

    {

        $franchiseId = Auth::guard('apifranchise')->user()->id;
        $sentMails = Mail::where('franchise_id', $franchiseId)
            ->where('service_type', 'mail_to_mail_single')
            ->with(['recipients', 'attachments', 'user', 'franchise'])
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


        return response()->json(['status' => 200, 'message' => 'data fetch successfull', 'data' => $sentMails]);
    }


    public function sentBulk()

    {

        $franchiseId = Auth::guard('apifranchise')->user()->id;
        $sentMails = Mail::where('franchise_id', $franchiseId)
            ->where('service_type', 'mail_to_mail_bulk')
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
                        // 2rupee = 1mb
                        $cost = $fileSizeInMB * 2;
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

    public function postSingle(Request $request)
    {

        // Mail::query()->truncate();
        // DB::statement('ALTER TABLE mail_attachments DROP FOREIGN KEY mail_attachments_recipient_id_foreign;');
        // DB::statement('TRUNCATE TABLE recipients;');
        // MailAttachment::query()->truncate();

        // return 'jjjjjjjj';
        // return $request;
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        try {

            $franchiseId = Auth::guard('apifranchise')->user()->id;
            $franchiseDetails = Auth::guard('apifranchise')->user();
            // Store the mail details
            $mail = Mail::create([
                'franchise_id' => $franchiseId,
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
                        $destinationPath = public_path('franchise/mailtomail/attachments/');
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
                                'file_path' => 'franchise/mailtomail/attachments/' . $newFileName,
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
            $franchiseDetails->decrement('remaining_balance', $payment_amount);
            return response()->json(['status' => true, 'message' => 'Mail sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}