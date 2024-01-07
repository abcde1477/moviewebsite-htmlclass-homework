<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "movie_website";    //databaseName
$movieTableName = "movies";   //tableName
$commentTableName = "comments";   //tableName
$userTableName = "users";

$conn = new mysqli($servername, $username, $password);
// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
function init($conn,$dbName,$moviesTable,$usersTable,$commentTable){

    $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbName";
    $conn->query($sqlCreateDB);
    $conn->select_db($dbName);

    $sqlCreateMoviesTable = "CREATE TABLE IF NOT EXISTS $moviesTable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_name VARCHAR(255) NOT NULL,
    attribution VARCHAR(255) NOT NULL,
    cover_url VARCHAR(255),
    rating DECIMAL(3, 1) CHECK (rating < 10),
    movie_content TEXT,
    photo_file_url VARCHAR(255),
    releaseTime TIMESTAMP,
    updataTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_rating (rating),
    INDEX idx_time (releaseTime)
    )";
    $conn->query($sqlCreateMoviesTable);
    //rating应当在每次对此电影评论后及时统计修改.

    $sqlCreateUsersTable = "CREATE TABLE IF NOT EXISTS $commentTable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(30) NOT NULL,
    profile_url  VARCHAR(255),
    password VARCHAR(30) NOT NULL,
    homepage_content TEXT,
    isAdmin BOOLEAN NOT NULL,
    register_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
    $conn->query($sqlCreateUsersTable);

    $sqlCreateCommentsTable = "CREATE TABLE IF NOT EXISTS $usersTable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    user_id INT NOT NULL,
    rating DECIMAL(3,1) NOT NULL,
    comment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_rating (user_id),
    INDEX idx_time (movie_id),
    INDEX idx_time (comment_time),
    )";
    $conn->query($sqlCreateCommentsTable);

}

init($conn,$dbName,$movieTableName,$commentTableName,$userTableName);
