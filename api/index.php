<?php
// Allow requests from your frontend
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Check if the 'info' parameter is provided
if (!isset($_GET['info']) || empty($_GET['info'])) {
    echo json_encode(['error' => 'Target info parameter is missing.']);
    exit;
}

// Sanitize inputs
$info_query = urlencode($_GET['info']);
$api_key = "ftgamer";

$demo_query = "";
if (isset($_GET['demo']) && !empty($_GET['demo'])) {
    $demo_query = "&demo=" . urlencode($_GET['demo']);
}

// Construct the target API URL
$target_url = "https://broad-dust-ad2f.mohammadumar7221.workers.dev/api/combined?key={$api_key}&info={$info_query}{$demo_query}";

// Initialize cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

// Execute the request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Handle execution errors
if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Proxy request failed', 'details' => $curl_error]);
    exit;
}

// Decode the raw response from the external API into a PHP array
$api_data = json_decode($response, true);

// Pass the status code from the external API back to the client
http_response_code($http_code);

// Construct your custom formatted output.
// NOTE: This assumes the external API actually returns keys named 'telegram_id', 'mobile', etc.
// If the external API uses different key names, change the $api_data['key'] below to match them.
$custom_output = [
    "success"      => true,
    "info"         => isset($_GET['info']) ? $_GET['info'] : "",
    "telegram_id"  => isset($api_data['telegram_id']) ? $api_data['telegram_id'] : "",
    "mobile"       => isset($api_data['mobile']) ? $api_data['mobile'] : "",
    "name"         => isset($api_data['name']) ? $api_data['name'] : "",
    "country_code" => isset($api_data['country_code']) ? $api_data['country_code'] : "",
    "by"           => "@ComRed2786"
];

// Output the strictly formatted JSON
echo json_encode($custom_output);
?>
