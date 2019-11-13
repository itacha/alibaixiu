<?php
include_once '../config.php';
include_once '../function.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
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
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
       <div class="alert alert-danger" style="display:none">
        <strong>错误！</strong><span class="msg"></span>
      </div> 
      <div class="row">
        <div class="col-md-4">
          <form id="userData">
            <h2>添加新用户</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="password" placeholder="密码">
            </div>
            <div class="form-group">
            状态：<select name="status" id="status">
                <option value="unactivated">未激活</option>
                <option value="activated">激活</option>
                <option value="forbidden">禁止</option>
                <option value="trashed">回收站</option>
              </select>
            </div>
            <div class="form-group">
              <input type="button" value="添加" class="btn btn-primary" id="btn-add">
              <input type="button" value="编辑完成" class="btn btn-primary" id="edit-complete" style="display:none">
              <input type="button" value="取消编辑" class="btn btn-primary" id="edit-cancel" style="display:none;">
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="delAll btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php $currentPage='users'; ?>
  <?php include_once 'public/_aside.php' ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
    $(function(){
      var statusArr={unactivated:'未激活',activated:'激活',forbidden:'禁止',trashed:'回收站'};
      var statusArr2={'未激活':'unactivated','激活':'activated','禁止':'forbidden','回收站':'trashed'};
      //渲染用户数据
      $.ajax({
        type: "post",
        url: "api/_getUserAvatar.php",
        success: function (response) {
          if (response.code==1) {
            $.each(response.data, function (indexInArray, e) { 
              var str=`<tr data-userId="${e.id}">
                  <td class="text-center"><input type="checkbox"></td>
                  <td class="text-center"><img class="avatar" src="..${e.avatar}"></td>
                  <td>${e.email}</td>
                  <td>${e.slug}</td>
                  <td>${e.nickname}</td>
                  <td>${statusArr[e.status]}</td>
                  <td class="text-center">
                    <a href="javascript:;" class="btn btn-default btn-xs edit">编辑</a>
                    <a href="javascript:;" class="btn btn-danger btn-xs del">删除</a>
                  </td>
                </tr>`;
                $(str).appendTo($("tbody"));
            });
          }
        }
      });
      //验证用户表单信息
      var email;
      var slug;
      var nickname;
      var password;
      var status;
      function testUser(){
         email=$("#email").val();
        slug=$("#slug").val();
         nickname=$("#nickname").val();
         password=$("#password").val();
        if ($.trim(email)=='') {
          $(".msg").text('邮箱不能为空');
          $(".alert-danger").show();
          return false;
        }
        if ($.trim(slug)=='') {
          $(".msg").text('别名不能为空');
          $(".alert-danger").show();
          return false;
        }
        if ($.trim(nickname)=='') {
          $(".msg").text('昵称不能为空');
          $(".alert-danger").show();
          return false;
        }
        if ($.trim(password)=='') {
          $(".msg").text('密码不能为空');
          $(".alert-danger").show();
          return false;
        }
        var regE=/\w+[@]\w+[.]\w+/;
        var regpwd=/^[a-zA-Z0-9]{6,10}$/;
        if (!regE.test(email)) {
          $(".msg").text("邮箱格式错误，请重新输入");
          $(".alert-danger").show();
          return false;
        }
        if (!regpwd.test(password)) {
          $(".msg").text("密码格式错误，请重新输入");
          $(".alert-danger").show();
          return false;
        }
        return true;
      }
      // 添加新用户
      $("#btn-add").on("click",function(){
        var bool=testUser();        
        status=$("#status").val();
        if (bool) {
          $.ajax({
          type: "post",
          url: "api/_addUser.php",
          data: $("#userData").serialize(),
          success: function (response) {
            if (response.code==1) {
              $(".msg").text("");
              $(".alert-danger").hide();                                  
              var str=`<tr data-userId="${response.id}">
                  <td class="text-center"><input type="checkbox"></td>
                  <td class="text-center"><img class="avatar" src="../static/uploads/avatar.jpg"></td>
                  <td>${email}</td>
                  <td>${slug}</td>
                  <td>${nickname}</td>
                  <td>${statusArr[status]}</td>
                  <td class="text-center">
                    <a href="javascript:;" class="btn btn-default btn-xs edit">编辑</a>
                    <a href="javascript:;" class="btn btn-danger btn-xs del">删除</a>
                  </td>
                </tr>`;
                $(str).appendTo($("tbody"));
                $("#email").val('');
                $("#nickname").val('');
                $("#slug").val('');
                $("#password").val('');
            } else {
              $(".msg").text(response.msg);
              $(".alert-danger").show();
            }
          }
        });
        }
      });
      //编辑用户
      var id;
      var currentRow;
      $("tbody").on("click",".edit",function(){
        var email=$(this).parents("tr").children().eq(2).text();
        var slug=$(this).parents("tr").children().eq(3).text();
        var nickname=$(this).parents("tr").children().eq(4).text();
        var status=$(this).parents("tr").children().eq(5).text();
        var password;
        currentRow=$(this).parents("tr");
        $("h2").text("编辑用户");
        $("#btn-add").hide();
        $("#edit-complete").show();
        $("#edit-cancel").show();
        $("#email").val(email);
        $("#slug").val(slug);
        $("#nickname").val(nickname);
        $("#status").val(statusArr2[status]);
        id=$(this).parents("tr").attr("data-userId");
        $.ajax({
          type: "post",
          url: "api/_getUser.php",
          data:{id:id},
          success: function (response) { 
            password=response.data.password;
            $("#password").val(password);
          }
        });
      })
      //编辑用户完成
      $("#edit-complete").on("click",function(){
        var bool=testUser();  
        status=$("#status").val(); 
        if (bool) {
          $.ajax({
            type: "post",
            url: "api/_updateUser.php",
            data: {id:id,slug:slug,email:email,password:password,nickname:nickname,status:status},
            success: function (response) {
              if (response.code==1) {
                currentRow.children().eq(2).text(email);
                currentRow.children().eq(3).text(slug);
                currentRow.children().eq(4).text(nickname);
                currentRow.children().eq(5).text(statusArr[status]);
                $("h2").text("添加新用户");
                $("#btn-add").show();
                $("#edit-cancel").hide();
                $("#edit-complete").hide();
                $("#nickname").val('');
                $("#slug").val('');
                $("#email").val('');
                $("#password").val('');
              }
            }
          });
        }  
      })
      //取消编辑
      $("#edit-cancel").on("click",function(){
        $("h2").text("添加新用户");
        $("#btn-add").show();
        $("#edit-cancel").hide();
        $("#edit-complete").hide();
        $("#nickname").val('');
        $("#slug").val('');
        $("#email").val('');
        $("#password").val('');
      })
      //删除用户
      $("tbody").on("click",".del",function(){
        var id=$(this).parents("tr").attr("data-userid");
        console.log(id);
        
        var row=$(this).parents("tr");
        $.ajax({
          type: "post",
          url: "api/_deleteUser.php",
          data: {id:id},
          success: function (response) {
            if (response.code==1) {
              row.remove();
            } else {
              alert(response.msg);
            }
          }
        });
      })
      // 批量删除
      $("thead input").on("click",function(){
        var status=$(this).prop("checked");
        $("tbody input").prop("checked",status);
          if (status) {
          $(".delAll").show();
        } else {
          $(".delAll").hide();        
        }
      })
      $("tbody").on("click","input",function(){
        var cks=$("tbody input:checked");
        $("thead input").prop("checked",cks.size()==$("tbody input").size());
          if (cks.size()>=2) {
          $(".delAll").show();
        }else{
          $(".delAll").hide();    
        }
      })
      $(".delAll").on("click",function(){
        var ids=[];
        var cks=$("tbody input:checked");
        $.each(cks, function (index, value) {      
          var id=$(value).parents("tr").attr("data-userid");
          ids.push(id);
        });
        $.ajax({
          type: "post",
          url: "api/_deleteUsers.php",
          data: {ids:ids},
          success: function (response) {
            if (response.code==1) {
              cks.parents("tr").remove();
            }else{
              $(".msg").text(response.msg);
              $(".alert-danger").show();
            }
          }
        });
      })
    })
  </script>
</body>
</html>
