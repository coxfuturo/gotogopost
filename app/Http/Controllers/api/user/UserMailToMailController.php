<?php



namespace App\Http\Controllers\api\user;



use App\Http\Controllers\Controller;
use App\Mail\MailToMail;

use App\Models\Mail;

use App\Models\MailAttachment;
use App\Models\Franchise;

use App\Models\Recipient;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Mail as MailFacade;
use Illuminate\Support\Facades\Http;
use App\Notifications\FranchisePushNotification;
use App\Notifications\SMSNotification;

use Config;

class UserMailToMailController extends Controller

{

    public function receivedMails(Request $request)

    {

        $user = Auth::guard('apiuser')->user();

        $userPhone = $user->phone;

        $userEmail = $user->email;

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        // Retrieve received mails where service type matches and recipient email or phone matches
        $recievedMails = Mail::where(function ($query) {
            // Check service types
            $query->where('service_type', 'mail_to_mail_single')
                ->orWhere('service_type', 'mail_to_mail_bulk');
        })
            // ->whereDate('created_at', $date)
            ->whereHas('recipients', function ($recipientQuery) use ($userPhone, $userEmail) {
                // Filter recipients based on franchise email or phone
                $recipientQuery->where('recipient_email', $userEmail)
                    ->orWhere('recipient_phone', $userPhone);
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

        return response()->json(['status' => 200, 'message' => 'data fetch successfull', 'data' => $recievedMails]);
    }


    public function sentMails(Request $request)

    {

        $userId = Auth::guard('apiuser')->user()->id;
        $sentMails = Mail::where('user_id', $userId)
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


    public function getPrice(Request $request)
    {
        try {
            $payment_amount = 0;
            $file_info = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    try {
                        $fileSize = $file->getSize();
                        $fileSizeInMB = $fileSize / (1024 * 1024);
                        // 2rupee = 1mb
                        $cost = $fileSizeInMB * 2;
                        $cost = number_format($cost, 2);
                        $payment_amount += $cost;
                        $file_info[] = [
                            'file_name' => $file->getClientOriginalName(),
                            'file_size' => $fileSize,
                            'cost' => $cost
                        ];
                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => 'File size error: ' . $e->getMessage()]);
                    }
                }
            }


            if ($payment_amount < 1) {
                $payment_amount = 1;
            }
            return response()->json([
                'status' => true,
                'file_costs' => $file_info,
                'total_payment_amount' => number_format($payment_amount, 2)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    function shortURL($longUrl)
    {
        $response = Http::get('https://tinyurl.com/api-create.php', [
            'url' => $longUrl
        ]);

        return $response->body();
    }



    public function sendE2ENotificationToUser($parcel, $toUser, $user)
    {
        try {

            $token = $toUser->fcm_token;

            if (!$token) {
                \Log::error("FCM token not found for user", ['user_id' => $toUser->id]);
                return;
            }

            // Get sender (franchise) name
            $fromName = $user->name;

            $title = "E2E Mail Received";
            // Title & Body for Notification
            $body = "Hello {$toUser->name},\n\n"
                . "You have received an E2E Mail from *{$fromName}*.\n\n"
                . "📌 Subject: {$parcel->subject}\n\n"
                . "📅 Delivered On: " . Carbon::now()->format('d M, Y') . "\n\n"
                . "Thank you for using our service! 🚀";

            \Log::info("Preparing to send push notification", [
                'title' => $title,
                'body' => $body,
                'user_id' => $toUser->id,
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
                'user_id' => $toUser->id,
                'service_type' => 8,
                'message' => "E2E Mail Received",
                'barcode_no' => $parcel->mail_code,
                'body' => $body,
                'current_location' => $toUser->address,
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

        $validator = Validator::make($request->all(), [
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        try {

            $user = Auth::guard('apiuser')->user();
            $userId = $user->id;

            $mail = Mail::create([
                'user_id' => $userId,
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
                        $fileSizeInMB = $fileSize / (1024 * 1024);
                        // 2rupee = 1mb
                        $cost = $fileSizeInMB * 1;
                        $cost = number_format($cost, 2);

                        if ($fileSize !== false) {
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


            $emails = $request->input('to-email', []);
            $phones = $request->input('to-phone', []);
            $attachmentsPaths = array_map(fn($attachment) => public_path($attachment['file_path']), $attachments);

            foreach ($emails as $email) {
                MailFacade::to($email)->send(new MailToMail([
                    'attachments' => $attachmentsPaths,
                    'subject' => $request->input('subject'),
                    'emailbody' =>  $request->input('message'),
                ]));

                $toUser = User::where("email", $email)->first();

                if ($user) {
                    $this->sendE2ENotificationToUser($mail, $toUser, $user);
                }
            }

            foreach ($phones as $phone) {
                // Construct download URL correctly
                $downloadUrl = env('APP_URL') . "/?/{$mail->mail_code}/{$phone}";

                $toUser = User::where("phone", $phone)->first();

                if ($toUser) {
                    $user_email = $toUser->email;
                    // if ($user_email) {
                    //     MailFacade::to($user_email)->send(new MailToMail([
                    //         'attachments' => $attachmentsPaths,
                    //         'subject' => $request->input('subject'),
                    //         'emailbody' =>  $request->input('message'),
                    //         'donwloadLink' =>  $downloadUrl
                    //     ]));
                    // }

                    $this->sendE2ENotificationToUser($mail, $toUser, $user);
                }

                // Uncomment this when ready to send the SMS
                // Uncomment this when ready to send the SMS
                $notification = new SMSNotification($phone, 'ETOE', [$mail->mail_code, $user->name, $downloadUrl]);
                $notification->sendMessage();
            }

            return response()->json(['status' => true, 'message' => 'Mail sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    public function delete($id)
    {
        // Find the mail by ID
        $mail = Mail::find($id);

        // Check if the mail exists
        if (!$mail) {
            return response()->json(['status' => false, 'message' => 'Mail not found!'], 404);
        }

        // Delete related recipients and attachments
        Recipient::where('mail_id', $id)->delete();
        MailAttachment::where('mail_id', $id)->delete();

        // Delete the mail itself
        $mail->delete();

        // Return a success response
        return response()->json(['status' => true, 'message' => 'Mail deleted successfully!']);
    }


    public function attachmentReportE2E(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();

        $userId = Auth::guard('apiuser')->user()->id;
        $mail_to_mail_single = "mail_to_mail_single";


        $sentMails = Mail::where('user_id', $userId)
            ->where(function ($query) use ($mail_to_mail_single) {
                $query->where('service_type', $mail_to_mail_single);
            })
            ->with([
                'recipients' => function ($query) {
                    $query->select('id', 'mail_id', 'recipient_name', 'recipient_phone', 'recipient_email', 'view_date')
                        ->whereNotNull('view_date'); // Only include recipients where view_date is not null
                },
                'attachments:id,mail_id,file_name,file_path'
            ])
            ->orderBy('created_at', 'desc')
            ->get();


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

        return response()->json(['status' => 'success', 'data' => $formattedData]);
    }
}
