<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "movie_website";    //databaseName
$movieTableName = "movies";   //tableName
$commentTableName = "comments";   //tableName
$userTableName = "users";

$conn = new mysqli($servername, $username, $password,$dbName);
// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
function init($conn,$dbName,$movieTable,$userTable,$commentTable){

    $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbName";
    $conn->query($sqlCreateDB);
    $conn->select_db($dbName);

    $sqlCreateMoviesTable = "CREATE TABLE IF NOT EXISTS $movieTable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_name VARCHAR(255) NOT NULL,
    attribution TEXT NOT NULL,
    cover_url VARCHAR(255),
    rating INT CHECK (rating <= 100) DEFAULT 0,
    movie_content TEXT,
    photo_file_url VARCHAR(255),
    releaseTime TIMESTAMP,
    INDEX idx_rating (rating),
    INDEX idx_time (releaseTime)
    )";
    $conn->query($sqlCreateMoviesTable);
    //rating应当在每次对此电影评论后及时维护.

    $sqlCreateUsersTable = "CREATE TABLE IF NOT EXISTS $userTable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(30) NOT NULL,
    profile_url  VARCHAR(255),
    password VARCHAR(30) NOT NULL,
    homepage_content TEXT,
    isAdmin BOOLEAN NOT NULL,
    register_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
    $conn->query($sqlCreateUsersTable);

    $sqlCreateCommentsTable = "CREATE TABLE IF NOT EXISTS $commentTable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT,
    rating INT NOT NULL CHECK (rating <= 100),
    comment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ui (user_id),
    INDEX idx_mi (movie_id),
    INDEX idx_rating (rating),
    INDEX idx_time (comment_time)
    )";
    $conn->query($sqlCreateCommentsTable);
}

init($conn,$dbName,$movieTableName,$userTableName,$commentTableName);
$conn->close();