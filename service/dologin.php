<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/lib/db.php';
//登入验证
$username=$_POST['username'];
$pwd=$_POST['pwd'];
//验证用户名和密码
$db=new db();
$user=$db->table('ywn_user')->where(['username'=>$username])->item();
if(!$user){
    exit(json_encode(['code'=>1,'msg'=>'该用户不存在'],JSON_ERROR_UTF8));
}
//验证密码
if($user['password']!==md5($pwd)){
    exit(json_encode(['code'=>1,'msg'=>'密码错误'],JSON_ERROR_UTF8));
}
//保存session
session_start();
$_SESSION['user']=$user;
exit(json_encode(['code'=>0,'msg'=>'登入成功'],JSON_ERROR_UTF8));