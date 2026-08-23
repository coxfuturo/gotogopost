<?php
namespace App;

use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Http;

class SmsServices
{


public function sendSms($templateName, $variables, $mobileNumber) {
    // Get the template by name
    $template = SmsTemplate::where('name', $templateName)->first();

   

    if ($template) {
        // Replace placeholders with variables
        $message = $template->content;
        foreach ($variables as $value) {
            $message = preg_replace('/\{#var#\}/', $value, $message, 1);
        }

       // dd($message);

        // Send SMS using the HTTP client
        $response = Http::get('http://123.108.46.13/sms-panel/api/http/index.php', [
            'username'    => 'GOTOGO',
            'apikey'      => '17D73-2311B',
            'apirequest'  => 'Text',
            'sender'      => 'GOPOST',
            'mobile'      => $mobileNumber,
            'message'     => $message,
            'route'       => 'TRANS',
            'TemplateID'  => $template->template_id,
            'format'      => 'JSON'
        ]);

        return $response->json();
    } else {
        return response()->json(['error' => 'Template not found'], 404);
    }
}

}
