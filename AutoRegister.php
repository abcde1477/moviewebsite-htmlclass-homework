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

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli($servername, $username, $password,$dbName);
if ($conn->connect_error) {
    die("数据库连接失败,请联系管理员,错误:" . $conn->connect_error);
}

register($conn,'TestUser','666666',true);
session_unset();
register($conn,'NormalUser','123123');
session_unset();

for ($i = 0; $i < 10; $i++) {

    $un = "clone_man_No".($i+1);
    $pw = '12345678';
    register($conn,$un,$pw);
    session_unset();

}

$conn->close();



