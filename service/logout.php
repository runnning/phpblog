<?php
//退出登入
session_start();
$_SESSION['user']=null;
exit(json_encode(['code'=>0,'msg'=>'退出成功'],JSON_ERROR_UTF8));