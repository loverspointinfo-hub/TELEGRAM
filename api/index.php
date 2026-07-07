<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram API 查询工具</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background: #f4f7fc;
            color: #333;
        }
        .container {
            background: #fff;
            border-radius: 12px;
            padding: 30px 35px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        h1 {
            text-align: center;
            font-weight: 400;
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #eaeef2;
            padding-bottom: 15px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #2c3e50;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d9e6;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: 0.2s;
        }
        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            transition: background 0.2s;
        }
        button:hover {
            background: #2980b9;
        }
        .result {
            margin-top: 30px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            border-left: 5px solid #3498db;
            white-space: pre-wrap;
            word-break: break-all;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            max-height: 500px;
            overflow-y: auto;
        }
        .error {
            border-left-color: #e74c3c;
            background: #fdf0ed;
        }
        .loading {
            display: none;
            text-align: center;
            margin: 15px 0;
            color: #7f8c8d;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            color: #95a5a6;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📡 Telegram 用户信息查询</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="tg_id">输入 Telegram ID（或用户名）</label>
                <input type="text" id="tg_id" name="tg_id" placeholder="例如：123456789 或 @username" value="<?php echo isset($_POST['tg_id']) ? htmlspecialchars($_POST['tg_id']) : ''; ?>" required>
            </div>
            <button type="submit">🔍 查询</button>
        </form>

        <div class="loading" id="loading">⏳ 正在请求数据，请稍候...</div>

        <?php
        // ============================================================
        // 后端 PHP 处理逻辑
        // ============================================================
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tg_id']) && trim($_POST['tg_id']) !== '') {
            $tgId = trim($_POST['tg_id']);
            
            // 调用 API 的函数（使用 cURL）
            function callTgApi($tgId) {
                $apiKey = 'ftgamer';
                $baseUrl = 'https://broad-dust-ad2f.mohammadumar7221.workers.dev/api/tg';
                
                // 构建完整 URL
                $url = $baseUrl . '?key=' . urlencode($apiKey) . '&info=' . urlencode($tgId);
                
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
                    return ['error' => "cURL 错误: " . $error];
                }
                
                if ($httpCode !== 200) {
                    return ['error' => "HTTP 状态码: " . $httpCode . "，可能 API 返回了非预期响应。"];
                }
                
                // 尝试解析 JSON
                $data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $data;
                } else {
                    // 如果不是 JSON，返回原始文本
                    return ['raw_response' => $response];
                }
            }
            
            // 执行调用
            $result = callTgApi($tgId);
            
            // 显示结果
            echo '<div class="result' . (isset($result['error']) ? ' error' : '') . '">';
            echo '<strong>📌 查询结果：</strong><br><br>';
            if (isset($result['error'])) {
                echo '❌ ' . htmlspecialchars($result['error']);
            } else {
                // 美化输出（如果是数组，以 JSON 格式展示）
                echo '<pre style="margin:0;font-family:inherit;">';
                echo htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                echo '</pre>';
            }
            echo '</div>';
        }
        ?>
        <div class="footer">Powered by PHP cURL · 数据来源于外部 API</div>
    </div>

    <script>
        // 简单的加载提示
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('loading').style.display = 'block';
        });
    </script>
</body>
</html>
