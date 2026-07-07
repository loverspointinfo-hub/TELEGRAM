<?php
// Allow requests from your frontend
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Check if the 'info' parameter is provided in the request
if (!isset($_GET['info']) || empty($_GET['info'])) {
    echo json_encode(['error' => 'Target info parameter is missing.']);
    exit;
}

$info_query = urlencode($_GET['info']);
$api_key = "ftgamer";

// Construct the target API URL
$target_url = "https://broad-dust-ad2f.mohammadumar7221.workers.dev/api/combined?key={$api_key}&info={$info_query}";

// Initialize cURL session
$ch = curl_init();

// Configure cURL options
curl_setopt($ch, CURLOPT_URL, $target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// Add timeout to prevent hanging requests
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

// Pass the status code from the external API back to the client
http_response_code($http_code);

// Output the raw JSON response directly to the client
echo $response;
?>