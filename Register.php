<?php
include_once 'private/DBInit.php';
include_once 'private/DBSet.php';
include_once 'private/generateJumpPage.php';
/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

///////////////////////////////////
///////register.html前端没有完成/////


///////////////////
///////测试正常/////
///////////////////
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

///////GET///////////
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])) {
      /*  $UrlToJump ='';
        //已经登录,还用GET访问此页面,是不允许的方式,将回到上一个页面
        if (isset($_SESSION['last_url'])){
            $UrlToJump = $_SESSION['last_url'];
        }else{
            $UrlToJump = 'index.php';
        }
        //不允许的访问，进入跳转页面
        header('Content-Type: text/html');
        echo jumpPage($UrlToJump,'','<p>您已经登录,稍后将返回至上一个页面</p>');*/
        echo '您已登录';
        exit();
    }else{
        //生成登录页面
        header('Content-Type: text/html');
        $htmlContent = file_get_contents('html/register.html');
        echo $htmlContent;
        exit();
    }
}
///////GET///////////
///
///

///////POST///////////
if($_SERVER["REQUEST_METHOD"] == "POST"){
    header('Content-Type: text/html');
    $message = 'Success';
    if (!(
        isset($_POST['username']) &&
        isset($_POST['password'])
    )) $message = 'LackParam';
    $un = $_POST['username'];
    $pw = $_POST['password'];
    $conn = new mysqli($servername, $username, $password,$dbName);
    if ($conn->connect_error)
        $message = "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;

    if($message == 'Success') {
        register($conn,$un,$pw);
    }
    $conn->close();

    echo $message;/*
    echo "会话状态:<br>";
    var_dump($_SESSION);*/

    header("Location: home.php");
    ob_end_flush();
}

///////POST///////////
/*if (isset($_SESSION['last_url'])){
    echo "注册成功,欢迎".$_SESSION['username'].",稍后将返回至上一页面";
}else{
    echo "注册成功,欢迎".$_SESSION['username'].",将稍后返回主页";
}*/