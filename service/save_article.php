<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/lib/db.php';
session_start();
$user=isset($_SESSION['user'])?$_SESSION['user']:false;
if(!$user){
    exit(json_encode(['code'=>1,'msg'=>'你还没有登入'],JSON_ERROR_UTF8));
}
//保存博客
$data['uid']=$user['uid'];
$data['title']=trim($_POST['title']);
$data['cid']=(int)$_POST['cid'];
$data['keywords']=trim($_POST['keywords']);
$data['descriptions']=trim($_POST['desc']);
$data['add_time']=time();
//去除危险字符
$data['contents']=htmlspecialchars(trim($_POST['contents']),ENT_QUOTES);

if(!$data['title']){
    exit(json_encode(['code'=>1,'msg'=>'标题不能为空'],JSON_ERROR_UTF8));
}
if(!$data['keywords']){
    exit(json_encode(['code'=>1,'msg'=>'关键字不能为空'],JSON_ERROR_UTF8));
}
if(!$data['descriptions']){
    exit(json_encode(['code'=>1,'msg'=>'描述不能为空'],JSON_ERROR_UTF8));
}
//判断有没有输出contents

//保存数据
$db=new Db();
$id=$db->table('ywn_article')->insert($data);
if(!$id){
    exit(json_encode(['code'=>1,'msg'=>'保存失败'],JSON_ERROR_UTF8));
}
exit(json_encode(['code'=>0,'msg'=>'保存成功'],JSON_ERROR_UTF8));
