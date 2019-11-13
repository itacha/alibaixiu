<?php
include_once '../config.php';
include_once '../function.php';
checkLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
  <style>
  .circle>li{
    float:left;
    margin-left: 10px;
    
  }
  .leftArr{
    background-image: url(../static/assets/img/error.png);
    width: 30px;
    height: 30px;
    display: inline-block;
  }
  .rightArr{
    background-image: url(../static/assets/img/success.png);
    width: 30px;
    height: 30px;
    display: inline-block;

  }
  .circle {
    list-style: none;
    margin-bottom: 20px;
  }
  .circle li{   
    border-radius: 50%;
    border:5px solid #eee;
  }
  .circle .color{
    border:5px solid blue;
  }
  
  </style>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
   <?php include_once 'public/_navbar.php' ?>    
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select id="category" name="" class="form-control input-sm">
            <option value="all">所有分类</option>
          </select>
          <select id="status" name="" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted">草稿</option>
            <option value="published">已发布</option>
            <option value="trashed">已删除</option>
          </select>
          <!-- <button class="btn btn-default btn-sm">筛选</button> -->
          <input type="button" value="筛选" id="btn-filt" class="btn btn-default btn-sm">
        </form>
        <ul class="pagination pagination-sm pull-right">
        </ul>
      </div>

      <!-- 分页 -->
      <a href="#" class="leftArr"> < </a>
      <div id="wrap"></div>
      <a href="#" class="rightArr"> ></a>
      <ul class="circle">        
      </ul>

<!-- 切换 -->
<ul class="tab-left">
  <li>1</li>
  <li>2</li>
  <li>3</li>
</ul>

<ul style="height:20px;overflow:hidden" class="tab-right">
  <li>1</li>
  <li>2</li>
  <li>3</li>
</ul>

      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
  <?php include_once 'public/_aside.php' ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../static/assets/js/template.js"></script>
  <script id="info-1" type="text/html">
    <ul>
      {{each data}}
      <li>{{$value.title}}</li>
      {{/each}}
    </ul>
  </script>
  
  <script>NProgress.done()</script>
  <script>
    var currentPage=1;
    var pageSize=8;
    var pageCount=1;
    //渲染分页按钮
    function makePageList(){
        var start=currentPage-2;
        if (start<1) {
          start=1;
        }
        var end=start+4;
        if (end>pageCount) {
          end=pageCount;
        }
        var html='';
          if (currentPage>1) {
        html+='<li class="item" data-page="'+(currentPage-1)+'"><a href="javascript:;">上一页</a></li>';      
        }
        for (let i = start; i <=end; i++) {
          if (i==currentPage) {
            html+='<li class="item active" data-page="'+i+'"><a href="#">'+i+'</a></li>';
          } else {
            html+='<li class="item" data-page="'+i+'"><a href="#">'+i+'</a></li>';        
          }     
        }
        if (end<pageCount) {
          html+='<li class="item" data-page="'+(currentPage+1)+'"><a href="javascript:;">下一页</a></li>';
        }
        $(".pagination").html(html);
    }
    var statusArr={'drafted':'草稿','published':'已发布','trashed':'已作废'};
    // 渲染表格文章数据
    function getPosts(data){
      $.each(data, function (indexInArray, value) { 
            var str=`<tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>${value.title}</td>
            <td>${value.nickname}</td>
            <td>${value.name}</td>
            <td class="text-center">${value.created}</td>
            <td class="text-center">${statusArr[value.status]}</td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>`;
          $(str).appendTo($("tbody"));
      })
    }
    // 第一次进入页面渲染数据
  $.ajax({ 
    type: "post",
    url: "api/_getPosts.php",
    data:{currentPage:currentPage,pageSize:pageSize,status:$("#status").val(),categoryId:$("#category").val()},
    success: function (response) {
      // console.log(response)
      if (response.code==1) {
        $("tbody").empty();
        pageCount=Math.ceil(response.totalCount/pageSize);
        makePageList();
        getPosts(response.data);
      }
    }
  });

