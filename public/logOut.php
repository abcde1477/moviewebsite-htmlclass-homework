<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    session_unset();
    echo "会话状态:<br>";
    var_dump($_SESSION);

}
?>