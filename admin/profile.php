<?php
include_once '../config.php';
include_once '../function.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
  <style>
  .alert{
    width:560px;
    margin: 0 auto 10px;
  }
  </style>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
  <?php include_once 'public/_navbar.php' ?>   
    <div class="container-fluid">
      <div class="page-title">
        <h1>我的个人资料</h1>
      </div> 
      <form class="form-horizontal" id="userData" method="post">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src="../static/assets/img/default.png" class="user-avatar">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="w@zce.me" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="zce" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="汪磊" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" placeholder="Bio" cols="30" rows="6">MAKE IT BETTER!</textarea>
          </div>
        </div>
         <!-- 有错误信息时展示 -->
        <div class="alert alert-danger" style="display:none;">
          <strong>错误！</strong><span class="msg"></span>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <input type="button" value="更新" class="btn btn-primary btn-update">
            <a id="modifyPwd" class="btn btn-link" href="password-reset.php">修改密码</a>
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
    var avatar=$(".user-avatar");
    var email=$("#email");
    var slug=$("#slug");
    var nickname=$("#nickname");
    var bio=$("#bio");
    var userId;
    var userAvatar;
//首次进入页面渲染数据
    $.ajax({
      type: "post",
      url: "api/_getUserAvatar.php",
      data: "data",
      success: function (response) {
        if (response.code==1) {
          avatar.attr("src",".."+response.avatar);
          email.val(response.email);
          slug.val(response.slug);
          nickname.val(response.name);
          bio.val(response.bio);
          userId=response.userId;
          $("#modifyPwd").attr("href","password-reset.php?id="+userId);
        }
      }
    });
    $("#avatar").on("change",function(){
      //文件上传框元素有一个属性files,是个伪数组，里面存储了修改的文件数据
      var file=this.files[0];
      // jquery 是无法直接把文件上传的，需要一个FormData对象来配合着上传才可以
      var data=new FormData;
      //FormData这个对象有一个append(键，值)方法     
      data.append("file",file);
      $.ajax({
        type: "post",
        url: "api/_uploadFile.php",
        data: data,
        // 需要配置两个参数，jQuery才能把文件带回到服务端
        contentType : false,  //只有设置了这个参数，jquery才会把文件带回到服务端
        processData : false,  //告诉jq不要序列化我们的参数
        success: function (response) {
          if (response.code==1) {
            $(".user-avatar").attr("src",response.src);
            userAvatar=response.src;
          }
        }
      });
    })
    $(".btn-update").on("click",function(){
      if ($.trim(nickname.val())=='') {
        $(".msg").text('昵称不能为空');
          $(".alert-danger").show();
          return false;
      }
      var regName=/^[\d\w\W\u4e00-\u9fa5,\.;\:"'?!\-]{2,16}$/;
      if (!regName.test(nickname.val())) {
        $(".msg").text('昵称格式错误，请重新输入');
          $(".alert-danger").show();
          return false;
      }
      $.ajax({
        type: "post",
        url: "api/_updateUser.php",
        data: {id:userId,slug:slug.val(),nickname:nickname.val(),avatar:userAvatar,bio:bio.val()},
        success: function (response) {
          if (response.code==1) {
            $(".alert-danger").text('更新成功');
            $(".alert-danger").fadeIn(2000).delay(500).fadeOut(1000);
          }
        }
      });
    })
  })
  </script>
</body>
</html>
