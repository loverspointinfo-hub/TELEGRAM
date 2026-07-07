<?php
// 设置响应头为 JSON
header('Content-Type: application/json; charset=utf-8');

// 获取请求参数（支持 GET 和 POST）
$tgId = isset($_REQUEST['info']) ? trim($_REQUEST['info']) : '';

// 如果没有提供 info 参数
if (empty($tgId)) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing "info" parameter. Please provide Telegram ID or username.'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// API 配置
$apiKey = 'ftgamer';
$baseUrl = 'https://broad-dust-ad2f.mohammadumar7221.workers.dev/api/tg';

// 构建完整 URL
$url = $baseUrl . '?key=' . urlencode($apiKey) . '&info=' . urlencode($tgId);

// 使用 cURL 发起请求
function callApi($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($error) {
        return [
            'success' => false,
            'error' => 'cURL Error: ' . $error
        ];
    }
    
    if ($httpCode !== 200) {
        return [
            'success' => false,
            'error' => 'HTTP Error: ' . $httpCode,
            'raw_response' => $response
        ];
    }
    
    // 尝试解析 JSON
    $data = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return array_merge(['success' => true], $data);
    } else {
        return [
            'success' => false,
            'error' => 'Invalid JSON response from API',
            'raw_response' => $response
        ];
    }
}

// 执行 API 调用
$result = callApi($url);

// 输出 JSON 结果
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
