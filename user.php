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
}/*
echo "会话状态:<br>";
var_dump($_SESSION);*/
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
    if (isset($_GET['query_id']) && is_numeric($_GET['query_id']) && $_GET['query_id'] !='-1'){
        $conn = new mysqli($servername, $username, $password, $dbName);
        if ($conn->connect_error)
            $message = "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;


        $query_id = intval($_GET['query_id']);

        $result = getDataById_GET($conn, $_GET)['userdata'];

        header('Content-Type: text/html');
        $htmlContent = file_get_contents('html/user.html');

        $htmlContent = str_replace('这里替换为头像url', $result['profile_url'], $htmlContent);
        $htmlContent = str_replace('这里替换为用户名', $result['user_name'], $htmlContent);
        $htmlContent = str_replace('这里替换为用户id', $query_id, $htmlContent);

        if(isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
            $userdata = getDataById($conn, $_SESSION['user_id']);
            $userURL = 'user.php?query_id=' . $_SESSION['user_id'];
            $htmlContent = str_replace('替换为会话者id', $_SESSION['user_id'], $htmlContent);
        }else{
            $htmlContent = str_replace('替换为会话者id', -1, $htmlContent);

        }


            $htmlContent = str_replace('在这里替换为注册时间', $result['register_time'], $htmlContent);
        $htmlContent = str_replace('这里替换为个人简介', $result['homepage_content'], $htmlContent);

        $modifiable = checkPermission(!(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $query_id),
            isset($_SESSION['admin_permission'])&& $_SESSION['admin_permission']
        );
        $modifiable = $modifiable?'true':'false';

        $htmlContent = str_replace('删除按钮显示与否',$modifiable, $htmlContent);


        if(!(isset($_SESSION['user_id']) && ($_SESSION['user_id'] ==$query_id || $_SESSION['admin_permission']))){
            $htmlContent = str_replace('<button id="edit-btn" class="edit-btn" onclick="enableEditMode()">Edit</button>', '', $htmlContent);
        }

        echo $htmlContent;
        exit();
    }else{
        echo "登录后方可访问此页面<br>";
        echo '<a href="login.php">去登录</a>';
    }
}
