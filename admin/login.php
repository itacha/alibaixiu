<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap">
      <img class="avatar" src="../static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
       <div class="alert alert-danger" style="display:none;">
        <strong>错误！</strong> 用户名或密码错误！ 
      </div> 
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" class="form-control" placeholder="密码">
      </div>
      <span class="btn btn-primary btn-block">登 录</span>
    </form>
  </div>
  <script src="../static/assets/vendors/jquery/jquery.min.js"></script>
  <script>
  $(".btn-primary").on("click",function(){
    var email=$("#email").val();
    var pwd=$("#password").val();
    var regE=/\w+[@]\w+[.]\w+/;
    var regpwd=/^[a-zA-Z0-9]{6,10}$/;
    if (!regE.test(email)) {
      $(".alert-danger").text("邮箱格式错误，请重新输入");
      $(".alert-danger").show();
      return;
    }
    if (!regpwd.test(pwd)) {
      $(".alert-danger").text("密码格式错误，请重新输入");
      $(".alert-danger").show();
      return;
    }
    $.ajax({
      type: "post",
      url: "api/_userlogin.php",
      data: {email:email,pwd:pwd},
      success: function (response) {
        if (response.code==1) {
          location.href="index.php";
        }else{
          $(".alert-danger").text("邮箱或密码错误，请重新输入");
          $(".alert-danger").show();        
        }
      }
    });
  })
  </script>
</body>
</html>
