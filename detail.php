<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db.php';
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
//文章详情
$aid = (int)$_GET['aid'];
$db = new Db();
$article = $db->table('ywn_article')->where(['id' => $aid])->item();
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
    <title><?php echo $article['title']; ?></title>
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
            <?php if ($user) { ?>
                <span class="text-white"><?php echo $user['username'] ?></span>
                <a href="#" onclick="logout()">退出</a>
            <?php } else { ?>
                <button type="button" class="btn btn-success" onclick="login()">登入</button>
            <?php } ?>
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
                    <a href="index.php?cid=24">后端开发</a>
                </div>
                <div class="cate-item">
                    <a href="#">web前端</a>
                </div>
                <div class="cate-item">
                    <a href="#">企业信息化</a>
                </div>
                <div class="cate-item">
                    <a href="#">安卓开发</a>
                </div>
                <div class="cate-item">
                    <a href="#">IOS开发</a>
                </div>
                <div class="cate-item">
                    <a href="#">软件工程</a>
                </div>
                <div class="cate-item">
                    <a href="#">数据库技术</a>
                </div>
                <div class="cate-item">
                    <a href="#">操作系统</a>
                </div>
                <div class="cate-item">
                    <a href="#">其他分类</a>
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
                <h2 class="text-center font-weight-bold"><?php echo $article['title']; ?></h2>
                <p class="text-center"><span><?php echo date('Y-m-d H:i:s', $article['add_time']); ?></span></p>
                <div>
                    <?php echo htmlspecialchars_decode($article['contents']) ;?>
                </div>
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
    function login() {
        // ui.alert({title:'系统消息',msg:'请输入用户名',icon:'error'});
        ui.open({title: '登入', url: '/login.php', width: 500, height: 350});
    }

    //退出
    function logout() {
        if (!confirm('确定要退出吗？')) {
            return;
        }
        $.get('/service/logout.php', {}, res => {
            if (res.code > 0) {
                ui.alert({msg: res.msg, icon: 'error'});
            } else {
                ui.alert({msg: res.msg, icon: 'success'});
                setTimeout(() => parent.window.location.reload(), 1000);
            }
        }, 'json');
    }

    //发表文章
    function add_article() {
        ui.open({title: '发表博客', url: '/add_article.php', width: 700, height: 650});
    }
</script>
</body>
</html>
