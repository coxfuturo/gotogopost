<?php
echo "<h2>PHP cURL Check</h2>";

// 1. cURL Extension Check
echo "<h3>1. cURL Extension:</h3>";
if (function_exists('curl_version')) {
    $curl_version = curl_version();
    echo "✅ cURL is ENABLED<br>";
    echo "Version: " . $curl_version['version'] . "<br>";
    echo "SSL Version: " . $curl_version['ssl_version'] . "<br>";
    echo "Protocols: " . implode(', ', $curl_version['protocols']) . "<br>";
} else {
    echo "❌ cURL is DISABLED<br>";
}

// 2. OpenSSL Check
echo "<h3>2. OpenSSL Extension:</h3>";
if (extension_loaded('openssl')) {
    echo "✅ OpenSSL is ENABLED<br>";
    echo "Version: " . OPENSSL_VERSION_TEXT . "<br>";
} else {
    echo "❌ OpenSSL is DISABLED<br>";
}

// 3. HTTP Wrapper Check
echo "<h3>3. HTTP Wrapper:</h3>";
if (ini_get('allow_url_fopen')) {
    echo "✅ allow_url_fopen is ENABLED<br>";
} else {
    echo "❌ allow_url_fopen is DISABLED<br>";
}

// 4. Test API Connection
echo "<h3>4. SMS API Connection Test:</h3>";
$api_url = "http://123.108.46.13/sms-panel/api/http/index.php";
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

if ($response !== false) {
    echo "✅ API is REACHABLE<br>";
    echo "HTTP Status: " . $info['http_code'] . "<br>";
    echo "Response: " . htmlspecialchars(substr($response, 0, 200)) . "<br>";
} else {
    echo "❌ API is NOT REACHABLE<br>";
    echo "Error: " . $error . "<br>";
}

// 5. PHP Version
echo "<h3>5. PHP Version:</h3>";
echo "PHP Version: " . phpversion() . "<br>";

// 6. Server IP
echo "<h3>6. Server IP:</h3>";
echo "Server IP: " . ($_SERVER['SERVER_ADDR'] ?? gethostbyname(gethostname())) . "<br>";
?>