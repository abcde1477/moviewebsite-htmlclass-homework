<?php

include_once '../private/DBInit.php';
include_once '../private/DBGet.php';

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
    if($data['errorMessage'] == 'NoError'){
        $idType = $_POST['idType'];
        if ($idType =='movie'){
            $idType = "movie_id";
        }else if($idType =='user')
        {
            $idType = "user_id";
        }else{
            $data['errorMessage'] = 'InvalidIdType';
        }

        $query_id = $_POST['query_id'];
        $sort_by = $_POST['sort_by'];
        $sort_order =  $_POST['sort_order'];
        $from =  intval($_POST['from']);
        $to =  intval($_POST['to']);
    }
    if($data['errorMessage'] == 'NoError')
        $data = getComment($conn,$idType,$query_id,$sort_by,$sort_order,$from,$to);
    $json_data = json_encode($data);
    header('content-Type:application/json');
    echo $json_data;
}
if($_SERVER["REQUEST_METHOD"] == "GET") {
    $data = getCommentByTime($conn,1,3);
    $json_data = json_encode($data['comments']);
    header('content-Type:application/json');
    echo $json_data;
}


$conn->close();