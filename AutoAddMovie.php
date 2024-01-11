<?php
include_once 'private/DBInit.php';
include_once 'private/DBSet.php';

/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

$jsonData = file_get_contents('testData/MovieDataJSON/movieData.json');

// 将JSON数据转换为PHP数组
$phpArray = json_decode($jsonData, true);

// 检查是否成功解析JSON
if ($phpArray === null && json_last_error() !== JSON_ERROR_NONE) {
    die('Error decoding JSON: ' . json_last_error_msg());
}else{
    $conn = new mysqli($servername, $username, $password,$dbName);
    if ($conn->connect_error) {
        echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
        exit();
    }
    foreach ($phpArray as $item) {
        $movie_name = $item['movie_name'] . PHP_EOL;
        $attribution = $item['attribution'] . PHP_EOL;
        $movie_content = $item['movie_content'] . PHP_EOL;
        $releaseTime = $item['releaseTime'] . PHP_EOL;
        //添加电影
        addMovie($conn,$movie_name,$attribution,$movie_content,$releaseTime);
        $id = mysqli_insert_id($conn);
        $cover_url="movie_file/".$id;
        mkdir($cover_url, 0777, true);
        $photo_file_url="movie_file/".$id."/photos";
        mkdir($photo_file_url, 0777, true);
        //选择随机封面。
        $RandomCover = "cover(".rand(1,9).").png";
        $cover_url = $cover_url."/".'cover.png';
        $originCover = "testData/RandomMovieCover"."/".$RandomCover;
        copy($originCover, $cover_url);

        //填入剧照
        $numbers = range(1, 9);
        shuffle($numbers); //随机排序
        $selectedNumbers = array_slice($numbers, 0, 3); // 从数组中取前4个数字

        $originPhoto ="testData/RandomSelectPhotos";
        for ($i = 0; $i < 3; $i++) {
            $fileName = "photo(" . $selectedNumbers[$i] . ").png";
            copy($originPhoto . "/" . $fileName, $photo_file_url . "/" . $fileName);
        }
        //
        $sql = "UPDATE $movieTableName SET cover_url='$cover_url', photo_file_url='$photo_file_url' WHERE id=$id";
        $conn->query($sql);
    }
}
