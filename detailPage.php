<?php
include_once 'private/DBInit.php';
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
$logStat = isset($_SESSION['user_id']);
$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;


if($_SERVER['REQUEST_METHOD'] == 'GET'){

    $movie_id = $_GET['movie_id'];
    $_SESSION['last_url'] = "detailPage.php?movie_id=$movie_id";

    $conn = new mysqli($servername, $username, $password,$dbName);
    if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
    if($logStat){
        $haveComment = ExistComment($conn,$_SESSION['user_id']);
    }

    $SQL = "SELECT * FROM $movieTableName WHERE id = $movie_id";
    $result = $conn->query($SQL);
    $row = $result->fetch_assoc();
    //////////$rating是字符串！
    $decade_rating = $row['rating'];
    $rating = number_format((float)$decade_rating/ 10.0, 1);
    //////////$rating是字符串！
    $photos = [];
    $directory = $row['photo_file_url'];
    $files = scandir('../'.$directory);   //如果无??
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $fileUrl = $directory . '/' . $file;
            $photos[] = $fileUrl;
        }
    }
    $movie[] = [
        'id' => $row['id'],
        'rating' => $rating,
        'movie_name' => $row['movie_name'],
        'attribution' => $row['attribution'],
        'movie_content' => $row['movie_content'],
        'cover_url' => $row['cover_url'],
        'releaseTime' => $row['releaseTime'],
        'photos' => $photos
    ];
    /////////////开始生成页面////////////
    //通过$movie生成
    echo '<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>body {
        background-size: cover;
        background: url('.'css/images/back6.jpg'.') no-repeat fixed  top;
        background-size: 100% 100%;
        position: static;
        margin-left: 500px;
        font-size: 17px;
        line-height: 1.3;
        color: black;
        padding: 0;
        display: flex;
        flex-direction: column;

    }

    img{
        height: 220px;
        width: 220px;
        background-size: cover;
        background-repeat: no-repeat;
    }
    h1{
        color: white;
    }
    html h1 {
        font-size: 26px;
    }
    .article {
        border: 3px solid #000000;
        border-radius: 10px;
        padding-right: 25px;
        width: 675px;
        background-color: rgba(187, 231, 232, 0.7);
        flex: 1;
    }
    .year {
        zoom: 1;
        display: inline-block;
    }
    .grid-16-8  {
        float: left;
        width: 675px;
        padding-right: 40px;
    }
    .clearfix {
        display: block;
    }
    .indent {
        word-break: normal;
        word-wrap: break-word;
    }
    .subjectwrap {
        float: none;
        width: auto;
        height:250px;
        position: relative;
        margin-bottom: 15px;
    }
    .subject {
        width: 500px;
        float: left;
    }
    #mainpic {
        width: 143px;
        float: left;
        text-align: center;
        margin: 3px 12px 0 0;
        max-width: 155px;
        overflow: hidden;
    }
    #info {
        max-width: 333px;
        float: left;
        word-wrap: break-word;
    }
    .pl {
        font: 12px Arial, Helvetica, sans-serif;
        line-height: 150%;
        color: #666666;
    }
    #interest_sectl {
        float: left;
        width: 155px;
        margin: 2px 0 0 0;
        padding: 0 0 0 15px;
        border-left: 1px solid #eaeaea;
        color: #000000;
        font-size: 18px;
    }

    .ll {
        float: left;
    }
    .rating_right {
        float: left;
        padding: 10px 0 10px 6px;
    }
    .rating_self::after {
        content: ;
        display: block;
        clear: both;
    }
    .starstop {
        float: left;
        margin-right: 5px;
    }
    .power {
        height: 10px;
        float: left;
        margin: 1px 4px;
        background: #ffd596 none repeat scroll 0 0;
    }
    .rating_per {
        font-size: 11px;
    }
    a {
        cursor: pointer;
    }
    i {
        font-style: normal;
        font-weight: normal;
    }
    h2 {
        color: #007722;
        font: 15px Arial, Helvetica, sans-serif;
        line-height: 150%;
    }
    .intro{
        float:none;
    }
    .related-pic {
        margin-bottom: 30px;
    }
    .related-pic-bd {
        font-size:0;
        text-align:justify;
        text-justify:distribute
    }
    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .label-trailer {
        position: relative;
    }

    .related-pic-bd img {
        height: 115px;
        width: 115px;
    }
    a img {
        border-width: 0;
        vertical-align: middle;
    }
    .related-pic-bd li {
        display: inline-block;
        height: 115px;
        vertical-align: top;
    }
    .comment {
        position: relative;

        display: flex;
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }

    .sortOptions {
        text-align: right;
        margin-bottom: 20px;
    }
    #comments {
        margin-top: 20px;
    }
    .comment {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
    .star {
        align-items: center;
    }

    .rating5-t::before {
        content: '.'★★★★★'.';
        letter-spacing: 2px;
        display: inline-block;
        width: var(--rating, 0); /* 星星的宽度由--rating变量控制 */
        overflow: hidden;
        color: #ffd700;
    }

    .rating_num {
        margin-left: 5px;
        color: #ff0000;
        font-size: 30px;
    }
    </style>
</head>
<body>
<style>
    body
    {
    background-size: cover;
    background: url('.'css/images/11111.png'.') no-repeat fixed top;
    background-size: 100% 100%;
}
</style>
<h1>
';
    echo '<span id="moive">'.$movie['$movie_name'].'</span>';
    echo '<span id="time">'.substr($movie['releaseTime'], 0, 4).'/span>';

    echo '</h1>

<div class="article">
    <div class="indent clearfix">
        <div class="subjectwrap clearfix">
            <div class="subject clearfix">
            <div id="mainpic" class="">';

    echo '<img src='.$movie['cover_url'].' alt="cover" id="picture">';
    echo '</div>
            <div id="info">
            </div>
        </div><div id="interest_sectl">
        <div class="rating_wrap clearbox" rel="v:rating">
        <div class="clearfix">
            <div class="rating_logo ll">
                评分
            </div>
            <div class="star">';
    echo '<span class="rating5-t" style="--rating: calc('.$movie['rating'].' / 10 * 100%)"></span>
                <span class="rating_num" property="v:average" id="rating">'.$movie['rating'].'分</span>
                <span property="v:best" content="10.0"></span>            </div>
        </div>
        </div>
            </div>
            </div>
    </div>
    <div class="intro" style="margin-bottom:-10px;">
        <h2>
            <i class="" >剧情简介</i>
            · · · · · ·
        </h2>';

    ///////////生成空评论区///////////
    if(!$logStat){
        ///点我登录
    }
    //////////生成点我评论///////
    if($logStat){
        if($haveComment){
            //生成1.我的评论
            echo"
            <!--此处写我的评论-->
            <!--此处写我的评论-->
            <script>
                let data =
                {
                    idType:'user',//'user'
                    query_id:".$_SESSION['$user_id'].",
                    sort_by:'rating',
                    sort_order:'decrease',
                    from:1,
                    to:255
                }
                    fetch('/public/getComment.php',{
                        method: 'post',
                        body: data
                    }).then(data => data.json())
                        .then(jsData => {
                            if(jsData['errorMessage'] === 'NoError')
                            {
                                let comment_content = '';
                                let rating ='';
                                let comment_time = '';
                                for (let i = 0; i < 255; i++) {
                                    comment_time = jsData['comments'][i]['comment_content'];
                                    rating = jsData['comments'][i]['rating'];
                                    comment_content = jsData['comments'][i]['comment_time'];
                                    if (".$movie_id." == jsData['comments'][i]['movie_id']) break;
                                }
                                
                                //在这里将pageNum写入html
                                //在这里将pageNum写入html
                            }else if(jsData['errorMessage'] === 'NoFound'){
                                alert('错误');
                            }
                        });
            </script>
            ";
        }else{
            //生成2，我来评论
            echo"
            
            
            
            
            
            ";

        }
    }
}
