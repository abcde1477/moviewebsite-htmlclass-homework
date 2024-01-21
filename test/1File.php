<?php
// 检查是否有文件上传
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_FILES["upfile_image"]["name"] != "") {
        // 获取上传文件的相关信息
        $file_name = $_FILES['upfile_image']['name'];
        $file_tmp = $_FILES['upfile_image']['tmp_name'];
        $file_size = $_FILES['upfile_image']['size'];
        $file_type = $_FILES['upfile_image']['type'];

        // 指定存储目录
        $upload_directory = 'dir2/';

        // 构造新的文件名，以避免重复
        $new_file_name = $upload_directory . uniqid() . '_' . $file_name;

        // 移动文件到指定目录
        if (move_uploaded_file($file_tmp, $new_file_name)) {
            echo '文件上传成功！';
            // 在这里你可以对上传成功的文件进行进一步处理，如保存文件路径到数据库等
        } else {
            echo '文件上传失败！';
        }
    } else {
        // 处理文件上传错误
        echo '文件上传出错，错误码：' . $_FILES['upfile_image']['error'];
    }
    if(isset($_POST['number'])){
        echo "isset TRUE<br>";
    }
    if($_POST['number'] !=''){
        echo " != '' TRUE\n";
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
    <input type="file" name="upfile_image"/>
    <input type="number" name="number"/>
    <label for="birthdate">选择出生日期:</label>
    <input type="date" id="birthdate" name="birthdate">

    <input type="submit" value="上传文件"/>
</form>
</body>
</html>
