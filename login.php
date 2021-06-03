<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登入</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
</head>
<body>
<div class="container">
    <p class="text-center">博客登入</p>
    <form>
        <div class="form-group">
            <label for="InputUser">账号</label>
            <input type="text" class="form-control" id="InputUser" name="username" placeholder="请输入用户名">
        </div>
        <div class="form-group">
            <label for="InputPassword">密码</label>
            <input type="password" class="form-control" id="InputPassword" name="pwd" placeholder="请输入密码">
        </div>
        <button type="button" class="btn btn-success btn-lg btn-block" onclick="login()">确认</button>
    </form>
</div>
<script src="assets/js/jquery-3.5.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/ui.js"></script>
<script>
    //登入
    function login(){
        const username=$.trim($('input[name="username"]').val());
        const pwd=$.trim($('input[name="pwd"]').val());
        if(username===''){
            ui.alert({msg:'用户名不能为空',icon:'error'});
            return;
        }
        if(pwd===''){
            ui.alert({msg:'密码不能为空',icon:'error'});
            return;
        }
        //jq ajax简写
        $.post('/service/dologin.php',{username:username,pwd:pwd},res=>{
            if(res.code>0){
                ui.alert({msg:res.msg,icon:'error'});
            }else {
                ui.alert({msg:res.msg,icon:'success'});
                setTimeout( ()=>parent.window.location.reload(),1000);
            }
        },'json');
    }
</script>
</body>
</html>