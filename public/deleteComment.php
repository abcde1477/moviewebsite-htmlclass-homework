<?php
include_once '../private/DBInit.php';
include_once '../private/verify.php';
include_once '../private/DBSet.php';


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

$message = 'Success';
$self_id = $_SESSION['user_id'];
$modify_user=0;
$movie_of_comment=0;
if($_SERVER['REQUEST_METHOD'] == "POST"){
    if (!(isset($_POST['user_id']) && isset($_POST['movie_id']))) {
        $message = 'LackParam';
    }else{
        if(!(ctype_digit($_POST['user_id']) && ctype_digit($_POST['movie_id']))){
            $message = 'ERROR';
        }
    }//保证了user_id，movie_id表单为数字

    $modify_user =$_POST['user_id'];
    $movie_of_comment = $_POST['movie_id'];


}else if($_SERVER['REQUEST_METHOD'] == "GET"){
    if (!(isset($_GET['user_id']) && isset($_GET['movie_id']))) {
        $message = 'LackParam';
    }else{
        if(!(ctype_digit($_GET['user_id']) && ctype_digit($_GET['movie_id']))){
            $message = 'ERROR';
        }
    }//保证了user_id，movie_id表单为数字


    $modify_user =$_GET['user_id'];
    $movie_of_comment = $_GET['movie_id'];

}


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
echo '<br><a href="../home.php" >回到主页</a>';