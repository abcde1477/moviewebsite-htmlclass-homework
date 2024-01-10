<?php

//获取电影信息，具体使用细则请看文档
//获取电影信息，具体使用细则请看文档
//获取电影信息，具体使用细则请看文档
include_once '../private/DBInit.php';
include_once '../private/DBGet.php';
/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

$conn = new mysqli($servername, $username, $password,$dbName);
if ($conn->connect_error) {
    die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
}
if($_SERVER["REQUEST_METHOD"] == "POST") {

    $json_data = json_encode(getMovie($conn,$_POST));
    header('content-Type:application/json');
    echo $json_data;
}
$conn->close();