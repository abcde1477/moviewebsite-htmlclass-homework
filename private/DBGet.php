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

function ExistComment($conn_p,$user_id)
{
            $SqlSearchComments = "SELECT * FROM comments WHERE user_id = $user_id;";
            $result = $conn_p->query($SqlSearchComments);
            if ($result->num_rows > 0)
                return true;
            else {
                return false;
            }
}


function getDataById($conn_p,$query_id){


    $userdata=[];
    $sql = "SELECT * FROM users WHERE id='$query_id'";
    $result = $conn_p->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $userdata['user_name'] = $row['user_name'];
        $userdata['profile_url'] = $row['profile_url'];
        //$userdata['password'] = $row['password'];
        $userdata['homepage_content'] = $row['homepage_content'];
        $userdata['isAdmin'] = $row['isAdmin'];
        $userdata['register_time'] = $row['register_time'];
    }
    return $userdata;
};
function getDataById_GET($conn_p,$GET_P){
    $data = [
        'userdata'=>[]
    ];
    $user_id=0;
    $data['errorMessage'] = 'NoError';

    if(!isset($GET_P['query_id'])){
        $data['errorMessage'] = 'LackParam';
    }else if(!is_numeric($GET_P['query_id'])){
        $data['errorMessage'] = 'InvalidId';
    }else{
        $user_id = intval($GET_P['query_id']);
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
function getCommentByTime($conn_p,$from,$to){
    $data = [
        'comments' => []
    ];
    $data['errorMessage'] = 'NoError';

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

                //////////$rating是字符串！
                $decade_rating = $row['rating'];
                $rating = number_format((float)$decade_rating/ 10.0, 1);
                //////////$rating是字符串！

                $comments[] = [
                    'id' => $row['id'],
                    'movie_id' => $row['movie_id'],
                    'user_id' => $row['user_id'],
                    'comment_content'=>$row['comment'],
                    'rating' => $rating,
                    'comment_time' => $row['comment_time'],
                ];
            }
            $data['comments'] =$comments;
        }
    }
    return $data;
}
function getComment($conn,$idType,$query_id,$sort_by,$sort_order,$from,$to): array
{
    $data = [
        'comments' => []
    ];
    $data['errorMessage'] = 'NoError';

    $basis = $sort_by;
    if ($basis != 'rating' && $basis != 'comment_time')
        $data['errorMessage'] = 'InvalidRange';

    $order = $sort_order;
    if ($order != 'increase' && $order != 'decrease')
        $data['errorMessage'] = 'InvalidOrder';
    else{
        if($order === 'increase') $order = 'ASC';
        if($order === 'decrease') $order = 'DESC';
    }

    $LIMIT = $to - $from + 1;
    $OFFSET = $from - 1;
    if ($LIMIT < 0 || $OFFSET < 0)
        $data['errorMessage'] = 'InvalidRange';

    if($data['errorMessage'] === 'NoError'){
        $SqlSearchComments = "SELECT * FROM comments WHERE $idType = $query_id ORDER BY $basis $order LIMIT $LIMIT OFFSET $OFFSET;";
        $result = $conn->query($SqlSearchComments);
        if ($result->num_rows == 0) {
            $checkId = "SELECT * FROM comments WHERE $idType = $query_id";
            //NO LIMIT $LIMIT OFFSET $OFFSET
            $result = $conn->query($checkId);
            if($result->num_rows == 0)
                $data['errorMessage'] = 'NoFoundInId';
            else//found,which means 'LIMIT $LIMIT OFFSET $OFFSET' causes NoFound
                $data['errorMessage'] = 'NoFoundInRange';
        }
        else {
            //$data['errorMessage'] = 'NoError';
            $comments = [];
            while ($row = $result->fetch_assoc()) {

                //////////$rating是字符串！
                $decade_rating = $row['rating'];
                $rating = number_format((float)$decade_rating/ 10.0, 1);
                //////////$rating是字符串！

                $comments[] = [
                    'id' => $row['id'],
                    'movie_id' => $row['movie_id'],
                    'user_id' => $row['user_id'],
                    'comment_content'=>$row['comment'],
                    'rating' => $rating,
                    'comment_time' => $row['comment_time'],
                ];
            }
            $data['comments'] =$comments;
        }
    }
    //var_dump($data);
    return $data;
}
function getMovie($conn,$basis,$order,$LIMIT,$OFFSET,$BackToRoot): array
{
    $movieTableName = 'movies';

    $movies = [];

    $SqlSearchMovie = "SELECT * FROM $movieTableName ORDER BY $basis $order LIMIT $LIMIT OFFSET $OFFSET;";
    $result = $conn->query($SqlSearchMovie);

    if ($result->num_rows != 0) {
        while ($row = $result->fetch_assoc()) {
            //////////$rating是字符串！
            $decade_rating = $row['rating'];
            $rating = number_format((float)$decade_rating/ 10.0, 1);
            //////////$rating是字符串！
            $photos = [];
            $directory = $row['photo_file_url'];
            if($BackToRoot)
                $files = scandir('../'.$directory);
            else
                $files = scandir($directory);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    $fileUrl = $directory . '/' . $file;
                    $photos[] = $fileUrl;
                }
            }
            $movies[] = [
                'id' => $row['id'],
                'rating' => $rating,
                'movie_name' => $row['movie_name'],
                'attribution' => $row['attribution'],
                'movie_content' => $row['movie_content'],
                'cover_url' => $row['cover_url'],
                'releaseTime' => $row['releaseTime'],
                'comment_number'=>$row['comment_number'],
                'photos' => $photos
            ];
        }
    }
    return $movies;
}







function getMovieById($conn,$query_id){
    $movieTableName = "movies";
    $SqlSearchMovie = "SELECT * FROM $movieTableName WHERE id = $query_id;";
    $row = $conn->query($SqlSearchMovie)->fetch_assoc();
    $photos = [];
    $directory = $row['photo_file_url'];

    $files = scandir($directory);   //如果无??
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $fileUrl = $directory . '/' . $file;
            $photos []= $fileUrl;
        }

    }
    $row['photos']=$photos;

    return $row;
}


/*
function searchMovie($conn,$_POST_P){
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
                //////////$rating是字符串！
                $decade_rating = $row['rating'];
                $rating = number_format((float)$decade_rating/ 10.0, 1);
                //////////$rating是字符串！
                $movies[] = [
                    'id' => $row['id'],
                    'rating' => $rating,
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
}*/