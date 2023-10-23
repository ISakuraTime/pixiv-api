<?php
// 目标图片 URL
$imageUrl = $_GET['url'];;

if (empty($imageUrl) === true) {
    $errorJson = array();
    $errorJson["msg"] = "Please enter the image address";
    $errorJson["code"] = 500;
    $errorJson["body"] = "";
    echo json_encode($errorJson);
    exit(0);
}

// 初始化 cURL 会话
$ch = curl_init();

// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $imageUrl);
// 设置代理
//curl_setopt($ch, CURLOPT_PROXY, 'http://localhost:7890'); // 设置代理服务器的地址和端口
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: https://www.pixiv.net/'));
// 设置为 true 表示将 cURL 请求的响应数据作为字符串返回，而设置为 false 表示将响应数据直接输出到标准输出或浏览器。
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
// SSL 取消
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// 启用自动跟随重定向（默认情况下已启用）
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// header("Content-Type: image/jpeg");
// 自定义回调函数来处理响应头
curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $headerLine) use (&$header) {
    header($headerLine);
    return strlen($headerLine);
});


// 执行 cURL 请求
$response = curl_exec($ch);

// 获取响应头
$responseHeaders = curl_getinfo($ch);
if (isset($response)) {
    // 获取错误消息和错误代码
    $error_message = curl_error($ch);
    $error_code = curl_errno($ch);

    $errorJson = array();
    $errorJson["msg"] = $error_message;
    $errorJson["code"] = $error_code;
    $errorJson["body"] = $response;
    echo json_encode($errorJson);
}

// 关闭 cURL 会话
curl_close($ch);

