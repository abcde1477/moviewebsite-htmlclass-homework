<?php





if (isset($_SESSION['last_url'])){
    echo "注册成功,欢迎".$_SESSION['username'].",稍后将返回至上一页面";
}else{
    echo "注册成功,欢迎".$_SESSION['username'].",将稍后返回主页";
}