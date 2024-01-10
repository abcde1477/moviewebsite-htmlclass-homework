<?php
include_once '../private/DBInit.php';
include_once '../private/verify.php';
/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

///////////
header('Content-Type: text/html');
$query_id = 0;



if(isset($_SESSION['user_id'])){
    $query_id =$_SESSION['user_id'];
}else{
    echo 'NotLogIn';
    exit();
}


if(!checkPermission(false,$_SESSION['admin_permission'])){
    echo 'Exception,error in checkPermission';
    exit();
}else {


    $conn = new mysqli($servername, $username, $password);
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

        //查找电影是否存在
        $checkId = "SELECT * FROM $movieTableName WHERE id = $movie_id LIMIT 1";
        $result = $conn->query($checkId);
        if ($result->num_rows == 0)
            $message = 'NoFound';

        if ($message == 'Success') {
            $checkCollision = "SELECT * FROM $commentTableName WHERE user_id = $query_id AND movie_id = $movie_id LIMIT 1";
            $result = $conn->query($checkCollision);
            if ($result->num_rows == 0) {
                //写入评论
                $sql = "INSERT INTO $commentTableName (movie_id, user_id, comment, rating) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                $stmt->bind_param("iisi", $movie_id, $query_id, $comment, $decade_rating);
                if ($stmt->execute()) {
                    $message = 'Success';
                } else {
                    $message = 'Error';
                }

                $stmt->close();
                //更新电影评分

                reRating($conn, intval($movie_id));
            } else {
                $message = 'Collision';
            }
        }
        echo $message;
    }
    $conn->close();
}