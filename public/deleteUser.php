<?php
////////////////////////////
////////////////////////////
////////////未测试///////////
////////////////////////////
////////////////////////////a
include_once '../private/DBInit.php';
include_once '../private/DBSet.php';
include_once '../private/verify.php';
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
////////未登录

if (!isset($_SESSION['user_id'])) {
    echo 'NotLogIn';
    exit();
}


$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = 'Success';
    if (!(isset($_POST['delete_user']))) {
        $message = 'LackParam';
    } else {
        if (!ctype_digit($_POST['delete_user'])) {
            $message = 'ERROR';
        }
    }
    $self_id = $_SESSION['user_id'];
    $delete_user = $_POST['delete_user'];

    if (!checkPermission($self_id != $delete_user,
        $SessionIsAdmin)) {
        $message = 'PermissionDeny';
    } else if ($message = 'Success') {
        $conn = new mysqli($servername, $username, $password,$dbName);
        if ($conn->connect_error) {
            echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
            exit();
        }
        //事先查找
        $checkIfExist = "SELECT * FROM $userTableName WHERE id = $delete_user";
        $result = $conn->query($checkIfExist);

        if ($result->num_rows == 0) {
            $message = 'NoFound';
        } else {
            $row = $result->fetch_assoc();
            $originProfileURL = $row['profile_url'];
            //头像文件删除
            $deleteURL = "../" . $originProfileURL;
            unlink($deleteURL);
            //头像文件夹删除
            $deleteURL = "../user_file" . $delete_user;
            if (is_dir($deleteURL)) {
                rmdir($deleteURL);
            }
            //所有评论删除
            {
                $checkIfExist = "SELECT * FROM $commentTableName WHERE user_id = $delete_user";
                $result = $conn->query($commentTableName);
                $ReRating_movie_id = [];
                while ($row = $result->fetch_assoc()) {
                    $ReRating_movie_id[] = $row['movie_id'];
                }

                //删除所有评论
                $Delete = "DELETE FROM $commentTableName WHERE user_id = $delete_user";
                $result = $conn->query($Delete);

                //重新计算所有的电影评分
                foreach ($ReRating_movie_id as $movie_id) {
                    reRating($conn, $movie_id);//$movie_id就是整数
                }
            }
            //删除用户
            $Delete = "DELETE FROM $userTableName WHERE user_id = $delete_user";
            $result = $conn->query($Delete);
        }
        $conn->close();
    }
}