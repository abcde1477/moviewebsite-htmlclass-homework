<?php
include_once 'DBInit.php';
/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */



if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameToCheck= $_POST['name'];
    $message ='';
    if (preg_match('/^[a-zA-Z0-9_]+$/', $nameToCheck)) {
        $conn = new mysqli($servername, $username, $password);
        if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);

        $SqlSearchComments = "SELECT * FROM $userTableName WHERE user_name = $nameToCheck;";
        $result = $conn->query($SqlSearchComments);
        if($result->num_rows >0)
            $message ="用户名已存在";
        else{
            $message ="用户名可用";
        }
    } else {
        $message ="用户名只能为字母数字下划线";
    }
    header('content-Type:text/html');
    echo $message;
}
?>