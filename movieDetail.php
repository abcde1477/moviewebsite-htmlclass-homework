<?php
include_once 'private/DBInit.php';
include_once 'private/DBGet.php';
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
echo "会话状态:<br>";
var_dump($_SESSION);
function getStringBetween($str, $begin, $end) {
    $start = strpos($str, $begin);
    if ($start === false) {
        return false;
    }
    $start += strlen($begin);

    if($end == '_END'){
        $end =strlen($str);
    }else {
        $end = strpos($str, $end, $start);
        if ($end === false) {
            return false;
        }
    }
    return substr($str, $start, $end - $start);
}
if($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['query_id']) && is_numeric($_GET['query_id'])) {
        $conn = new mysqli($servername, $username, $password,$dbName);
        if ($conn->connect_error)
            $message = "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;



        $query_id=intval($_GET['query_id']);
        $result = getMovieById($conn,$query_id);

        header('Content-Type: text/html');
        $htmlContent = file_get_contents('html/movie.html');

        $movieIdPosition = '<input type="hidden" id="movie_id" name="movie_id" value="-1"/>';
        $htmlContent = str_replace($movieIdPosition,'<input type="hidden" id="movie_id" name="movie_id" value="'.$result['id'].'"/>',$htmlContent);

        $user_id =-1;
        if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
        }
        $movieIdPosition = '<input type="hidden" id="user_id" name="user_id" value="-1"/>';
        $htmlContent = str_replace($movieIdPosition,'<input type="hidden"  id="user_id" name="user_id" value="'.$user_id.'"/>',$htmlContent);




        $htmlContent = str_replace('在这里填入电影封面url这里是电影详情页电影封面url填入处',$result['cover_url'],$htmlContent);
        $htmlContent = str_replace('在这里填入电影名这里是电影详情页电影名填入处',$result['movie_name'],$htmlContent);




        $rating = number_format(intval($result['rating']) / 10,1);

        $htmlContent = str_replace('9.7',$rating,$htmlContent);
        $htmlContent = str_replace('2968582人评价',$result['comment_number'].'人评价',$htmlContent);



        $str = $result['attribution'];
        $directorPiece = getStringBetween($str, '导演：', '，');
        $htmlContent = str_replace('在这里填入导演这里是电影详情页导演填入处',$directorPiece,$htmlContent);
        $mainActorPiece = getStringBetween($str, '主演：', '_END');
        $htmlContent = str_replace('在这里填入主演这里是电影详情页主演填入处',$mainActorPiece,$htmlContent);


        $releaseDate = $result['releaseTime'];
        $releaseDate = strstr($releaseDate, ' ', true);
        $htmlContent = str_replace('在这里填入上映时间这里是电影详情页上映时间填入处',$releaseDate,$htmlContent);


        $displayPhotos ='<ul class="related-pic-bd  " id="photos">';
        for($i = 0;$i < count($result['photos']);$i++) {
            $displayPhotos .= '
                <a href="#"><img class="photos" src="'.$result['photos'][$i].'" alt="图片" ></a>
            <br>';
        }
        $displayPhotos.='</ul>';
        $htmlContent = str_replace('剧照填入',$displayPhotos,$htmlContent);



        $htmlContent = str_replace('在这里填入简介这里是电影详情页简介模板',$result['movie_content'],$htmlContent);




        echo $htmlContent;
        exit();
    }
}
