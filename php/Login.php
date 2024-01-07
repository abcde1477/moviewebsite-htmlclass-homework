<?php
include_once 'DBInit.php';
include_once 'getUserData.php';
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
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])) {

        echo "欢迎回来，" . $_SESSION["user_name"];
        if (isset($_SESSION['last_url'])){
            echo "稍后将返回至上一页面";
        }else{
            echo "将稍后返回主页";
        }
    }else{
        //生成登录页面
        echo '';

    }
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name= $_POST['username'];
    $pw= $_POST['$password'];
    $message ='';
    if (preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
        if (preg_match('/^[a-zA-Z0-9_]+$/', $pw)) {
            $conn = new mysqli($servername, $username, $password);
            if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
            $name = mysqli_real_escape_string($conn, $name);
            $pw = mysqli_real_escape_string($conn, $pw);

            $sql = "SELECT * FROM $userTableName WHERE username='$name' AND password='$pw'";
            $result = $conn->query($sql);
            if($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $message = "欢迎您";
                $_SESSION['user_name'] =$row['user_name'];
                $_SESSION['user_id'] =$row['id'];
            }
            else if($result->num_rows == 0){
                $message ="用户名或密码不正确";
            }else{
                $message ="数据库错误,请联系管理员";
            }
        }
    } else {
        //按理来说,username，password需要用JavaScript进行检查.对用户的提醒也应该在前端进行
        $message ="username或password的格式不合法";
    }
    header('content-Type:text/html');
    echo $message;
}
?>