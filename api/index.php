<?php
// 1. Tell the browser/client to expect ONLY JSON data
header('Content-Type: application/json');

// (Optional) Suppress any lingering deprecation warnings so they don't break the JSON
error_reporting(E_ALL & ~E_DEPRECATED);

// 2. Get the Telegram ID from the URL (e.g., tg_api.php?id=987654321)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // If no ID is provided, return a JSON error message and stop the script
    echo json_encode([
        "success" => 0,
        "error" => "No ID provided. Please use ?id=123456789"
    ]);
    exit;
}

$tg_id = $_GET['id'];

// 3. Define the base API URL and parameters
$base_url = "https://broad-dust-ad2f.mohammadumar7221.workers.dev/api/tg";
$key = "ftgamer";

// 4. Construct the full API link
$api_link = $base_url . "?key=" . urlencode($key) . "&info=" . urlencode($tg_id);

// 5. Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $api_link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return string instead of printing directly

// Execute the request
$response = curl_exec($ch);

// 6. Handle the response
if(curl_errno($ch)){
    // If there's a server/connection error, output it in clean JSON
    echo json_encode([
        "success" => 0, 
        "error" => curl_error($ch)
    ]);
} else {
    // The target API already returns JSON. 
    // We just echo it exactly as we received it so your output is pure JSON.
    echo $response;
}

// NOTE: curl_close($ch) has been intentionally completely removed here to fix the PHP 8.5 error!
?>
