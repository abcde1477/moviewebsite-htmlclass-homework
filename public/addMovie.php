<?php
//管理员专用页面，用于添加电影
//管理员专用页面，用于添加电影
//管理员专用页面，用于添加电影

////////////////////////////////
////////////////////////////////
/////////未完成//////////////////
////////////////////////////////
////////////////////////////////
include_once '../private/DBInit.php';
include_once '../private/verify.php';
include_once '../private/remove.php';
include_once '../private/DBSet.php';

/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

header('Content-Type: text/html');

//无所谓，无会话不应该进入此页面
//无会话则跳转至登录页面
//无会话则跳转至登录页面
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    /*$_SESSION['last_url']='addMovie.php';
    header('Content-Type: text/html');
    echo jumpPage('login.php','','<p>此页面必须登录后访问,将跳转到登录页面</p>');
    exit();*/
}
/*上下两处注释的功能被checkPermission替代*/
//验证是否为管理员
/*if(isset($_SESSION['admin_permission']) &&($_SESSION['admin_permission'] === true||$_SESSION['admin_permission'] === 'true')){
    //
}else{
    //不允许的访问，进入跳转页面
    echo jumpPage('index.php','','<p>您不是管理员用户,不能访问该页面，将跳转到主页</p>');
    exit();
}*/

$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;

if(!checkPermission(false,$SessionIsAdmin)){
    echo 'PermissionDeny';
    exit();
}else {
//GET方法
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        //登录页面
        //$htmlContent = file_get_contents('../html/addMovie.html');
        //echo $htmlContent;
        echo $movieTableName;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //表单处理
        ////////////////////////////////
        ////////////////////////////////
        /////////未完成//////////////////
        ////////////////////////////////
        //['movie_name'];必填
        //['attribution'];若不填，则 暂无信息
        //['cover_url'];若不上传，移动默认封面至电影文件区,并设置为默认封面url
        //['movie_content'];若为空，则设置为 暂无简介
        //['photo_file_url'];自动设置
        //['releaseTime'];必填,年月日
        //[''];
        $conn = new mysqli($servername, $username, $password,$dbName);
        if ($conn->connect_error) {
            echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
            exit();
        }
        if(isset($_POST['movie_name']) && $_POST['movie_name']!=""){
            //测有无
        }else{
            echo "LackName";
            exit();
        }
        $attribution = "暂无信息";
        if(isset($_POST['attribution']) && $_POST['attribution']!=""){
            $attribution = $_POST['attribution'];
        }
        $movie_content = "暂无简介";
        if(isset($_POST['movie_content']) && $_POST['movie_content']!=""){
            $movie_content =$_POST['movie_content'];
        }
        $releaseTime="";
        if(isset($_POST['releaseTime']) && $_POST['releaseTime']!=""){
            //测有无
            $releaseTime=$_POST['releaseTime']." 00:00:00";
        }else{
            echo "LackDate";
            exit();
        }
        $sql = "INSERT INTO $movieTableName (movie_name, attribution, movie_content, releaseTime) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        echo $releaseTime;
        $stmt->bind_param("ssss", $_POST['movie_name'], $attribution, $movie_content, $releaseTime);
        if ($stmt->execute()) {
            //
        } else {
            echo 'Error';
            exit();
        }
        $stmt->close();

        $id = mysqli_insert_id($conn);

        $cover_url="../movie_file/".$id;
        mkdir($cover_url, 0777, true);
        $photo_file_url="../movie_file/".$id."/photos";
        mkdir($photo_file_url, 0777, true);

        //通过UPDATE方式写入
        if(isset($_FILES['cover']) && $_FILES['cover']['error'] == 0){
            $cover_url = $cover_url."/".$_FILES['cover']['name'];
            $file_tmp = $_FILES["cover"]["tmp_name"];
            move_uploaded_file($file_tmp, $cover_url);
        }
        else{
            $cover_url=$cover_url."/default_cover.jpg";
            //准备移动默认。
            copy("../default/default_cover.jpg",$cover_url);
        }

        if(isset($_FILES['photos']) && $_FILES['photos']['error'][0] == 0){
            if (is_array($_FILES["photos"]["name"])) {
                //$_FILES["filename"]["name"][0] 来检测是否上传

                // 循环处理每个上传的文件
                for ($i = 0; $i < count($_FILES["photos"]["name"]); $i++) {
                    $file_name = $_FILES["photos"]["name"][$i];
                    $file_tmp = $_FILES["photos"]["tmp_name"][$i];
                    $file_size = $_FILES["photos"]["size"][$i];
                    $file_destination = $photo_file_url . "/".$file_name;

                    // 移动上传的文件到指定目录
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        //
                    } else {
                        echo "文件 $file_name 上传失败。$file_size";
                        exit();
                    }
                }
            } else {
                echo "ErrorInPhotos";
            }
        }
        $sql = "UPDATE $movieTableName SET cover_url='$cover_url', photo_file_url='$photo_file_url' WHERE id=$id";
        $conn->query($sql);

    }

}