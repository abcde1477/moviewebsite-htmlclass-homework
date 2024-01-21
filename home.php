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
    $conn = new mysqli($servername, $username, $password, $dbName);
    if ($conn->connect_error)
        $message = "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;

    /*$result = getDataById_GET($conn, $_GET)['userdata'];*/

    header('Content-Type: text/html');
    $htmlContent = file_get_contents('html/home.html');

    //获取轮播图推荐.
    $movies = getMovie($conn,'rating','DESC',3,1,false);
    $imgHTML = '';
    foreach ($movies as $movie){
        $ShowURL = (isset($movie['photos'][0]))?$movie['photos'][0]:$movie['cover_url'];
        $detailURL = 'movieDetail.php?query_id='.$movie['id'];
        $imgHTML.='<a href ="'.$detailURL.'"><img src="'.$ShowURL.'" alt="'.$movie['movie_name'].'"></a>';
    }
    $htmlContent = str_replace('填入轮播图填入轮播图',$imgHTML,$htmlContent);


    if(isset($_SESSION['user_id']) && isset($_SESSION['user_name'])){
        $userdata = getDataById($conn,$_SESSION['user_id']);
        $userURL = 'user.php?query_id='.$_SESSION['user_id'];
        $htmlContent = str_replace('替换为会话者id',$_SESSION['user_id'],$htmlContent);

        $origin = '<div id="loginInfo">
            <div class="user-info" id="login">游客,更多功能需要
                <a href="login.php"class="111">登录</a>
                <a href="Register.php">注册</a>
            </div>
        </div>';
        $hasLogin = '<div id="loginInfo">
                    <div class="user-info">
                    <div class="user-img">
                    <a href ="'.$userURL.'">
                    <img src="'.$userdata['profile_url'].'"alt="头像">
                    </ahref>
                    </div>
                    <div class="user-details">
                    <div class="user-name">你好,'.$userdata['user_name'].'</div>
                    <a href="public/logOut.php">注销</a>
                    </div></div></div>';
        $htmlContent = str_replace($origin,$hasLogin,$htmlContent);
    }else{
        $htmlContent = str_replace('替换为会话者id', -1, $htmlContent);
    }


    //由登录状态设置.





    echo $htmlContent;
}