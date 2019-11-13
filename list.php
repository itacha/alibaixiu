<?php
include_once './config.php';
// $connect=mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
include_once 'function.php';
$id=$_GET['categories-id'];
$pageSize=5;
$offset=0;
$sql="select p.id,p.title,p.created,p.content,p.views,p.likes,c.name,p.feature,
(select count(*) from comments co where co.post_id=p.id) as commentsCount,u.nickname
from posts p join categories c on c.id=p.category_id
join users u on u.id=p.user_id
where p.category_id = $id
order by p.created desc limit $offset, $pageSize;";
// $res=mysqli_query($connect,$sql);
// $postArr=[];
// while ($row=mysqli_fetch_assoc($res)) {
//   $postArr[]=$row;
// }
$postArr=query($sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" href="static/assets/css/style.css">
  <link rel="stylesheet" href="static/assets/vendors/font-awesome/css/font-awesome.css">
  <!-- <link rel="stylesheet" href="static/assets/vendors/nprogress/nprogress.css"> -->
</head>
<body>
  <div class="wrapper">
    <div class="topnav">
      <ul>
        <li><a href="javascript:;"><i class="fa fa-glass"></i>奇趣事</a></li>
        <li><a href="javascript:;"><i class="fa fa-phone"></i>潮科技</a></li>
        <li><a href="javascript:;"><i class="fa fa-fire"></i>会生活</a></li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i>美奇迹</a></li>
      </ul>
    </div>
    <?php include_once './public/_header.php'; 
    include_once './public/_aside.php';
    ?>
    <div class="content">
      <div class="panel new">
        <h3><?php echo $postArr[0]['name'] ?></h3>
        <?php 
        foreach ($postArr as $key => $value) {?>
           <div class="entry">
          <div class="head">
            <a href="detail.php?postId=<?php echo $value['id'] ?>"><?php echo $value['title'] ?></a>
          </div>
          <div class="main">
            <p class="info"><?php echo $value['nickname'] ?> 发表于 <?php echo $value['created'] ?></p>
            <p class="brief"><?php echo $value['content'] ?></p>
            <p class="extra">
              <span class="reading">阅读(<?php echo $value['views'] ?>)</span>
              <span class="comment">评论(<?php echo $value['commentsCount'] ?>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $value['likes'] ?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?php echo $value['name'] ?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $value['feature'] ?>" alt="">
            </a>
          </div>
        </div>
       <?php } ?>
      <div class="loadmore">
           <span class="btn">加载更多</span>
        </div>
      </div>
    
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
  <script src="static/assets/vendors/jquery/jquery.js"></script>
  <script>
  $(function(){
    var currentPage=1;
    var pageSize=10;
    $(".loadmore").on("click",function(){
      var categoryId=location.search.split("=")[1];
      currentPage++;
      $.ajax({
        type: "get",
        url: "api/_getMore.php",
        data: {categoryId:categoryId,currentPage:currentPage,pageSize:pageSize},
        dataType:"json",
        success: function (response) {  
          console.log(response);
                          
          if (response.code==1) {
            var data=response.data;
            $.each(data, function (indexInArray, value) {              
               var str=`<div class="entry">
          <div class="head">
            <a href="javascript:;">${value.title}</a>
          </div>
          <div class="main">
            <p class="info">${value.nickname} 发表于 ${value.created}</p>
            <p class="brief">${value.content}</p>
            <p class="extra">
              <span class="reading">阅读(${value.views})</span>
              <span class="comment">评论(${value.commentsCount})</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(${value.likes})</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span>${value.name}</span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="${value.feature}" alt="">
            </a>
          </div>
        </div>`;
        $(str).insertBefore($(".loadmore"));
            });
            var maxPage=Math.ceil(response.totalCount/pageSize);
          if (currentPage>=maxPage) {
            $(".loadmore").hide();
          }
          }
        }
      });
    })
  })
  </script>
</body>
</html>