<?php
include_once '../config.php';
include_once '../function.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
     <div class="alert alert-danger" style="display:none">
        <strong>错误！</strong><span class="msg">发生XXX错误</span>
      </div> 
      <form class="row" id="posts">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <!-- <button class="btn btn-primary" type="submit">保存</button> -->
            <input type="button" value="提交" class="btn btn-primary" id="btn-save">
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php include_once 'public/_aside.php' ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <!-- 引入富文本编辑器插件 -->
  <script src="../static/assets/vendors/ckeditor/ckeditor.js"></script>
  <script>
    //渲染分类数据
    $.ajax({
      type: "post",
      url: "api/_getCategoryData.php",
      success: function (response) {
        var str='';
        if (response.code==1) {
          $.each(response.data, function (indexInArray, e) { 
              str+=`<option value="${e.id}">${e.name}</option>`;
          });
          $("#category").html(str);
        }
      }
    });
    // 先上传图片到服务器，成功上传后预览图片，要在客户端预览图片，必须先上传图片到服务器，再从服务器拿图片显示到客户端
  $("#feature").on("change",function(){
    var file=this.files[0];
    var data=new FormData;
    data.append("file",file);
    $.ajax({
      type: "post",
      url: "api/_uploadFile.php",
      data: data,
      contentType:false,
      processData:false,
      success: function (response) {
        $(".help-block").attr("src",response.src).show();
        $(".feature").val(response.src);
      }
    });
  })
  var userId;
  $.ajax({
    type: "post",
    url: "api/_getUserAvatar.php",
    success: function (response) {
      if (response.code==1) {
        // var str=`<input id="userId" class="form-control input-lg" 
        // name="userId" type="text" value="${response.userId}" style="display:none">`;
        // $(str).appendTo($("#posts"));      
        userId=response.userId; 
      }
    }
  });
  // 将文本域替换为富文本编辑器
  var content=CKEDITOR.replace("content");

  var time=document.getElementById("created");
  var now=new Date();
  var year=now.getFullYear();
  var month=now.getMonth()+1;
  month=month<10?"0"+month:month;
  var day=now.getDate();
  day=day<10?"0"+day:day;
  var hour=now.getHours();
  hour=hour<10?"0"+hour:hour;
  var min=now.getMinutes();
  min=min<10?"0"+min:min;
  var sec=now.getSeconds();
  sec=sec<10?"0"+sec:sec;
  time.value=year+"-"+month+"-"+day+"T"+hour+":"+min+":"+sec;
  
  $("#title").on("blur",function(){
    if ($.trim($(this).val())=='') {
      $(".alert-danger").show();
        $(".msg").text("标题不能为空");
    }
  })
// 验证富文本编辑器内容不为空
function checkContent(){
  if($("#content")[0].value=="")
    {
      $(".alert-danger").show();
        $(".msg").text("内容不能为空");
        // $("#TxtSubject")[0].focus();
        return false;
    }else if(content.document.getBody().getText()==""){
    $(".alert-danger").show();
        $(".msg").text("内容不能为空");
        return false; 
    }
}

  $("#btn-save").on("click",function(){
    //富文本编辑器里的数据更新到文本域
    content.updateElement();
    // var data=$("#posts").serialize();  
    checkContent();
    $.ajax({
      type: "post",
      url: "api/_addPosts.php",
      data: {title:$("#title").val(),content:$("#content").val(),slug:$("#slug").val(),
      feature:$(".help-block").attr("src"),category:$("#category").val(),created:time.value,
      status:$("#status").val(),userId:userId},
			dataType:'json',
      success: function (response) {
        if (response.code==1) {
          $("#title").val('');
          $("#content").val('');
          $("#slug").val('');
          $(".help-block").attr("src","");
          $("#category").val('');
          $("#status").val('');
        }
        
      }
    });
  })
  </script>
</body>
</html>
