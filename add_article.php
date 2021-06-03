<?php
//验证用户是否已登入
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db.php';
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
$db = new Db();
if (!$user) {
    exit('你还未登入请先登入后再发表博客');
}
$cates = $db->table('ywn_cates')->list();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>发表文章</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <!--    <link rel="stylesheet" href="assets/css/quill.snow.css">-->
</head>
<body>
<div class="container-fluid">
    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">博客标题</span>
        </div>
        <input type="text" class="form-control" placeholder="请输入标题" name="title">
    </div>
    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">博客分类</span>
        </div>
        <select class="form-control" name="cid">
            <?php foreach ($cates as $cate) { ?>
                <option value="<?php echo $cate['id'] ?>"><?php echo $cate['title']; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">关键字</span>
        </div>
        <input type="text" class="form-control" placeholder="请输入关键字" name="keywords">
    </div>
    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">描述</span>
        </div>
        <input type="text" class="form-control" placeholder="请输入描述" name="desc">
    </div>
    <div>
        <p class="form-control text-center">博客内容</p>
        <div id="edit">

        </div>
    </div>
    <button type="button" class="btn btn-success btn-lg btn-block mt-3" onclick="save()">保存</button>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/ui.js"></script>
<script src="assets/js/wangEditor.min.js"></script>
<!--<script src="assets/js/quill.js"></script>-->
<script type="text/javascript">
    //初始化符文便编辑器
    let editor;

    function initEdit() {
        const E = window.wangEditor;
        editor = new E('#edit');
        editor.config.zIndex = 500;
        editor.config.uploadImgServer = '/service/upload.php';
        editor.config.uploadFileName = 'fileName';
        editor.config.customAlert = (info) => ui.alert({msg: info, icon: 'error'});
        editor.create();
    }

    initEdit();

    //保存
    function save() {
        const data = {};
        data.title = $.trim($('input[name="title"]').val());
        data.cid = $.trim($('select[name="cid"]').val());
        data.keywords = $.trim($('input[name="keywords"]').val());
        data.desc = $.trim($('input[name="desc"]').val());
        data.contents = editor.txt.html();
        if (data.title === '') {
            ui.alert({msg: '请输入博客标题', icon: 'error'});
            return;
        }
        if (data.keywords === '') {
            ui.alert({msg: '请输入关键字', icon: 'error'});
            return;
        }
        if (data.desc === '') {
            ui.alert({msg: '请输入关键字', icon: 'error'});
            return;
        }
        if (data.contents === '') {
            ui.alert({msg: '请输入博客内容', icon: 'error'});
            return;
        }
        $.post('/service/save_article.php', data, res => {
            if (res.code > 0) {
                ui.alert({msg: res.msg, icon: 'error'});
            } else {
                ui.alert({msg: res.msg, icon: 'success'});
                setTimeout(() => parent.window.location.reload(), 1000);
            }
        }, 'json');
    }
</script>
<!--<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });
</script>-->
</body>
</html>