<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use GuzzleHttp\Client;
use Google\Auth\ApplicationDefaultCredentials;

class PickupPushNotification extends Notification
{
    use Queueable;

    protected $token;
    protected $title;
    protected $body;

    public function __construct($token, $title, $body)
    {
        $this->token = $token;
        $this->title = $title;
        $this->body = $body;
    }

    public function sendPushNotification()
    {
        try {
            // 🔹 Firebase JSON File Path
            $firebaseJsonPath = env('FIREBASE_CREDENTIALS', storage_path('firebase/firebase_credentials.json'));

            // 🔹 Retrieve OAuth Token
            $accessToken = $this->getAccessToken($firebaseJsonPath);
            if (!$accessToken) {
                \Log::error("FCM Error: Failed to retrieve OAuth token");
                return 0;
            }

            // 🔹 Firebase Project ID
            $projectId = env('PROJECT_ID');
            if (!$projectId) {
                \Log::error("FCM Error: PROJECT_ID is missing in .env");
                return 0;
            }

            $firebaseUrl = "https://fcm.googleapis.com/v1/projects/$projectId/messages:send";

            // 🔹 Notification Payload
            $payload = [
                "message" => [
                    "token" => $this->token,
                    "notification" => [
                        "title" => $this->title,
                        "body"  => $this->body,
                    ],
                ]
            ];

            // 🔹 Send Notification using Guzzle
            $client = new Client();
            $response = $client->post($firebaseUrl, [
                'headers' => [
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload
            ]);

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);

            if ($statusCode == 200 || $statusCode == 201) {
                \Log::info('Notification sent successfully', ['response' => $responseBody]);
                return 1; // ✅ Success
            }

            \Log::error('FCM Error: Failed to send notification', ['status' => $statusCode, 'response' => $responseBody]);
            return 0;
        } catch (\Exception $e) {
            \Log::error('FCM Notification Error: ' . $e->getMessage());
            return 0;
        }
    }


    private function getAccessToken($firebaseJsonPath)
    {
        try {
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $firebaseJsonPath);

            $client = new Client();
            $credentials = json_decode(file_get_contents($firebaseJsonPath), true);

            $scope = 'https://www.googleapis.com/auth/firebase.messaging';
            $jwt = $this->generateJWT($credentials, $scope);

            $response = $client->post('https://oauth2.googleapis.com/token', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['access_token'] ?? null;
        } catch (\Exception $e) {
            \Log::error('FCM OAuth Error: ' . $e->getMessage());
            return null;
        }
    }

    private function generateJWT($credentials, $scope)
    {
        try {
            $now = time();
            $jwtHeader = ['alg' => 'RS256', 'typ' => 'JWT'];
            $jwtPayload = [
                'iss' => $credentials['client_email'],
                'scope' => $scope,
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            $base64UrlEncode = function ($data) {
                return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
            };

            $headerEncoded = $base64UrlEncode(json_encode($jwtHeader));
            $payloadEncoded = $base64UrlEncode(json_encode($jwtPayload));

            $signature = '';
            openssl_sign($headerEncoded . "." . $payloadEncoded, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256);
            $signatureEncoded = $base64UrlEncode($signature);

            return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
        } catch (\Exception $e) {
            \Log::error('FCM JWT Error: ' . $e->getMessage());
            return null;
        }
    }
}
