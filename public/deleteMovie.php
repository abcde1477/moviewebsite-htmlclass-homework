<?php
////////////////////////////
////////////////////////////
////////////未完工///////////
////////////////////////////
////////////////////////////
include_once '../private/DBInit.php';
include_once '../private/verify.php';
include_once '../private/remove.php';

/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */
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
}

echo "会话状态:<br>";
var_dump($_SESSION);

$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;
if(!checkPermission(true,$SessionIsAdmin)) {
    echo 'PermissionDeny';
    exit();
}else{
    echo 'Access ';
}



if($_SERVER["REQUEST_METHOD"] == "GET"){

}



if($_SERVER["REQUEST_METHOD"] == "POST"){
    echo 'POST ';
    //检查会话状态,admin_permission为false则返回
    $SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;
    if(!checkPermission(true,$SessionIsAdmin)){
        echo 'PermissionDeny';
        exit();
    }else{
        $conn = new mysqli($servername, $username, $password,$dbName);
        if ($conn->connect_error) {
            echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
            exit();
        }
        if (isset($_POST['delete_movie_id']) && $_POST['delete_movie_id']!='' && ctype_digit($_POST['delete_movie_id'])) {
            echo 'formDataTypeCorrect ';
            $delete_movie_id = $_POST['delete_movie_id'];
            //事先检查存在与否
            $checkIfExist= "SELECT * FROM $movieTableName WHERE id =$delete_movie_id";
            $result = $conn->query($checkIfExist);

            if($result->num_rows == 0){
                echo 'NoFound';
                exit();
            }else {
                echo 'beginDelete ';
                //删除文件夹
                $Dir = "../movie_file/".$delete_movie_id;
                deleteFilesInDirectory($Dir);
                rmdir($Dir);

                //删除电影表
                $DeleteMovie = "DELETE FROM $movieTableName WHERE id = $delete_movie_id";
                $result = $conn->query($DeleteMovie);

                //删除与之相关的评论
                $DeleteComment = "DELETE FROM $commentTableName WHERE movie_id = $delete_movie_id";
                $result = $conn->query($DeleteComment);

            }
        }else{
            echo 'LackParam';
            exit();
        }
        $conn->close();
    }
}