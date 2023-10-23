<?php
$imageUrl = "https://www.pixiv.net/tags";

$header = array(
    'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/118.0',
);
$lang = $_GET['lang'];
if (empty($lang)) {
    $lang = 'zh';
}

if ($lang == 'zh') {
    $header[] = 'Accept-Language:zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2';
}
// 默认就是jp
 if ($lang == 'jp') {
     $header[] = "";
 }

// 初始化 cURL 会话
$ch = curl_init();
// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $imageUrl);

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
    $sectionElements = $xpath->query('//ul[@class="tag-list inline-list slash-separated"]/li');
    foreach ($sectionElements as $sectionElement) {
        $json = array();

        // tag name
        $thumbnail = $xpath->query('a', $sectionElement)->item(0);
        $json["name"] = $thumbnail->textContent;

        // tag 热度
        $countBadge = $xpath->query('span', $sectionElement)->item(0);
        $json["countBadge"] = $countBadge->textContent;

        // tooltip
        $dataTooltip = $xpath->query('a', $sectionElement)->item(1);
        $json["dataTooltip"] = $dataTooltip->getAttribute("data-tooltip");

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
