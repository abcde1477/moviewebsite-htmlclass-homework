<?php

//给定movie_id或者user_id获取评论信息，具体使用细则请看文档
//给定movie_id或者user_id获取评论信息，具体使用细则请看文档
//给定movie_id或者user_id获取评论信息，具体使用细则请看文档
include_once '../private/DBInit.php';

/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */


$conn = new mysqli($servername, $username, $password,$dbName);
if ($conn->connect_error) {
    die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'comments' => []
    ];
    $data['errorMessage'] = 'NoError';

    if(!(
        isset($_POST['idType'])&&
        isset($_POST['query_id'])&&
        isset($_POST['sort_by'])&&
        isset($_POST['sort_order'])&&
        isset($_POST['from'])&&
        isset($_POST['to'])
    ))
        $data['errorMessage'] = 'LackParam';


    $query_type = '';
    $idType = $_POST['idType'];
    if ($idType =='movie'){
        $query_type = "movie_id";
    }else if($idType =='user')
    {
        $query_type = "user_id";
    }else{
        $data['errorMessage'] = 'InvalidIdType';
    }

    $query_id = $_POST['query_id'];
    $basis = $_POST['sort_by'];
    if ($basis != 'rating' && $basis != 'releaseTime')
        $data['errorMessage'] = 'InvalidRange';

    $order = $_POST['sort_order'];
    if ($order != 'increase' && $order != 'decrease')
        $data['errorMessage'] = 'InvalidOrder';
    else{
        if($order === 'increase') $order = 'ASC';
        if($order === 'decrease') $order = 'DESC';
    }
    $from =  intval($_POST['from']);
    $to =  intval($_POST['to']);
    $LIMIT = $to - $from + 1;
    $OFFSET = $from - 1;
    if ($LIMIT < 0 || $OFFSET < 0)
        $data['errorMessage'] = 'InvalidRange';

    if($data['errorMessage'] === 'NoError'){
        $SqlSearchComments = "SELECT * FROM $commentTableName WHERE $query_type = $query_id ORDER BY $basis $order LIMIT $LIMIT OFFSET $OFFSET;";
        $result = $conn->query($SqlSearchComments);
        if ($result->num_rows == 0) {
            $checkId = "SELECT * FROM $commentTableName WHERE $query_type = $query_id";
            //NO LIMIT $LIMIT OFFSET $OFFSET
            $result = $conn->query($checkId);
            if($result->num_rows == 0)
                $data['errorMessage'] = 'NoFoundInId';
            else//found,which means 'LIMIT $LIMIT OFFSET $OFFSET' causes NoFound
                $data['errorMessage'] = 'NoFoundInRange';
        }
        else {
            //$data['errorMessage'] = 'NoError';
            $comments = [];
            while ($row = $result->fetch_assoc()) {

                //////////$rating是字符串！
                $decade_rating = $row['rating'];
                $rating = number_format((float)$decade_rating/ 10.0, 1);
                //////////$rating是字符串！

                $comments[] = [
                    'id' => $row['id'],
                    'movie_id' => $row['movie_id'],
                    'user_id' => $row['user_id'],
                    'comment_content'=>$row['comment'],
                    'rating' => $rating,
                    'comment_time' => $row['comment_time'],
                ];
            }
            $data['comments'] =$comments;
        }
    }
    //var_dump($data);

    $json_data = json_encode($data);
    header('content-Type:application/json');
    echo $json_data;
}
$conn->close();