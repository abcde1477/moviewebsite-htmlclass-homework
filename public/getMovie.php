<?php

//获取电影信息，具体使用细则请看文档
//获取电影信息，具体使用细则请看文档
//获取电影信息，具体使用细则请看文档
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
        'movies' => []
    ];
    $data['errorMessage'] = 'NoError';
    //检查是否缺少参数
    if(!(
        isset($_POST['sort_by'])&&
        isset($_POST['sort_order'])&&
        isset($_POST['from'])&&
        isset($_POST['to'])
    ))
        $data['errorMessage'] = 'LackParam';

    $basis = $_POST['sort_by'];
    if ($basis != 'rating' && $basis != 'releaseTime')
        $data['errorMessage'] = 'InvalidRange';

    $order = $_POST['sort_order'];
    if ($order != 'increase' && $order != 'decrease')
        $data['errorMessage'] = 'InvalidOrder';
    else {
        if($order === 'increase') $order = 'ASC';
        if($order === 'decrease') $order = 'DESC';
    }
    $from = intval($_POST['from']);
    $to = intval($_POST['to']);

    $movieTableName = "movies";

    $LIMIT = $to - $from + 1;
    $OFFSET = $from - 1;
    if ($LIMIT < 0)
        $data['errorMessage'] = 'InvalidRange';
//请求参数合法性判断
    if($OFFSET < 0)
        $data['errorMessage'] = 'NoFound';
    if($data['errorMessage'] === 'NoError') {

        $data['movies'] = getMovie($conn,$basis,$order,$LIMIT,$OFFSET,true);
        if(count($data['movies'])==0){
            $data['errorMessage'] ='NoFound';
        }
        $json_data = json_encode($data);
        header('content-Type:application/json');
        echo $json_data;
    }
}
$conn->close();