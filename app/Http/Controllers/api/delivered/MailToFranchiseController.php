<?php

namespace App\Http\Controllers\api\delivered;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mail;
use Illuminate\Support\Facades\Mail as MailFacade;
use App\Models\DeliveryBoy;
use App\Models\User;
use App\Models\Franchise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use  App\Http\Controllers\franchise\RateCalculator;
use App\Models\DeliveryBoyCommissionDetail;
use App\Models\E2HTrackOrder;
use App\Notifications\SMSNotification;
use App\Notifications\FranchisePushNotification;
use DB;

class MailToFranchiseController extends Controller
{
    public function e2hAssigned(Request $request)
    {
        $delivery_boy_id = Auth::guard('apidelboy')->user()->id;

        $recievedMails = Mail::where('delivery_boy_id', $delivery_boy_id)
            ->whereNull('delivered_date')
            ->where(function ($query) {
                $query->whereNull('otp')
                    ->orWhere('otp', '!=', 0);
            })
            ->with([
                'recipients:id,mail_id,recipient_name,recipient_phone,recipient_address,recipient_email',
                'attachments'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($mail) {
                // First recipient details (since it's always one)
                $recipient = $mail->recipients->first();

                return [
                    'id' => $mail->id,
                    'mail_code' => $mail->mail_code,
                    'service_type' => $mail->service_type,
                    'subject' => $mail->subject,
                    'otp' => $mail->otp,
                    'created_at' => $mail->created_at,
                    'delivery_status' => $mail->delivery_status,
                    'total_payment_amount' => number_format(
                        $mail->attachments->sum(fn($attachment) => (float) $attachment->payment_amount),
                        2
                    ),
                    'recipient_name' => $recipient?->recipient_name,
                    'recipient_phone' => $recipient?->recipient_phone,
                    'recipient_address' => $recipient?->recipient_address,
                    'recipient_email' => $recipient?->recipient_email,
                    'attachments' => $mail->attachments
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $recievedMails,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function e2hDelivered2(Request $request)
    {

        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $delivery_boy_id = Auth::guard('apidelboy')->user()->id;
        $recievedMails = Mail::where('delivery_boy_id', $delivery_boy_id)
            ->with(['recipients', 'attachments', 'franchise', 'deliveryBoy', 'cancelDelivery'])
            ->whereDate('delivered_date', $date)
            ->orWhere(function ($subQuery) use ($date) {
                $subQuery->where('otp', '0')
                    ->whereHas('cancelDelivery', function ($query) use ($date) {
                        $query->whereDate('created_at', $date);
                    });
            })
            ->with('cancelDelivery')
            ->orderBy('created_at', 'desc')
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

                $mail->recipients = $mail->recipients->map(function ($recipient) {
                    if ($recipient->destination_franchise_id) {
                        $recipient->destinationFranchiseDetails = Franchise::find($recipient->destination_franchise_id);
                    } else {
                        $recipient->destinationFranchiseDetails = null;
                    }
                    return $recipient;
                });

                return $mail;
            });

        return response()->json([
            'success' => true,
            'data' =>  $recievedMails,
            'message' => 'Data retrieved successfully',
        ], 200);
    }


    public function e2hDelivered(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date'))->startOfDay() : Carbon::today();
        $deliveryBoyId = Auth::guard('apidelboy')->id();

        // ✅ Delivered mails
        $deliveredMails = Mail::where('delivery_boy_id', $deliveryBoyId)
            ->whereDate('delivered_date', $date)
            ->with(['recipients', 'attachments', 'cancelDelivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Cancelled mails (otp = 0 and cancelDelivery created today)
        $cancelledMails = Mail::where('delivery_boy_id', $deliveryBoyId)
            ->where('otp', '0')
            ->whereHas('cancelDelivery', function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            })
            ->with(['recipients', 'attachments', 'cancelDelivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Merge both collections
        $recievedMails = $deliveredMails->merge($cancelledMails)->sortByDesc('created_at')->values();

        // ✅ Process each mail
        $recievedMails = $recievedMails->map(function ($mail) {
            // $mail->franchise = $mail->franchise_id != 0 ? $mail->franchise : null;
            // $mail->user = $mail->user_id != 0 ? $mail->user : null;

            // Calculate total payment from attachments
            $cost = $mail->attachments->sum(function ($attachment) {
                return (float) $attachment->payment_amount;
            });
            $mail->total_payment_amount = number_format($cost, 2);

            // // Add destination franchise details for each recipient
            // $mail->recipients = $mail->recipients->map(function ($recipient) {
            //     $recipient->destinationFranchiseDetails = $recipient->destination_franchise_id
            //         ? Franchise::find($recipient->destination_franchise_id)
            //         : null;
            //     return $recipient;
            // });

            return $mail;
        });

        return response()->json([
            'success' => true,
            'data' =>  $recievedMails,
            'message' => 'Data retrieved successfully',
        ], 200);
    }

    public function sendE2HOtp(Request $request)
    {
        try {

            $parcelToUpdate = Mail::with(['recipients', 'attachments'])->findOrFail($request->id);
            $recipient_email = $parcelToUpdate->recipients[0]['recipient_email'];
            $recipient_phone = $parcelToUpdate->recipients[0]['recipient_phone'];
            if ($parcelToUpdate) {
                $parcelToUpdate->otp = mt_rand(1111, 9999);
                $parcelToUpdate->save();

                \Illuminate\Support\Facades\Mail::to($recipient_email)->send(new \App\Mail\SendOtpMail([
                    'otp' => $parcelToUpdate->otp,
                ]));

                $notification = new SMSNotification($recipient_phone, 'OTP', [$parcelToUpdate->otp]);
                $response = $notification->sendMessage();

                return response()->json([
                    'success' => true,
                    'data' => $parcelToUpdate->otp,
                    'message' => 'OTP sent successfully!! otp',
                ], 200);
            } else {
                // return back()->with('error', 'Parcel not found.')->withInput();
                return response()->json([
                    'success' => false,
                    'message' => 'Parcel not found',
                ], 401)->withInput();
            }
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 400);
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


    public function verifyE2HOtp(Request $request, RateCalculator $rateCalculator)
    {

        if ($request->isMethod('POST')) {
            try {

                $parcelToUpdate = Mail::with(['recipients', 'attachments'])->findOrFail($request->id);


                if (count($parcelToUpdate->attachments) == 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No attachment found',
                    ], 400);
                }

                $pages = $parcelToUpdate->attachments[0]['pages'];
                $payment_amount = $parcelToUpdate->attachments[0]['payment_amount'];
                if ($parcelToUpdate) {
                    $submittedOtp = $request->input('otp');

                    if ($parcelToUpdate->otp == $submittedOtp) {
                        if ($parcelToUpdate->delivered_date == null) {
                            $commission = $rateCalculator->calculateCommissionForE2h($pages, 'delivery', Mail::E2H);

                            DeliveryBoyCommissionDetail::create([
                                "delivery_boy_id" => Auth::guard('apidelboy')->user()->id,
                                "service_type" => Mail::E2H,
                                "amount" => $payment_amount,
                                "commission" => $commission,
                            ]);
                        }

                        $parcelToUpdate->delivered_date = Carbon::today()->toDateString();
                        $parcelToUpdate->save();

                        E2HTrackOrder::where('mail_id', $request->id)
                            ->update([
                                'delivery_datetime' => now(),
                            ]);


                        if ($parcelToUpdate->user) {
                            $this->sendDeliveryNotificationToUser($parcelToUpdate);
                        }
                        return response()->json([
                            'success' => true,
                            'data' => $parcelToUpdate->otp,
                            'message' => 'Verified successfully!',
                        ], 200);
                    } else {

                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid OTP. Please try again.',
                        ], 400);
                        return back()->with('error', 'Invalid OTP. Please try again.')->withInput();
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Parcel not found.',
                    ], 400);
                }
            } catch (\Exception $th) {
                return response()->json([
                    'success' => false,
                    'error' => $th->getMessage(),
                ], 400);
            }
        }
    }

    public function cancelE2HDelivery(Request $request, RateCalculator $rateCalculator)
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
                    "delivery_boy_id" => Auth::guard('apidelboy')->user()->id,
                    "service_type" => Mail::E2H,
                    "amount" => $payment_amount,
                    "commission" => $commission,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'cancelled successfully',
                ], 200);
            } catch (\Exception $th) {
                return response()->json([
                    'success' => false,
                    'error' => $th->getMessage(),
                ], 400);
            }
        }
    }
}
