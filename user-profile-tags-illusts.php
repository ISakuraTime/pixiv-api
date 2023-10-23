<?php

//$arr = '112773230, 112712669, 112686411, 112686408, 112686379, 112611596, 112498136, 112498108, 112420123, 112329768, 112251703, 112166013, 112165999, 112009363, 111903802, 111893579, 111867023, 111843373, 111778359, 111778219, 111723671, 111698466, 111673429, 111647126, 111620843, 111620793, 111587947, 111587898, 111557224, 111504309, 111452406, 111426266, 111426187, 111393045, 111362383, 111334138, 111302836, 111302810, 111302711, 111275768, 111249061, 111187313, 111047808, 110946965, 110925611, 110866891, 110834306, 110773705, 110683843, 110655126';
$param = array();
$ids = $_GET["ids"];
$idsParam = array();
if (isset($ids)) {
    $ids = str_replace(" ", "", $ids);
    $idsArr = explode(",", $ids);
    // 将数字转换为 ids%5B%5D=数字 格式
    $idsParam = array_map(function ($id) {
        return "ids%5B%5D=" . $id;
    }, $idsArr);
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
    $param["version"] = "1eb1c2f8f3d5fcd51de6662cc60d319cde1082aa";
}

$imageUrl = "https://www.pixiv.net/ajax/tags/frequent/illust";
$imageUrl = "$imageUrl?" . http_build_query($param) . "&" . join("&", $idsParam);
// 初始化 cURL 会话
$ch = curl_init();
// 设置 cURL 选项
curl_setopt($ch, CURLOPT_URL, $imageUrl);
$header = array(
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/118.0',
    'Accept: application/json',
    'Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2',
    'Referer: https://www.pixiv.net/users/16582456/illustrations',
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

    $json_error = array();
    $json_error["error_code"] = $error_code;
    $json_error["error_message"] = $error_message;
    $json_error["status"] = "error";
    $json_error["body"] = $response;
    echo json_encode($json_error);
}
// 关闭 cURL 会话
curl_close($ch);