// 分页
var json={};
  var circleLength;
  $.ajax({ 
    type: "post",
    url: "api/_getPosts.php",
    data:{currentPage:currentPage,pageSize:pageSize,status:$("#status").val(),categoryId:$("#category").val()},
    success: function (response) {
      json=response;
      $("#wrap").append(template('info-1',json));
       circleLength=Math.ceil(response.totalCount/100)
      var html=''
      for (let i = 0; i < circleLength; i++) {
        html+='<li></li>'  
      }
      $('.circle').append(html)
   $(".circle li").eq(currentPage-1).addClass("circle color").siblings().removeClass("circle color");
    }
  });
  
 $('.leftArr').click(function(){
  currentPage--;
  if(currentPage<1){
      currentPage=1;
      $('.leftArr').css('background-image', 'url(../static/assets/img/error.png)')
      return;
   } 
   $('.leftArr').css('background-image', 'url(../static/assets/img/arrow-prev.png)')
   $(".circle li").eq(currentPage-1).addClass("circle color").siblings().removeClass("circle color");
  $.ajax({ 
    type: "post",
    url: "api/_getPosts.php",
    data:{currentPage:currentPage,pageSize:pageSize,status:$("#status").val(),categoryId:$("#category").val()},
    success: function (response){
      json=response;
      $('#wrap').html('')
      $("#wrap").append(template('info-1',json));
    }
  });
 })
 $('.rightArr').click(function(){
   currentPage++;
   if(currentPage>circleLength){
      currentPage=circleLength;
      $('.rightArr').css('background-image', 'url(../static/assets/img/arrow-next.png)')
      return;
   }
   $(".circle li").eq(currentPage-1).addClass("circle color").siblings().removeClass("circle color");
   $('.leftArr').css('background-image', 'url(../static/assets/img/arrow-prev.png)')
   $('.rightArr').css('background-image', 'url(../static/assets/img/success.png)')
  $.ajax({ 
    type: "post",
    url: "api/_getPosts.php",
    data:{currentPage:currentPage,pageSize:pageSize,status:$("#status").val(),categoryId:$("#category").val()},
    success: function (response) {
      json=response;
      $('#wrap').html('')
      $("#wrap").append(template('info-1',json));
    }
  });
 })
// 切换
$('.tab-left').on('click','li',function(){
  $(this).addClass('.circle color').siblings().removeClass('.circle color')
  var index=$('.tab-left>li').index($(this))
  console.log(index)
  $('.tab-right li').eq(index).show().siblings().hide()
})




  // 点击分页按钮渲染表格对应数据
  $(".pagination").on("click",".item",function(){
    currentPage=parseInt($(this).attr("data-page"));
    $.ajax({
    type: "post",
    url: "api/_getPosts.php",
    data: {currentPage:currentPage,pageSize:pageSize,status:$("#status").val(),categoryId:$("#category").val()},
    success: function (response) {
      if (response.code==1) {
        $("tbody").empty();
        pageCount=Math.ceil(response.totalCount/pageSize);
        makePageList();
       getPosts(response.data);
      }
    }
    });
  })
  // 渲染分类数据
  $.ajax({
    type: "post",
    url: "api/_getCategoryData.php",
    success: function (response) {
      $.each(response.data, function (indexInArray, value) { 
         var str=`<option value="${value.id}">${value.name}</option>`;
        $(str).appendTo($("#category"));
      });
    }
  });
  // 筛选数据
  $("#btn-filt").on("click",function(){
    var status=$("#status").val();
    var categoryId=$("#category").val();
    $.ajax({
      type: "post",
      url: "api/_getPosts.php",
      data: {currentPage:currentPage,pageSize:pageSize,status:status,categoryId:categoryId},
      success: function (response) {
        if (response.code==1) {
        $("tbody").empty();
        pageCount=Math.ceil(response.totalCount/pageSize);
        makePageList();
        getPosts(response.data);
        }else{
        $("tbody").empty();
        var html="<tr><td colspan=7>"+response.msg+"</td></tr>";
          $(html).appendTo($("tbody"));
        }
      }
    });
  })
  </script>
</body>
</html>
