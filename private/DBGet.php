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
function getMovie($conn,$_POST){
    $movieTableName = "movies";
    $data = ['movies' => []];
    $data['errorMessage'] = 'NoError';

    $basis = $_POST['sort_by'];
    if ($basis != 'rating' && $basis != 'releaseTime')
        $data['errorMessage'] = 'InvalidRange';
    $order = $_POST['sort_order'];
    if ($order != 'increase' && $order != 'decrease')
        $data['errorMessage'] = 'InvalidOrder';

    $from = $_POST['from'];
    $to = $_POST['to'];
    $LIMIT = $to - $from + 1;
    $OFFSET = $from - 1;
    if ($LIMIT < 0 || $OFFSET < 0)
        $data['errorMessage'] = 'InvalidRange';

    if($data['errorMessage'] === 'NoError'){
        $SqlSearchMovie = "SELECT * FROM $movieTableName ORDER BY $basis LIMIT $LIMIT OFFSET $OFFSET;";
        $result = $conn->query($SqlSearchMovie);

        if ($result->num_rows == 0) {
            $data['errorMessage'] = 'NoFound';
        } else {
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
        }
    }
    return $data;
}
