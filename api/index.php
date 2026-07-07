<?php

// 1. Get the Telegram ID from the URL (e.g., yourscript.php?id=123456789)
// If no ID is provided in the URL, it defaults to a placeholder.
$tg_id = isset($_GET['id']) ? $_GET['id'] : 'YOUR_TG_ID_HERE';

// 2. Define the base API URL and your parameters
$base_url = "https://broad-dust-ad2f.mohammadumar7221.workers.dev/api/tg";
$key = "ftgamer";

// 3. Construct the full API link
// urlencode() ensures that any special characters are safely formatted for a URL
$api_link = $base_url . "?key=" . urlencode($key) . "&info=" . urlencode($tg_id);

// --- Optional: Fetch the data from the API ---

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $api_link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string

// Execute the request and fetch the response
$response = curl_exec($ch);

// Check if there were any errors during the request
if(curl_errno($ch)){
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Print the generated link and the API's response
    echo "<b>Generated API Link:</b> <a href='{$api_link}' target='_blank'>{$api_link}</a><br><br>";
    echo "<b>API Response:</b><br>";
    
    // If the API returns JSON, you can decode it like this:
    // $json_data = json_decode($response, true);
    
    // Display the raw response securely
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

// Close the cURL session to free up resources
curl_close($ch);

?>
