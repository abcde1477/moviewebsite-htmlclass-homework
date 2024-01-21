<?php
include_once '../private/DBInit.php';
include_once '../private/verify.php';
include_once "../private/DBSet.php";
include_once "../private/generateJumpPage.php";

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

header('Content-Type: text/html');
$user_id = 0;


if(isset($_SESSION['user_id'])){
    $user_id =$_SESSION['user_id'];
}else{
    echo 'NotLogIn';
    exit();
}

$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;

if(!checkPermission(false,$SessionIsAdmin)){
    echo 'Exception,error in checkPermission';
    exit();
}else {


    $conn = new mysqli($servername, $username, $password,$dbName);
    if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $message = 'Success';
        if (!(
            isset($_POST['movie_id']) &&
            isset($_POST['comment_content']) &&
            isset($_POST['rating'])
        )) $message = 'LackParam';
        $movie_id = $_POST['movie_id'];
        $comment = $_POST['comment_content'];
        $rating = $_POST['rating'];
        $decade_rating = 100;
        ///
        if(preg_match('/^[0-9.]*$/', $rating)) {
            $floatNumber = floatval($rating);
            if($floatNumber<= 100 && $floatNumber>=0){
                $decade_rating = (int)($floatNumber * 10);
            }else{
                echo 'Error';
                exit();
            }
        }///
        header('content-Type:text/html');
        $message = addComment($conn,$movie_id,$user_id,$comment,$decade_rating);
        echo $message;
        exit();
        $lastURL = isset($_SESSION['last_url'])?$_SESSION['last_url']:"index.php";
        echo jumpPage($lastURL,"","评论成功，将在3秒后跳转至上一页面");
    }
    $conn->close();
}