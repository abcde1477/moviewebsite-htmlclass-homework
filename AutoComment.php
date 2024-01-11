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

function autoComment($conn,$UserFrom,$UserTo,$MovieFrom,$MovieTo){
    $commentArray =[
        "这部电影真是太精彩了，情节扣人心弦，演员演技一流。",
        "影片画面很美，故事情节设计巧妙，值得一看。",
        "感动得泪流满面，剧情真实感人。",
        "导演的执导水平真的很高，给人留下深刻印象。",
        "音乐搭配恰到好处，为整部电影增色不少。",
        "电影节奏紧凑，让人一直紧张到底。",
        "情节反转出乎意料，让观众始终保持好奇心。",
        "演员的表演令人印象深刻，每个角色都饱含深意。",
        "影片的画面设计别具匠心，令人留连忘返。",
        "这部电影给人一种独特的观影体验，非常推荐！",
        "剧情跌宕起伏，让人一直猜测下文发展。",
        "影片场景布置非常用心，每一帧都让人沉浸其中。",
        "主题深刻，观后让人思考很多人生问题。",
        "电影中的情感表达非常真实，触动人心。",
        "这部影片既有深度又充满娱乐性，非常值得一看。",
        "这部电影的情节发展十分出奇不意，令人难以预料。",
        "演员们的表演技巧令人叹为观止，真实感十足。",
        "导演对细节的把握非常到位，每一个画面都令人震撼。",
        "音乐的运用让整个电影更富有层次感，令人陶醉。",
        "剧情紧凑但不失深度，是一部值得深思的佳作。",
        "这部电影给人一种耳目一新的感觉，让人难以忘怀。",
        "演员的表演充满力量，每一个角色都独具特色。",
        "影片画面简直是艺术品般的存在，美不胜收。",
        "剧情紧凑而引人入胜，令人屏息凝视。",
        "音效的运用让整个电影充满了神秘感，令人沉浸其中。",
        "这部电影情节跌宕起伏，让人瞬间被吸引。",
        "演员的表演水平令人惊叹，每个角色都深具魅力。",
        "导演的镜头运用别出心裁，给人耳目一新的感觉。",
        "影片中的配乐悠扬动听，为整体氛围加分。",
        "剧情层次分明，每个细节都被精心打磨，令人回味无穷。",
        "电影中的情感描绘十分细腻，让人感同身受。",
        "影片的节奏把握得恰到好处，引人入胜。",
        "演员们的表演让人忍不住为之动容，感情真挚。",
        "导演对细节的把握非常出色，每一个画面都很有味道。",
        "这部电影无论是画面还是音乐都充满了艺术感，给人留下深刻印象。",
    ];
    $counter =0;
    $userNumber = $UserTo -$UserFrom+1;
    $movieNumber = $MovieTo -$MovieFrom+1;
    for ($i = 0; $i < $userNumber; $i++) {
        for ($j = 0; $j < $movieNumber; $j++) {
            addComment($conn,$j,$i,$commentArray[$counter],rand(0, 100));
            sleep(1);
            $counter++;
            if($counter > count($commentArray)-4) $counter=0;
            echo $commentArray[$counter]."<br>";
        }
    }
}

$conn = new mysqli($servername, $username, $password,$dbName);
if ($conn->connect_error) {
    die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
}

autoComment($conn,1,10,1,12);
$conn->close();