<?php
/**
 * Fetch user information from the Telegram API endpoint.
 *
 * @param int $tgId The Telegram user ID to look up.
 * @param string $apiKey The API key required by the endpoint. Default is 'ftgamer'.
 * @return array|null Returns an associative array with 'success' and 'data' or 'error' keys,
 *                     or null if the request fails completely.
 */
function getTelegramUserInfo(int $tgId, string $apiKey = 'ftgamer'): ?array {
    // Construct the full URL with query parameters
    $baseUrl = 'https://broad-dust-ad2f.mohammadumar7221.workers.dev/api/tg';
    $queryParams = http_build_query([
        'key' => $apiKey,
        'info' => $tgId
    ]);
    $fullUrl = $baseUrl . '?' . $queryParams;

    // Initialize cURL session
    $ch = curl_init($fullUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,   // Return response as string
        CURLOPT_TIMEOUT => 30,            // Maximum execution time in seconds
        CURLOPT_FOLLOWLOCATION => true,   // Follow redirects if any
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',   // Expect JSON response
            'User-Agent: PHP-API-Client/1.0'
        ]
    ]);

    // Execute the request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    // Handle cURL errors
    if ($response === false) {
        error_log("cURL Error: " . $curlError);
        return ['success' => false, 'error' => 'Connection error: ' . $curlError];
    }

    // Decode JSON response
    $decodedResponse = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON Decode Error: " . json_last_error_msg() . " | Raw Response: " . substr($response, 0, 500));
        return ['success' => false, 'error' => 'Invalid JSON response from server'];
    }

    // Check HTTP status code
    if ($httpCode !== 200) {
        return [
            'success' => false,
            'error' => 'Server returned HTTP ' . $httpCode,
            'details' => $decodedResponse ?? $response
        ];
    }

    // Return successful response with data
    return ['success' => true, 'data' => $decodedResponse];
}

// --- Example Usage ---

// Replace with the actual Telegram ID you want to query
$telegramId = 123456789;

$result = getTelegramUserInfo($telegramId);

if ($result === null) {
    echo "An unexpected error occurred during the request process.";
} elseif ($result['success'] === true) {
    echo "API call successful!\n";
    echo "Response Data:\n";
    print_r($result['data']);
} else {
    echo "API call failed.\n";
    echo "Error: " . $result['error'] . "\n";
    if (isset($result['details'])) {
        echo "Details: " . print_r($result['details'], true) . "\n";
    }
}
?>
