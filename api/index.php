<?php
// 1. Tell the browser/client to expect ONLY JSON data
header('Content-Type: application/json');

// Suppress any lingering deprecation warnings
error_reporting(E_ALL & ~E_DEPRECATED);

// 2. Get the Telegram ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
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
curl_setopt($ch, CURLOPT_URL, $api_link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request
$response = curl_exec($ch);

// 6. Handle and Modify the response
if(curl_errno($ch)){
    // If there's a cURL error, output it as JSON
    echo json_encode([
        "success" => 0, 
        "error" => curl_error($ch)
    ]);
} else {
    // Decode the JSON response from the original API into a PHP array
    $data = json_decode($response, true);

    // Make sure the API actually returned valid JSON
    if ($data !== null) {
        
        // Remove the old "by" value
        if (isset($data['by'])) {
            unset($data['by']);
        }
        
        // Add your new credit
        $data['credit by'] = '@ComRed2786';

        // Re-encode the modified array back into JSON and output it
        echo json_encode($data);
        
    } else {
        // Fallback in case the target API breaks or returns plain text
        echo json_encode([
            "success" => 0,
            "error" => "Invalid JSON received from target API."
        ]);
    }
}
?>
