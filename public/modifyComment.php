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
    //$selfId =$_SESSION['user_id'];
}else{
    echo 'NotLogIn';
    exit();
}


$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $message = 'Success';
    if (!(isset($_POST['modify_user']) && isset($_POST['movie_id']))) {
        $message = 'LackParam';
    }else{
        if(!(ctype_digit($_POST['modify_user']) && ctype_digit($_POST['movie_id']))){
            $message = 'ERROR';
        }
    }//保证了modify_user，movie_id表单为数字

    $self_id = $_SESSION['user_id'];
    $modify_user =$_POST['modify_user'];
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
            if (isset($_POST['rating']) && $_POST['rating']!='') {
                $rating = $_POST['rating'];
                //rating到decade_rating
                $decade_rating = 100;
                if (preg_match('/^[0-9.]*$/', $rating)) {
                    $floatNumber = floatval($rating);
                    if ($floatNumber <= 100 && $floatNumber >= 0) {
                        $decade_rating = (int)($floatNumber * 10);
                    } else {
                        $message = 'Success';
                    }
                }
                // 使用参数化查询
                if ($message == 'Success') {
                    $stmt = $conn->prepare("UPDATE $commentTableName SET rating=? WHERE user_id=$modify_user AND movie_id = $movie_of_comment");// 假设使用id为1的用户作为示例
                    $stmt->bind_param("i", $decade_rating);
                    if ($stmt->execute()) {
                        $message = "Success";
                    } else {
                        $message = "Error" . $conn->error;
                    }
                    $stmt->close();
                    reRating($conn,intval($movie_of_comment));
                }
            }
            if (isset($_POST['comment_content']) && $_POST['comment_content']!='') {
                $comment_content = $_POST['comment_content'];

                $stmt = $conn->prepare("UPDATE $commentTableName SET comment=? WHERE user_id=$modify_user AND movie_id = $movie_of_comment"); // 假设使用id为1的用户作为示例
                $stmt->bind_param("s", $comment_content);
                if ($stmt->execute()) {
                    $message = "Success";
                } else {
                    $message = "Error" . $conn->error;
                }
                // 关闭语句
                $stmt->close();
            }
        }
        $conn->close();
    }
    echo $message;
}
