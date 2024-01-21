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
///////前端缺少peekName///////////////////


///////////////////
///////测试正常/////
///////////////////
///
///
///
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

///////GET///////////
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])) {
        $UrlToJump ='';
        echo '您已登录';
    }else{
        //生成登录页面
        header('Content-Type: text/html');
        $htmlContent = file_get_contents('html/login.html');
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


    $checkIfExist= "SELECT * FROM users WHERE user_name = '$un' AND password = '$pw'";
    $result = $conn->query($checkIfExist);
    if($result->num_rows >=1){
        $row = $result->fetch_assoc();
        $_SESSION['user_id']=$row['id'];
        $_SESSION['user_name']=$row['user_name'];
        $_SESSION['admin_permission']=$row['isAdmin']==1;
        echo 'Success';
    }else{
        echo 'NoFound';
    }
    $conn->close();/*
    echo $message;
    echo "会话状态:<br>";
    var_dump($_SESSION);*/
}
