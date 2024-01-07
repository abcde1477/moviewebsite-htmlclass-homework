<?php
//管理员专用页面，用于添加电影
//管理员专用页面，用于添加电影
//管理员专用页面，用于添加电影

include '../private/generateJumpPage.php';

//无会话则跳转至登录页面
//无会话则跳转至登录页面
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['last_url']='addMovie.php';
    header('Content-Type: text/html');
    echo jumpPage('login.php','','<p>此页面必须登录后访问,将跳转到登录页面</p>');
    exit();
}

include_once 'private/DBSet.php';


//验证是否为管理员
//验证是否为管理员
if(isset($_SESSION['admin_permission']) &&($_SESSION['admin_permission'] === true||$_SESSION['admin_permission'] === 'true')){
    //
}else{
    //不允许的访问，进入跳转页面
    header('Content-Type: text/html');
    echo jumpPage('index.php','','<p>您不是管理员用户,不能访问该页面，将跳转到主页</p>');
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "GET") {
    //登录页面
    $htmlContent = file_get_contents('../html/addMovie.html');
    header('Content-Type: text/html');
    echo $htmlContent;
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //表单处理
    ////////////////////////////////
    ////////////////////////////////
    /////////未完成//////////////////
    ////////////////////////////////
}

