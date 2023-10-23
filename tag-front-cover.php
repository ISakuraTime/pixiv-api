<?php
// tag 封面信息
$param = array();
// 获取用户ID
$tagName = $_GET["tagname"];
//$tagName = '風景';
if (empty($tagName)) {
    $errorJson = array();
    $errorJson["msg"] = "Please enter the tag name";
    $errorJson["code"] = 500;
    $errorJson["body"] = "";
    echo json_encode($errorJson);
    exit(0);
}

$lang = $_GET["lang"];
if (isset($lang)) {
    $param["lang"] = $lang;
} else {
    $param["lang"] = "zh";
}

$version = $_GET["version"];
if (isset($version)) {
    $param["version"] = $version;
} else {
    $param["version"] = "b2d6f6256ccf3a9b62b0b55692d6ac3f980d53cb";
}


$imageUrl = "https://www.pixiv.net/ajax/search/tags/".urlencode($tagName);
$imageUrl = "$imageUrl?" . http_build_query($param);
// 初始化 cURL 会话
$ch = curl_init();
// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $imageUrl);
$header = array(
    'Accept: application/json',
    'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
    'Referer: https://www.pixiv.net/tags/%E9%A2%A8%E6%99%AF/illustrations',
    'baggage: sentry-environment=production,sentry-release=b2d6f6256ccf3a9b62b0b55692d6ac3f980d53cb,sentry-public_key=7b15ebdd9cf64efb88cfab93783df02a,sentry-trace_id=ec20a9f3503643d4802dda8ad482b44d,sentry-sample_rate=0.0001',
    'sentry-trace: ec20a9f3503643d4802dda8ad482b44d-a3765b1f6da212f9-0',
    'Sec-Fetch-Dest: empty',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Site: same-origin',
    'Connection: keep-alive',
    'TE: trailers',
    'Host: www.pixiv.net'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//curl_setopt($ch, CURLOPT_PROXY, 'http://localhost:7890'); // 设置代理服务器的地址和端口
// 设置为 true 表示将 cURL 请求的响应数据作为字符串返回，而设置为 false 表示将响应数据直接输出到标准输出或浏览器。
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// SSL 取消
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// 启用自动跟随重定向（默认情况下已启用）
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// 执行 cURL 请求
$response = curl_exec($ch);

// 获取响应头
$responseHeaders = curl_getinfo($ch);

// 检查响应是否成功
if ($response !== false && $responseHeaders['http_code'] === 200) {
    header('Content-Type: application/json charset=UTF-8');
    // 计算响应主体的字节长度
    $contentLength = strlen($response);
    // 设置HTTP响应头部中的Content-Length
    header("Content-Length: $contentLength");
    // 设置HTTP响应头部，指定Content-Type为text/html，并字符集为UTF-8
//    echo iconv("UTF-8", "ISO-8859-1//TRANSLIT", $response);
    // 将content属性值解码为关联数组（通常是JSON格式）
    // $data = json_decode($content, true);
    echo $response;
} else {
    // 检查是否请求失败
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
