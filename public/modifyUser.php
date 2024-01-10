<?php
////////////////////////////
////////////////////////////
////////////未完工///////////
////////////////////////////
////////////////////////////
include_once '../private/DBInit.php';
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

if(!isset($_SESSION['user_id'])){
    echo 'NotLogIn';
    exit();
}


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $message = 'Success';
    if (!isset($_POST['modify_user'])) {
        $message = 'LackParam';
    }else{
        if(!ctype_digit($_POST['modify_user'])){
            $message = 'ERROR';
        }
    }
    $self_id = $_SESSION['user_id'];
    $modify_user =$_POST['modify_user'];

    if(!checkPermission($self_id != $modify_user,
        $_SESSION['admin_permission'])){
        $message= 'PermissionDeny';
    }
    else if($message = 'Success'){
        $conn = new mysqli($servername, $username, $password);
        if ($conn->connect_error) {
            echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
            exit();
        }

        //事先查找
        $checkIfExist= "SELECT * FROM $userTableName WHERE id = $modify_user";
        $result = $conn->query($checkIfExist);

        if($result->num_rows == 0){
            $message = 'NoFound';
        }else {
            $row = $result->fetch_assoc();
            $originProfileURL = $row['profile_url'];
            if (isset($_POST['homepage_content'])) {
                $content = $_POST['homepage_content'];

                $stmt = $conn->prepare("UPDATE $userTableName SET homepage_content=? WHERE id=$modify_user");// 假设使用id为1的用户作为示例
                $stmt->bind_param("s", $content);
                if ($stmt->execute()) {
                    $message = "Success";
                } else {
                    $message = "Error" . $conn->error;
                }
                $stmt->close();
            }
            if (isset($_FILES["profileFile"]) && $_FILES["profileFile"]["error"] == 0) {
                // 获取文件信息
                $file_name = $_FILES["profileFile"]["name"];
                $file_tmp = $_FILES["profileFile"]["tmp_name"];
                $file_size = $_FILES["profileFile"]["size"];
                $file_type = $_FILES["profileFile"]["type"];

                // 检查文件类型，可以根据需求添加其他限制条件
                $allowed_types = array("image/jpeg", "image/png","image/gif");
                if (in_array($file_type, $allowed_types)) {
                    // 文件移动到指定目录
                    $deleteURL = "../".$originProfileURL;
                    unlink($deleteURL);
                    move_uploaded_file($file_tmp, "user_file/$modify_user".$file_name);
                    $message = "Success";
                } else {
                    $message = "WrongPictureFormat";
                }
            }
        }
        $conn->close();
    }
}

