<?php
include_once 'DBInit.php';

/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
function getSingleDataById($conn_p,$user_id_p,$dataType_p){
    if( $dataType_p !=='user_name'&&
        $dataType_p !=='profile_url'&&
        $dataType_p !=='homepage_content'&&
        $dataType_p !=='isAdmin'&&
        $dataType_p !=='register_time'
    ) return "illegalType";

    $sql = "SELECT * FROM users WHERE id='$user_id_p'";
    $result = $conn_p->query($sql);
    $data = '';
    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row[$dataType_p];
    }
    else if($result->num_rows == 0){
        return "NoData";
    }else{
        return "DataBaseError";
    }
}