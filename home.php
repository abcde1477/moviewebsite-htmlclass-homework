<?php


include_once 'private/DBInit.php';
include_once 'private/DBGet.php';
include_once 'private/verify.php';

/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo "会话状态:<br>";
var_dump($_SESSION);
function getStringBetween($str, $begin, $end)
{
    $start = strpos($str, $begin);
    if ($start === false) {
        return false;
    }
    $start += strlen($begin);

    if ($end == '_END') {
        $end = strlen($str);
    } else {
        $end = strpos($str, $end, $start);
        if ($end === false) {
            return false;
        }
    }
    return substr($str, $start, $end - $start);
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['query_id']) && is_numeric($_GET['query_id'])) {
        $conn = new mysqli($servername, $username, $password, $dbName);
        if ($conn->connect_error)
            $message = "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;


        $query_id = intval($_GET['query_id']);
        $result = getDataById_GET($conn, $_GET)['userdata'];

        header('Content-Type: text/html');
        $htmlContent = file_get_contents('html/home.html');

    }
}