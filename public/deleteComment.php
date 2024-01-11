<?php
include_once 'private/DBInit.php';

/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

//                $_SESSION['user_name'] =$row['user_name'];
//                $_SESSION['user_id'] =$row['id'];
//                $_SESSION['admin_permission'] =$row['isAdmin'];

//I:请求
//O:字符串


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
////////未登录
if(isset($_SESSION['user_id'])){
    $selfId =$_SESSION['user_id'];
}else{
    echo 'NotLogIn';
    exit();
}
$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $message = 'Success';
    if (!(isset($_POST['user_id']) && isset($_POST['movie_id']))) {
        $message = 'LackParam';
    }else{
        if(!(ctype_digit($_POST['user_id']) && ctype_digit($_POST['movie_id']))){
            $message = 'ERROR';
        }
    }//保证了user_id，movie_id表单为数字

    $self_id = $_SESSION['user_id'];
    $modify_user =$_POST['user_id'];
    $movie_of_comment = $_POST['movie_id'];

    if(!checkPermission($self_id != $modify_user,
        $SessionIsAdmin)){
        $message= 'PermissionDeny';
    }else if($message = 'Success') {
        $conn = new mysqli($servername, $username, $password,$dbName);
        if ($conn->connect_error) {
            echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
            exit();
        }
        //事先查看是否存在
        $checkIfExist= "SELECT * FROM $commentTableName WHERE user_id = $modify_user AND movie_id = $movie_of_comment";

        $result = $conn->query($checkIfExist);
        if($result->num_rows == 0){
            $message = 'NoFound';
        }else {
            //删除评论
            $Delete = "DELETE FROM $commentTableName WHERE user_id = $modify_user AND movie_id = $movie_of_comment";
            $result = $conn->query($Delete);
            reRating($conn,intval($movie_of_comment));
        }
        $conn->close();
    }
    echo $message;
}
