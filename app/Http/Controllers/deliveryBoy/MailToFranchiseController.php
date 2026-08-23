<?php



namespace App\Http\Controllers\deliveryBoy;



use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use Smalot\PdfParser\Parser;
use App\Models\SoftCopyParcel;

use App\Models\PickupDetails;
use App\Mail\E2HDeliveredMail;
use App\Models\Mail;
use Illuminate\Support\Facades\Mail as MailFacade;
use App\Models\Franchise;
use App\Models\MailAttachment;
use App\Models\DeliveryBoy;
use App\Models\Recipient;
use App\Models\E2HTrackOrder;
use App\Models\User;
use App\Notifications\FranchisePushNotification;
use Illuminate\Http\Request;

use Illuminate\Contracts\Support\Renderable;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;

use  App\Http\Controllers\franchise\RateCalculator;
use Illuminate\Support\Facades\Config;

use DB;

use App\Models\DeliveryBoyCommissionDetail;
use Carbon\Carbon;
use App\Notifications\SMSNotification;


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

        // return $sentMails;

        return view('franchise.mailToFranchise.sentMails', ['sentMails' => $sentMails]);
    }


    public function receivedview($id)
    {
        $mail = Mail::with(['recipients', 'attachments', 'franchise'])->findOrFail($id);

        return view('franchise.mailToFranchise.receivedview', ['mail' => $mail]);
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

        return view('franchise.mailToFranchise.sentview', ['mail' => $mail]);
    }



    public function sendOtp(Request $request)
    {

        try {

            $parcelToUpdate = Mail::with(['recipients', 'attachments'])->findOrFail($request->id);
            $recipient_email = $parcelToUpdate->recipients[0]['recipient_email'];

            if ($parcelToUpdate) {
                $parcelToUpdate->otp = mt_rand(1111, 9999);
                $parcelToUpdate->save();

                MailFacade::to($recipient_email)->send(new SendOtpMail([
                    'otp' =>  $parcelToUpdate->otp,
                ]));

                return redirect()->back()->with('success', 'OTP sent successfully!! ' . $parcelToUpdate->otp);
            } else {
                return back()->with('error', 'Parcel not found.')->withInput();
            }
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function sendDeliveryNotificationToUser($parcel)
    {
        try {
            \Log::info("sendNotificationToUser function called", [
                'parcel_id' => $parcel->id ?? null,
            ]);

            // Fetch user details
            $userPhone = $parcel->user->phone ?? null;
            if (!$userPhone) {
                \Log::error("Recipient phone not found for parcel", ['parcel_id' => $parcel->id]);
                return;
            }

            $user = User::where('phone', $userPhone)->first();
            if (!$user) {
                \Log::error("User not found for phone: {$userPhone}");
                return;
            }

            \Log::info("User found", ['user_id' => $user->id]);

            $token = $user->fcm_token;

            if (!$token) {
                \Log::error("FCM token not found for user", ['user_id' => $user->id]);
                return;
            }

            // **Title & Body for Notification**
            $title = "📦 Your Parcel is Delivered!";
            $body = "Hello {$user->name},\n\n"
                . "Your parcel with Mail Code *{$parcel->mail_code}* has been successfully delivered to {$parcel->recipients[0]->recipient_name}.\n\n"
                . "📍 Delivery Address: {$parcel->recipients[0]->recipient_address}\n"
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
                'service_type' => 9,
                'message' => "Parcel delivered successfully",
                'barcode_no' => $parcel->mail_code,
                'body' => $body,
                'current_location' => $parcel->recipients[0]->recipient_address,
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

    public function verifyOtp(Request $request, RateCalculator $rateCalculator)
    {

        if ($request->isMethod('POST')) {
            try {

                $parcelToUpdate = Mail::with(['recipients', 'attachments'])->findOrFail($request->id);

                $recipient_phone = $parcelToUpdate->recipients[0]['recipient_phone'];
                $recipient_email = $parcelToUpdate->recipients[0]['recipient_email'];
                $recipient_name = $parcelToUpdate->recipients[0]['recipient_name'];
                $recipient_phone = $parcelToUpdate->recipients[0]['recipient_phone'];
                $recipient_address = $parcelToUpdate->recipients[0]['recipient_address'];
                if (count($parcelToUpdate->attachments) == 0) {
                    return back()->with('error', 'no attachemt found.')->withInput();
                }


                $pages = $parcelToUpdate->attachments[0]['pages'];
                $payment_amount = $parcelToUpdate->attachments[0]['payment_amount'];
                if ($parcelToUpdate) {
                    $submittedOtp = implode('', $request->input('otp', []));

                    if ($parcelToUpdate->otp === $submittedOtp) {
                        if ($parcelToUpdate->delivered_date == null) {
                            $commission = $rateCalculator->calculateCommissionForE2h($pages, 'delivery', Mail::E2H);

                            DeliveryBoyCommissionDetail::create([
                                "delivery_boy_id" => Auth::guard('delboy')->id(),
                                "service_type" => Mail::E2H,
                                "amount" => $payment_amount,
                                "commission" => $commission,
                            ]);
                        }

                        E2HTrackOrder::where('mail_id', $request->id)
                            ->update([
                                'delivery_datetime' => now(),
                            ]);

                        $parcelToUpdate->delivered_date = Carbon::today()->toDateString();
                        $parcelToUpdate->save();

                        $attachmentsPaths = $parcelToUpdate->attachments->map(function ($attachment) {
                            return [
                                'file_name' => $attachment->file_name,
                                'file_path' => $attachment->file_path,
                            ];
                        })->toArray();


                        MailFacade::to($recipient_email)->send(new E2HDeliveredMail([
                            'attachments' => $attachmentsPaths,
                            'subject' => $parcelToUpdate->subject,
                            'emailbody' => $parcelToUpdate->body,
                            'recipient_name' => $recipient_name,
                            'recipient_address' => $recipient_address,
                            'recipient_phone' => $recipient_phone,
                        ]));

                        $notification = new SMSNotification($recipient_phone, 'OTP', [$parcelToUpdate->otp]);
                        $response = $notification->sendMessage();

                        if ($parcelToUpdate->user) {
                            $this->sendDeliveryNotificationToUser($parcelToUpdate);
                        }

                        return redirect()->back()->with('success', 'Verified successfully!');
                    } else {
                        return back()->with('error', 'Invalid OTP. Please try again.')->withInput();
                    }
                } else {
                    return back()->with('error', 'Parcel not found.')->withInput();
                }
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }

        return view('deliveryBoy.mailToFranchise.verifyOtp');
    }


    public function cancelDelivery(Request $request, RateCalculator $rateCalculator)
    {

        $record = Mail::with(['recipients', 'attachments'])->findOrFail($request->id);
        $pages = $record->attachments[0]['pages'];
        $payment_amount = $record->attachments[0]['payment_amount'];
        $record->otp = "0";
        $record->save();

        if ($request->isMethod('POST')) {
            try {

                $record->cancelDelivery()->create([
                    'cancel_reason' => $request->reason,
                ]);

                $commission = 0;
                $commission = $rateCalculator->calculateCommissionForE2h($pages, 'delivery', Mail::E2H);

                DeliveryBoyCommissionDetail::create([
                    "delivery_boy_id" => Auth::guard('delboy')->id(),
                    "service_type" => Mail::E2H,
                    "amount" => $payment_amount,
                    "commission" => $commission,
                ]);

                return redirect()->back()->with('success', 'Parcel Canceled successfully!');
            } catch (\Exception $th) {
                return back()->with('error', $th->getMessage())->withInput();
            }
        }
    }


    public function viewAssignedMailParcel(Request $request)
    {

        $recievedMails = Mail::where('delivery_boy_id', Auth::guard('delboy')->id())
            ->whereNull('delivered_date') // ✅ delivered_date must be NULL
            ->where(function ($query) {
                $query->whereNull('otp')  // ✅ OTP is NULL
                    ->orWhere('otp', '!=', 0); // ✅ OTP is NOT zero (non-zero values)
            })
            ->with(['recipients', 'attachments', 'franchise', 'deliveryBoy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {
                // Franchise details if present
                $mail->franchise = $mail->franchise_id != 0 ? $mail->franchise : null;

                // User details if present
                $mail->user = $mail->user_id != 0 ? $mail->user : null;

                // Calculate total payment amount from attachments
                $cost = $mail->attachments->sum(fn($attachment) => (float) $attachment->payment_amount);
                $mail->total_payment_amount = number_format($cost, 2);

                // Add destination franchise details for each recipient
                $mail->recipients = $mail->recipients->map(function ($recipient) {
                    $recipient->destinationFranchiseDetails = $recipient->destination_franchise_id
                        ? Franchise::find($recipient->destination_franchise_id)
                        : null;
                    return $recipient;
                });

                return $mail;
            });


        // Return the view with the retrieved mails
        return view('deliveryBoy.mailToFranchise.viewAssignedMailParcel', [
            'recievedMails' => $recievedMails,
        ]);
    }


    public function viewDeliveredMailParcel(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $deliveryBoyId = Auth::guard('delboy')->id();

        // ✅ Delivered mails
        $deliveredMails = Mail::where('delivery_boy_id', $deliveryBoyId)
            ->whereDate('delivered_date', $date)
            ->with(['recipients', 'attachments', 'franchise', 'deliveryBoy', 'cancelDelivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Cancelled mails (otp = 0 and cancelDelivery created today)
        $cancelledMails = Mail::where('delivery_boy_id', $deliveryBoyId)
            ->where('otp', '0')
            ->whereHas('cancelDelivery', function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            })
            ->with(['recipients', 'attachments', 'franchise', 'deliveryBoy', 'cancelDelivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Merge both collections
        $recievedMails = $deliveredMails->merge($cancelledMails)->sortByDesc('created_at')->values();

        // ✅ Process each mail
        $recievedMails = $recievedMails->map(function ($mail) {
            $mail->franchise = $mail->franchise_id != 0 ? $mail->franchise : null;
            $mail->user = $mail->user_id != 0 ? $mail->user : null;

            // Calculate total payment from attachments
            $cost = $mail->attachments->sum(function ($attachment) {
                return (float) $attachment->payment_amount;
            });
            $mail->total_payment_amount = number_format($cost, 2);

            // Add destination franchise details for each recipient
            $mail->recipients = $mail->recipients->map(function ($recipient) {
                $recipient->destinationFranchiseDetails = $recipient->destination_franchise_id
                    ? Franchise::find($recipient->destination_franchise_id)
                    : null;
                return $recipient;
            });

            return $mail;
        });

        return view('deliveryBoy.mailToFranchise.viewDeliveredMailParcel', [
            'recievedMails' => $recievedMails,
        ]);
    }
}
