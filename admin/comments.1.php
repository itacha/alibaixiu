<?php
include_once '../config.php';
include_once '../function.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php require_once 'public/_navbar.php'; ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right">
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <?php $currentPage='comments'; ?>
  <?php  include_once 'public/_aside.php'; ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
  $(function(){
    var currentPage=1;
    var pageSize=10;
    var status={held:'待审核',approved:'准许',rejected:'拒绝',trashed:'回收站'};
    function makeTable(data){
      var str='';
      $.each(data, function (indexInArray, e) { 
            str+=`<tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>${e.author}</td>
            <td>${e.content}</td>
            <td>${e.title}</td>
            <td>${e.created}</td>
            <td>${status[e.status]}</td>
            <td class="text-center">
              <a href="post-add.php" class="btn btn-warning btn-xs">驳回</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>`;
      })
      $("tbody").html(str);
    }
    function makePaging(totalPage){
      var start=currentPage-2;
      if (start<=0) {
        start=1;
      }
      var end=start+4;
      var maxPage=Math.ceil(totalPage/pageSize);
      if (end>maxPage) {
        end=maxPage;
      }
      var html='';
      if (currentPage!=1) {
        html+='<li><a href="javascript:;">上一页</a></li>';
      } else {
        html="";
      }
      for (let i = start; i <=end; i++) {
        if (i==currentPage) {
          html+='<li class="items active" data-page="'+i+'"><a href="javascript:;">'+i+'</a></li>';
        } else {
          html+='<li class="items" data-page="'+i+'"><a href="javascript:;">'+i+'</a></li>';
        }
      }
      if (currentPage!=end) {
        html+='<li><a href="javascript:;">下一页</a></li>';
      } 
      $(".pagination").html(html);
    }
    $.ajax({
      type:"post",
      url: "api/_getCommentsData.php",
      data:{currentPage:currentPage,pageSize:pageSize},
      success: function (response) {
        makeTable(response.data);
        makePaging(response.totalPage);
      }
    });
    $(".pagination").on("click",".items",function(){
      currentPage=$(this).attr("data-page");     
      $.ajax({
        type: "post",
        url: "api/_getCommentsData.php",
        data: {currentPage:currentPage,pageSize:pageSize},
        success: function (response) {          
          makePaging(response.totalPage);
          makeTable(response.data);
        }
      });
    })
  })
  </script>
</body>
</html>
