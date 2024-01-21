<?php
//////////////////
///////可行////////
//////////////////

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 检查是否有文件上传错误
    if (isset($_FILES["filename"]) && $_FILES["filename"]["error"][0]==0 && is_array($_FILES["filename"]["name"])) {
        //$_FILES["filename"]["name"][0] 来检测是否上传

        $upload_dir = "dir/"; // 指定上传目录
        // 循环处理每个上传的文件
        for ($i = 0; $i < count($_FILES["filename"]["name"]); $i++) {
            $file_name = $_FILES["filename"]["name"][$i];
            $file_tmp = $_FILES["filename"]["tmp_name"][$i];
            $file_size = $_FILES["filename"]["size"][$i];
            $file_destination = $upload_dir . $file_name;

            // 移动上传的文件到指定目录
            if (move_uploaded_file($file_tmp, $file_destination)) {
                echo "文件 $file_name 上传成功。$file_size<br>";
            } else {
                echo "文件 $file_name 上传失败。$file_size<br>";
            }
        }
    } else {
        echo "没有文件被上传。<br>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>文件上传表单</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="filename[]" multiple="multiple"/>
    <input type="submit" value="上传文件"/>
</form>
</body>
</html>
