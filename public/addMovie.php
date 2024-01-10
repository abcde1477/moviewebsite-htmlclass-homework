<?php
//管理员专用页面，用于添加电影
//管理员专用页面，用于添加电影
//管理员专用页面，用于添加电影

include '../private/generateJumpPage.php';

header('Content-Type: text/html');

//无所谓，无会话不应该进入此页面
//无会话则跳转至登录页面
//无会话则跳转至登录页面
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    $_SESSION['last_url']='addMovie.php';
    header('Content-Type: text/html');
    echo jumpPage('login.php','','<p>此页面必须登录后访问,将跳转到登录页面</p>');
    exit();
}
//





//验证是否为管理员
//验证是否为管理员
/*if(isset($_SESSION['admin_permission']) &&($_SESSION['admin_permission'] === true||$_SESSION['admin_permission'] === 'true')){
    //
}else{
    //不允许的访问，进入跳转页面
    echo jumpPage('index.php','','<p>您不是管理员用户,不能访问该页面，将跳转到主页</p>');
    exit();
}*/
if(!checkPermission(false,$_SESSION['admin_permission'])){
    echo 'Exception,error in checkPermission';
    exit();
}else {
    include_once 'private/DBSet.php';
//GET方法
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        //登录页面
        $htmlContent = file_get_contents('../html/addMovie.html');
        echo $htmlContent;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //表单处理
        ////////////////////////////////
        ////////////////////////////////
        /////////未完成//////////////////
        ////////////////////////////////
        $form_data = ['cover'];


        $form_data['movie_name'];
        $form_data['attribution'];
        //$form_data['cover_url'];先插入其他项,如果有上传封面,就会
        $form_data['movie_content'];
        $form_data['photo_file_url'];
        $form_data['releaseTime'];
        $form_data[''];

        //连接数据库在DBSet进行.
        addMovie($form_data);
    }

}