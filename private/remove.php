<?php
function deleteFilesInDirectory($directory) {
    // 打开目录
    $dirHandle = opendir($directory);

    // 循环读取目录中的文件
    while ($file = readdir($dirHandle)) {
        if ($file != "." && $file != "..") {
            $filePath = $directory . "/" . $file;

            // 如果是文件，直接删除
            if (is_file($filePath)) {
                unlink($filePath);
            }

            // 如果是目录，递归调用删除该目录下的所有文件
            if (is_dir($filePath)) {
                deleteFilesInDirectory($filePath);
            }
        }
    }

    // 关闭目录句柄
    closedir($dirHandle);
}

/*$directoryPath = 'path/to/your/project/tempFile';
deleteFilesInDirectory($directoryPath);*/