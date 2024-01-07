<?php
////////////////////////////
////////////////////////////
////////////未完工///////////
////////////////////////////
////////////////////////////
//

//
//请求
//数字 movie_id
//
//响应
//字符串'OK'/'Deny'/'NoFound'/''
//OK
//Deny : 无论方法,admin_permission为false则返回Deny
//
//''

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['last_url']='addMovie.php';
    header('Content-Type: text/html');
    echo jumpPage('login.php','','<p>此页面必须登录后访问,将跳转到登录页面</p>');
    exit();
}
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



//检查会话状态,admin_permission为false则返回
if($_SERVER["REQUEST_METHOD"] == "GET"){

}
if($_SERVER["REQUEST_METHOD"] == "POST"){

}