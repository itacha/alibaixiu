<?php
include_once '../config.php';
include_once '../function.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
   <?php include_once 'public/_navbar.php' ?>    
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
       <div class="alert alert-danger" style="display:none">
        <strong>错误！</strong><span class="msg"></span>
      </div> 
      <form class="form-horizontal" id="userData">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" placeholder="旧密码" name="oldpwd">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" placeholder="新密码" name="newpwd">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" type="password" placeholder="确认新密码" name="confirm">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <input type="button" value="修改密码" class="btn btn-primary modify">
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include_once 'public/_aside.php' ?>  

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
  $(function(){
    var oldpwd=$("#old");
    var pwd=$("#password");
    var confirm=$("#confirm");
    var regpwd=/^[a-zA-Z0-9]{6,10}$/;
    var start=location.search.indexOf('=')+1;
    var id=location.search.substring(start);  
    $(".modify").on("click",function(){
      if ($.trim(oldpwd.val())==''||$.trim(pwd.val())==''||$.trim(confirm.val())=='') {
        $(".msg").text("密码不能为空");
          $(".alert-danger").show();
          return false;
      }
      if (!regpwd.test(oldpwd.val())||!regpwd.test(pwd.val())||!regpwd.test(confirm.val())) {
        $(".msg").text("密码格式错误，请重新输入");
          $(".alert-danger").show();
          return false;
      }  
      if (oldpwd.val()==pwd.val()) {
        $(".msg").text("新密码不能与旧密码一致，请重新输入");
          $(".alert-danger").show();
          return false;
      } 
    var data=$("#userData").serializeArray();
    data.push({"name":"id","value":id}); 
      $.ajax({
        type: "post",
        url: "api/_updateUserPwd.php",
        data: data,
        success: function (response) {
          if (response.code==1) {
            $(".alert-danger").text('密码更新成功，可使用新密码登录');
            $(".alert-danger").fadeIn(2000).delay(500).fadeOut(1000);
          }else{
            $(".alert-danger").text(response.msg);
            $(".alert-danger").fadeIn(2000).delay(1500).fadeOut(1000);
          }
        }
      });
    })
  })
  </script>
</body>
</html>
