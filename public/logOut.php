<?php
include_once '../private/generateJumpPage.php';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if($_SERVER["REQUEST_METHOD"] == "POST"|| $_SERVER["REQUEST_METHOD"] == "GET") {
    session_unset();
    //echo "会话状态:<br>";
    //var_dump($_SESSION);
    header("Location: ../home.php");
    ob_end_flush();
}
?>