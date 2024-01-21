<?php
//获取用户信息,
//获取用户信息,
//获取用户信息,

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
if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'errorMessage' => 'NoError'
    ];
    $query_id =2;
    if(!isset($_POST['query_id'])){
        $data['errorMessage'] = 'LackParam';
    }else if(!is_numeric($_POST['query_id'])){
        $data['errorMessage'] = 'InvalidId';
    }else{
        $query_id = intval($_POST['query_id']);
    }

    if($data['errorMessage'] === 'NoError') {
        $data['userdata'] = getDataById($conn,$query_id);
        if(count($data['userdata']) ==0){
            $data['errorMessage'] = 'NoFound';
        }
    }
    $json_data = json_encode($data);
    header('content-Type:application/json');
    echo $json_data;
}

if($_SERVER["REQUEST_METHOD"] == "GET") {
    $Array = getDataById_GET($conn,$_GET);
    //数据在$Array中

    $json_data = json_encode($Array);
    header('content-Type:application/json');
    echo $json_data;
}


$conn->close();