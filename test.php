<?php
//debug调试文件
require_once 'lib/db.php';
$db = new Db();
//$res=$db->table('ywn_cates')->field('title,id')->where('id>1')->order('id desc')->limit(3)->list();
//$id=$db->table('ywn_cates')->insert(['title'=>'十万个为什么']);
//$data=$db->table('ywn_cates')->where('id=23')->delete();
//$data=$db->table('ywn_cates')->where(['id'=>24])->update(['title'=>'后端开发']);
$page = $_GET['page'];//第几页
$pageSize=2;//每页加载多少条数据
//0 3第一页
//3 3第二页
//6 3第三页
//($page-1)*$pageSize
$res = $db->table('ywn_cates')->where('id >1')->field('title,id')->pages($page,$pageSize,'/test.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>分页</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
</head>
<body>
<div class="container mt-5">
    <p>共查询<?php echo $res['count'] ?>数据</p>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($res['data'] as $key => $value) {
            ?>
            <tr>
                <td><?php echo $value['id']; ?></td>
                <td><?php echo $value['title'] ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <!--分页    -->
    <div>
        <?php echo $res['pages'];?>
    </div>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
</body>
</html>
