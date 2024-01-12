<?php
    include_once 'DBInit.php';
    /** @var string $servername */
    /** @var string $username */
    /** @var string $password */
    /** @var string $dbName */
    /** @var string $movieTableName */
    /** @var string $commentTableName */
    /** @var string $userTableName */


function addMovie($conn,$movie_name, $attribution, $movie_content, $releaseTime){
    $movieTableName = 'movies';
    $sql = "INSERT INTO $movieTableName (movie_name, attribution, movie_content, releaseTime) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss",$movie_name, $attribution, $movie_content, $releaseTime);
    if ($stmt->execute()) {
        //
    } else {
        echo 'Error';
        exit();
    }
    $stmt->close();

}
function addComment($conn,$movie_id,$user_id,$comment,$decade_rating){
    $message ='Success';
    $movieTableName ='movies';
    $commentTableName ='comments';
    //查找电影是否存在
    $checkId = "SELECT * FROM $movieTableName WHERE id = $movie_id LIMIT 1";
    $result = $conn->query($checkId);
    if ($result->num_rows == 0)
        $message = 'NoFound';

    if ($message == 'Success') {
        $checkCollision = "SELECT * FROM $commentTableName WHERE user_id = $user_id AND movie_id = $movie_id LIMIT 1";
        $result = $conn->query($checkCollision);
        if ($result->num_rows == 0) {
            //写入评论
            $sql = "INSERT INTO $commentTableName (movie_id, user_id, comment, rating) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            $stmt->bind_param("iisi", $movie_id, $user_id, $comment, $decade_rating);
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
    return $message;
}

function register($conn,$un, $pw,$isAdmin = false){//仅在根目录使用
    $userTableName = 'users';
    $AdminInt = ($isAdmin)?1:0;
    $sql = "INSERT INTO $userTableName (user_name, password,isAdmin) VALUES (?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $un, $pw,$AdminInt);
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
    $sourcePath = "default/default_profile.jpg";
    $destinationPath = "user_file/$id/profile.jpg";
    copy($sourcePath, $destinationPath);


    $SqlSearchComments = "SELECT register_time FROM $userTableName WHERE id = $id";
    $result = mysqli_query($conn, $SqlSearchComments);
    $row = $result->fetch_assoc();
    $homepage_content = "注册于".$row['register_time'];
    //注册于register_time
    $query_update = "UPDATE $userTableName SET homepage_content = '$homepage_content' WHERE id = $id";
    $result_update = mysqli_query($conn, $query_update);

    $query_update = "UPDATE $userTableName SET profile_url = '$destinationPath' WHERE id = $id";
    $result_update = mysqli_query($conn, $query_update);
    $_SESSION['user_name'] = $un;
    $_SESSION['user_id'] = $id;
    $_SESSION['admin_permission'] = $isAdmin;
}
function reRating($conn_p,$movie_id){
    $commentTableName = "comments";
    $movieTableName = "movies";
    $decadeRatingArray=[];
    $sql = "SELECT rating FROM $commentTableName WHERE movie_id = $movie_id";
    $result = $conn_p->query($sql);

    while ($row = $result->fetch_assoc()) {
        $decadeRatingArray[] = $row['rating'];
    }
    if (empty($decadeRatingArray)) {
        $average = 0;
    } else {
        $average = floor(array_sum($decadeRatingArray) / count($decadeRatingArray));
    }

    $sql = "UPDATE $movieTableName SET rating = $average WHERE id = $movie_id";
    $conn_p->query($sql);
}