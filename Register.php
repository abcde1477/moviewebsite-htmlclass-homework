<?php

//注册页面以及注册表单处理
//注册页面以及注册表单处理
//注册页面以及注册表单处理
///////////////////////////////////
///////register.html前端没有完成/////
///////////////////////////////////


///////////////////////////////////
///////register.php没有完成/////
///////////////////////////////////
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
        //不允许的访问，进入跳转页面
        header('Content-Type: text/html');
        echo jumpPage($UrlToJump,'','<p>您已经登录,稍后将返回至上一页面</p>');
        exit();
    }else{
        //生成登录页面
        $htmlContent = file_get_contents('../html/register.html');
        header('Content-Type: text/html');
        echo $htmlContent;
        exit();
    }
}


if (isset($_SESSION['last_url'])){
    echo "注册成功,欢迎".$_SESSION['username'].",稍后将返回至上一页面";
}else{
    echo "注册成功,欢迎".$_SESSION['username'].",将稍后返回主页";
}