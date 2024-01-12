<?php
//检查名称是否被占用,请求一个包含name的表单，返回提示信息.前端应当在提示信息显示“用户名可用”之后，再进行对login.php发送POST表单
//检查名称是否被占用,请求一个包含name的表单，返回提示信息.前端应当在提示信息显示“用户名可用”之后，再进行对login.php发送POST表单
//检查名称是否被占用,请求一个包含name的表单，返回提示信息.前端应当在提示信息显示“用户名可用”之后，再进行对login.php发送POST表单

include_once '../private/DBInit.php';
/** @var string $servername */
/** @var string $username */
/** @var string $password */
/** @var string $dbName */
/** @var string $movieTableName */
/** @var string $commentTableName */
/** @var string $userTableName */


//未函数化过程.时间问题,暂不重构
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameToCheck= $_POST['name'];
    $message ='';
    if (preg_match('/^[a-zA-Z0-9_]+$/', $nameToCheck)) {
        $originData =[
            'massage' => '',
        ];

        $conn = new mysqli($servername, $username, $password,$dbName);
        if ($conn->connect_error) die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);

        $SqlSearchComments = "SELECT * FROM $userTableName WHERE user_name = '$nameToCheck';";
        $result = $conn->query($SqlSearchComments);
        if($result->num_rows >0)
            $originData['massage'] = "用户名已存在";
        else{
            $originData['massage'] = "用户名可用";
        }
        $conn->close();
    } else {
        $originData['massage'] = "用户名只能为字母数字下划线";
    }
    header('content-Type:application/json');
    $jsonData = json_encode($originData);
    echo $jsonData;
}
?>