<?php
require_once '../config.php';
require_once '../function.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display:none;">
        <strong>错误！</strong><span class="msg">发生XXX错误</span>
      </div> 
      <div class="row">
        <div class="col-md-4">
          <form id="data">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="classname">类名</label>
              <input id="classname" class="form-control" name="classname" type="text" placeholder="类名">
              <p class="help-block">https://zce.me/category/<strong>classname</strong></p>
            </div>
            <div class="form-group">
              <!-- <button class="btn btn-primary" type="submit">添加</button> -->
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
                <th>名称</th>
                <th>Slug</th>
                <th>类名</th>
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

  <?php include_once 'public/_aside.php' ?>
  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
    //渲染分类表格数据
  $(function(){
    $.ajax({
      type: "post",
      url: "api/_getCategoryData.php",
      success: function (response) {
        var str="";       
        $.each(response.data, function (indexInArray, value) { 
           str+='<tr data-categoryId="'+value.id+'">\
                <td class="text-center"><input type="checkbox"></td>\
                <td>'+value.name+'</td>\
                <td>'+value.slug+'</td>\
                <td>'+value.classname+'</td>\
                <td class="text-center">\
                  <a href="javascript:;" class="btn btn-info btn-xs edit">编辑</a>\
                  <a href="javascript:;" class="btn btn-danger btn-xs del">删除</a>\
                </td>\
              </tr>';
        });
        $(str).appendTo($("tbody"));
      }
    });
    //添加新分类
    $("#btn-add").on("click",function(){
      var name=$("#name").val();
      var slug=$("#slug").val();
      var classname=$("#classname").val();
      if($.trim(name)==''){
        $(".alert-danger").show();
        $(".msg").text("分类名称不能为空");
        return;
      }
      if($.trim(slug)==''){
        $(".alert-danger").show();
        $(".msg").text("别名不能为空");
        return;
      }
      if($.trim(classname)==''){
        $(".alert-danger").show();
        $(".msg").text("类名不能为空");
        return;
      }
      $.ajax({
        type: "post",
        url: "api/_addCategory.php",
        data:$("#data").serialize(),
        success: function (response) {
          if (response.code==0) {
            $(".msg").text(response.msg);
            $(".alert-danger").show();
          }else{
            $(".msg").text();
            $(".alert-danger").hide();
            str='<tr data-categoryId="'+response.id+'">\
                <td class="text-center"><input type="checkbox"></td>\
                <td>'+name+'</td>\
                <td>'+slug+'</td>\
                <td>'+classname+'</td>\
                <td class="text-center">\
                  <a href="javascript:;" class="btn btn-info btn-xs edit">编辑</a>\
                  <a href="javascript:;" class="btn btn-danger btn-xs del">删除</a>\
                </td>\
              </tr>';
              $(str).appendTo($("tbody"));
              $("#name").val('');
              $("#slug").val('');
              $("#classname").val('');
          }
        }
      });
      
    })
    var currentRow;
    //动态创建元素，事件委托绑定
    //编辑分类
    $("tbody").on("click",".edit",function(){
      let name=$(this).parents("tr").children().eq(1).text();
      let slug=$(this).parents("tr").children().eq(2).text();
      let classname=$(this).parents("tr").children().eq(3).text();
      let categoryId=$(this).parents("tr").attr("data-categoryId");
      currentRow=$(this).parents("tr");
      $("h2").text("编辑分类目录");
      $("#btn-add").hide();
      $("#edit-cancel").show();
      $("#edit-complete").show();
      $("#edit-complete").attr("data-categoryId",categoryId);
      $("#name").val(name);
      $("#slug").val(slug);
      $("#classname").val(classname);
    })
    //编辑分类完成
    $("#edit-complete").on("click",function(){
      var id=$(this).attr("data-categoryId");
      var name=$("#name").val();
      var slug=$("#slug").val();
      var classname=$("#classname").val();
      if($.trim(name)==''){
        $(".alert-danger").show();
        $(".msg").text("分类名称不能为空");
        return;
      }
      if($.trim(slug)==''){
        $(".alert-danger").show();
        $(".msg").text("别名不能为空");
        return;
      }
      if($.trim(classname)==''){
        $(".alert-danger").show();
        $(".msg").text("类名不能为空");
        return;
      }
      $.ajax({
        type: "post",
        url: "api/_updateCategory.php",
        data:{id:id,name:name,slug:slug,classname:classname},
        success: function (response) {
          if (response.code==1) {
            currentRow.children().eq(1).text(name);
            currentRow.children().eq(2).text(name);
            currentRow.children().eq(3).text(name);
            $("#btn-add").show();
            $("#edit-cancel").hide();
            $("#edit-complete").hide();
            $("#name").val('');
            $("#slug").val('');
            $("#classname").val('');
          }
        }
      });
    })
    //取消编辑分类
    $("#edit-cancel").on("click",function(){
      $("#btn-add").show();
      $("#edit-cancel").hide();
      $("#edit-complete").hide();
      $("#name").val('');
      $("#slug").val('');
      $("#classname").val('');
    })
    //删除分类
    $("tbody").on("click",".del",function(){
        let id=$(this).parents("tr").attr("data-categoryId");
        var row=$(this).parents("tr");
        $.ajax({
          type: "post",
          url: "api/_deleteCategory.php",
          data: {id:id},
          success: function (response) {
            if (response.code==1) {
              // $(this).parents("tr").remove();
               // 调用$这个对象的方法,此时$(this)是$.ajax对象
              // console.log($(this));
              row.remove();
            } else {
              alert(response.msg);
            }
          }
        });
    })
    //批量删除
    $("thead input").on("click",function(){
      var staus=$(this).prop("checked");
      $("tbody input").prop("checked",staus);
      if (staus) {
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
         var id=$(value).parents("tr").attr("data-categoryId");
         ids.push(id);
      });
      $.ajax({
        type: "post",
        url: "api/_deleteCategories.php",
        data: {ids:ids},
        success: function (response) {
          if (response.code==1) {
            cks.parents("tr").remove();
          }else{
            alert(response.msg);
          }
        }
      });     
    })
  })
  </script>
</body>
</html>
