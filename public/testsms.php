<?php

$url = "http://123.108.46.13/sms-panel/api/http/index.php";
$params = [
    "username" => "GOTOGO",
    "apikey" => "17D73-2311B",
    "apirequest" => "Text",
    "sender" => "GOPOST",
    "mobile" => "8287537054",
    "message" => "Your order with Article Number: 132332322 has been placed. The delivery is expected to reach EXECUTIVE by 12/02/2025. GOTOGO POST",
    "route" => "TRANS",
    "TemplateID" => "1707173893894767143",
    "format" => "JSON"
];

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Execute request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "Response: " . $response;
}

// Close cURL
curl_close($ch);
?>
