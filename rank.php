<?php
// 目标图片 URL
// $imageUrl = 'https://bing.com/th?id=OHR.WhitsundaySwirl_ZH-CN9085371328_800x480.jpg';
$imageUrl = "https://www.pixiv.net/ranking.php";

// 使用数组构建参数
$params = array();
$mode = $_GET['mode'];
// 排行榜模式
if (isset($mode)) {
    $params["mode"] = $mode;
}
// 排行榜内容
$content = $_GET['content'];
if (isset($content)) {
    $params["content"] = $content;
}
// 排行榜内容
$data = $_GET['date'];
if (isset($data)) {
    $params["data"] = $data;
}
$proxy_url = $_GET['proxy_url'];
$imageUrl = "$imageUrl?" . http_build_query($params);

// 初始化 cURL 会话
$ch = curl_init();
// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $imageUrl);

$header = array(
    'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/118.0'
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
    libxml_use_internal_errors(true); // 禁用 libxml 错误处理
    // 创建DOM对象
    $dom = new DOMDocument();
    $dom->loadHTML($response); // @ 用于抑制可能的错误信息
    $dom->encoding = 'UTF-8'; // 设置XML文档的字符编码
    // 创建XPath对象
    $xpath = new DOMXPath($dom);

    $jsonArr = array();
    // 查询标题元素
    // 使用XPath查询获取指定ID的meta标签
    $sectionElements = $xpath->query('//section[@class="ranking-item"]');
    foreach ($sectionElements as $sectionElement) {
        $json = array();
        // 图片排行榜信息
        $json["name"] = $sectionElement->getAttribute('data-title');
        $json["data"] = $sectionElement->getAttribute('data-date');
        $json["author"] = $sectionElement->getAttribute('data-user-name');
        $json["viewCount"] = $sectionElement->getAttribute('data-view-count');
        $json["ratingCount"] = $sectionElement->getAttribute('data-rating-count');
        $json["original"] = $sectionElement->getAttribute('data-attr');
        $json["imgId"] = $sectionElement->getAttribute('data-id');

        // 图片信息
        $thumbnail = $xpath->query('div//img', $sectionElement)->item(0);
        $_proxy_url = "";
        if (isset($proxy_url)) {
            $_proxy_url = $proxy_url . "?url=";
        }
        $json["imgUrl"] = $_proxy_url . $thumbnail->getAttribute('data-src');
        $json["tags"] = $thumbnail->getAttribute('data-tags');

        // 用户信息
        $user = $xpath->query('a', $sectionElement)->item(0);
        $json["userId"] = $user->getAttribute('data-user_id');
        $_proxy_url = "";
        if (isset($proxy_url)) {
            $_proxy_url = $proxy_url . "?url=";
        }
        $json["userPic"] = $_proxy_url . $user->getAttribute('data-profile_img');

        $pattern = '/\/(\d+)x(\d+)\//';
        preg_match($pattern, $json["imgUrl"], $matches);
        $json["width"] = $matches[1];
        $json["height"] = $matches[2];
        $jsonArr[] = $json;
    }
    // 获取meta标签的content属性值
//    $content = $sectionElements->getAttribute('content');
    header('Content-Type: application/json charset=UTF-8');
    // 允许来自任何域的跨域请求
    header("Access-Control-Allow-Origin: *");
    // 计算响应主体的字节长度
    $jsonString = json_encode($jsonArr);
    $contentLength = strlen($jsonString);
    // 设置HTTP响应头部中的Content-Length
    header("Content-Length: $contentLength");
    // 设置HTTP响应头部，指定Content-Type为text/html，并字符集为UTF-8
    echo iconv("UTF-8", "ISO-8859-1//TRANSLIT", $jsonString);
//    echo $jsonString;

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
