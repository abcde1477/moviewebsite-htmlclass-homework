<?php
include_once '../private/DBInit.php';
include_once '../private/verify.php';

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

$SessionIsAdmin = isset($_SESSION['admin_permission'])?$_SESSION['admin_permission']:false;
if (!checkPermission(true,
    $SessionIsAdmin)) {
    echo 'PermissionDeny';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_POST['user_id'];

    $conn = new mysqli($servername, $username, $password,$dbName);
    if ($conn->connect_error) {
        echo "数据库连接失败,请联系管理员,错误:" . $conn->connect_error;
        exit();
    }

    $sql = "SELECT * FROM $userTableName WHERE id='$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $EP = "UPDATE $userTableName SET isAdmin = 1 WHERE id='$user_id'";
        $result = $conn->query($EP);
        echo 'OK';
    } else if ($result->num_rows == 0) {
        echo 'NoFound';
    } else {
        echo 'Error';
    }
}