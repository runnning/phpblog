<?php
//上传图片
//上传错误
if ($_FILES['fileName']['error'] > 0) {
    exit(json_encode(['errno' => 1, 'data' => []],JSON_ERROR_UTF8));
}
//限制文件类型和大小
$file=new finfo(FILEINFO_MIME_TYPE);
$mime_type=$file->file($_FILES['fileName']['tmp_name']);
$allows = ['image/jpeg', 'image/png'];
if (!in_array($mime_type, $allows)) {
    exit(json_encode(['errno' => 1, 'data' => []],JSON_ERROR_UTF8));
}
move_uploaded_file($_FILES['fileName']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/upload/' . $_FILES['fileName']['name']);
exit(json_encode(['errno' => 0, 'data' => ['/upload/' . $_FILES['fileName']['name']]],JSON_ERROR_UTF8));