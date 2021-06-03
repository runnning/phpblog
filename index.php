<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db.php';
session_start();
$user=isset($_SESSION['user'])?$_SESSION['user']:false;
//读取博客列表
$db=new Db();
$path='/index.php';
$page=isset($_GET['page'])?(int)$_GET['page']:1;
$pageSize=3;
$cid=isset($_GET['cid'])?(int)$_GET['cid']:0;
$where=[];
if($cid){
    $where['cid']=$cid;
    $path.="?cid={$cid}";
}
$list=$db->table('ywn_article')->field('id,title,add_time,pv')->where($where)->pages($page,$pageSize,$path);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>欢迎访问我的博客</title>
</head>
<body>
<!--导航-->
<div class="header">
    <div class="container clearfix">
        <span class="title text-white float-left">小颜的博客</span>
        <div class="search float-left">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="输入标题搜索" aria-label="Recipient's username"
                       aria-describedby="button-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-warning" type="button" id="button-addon2">搜索</button>
                </div>
            </div>
        </div>
        <div class="login-reg float-right">
            <?php if ($user){?>
                <span class="text-white"><?php echo $user['username']?></span>
                <a href="#" onclick="logout()">退出</a>
            <?php }else{?>
            <button type="button" class="btn btn-success" onclick="login()">登入</button>
            <?php }?>
            <button type="button" class="btn btn-danger ml-5" onclick="add_article()">发表博客</button>
        </div>
    </div>
</div>
<!--内容-->
<div class="container main mt-3">
    <div class="row">
        <div class="col-md-3 left-container">
            <p class="mt-3 font-weight-lighter cates">博客分类</p>
            <div class="cate-list">
                <div class="cate-item">
                    <a href="index.php?cid=31">编程语言</a>
                </div>
                <div class="cate-item">
                    <a href="index.php?cid=24">后端开发</a>
                </div>
                <div class="cate-item">
                    <a href="index.php?cid=25">安卓开发</a>
                </div>
                <div class="cate-item">
                    <a href="index.php?cid=26">ios开发</a>
                </div>
                <div class="cate-item">
                    <a href="index.php?cid=27">软件工程</a>
                </div>
                <div class="cate-item">
                    <a href="index.php?cid=28">数据库技术</a>
                </div>
                <div class="cate-item">
                    <a href="index.php?cid=29">操作系统</a>
                </div>
                <div class="cate-item">
                    <a href="index.php?cid=30">其他分类</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="nav  mt-4 justify-content-end">
                <a href="#" class="active">热门</a>
                <a href="#">最新</a>
            </div>
            <hr>
            <div class="content-list">
                <?php foreach ($list['data'] as $value) {?>
                <div class="content-item overflow-hidden">
                    <img src="assets/images/head.png" class="img-fluid float-left">
                    <div class="float-left ml-3 content">
                        <div class="title">
                            <a href="/detail.php?aid=<?php echo $value['id']?>"><?php echo $value['title'];?></a>
                        </div>
                        <div class="font-weight-light mt-3">
                            <span><?php echo $value['pv']?>次浏览</span>
                            <span><?php echo date('Y-m-d H:i:s',$value['add_time']); ?></span>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
            <div>
                <?php echo $list['pages'];  ?>
            </div>
        </div>
    </div>
</div>
<!--登入弹出-->
<!--底部-->
<div class="container-fluid">
    <div class="col">
        <nav class="navbar fixed-bottom navbar-light bg-light justify-content-center">
            <span>Copyright © 2019 - 2021</span>
            <a class="navbar-brand" href="https://www.tzwl420.club" target="_blank">小颜博客</a>
        </nav>
    </div>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/ui.js"></script>
<script>
    //登录
    function login(){
        // ui.alert({title:'系统消息',msg:'请输入用户名',icon:'error'});
        ui.open({title:'登入',url:'/login.php',width:500,height:350});
    }
    //退出
    function logout(){
        if(!confirm('确定要退出吗？')){
            return;
        }
        $.get('/service/logout.php',{},res=>{
            if(res.code>0){
                ui.alert({msg:res.msg,icon:'error'});
            }else {
                ui.alert({msg:res.msg,icon:'success'});
                setTimeout(()=>parent.window.location.reload(),1000);
            }
        },'json');
    }
    //发表文章
    function add_article(){
        ui.open({title:'发表博客',url:'/add_article.php',width:700,height:650});
    }
</script>
</body>
</html>