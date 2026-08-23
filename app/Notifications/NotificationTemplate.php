<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Franchise;
use App\Models\FranchiseBag;
use App\Notifications\FranchisePushNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationTemplate
{
    public static function sendNotificationToUser($parcel, $service_type)
    {
        try {
            \Log::info("sendNotificationToUser function called", [
                'parcel_id' => $parcel->id ?? null,
            ]);

            $userPhone = $parcel->pickup_mobile;
            $user = User::where('phone', $userPhone)->first();

            if (!$user) {
                \Log::error("User not found for phone: {$userPhone}");
                return;
            }

            $token = $user->fcm_token;
            if ($token) {
                $title = FranchiseBag::getServiceType($service_type);
                $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                    "📅 Date: " . now()->format('M d, Y, h:i A') . "\n" .
                    "📍 Location: {$parcel->consignee_address}\n" .
                    "🚀 Order has Delivered successfully to {$parcel->consignee_name} in {$parcel->consignee_address}";

                $notification = new FranchisePushNotification($token, $parcel, $title, $body);
                $status = $notification->sendPushNotification();

                if ($status == 0) {
                    return;
                }

                $parcel->userNotifications()->create([
                    'user_id' => $user->id,
                    'service_type' => get_class($parcel),
                    'parcel_id' => $parcel->id,
                    'message' => "Order has Delivered",
                    'barcode_no' => $parcel->barcode_no,
                    'body' => $body,
                    'current_location' => $parcel->consignee_address,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToUser", ['exception' => $e]);
        }
    }

    public static function sendNotificationToFranchise($parcel, $service_type)
    {
        try {
            $franchise = Franchise::find($parcel->franchise_id);

            if (!$franchise) {
                \Log::warning("Franchise not found", ['franchise_id' => $parcel->franchise_id]);
                return;
            }

            $token = $franchise->fcm_token;
            if (!$token) {
                return;
            }

            $title = FranchiseBag::getServiceType($service_type);
            $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                "📅 Date: " . now()->format('M d, Y, h:i A') . "\n" .
                "📍 Location: {$parcel->consignee_address}\n" .
                "🚀 Order has Delivered successfully to {$parcel->consignee_name} in {$parcel->consignee_address}";

            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $status = $notification->sendPushNotification();

            if ($status == 0) {
                return;
            }

            $parcel->franchiseNotifications()->create([
                'franchise_id' => $franchise->id,
                'service_type' => get_class($parcel),
                'parcel_id' => $parcel->id,
                'message' => "Order has Delivered",
                'barcode_no' => $parcel->barcode_no,
                'body' => $body,
                'current_location' => $parcel->consignee_address,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToFranchise", ['exception' => $e]);
        }
    }

    public static function sendNotificationToDeliveryBoy($parcel, $service_type)
    {
        try {
            $delivery_details = Auth::guard('delboy')->user();
            if (!$delivery_details) {
                return;
            }

            $token = $delivery_details->fcm_token;
            if (!$token) {
                return;
            }

            $title = FranchiseBag::getServiceType($service_type);
            $body = "✅ Order Update: Article No. {$parcel->barcode_no}\n" .
                "📅 Date: " . now()->format('M d, Y, h:i A') . "\n" .
                "📍 Location: {$parcel->consignee_address}\n" .
                "🚀 Order has Delivered successfully to {$parcel->consignee_name} in {$parcel->consignee_address}";

            $notification = new FranchisePushNotification($token, $parcel, $title, $body);
            $status = $notification->sendPushNotification();

            if ($status == 0) {
                return;
            }

            $parcel->deliveryBoyNotifications()->create([
                'delivery_boy_id' => $delivery_details->id,
                'service_type' => get_class($parcel),
                'parcel_id' => $parcel->id,
                'message' => "Order has Delivered",
                'barcode_no' => $parcel->barcode_no,
                'body' => $body,
                'current_location' => $parcel->consignee_address,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in sendNotificationToDeliveryBoy", ['exception' => $e]);
        }
    }
}
