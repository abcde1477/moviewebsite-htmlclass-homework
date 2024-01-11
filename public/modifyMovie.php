<?php
////////////////////////////
////////////////////////////
////////////未完工///////////
////////////////////////////
////////////////////////////


////////////////////////////
////////////////////////////
////////////未完工///////////
////////////////////////////
////////////////////////////
///

///在modifyUser的基础上进行修改。
///当
///当表单中的photos[]有文件,将会清空剧照,重新上传
///当表单中含有cover文件,将覆盖原有cover。
///attribution
/// movie_content同理。
///
/// 但是在前端上，一个更好的实现是photos[],cover可选
/// attribution和movie_content就算没有修改也直接把旧数据发过来即可。


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
    if (!isset($_POST['modify_movie'])) {
        $message = 'LackParam';//modify_movie是必备参数。
    } else {
        if (!ctype_digit($_POST['modify_movie'])) {
            $message = 'ERROR';//modify_movie的参数检查
        }
    }
    //$self_id = $_SESSION['user_id'];//不需要
    $modify_movie = $_POST['modify_movie'];

    if (!checkPermission(true,
        $SessionIsAdmin)) {
        $message = 'PermissionDeny';

    } else if ($message = 'Success') {
        $conn = new mysqli($servername, $username, $password,$dbName);
        if ($conn->connect_error) {
            echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
            exit();
        }

        //事先查找
        $checkIfExist = "SELECT * FROM $movieTableName WHERE id = $modify_movie";
        $result = $conn->query($checkIfExist);
        if ($result->num_rows == 0) {
            $message = 'NoFound';
        }
        else{
            $row = $result->fetch_assoc();
            $originCoverURL = $row['cover_url'];
            $originPhotoURL = $row['photo_file_url'];

            if (isset($_POST['movie_content'])&& $_POST['movie_content'] !='') {
                $content = $_POST['movie_content'];
                $stmt = $conn->prepare("UPDATE $movieTableName SET movie_content=? WHERE id=$modify_movie");// 假设使用id为1的用户作为示例
                $stmt->bind_param("s", $content);
                if ($stmt->execute()) {
                    $message = "Success";
                } else {
                    $message = "Error" . $conn->error;
                    echo $message;
                    exit();
                }
                $stmt->close();
            }
            if (isset($_POST['attribution']) && $_POST['attribution'] !='') {
                $attribution = $_POST['attribution'];
                $stmt = $conn->prepare("UPDATE $movieTableName SET attribution=? WHERE id=$modify_movie");// 假设使用id为1的用户作为示例
                $stmt->bind_param("s", $attribution);
                if ($stmt->execute()) {
                    $message = "Success";
                } else {
                    $message = "Error" . $conn->error;
                    echo $message;
                    exit();
                }
                $stmt->close();
            }


            if (isset($_FILES["cover"]) && $_FILES["cover"]["error"] == 0) {
                // 获取文件信息
                echo "封面修改 ";

                $file_name = $_FILES["cover"]["name"];
                $file_tmp = $_FILES["cover"]["tmp_name"];
                $file_size = $_FILES["cover"]["size"];
                $file_type = $_FILES["cover"]["type"];

                // 检查文件类型，可以根据需求添加其他限制条件
                $allowed_types = array("image/jpeg", "image/png", "image/gif");
                if (in_array($file_type, $allowed_types)) {
                    // 文件移动到指定目录
                    $deleteURL = "../" . $originCoverURL;
                    unlink($deleteURL);

                    $newURL =  "movie_file/$modify_movie" . "/".$file_name;

                    move_uploaded_file($file_tmp,"../".$newURL);


                    $stmt = $conn->prepare("UPDATE $movieTableName SET cover_url=? WHERE id=$modify_movie");
                    $stmt->bind_param("s", $newURL);
                    if ($stmt->execute()) {
                        $message = "Success";
                    } else {
                        $message = "Error" . $conn->error;
                        echo $message;
                        exit();
                    }
                    $stmt->close();
                } else {
                    $message = "WrongPictureFormat";
                }
            }
            echo $_FILES["cover"]["error"]." ";
            if (isset($_FILES["photos"]) &&$_FILES["photos"]["error"][0] ==0 && is_array($_FILES["photos"]["name"])) {
                //清空原有剧照
                echo "剧照修改 ";

                deleteFilesInDirectory("../".$originPhotoURL);

                $upload_dir = "movie_file/".$modify_movie."/photos"; // 上传目录
                for ($i = 0; $i < count($_FILES["photos"]["name"]); $i++) {
                    $file_name = $_FILES["photos"]["name"][$i];
                    $file_tmp = $_FILES["photos"]["tmp_name"][$i];
                    $destination = $upload_dir ."/".$file_name;
                    // 移动上传的文件到指定目录
                    if (move_uploaded_file($file_tmp, "../".$destination)) {
                        $message = "Success";
                    } else {
                        $message = "ErrorInPhoto";
                        echo $message;
                        exit();
                    }
                }
                $stmt = $conn->prepare("UPDATE $movieTableName SET photo_file_url=? WHERE id=$modify_movie");
                $stmt->bind_param("s", $upload_dir);
                if ($stmt->execute()) {
                    $message = "Success";
                } else {
                    $message = "Error" . $conn->error;
                    echo $message;
                    exit();
                }
                $stmt->close();
            }
        }
        $conn->close();
    }
    echo $message;
}

