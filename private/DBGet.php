<?php
include_once 'DBInit.php';

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
function getDataById($conn_p,$_POST){
    $data = [
        'userdata'=>[]
    ];
    $user_id=0;
    $data['errorMessage'] = 'NoError';

    if(!isset($_POST['query_id'])){
        $data['errorMessage'] = 'LackParam';    //
    }else if(!is_numeric($_POST['query_id'])){
            $data['errorMessage'] = 'InvalidId';    //
        }else{
            $user_id = intval($_POST['query_id']);
    }

    if($data['errorMessage'] === 'NoError') {
        $sql = "SELECT * FROM users WHERE id='$user_id'";
        $result = $conn_p->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $userdata['user_name'] = $row['user_name'];
            $userdata['profile_url'] = $row['profile_url'];
            //$userdata['password'] = $row['password'];
            $userdata['homepage_content'] = $row['homepage_content'];
            $userdata['isAdmin'] = $row['isAdmin'];
            $userdata['register_time'] = $row['register_time'];
            $data['userdata'] = $userdata;
        } else if ($result->num_rows == 0) {
            $data['errorMessage'] = 'NoFound';
        } else {
            $data['errorMessage'] = 'DataBaseError';
        }
    }
    return $data;
};
function getCommentByTime($conn_p,$order,$from,$to){
    $data = [
        'comments' => []
    ];
    $data['errorMessage'] = 'NoError';

    $from = $_POST['from'];
    $to = $_POST['to'];
    $LIMIT = $to - $from + 1;
    $OFFSET = $from - 1;
    if ($LIMIT < 0 || $OFFSET < 0)
        $data['errorMessage'] = 'InvalidRange';
    //范围合法性判断

    if($data['errorMessage'] === 'NoError'){
        $SqlSearchComments = "SELECT * FROM comments ORDER BY comment_time LIMIT $LIMIT OFFSET $OFFSET;";
        $result = $conn_p->query($SqlSearchComments);
        if ($result->num_rows == 0) {
                $data['errorMessage'] = 'NoFoundInRange';
        }
        else {
            //$data['errorMessage'] == 'NoError';
            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = [
                    'id' => $row['id'],
                    'movie_name' => $row['movie_name'],
                    'user_id' => $row['user_id'],
                    'comment_content'=>$row['comment'],
                    'rating' => $row['rating'],
                    'comment_time' => $row['comment_time'],
                ];
            }
            $data['comments'] =$comments;
        }
    }
    return $data;
}
;
function getMovie($conn,$_POST){
    $movieTableName = "movies";
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
    $from = $_POST['from'];
    $to = $_POST['to'];
    $LIMIT = $to - $from + 1;
    $OFFSET = $from - 1;
    if ($LIMIT < 0 || $OFFSET < 0)
        $data['errorMessage'] = 'InvalidRange';
    //请求参数合法性判断

    if($data['errorMessage'] === 'NoError'){
        $SqlSearchMovie = "SELECT * FROM $movieTableName ORDER BY $basis $order LIMIT $LIMIT OFFSET $OFFSET;";
        $result = $conn->query($SqlSearchMovie);

        if ($result->num_rows == 0) {
            $data['errorMessage'] = 'NoFound';
        }else{
            //$data['errorMessage'] = 'NoError';
            $movies = [];
            while ($row = $result->fetch_assoc()) {
                $movies[] = [
                    'id' => $row['id'],
                    'rating' => $row['rating'],
                    'movie_name' => $row['movie_name'],
                    'attribution' => $row['movie_name'],
                    'movie_content' => $row['movie_content'],
                    'cover_url' => $row['cover_url'],
                    'releaseTime' => $row['movie_name'],
                ];
                $photos = [];
                //注意路径
                $directory = $row['photo_file_url'];
                $files = scandir($directory);   //如果无??
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        $fileUrl = $directory . '/' . $file;
                        $photos[] = $fileUrl;
                    }
                }
                $movies['photos'] = $photos;
            }
            $data['movies'] = $movies;
        }
    }
    return $data;
}
