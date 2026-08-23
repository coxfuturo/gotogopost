<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use App\Models\SmsTemplate;
use Carbon\Carbon;

class SMSNotification
{
    use Queueable;

    protected $phone;
    protected $templateName;
    protected $variables;

    public function __construct($phone, $templateName = null, $variables = [])
    {
        $this->phone = $phone;
        $this->templateName = $templateName;
        $this->variables = $variables;
    }

  public function sendMessage()
{
    try {
        \Log::emergency('========== SMS START ==========');
        \Log::emergency('Phone: ' . $this->phone);
        \Log::emergency('Template: ' . $this->templateName);
        \Log::emergency('Variables: ' . json_encode($this->variables));
        
        if (!$this->templateName) {
            throw new \Exception('Template name is required');
        }

        $template = SmsTemplate::where('name', $this->templateName)->first();
        \Log::emergency('Template Found: ' . ($template ? 'Yes' : 'No'));
        
        if (!$template) {
            throw new \Exception('SMS Template not found: ' . $this->templateName);
        }

        $message = $template->content;
        \Log::emergency('Original Template Content: ' . $message);
        
        foreach ($this->variables as $value) {
            $message = preg_replace('/\{#var#\}/', $value, $message, 1);
        }
        
        \Log::emergency('Final Message: ' . $message);
        \Log::emergency('Template ID: ' . $template->template_id);

        $response = Http::withoutVerifying()->get('http://123.108.46.13/sms-panel/api/http/index.php', [
            'username'    => 'GOTOGO',
            'apikey'      => '17D73-2311B',
            'apirequest'  => 'Text',
            'sender'      => 'GOPOST',
            'mobile'      => $this->phone,
            'message'     => $message,
            'route'       => 'TRANS',
            'TemplateID'  => $template->template_id,
            'date'        => now()->toDateTimeString(),
            'format'      => 'JSON'
        ]);

        \Log::emergency('API Response Status: ' . $response->status());
        \Log::emergency('API Response Body: ' . $response->body());
        
        if ($response->successful()) {
            $jsonResponse = $response->json();
            \Log::emergency('SMS Sent Successfully: ' . json_encode($jsonResponse));
            return $jsonResponse;
        } else {
            \Log::emergency('SMS API Failed');
            throw new \Exception('SMS API request failed: ' . $response->body());
        }
    } catch (\Exception $e) {
        \Log::emergency('SMS Error: ' . $e->getMessage());
        \Log::emergency('Error File: ' . $e->getFile());
        \Log::emergency('Error Line: ' . $e->getLine());
        return [
            'error'   => true,
            'message' => $e->getMessage()
        ];
    }
}


    public function testOTPMessage()
    {
        $response = Http::get('http://123.108.46.13/sms-panel/api/http/index.php', [
            'username'    => 'GOTOGO',
            'apikey'      => '17D73-2311B',
            'apirequest'  => 'Text',
            'sender'      => 'GOPOST',
            'mobile'      => 9536970222,
            'message'     => 'Your OTP for mobile verification is 1234. Do not share this code. GOTOGO POST',
            'route'       => 'TRANS',
            'TemplateID'  => 1707173893932058113,
            'date'        =>  now()->toDateTimeString(),
            'format'      => 'JSON'
        ]);

        return $response->json();
    }

    public function testOrderMessage()
    {
        $response = Http::get('http://123.108.46.13/sms-panel/api/http/index.php', [
            'username'    => 'GOTOGO',
            'apikey'      => '17D73-2311B',
            'apirequest'  => 'Text',
            'sender'      => 'GOPOST',
            'mobile'      => 9536970222,
            'message'     => 'Your Packet No:1234 has been booked on 12-12-12. Track your Packet @gotogopost.com. call@18001231617Your Packet No:1234 has been booked on 12-12-2012. Track your Packet @gotogopost.com. call@18001231617',
            'route'       => 'TRANS',
            'TemplateID'  => 1707173893894767143,
            'date'        =>  now()->toDateTimeString(),
            'format'      => 'JSON'
        ]);

        return $response->json();
    }
}
