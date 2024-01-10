<?php
//登录页面以及登录表单处理
//登录页面以及登录表单处理
//登录页面以及登录表单处理
///////////////////////////////////
///////login.html前端没有完成/////////
///////////////////////////////////
include_once 'private/DBInit.php';
include 'private/generateJumpPage.php';
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

if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])) {
        $UrlToJump ='';
        //已经登录,还用GET访问此页面,是不允许的方式,将回到上一页面
        if (isset($_SESSION['last_url'])){
            $UrlToJump = $_SESSION['last_url'];
        }else{
            $UrlToJump = 'index.php';
        }
        //进入跳转页面
        header('Content-Type: text/html');
        echo jumpPage($UrlToJump,'','<p>您已经登录,稍后将返回至上一页面</p>');
        exit();
    }else{
        //生成登录页面
        $htmlContent = file_get_contents('html/login.html');
        header('Content-Type: text/html');
        echo $htmlContent;
        exit();
    }
}

//未函数化查询过程.时间问题,暂不重构
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name= $_POST['username'];
    $pw= $_POST['password'];
    $message ='';
    if (preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
        if (preg_match('/^[a-zA-Z0-9_]+$/', $pw)) {
            $conn = new mysqli($servername, $username, $password);
            if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
            $name = mysqli_real_escape_string($conn, $name);
            $pw = mysqli_real_escape_string($conn, $pw);

            $sql = "SELECT * FROM $userTableName WHERE username='$name' AND password='$pw'";
            $result = $conn->query($sql);
            if($result->num_rows == 1) {
                //登录成功
                $row = $result->fetch_assoc();
                $_SESSION['user_name'] =$row['user_name'];
                $_SESSION['user_id'] =$row['id'];
                $_SESSION['admin_permission'] =$row['isAdmin'];
                //跳转url信息
                if (isset($_SESSION['last_url'])){
                    echo $_SESSION['last_url'];
                }else{
                    echo "index.php";
                }
            }
            else if($result->num_rows == 0){
                echo "incorrect";
            }else{
                echo "error-DB";
            }
        }else {
            //按理来说,username，password需要用JavaScript进行检查.对用户的提醒也应该在前端进行
            echo "error-INPUT";
        }
    }
    header('content-Type:text/html');
    echo $message;
}
?>