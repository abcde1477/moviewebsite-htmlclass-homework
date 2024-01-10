<?php
include_once 'private/DBInit.php';
/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

//注册页面以及注册表单处理
//注册页面以及注册表单处理
//注册页面以及注册表单处理
///////////////////////////////////
///////register.html前端没有完成/////
///////////////////////////////////


///////////////////////////////////
///////register.php没有完成/////
///////////////////////////////////
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
///////GET///////////
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])) {
        $UrlToJump ='';
        //已经登录,还用GET访问此页面,是不允许的方式,将回到上一页面
        if (isset($_SESSION['last_url'])){
            $UrlToJump = $_SESSION['last_url'];
        }else{
            $UrlToJump = 'index.php';
        }
        //不允许的访问，进入跳转页面
        header('Content-Type: text/html');
        echo jumpPage($UrlToJump,'','<p>您已经登录,稍后将返回至上一页面</p>');
        exit();
    }else{
        //生成登录页面
        $htmlContent = file_get_contents('../html/register.html');
        header('Content-Type: text/html');
        echo $htmlContent;
        exit();
    }
}
///////GET///////////

///////POST///////////
if($_SERVER["REQUEST_METHOD"] == "POST"){
    header('Content-Type: text/html');
    $message = 'Success';
    if (!(
        isset($_POST['username']) &&
        isset($_POST['password'])
    )) $message = 'LackParam';

    $un = $_POST['username'];
    $pw = $_POST['password'];

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error)
        $message = "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;

    if($message == 'Success') {
        $sql = "INSERT INTO $userTableName (user_name, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $un, $pw);
        if ($stmt->execute()) {
            $message = 'Success';
        } else {
            $message = 'Error';
        }
        //新建文件夹用于存储头像
        $stmt->close();
        $id = mysqli_insert_id($conn);
        $relativePath = "user_file/$id";
        if (!file_exists($relativePath)) {
            mkdir($relativePath, 0777, true); // 第一个参数是路径，第二个参数是权限，第三个参数是递归创建文件夹
        }
        $sourcePath = "default/default_cover.jpg";
        $destinationPath = "user_file/$id/profile.jpg";
        copy($sourcePath, $destinationPath);


        $query_update = "UPDATE $userTableName SET image_url = '$destinationPath' WHERE id = $id";
        $result_update = mysqli_query($conn, $query_update);


        $conn->close();
    }
    echo $message;
}

///////POST///////////
/*if (isset($_SESSION['last_url'])){
    echo "注册成功,欢迎".$_SESSION['username'].",稍后将返回至上一页面";
}else{
    echo "注册成功,欢迎".$_SESSION['username'].",将稍后返回主页";
}*/