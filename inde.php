<?php
include_once 'config.php';
include_once 'function.php';
// $conn=mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_NAME);
$pageSize=5;
$offset=0;
$sql="select p.id,p.category_id,p.title,p.created,p.content,p.views,p.likes,c.name,p.feature,
(select count(*) from comments co where co.post_id=p.id) as commentsCount,u.nickname
from posts p join categories c on c.id=p.category_id 
join users u on u.id=p.user_id
where c.classname!=''
order by p.created desc limit $offset, $pageSize;";
// $res=mysqli_query($conn,$sql);
// $postArr=[];
// while ($row=mysqli_fetch_assoc($res)) {
//   $postArr[]=$row;
// }
$postArr=query($sql);
$sqlHot="select p.created,p.likes,p.id,p.title,p.views,p.feature from posts p 
where DATEDIFF(current_timestamp(),p.created)/360 <3 ORDER BY views desc limit 5;";
$hotArr=query($sqlHot);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" href="static/assets/css/style.css">
  <link rel="stylesheet" href="static/assets/vendors/font-awesome/css/font-awesome.css">
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
    <!-- 抽取公共部分 -->
    <?php include_once './public/_header.php'; 
    include_once './public/_aside.php';
    ?>
    
    <div class="content">
      <div class="swipe">
        <ul class="swipe-wrapper">
          <li>
            <a href="#">
              <img src="static/uploads/slide_1.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="#">
              <img src="static/uploads/slide_2.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="#">
              <img src="static/uploads/slide_1.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="#">
              <img src="static/uploads/slide_2.jpg">
              <span>XIU主题演示</span>
            </a>
          </li>
        </ul>
        <p class="cursor"><span class="active"></span><span></span><span></span><span></span></p>
        <a href="javascript:;" class="arrow prev"><i class="fa fa-chevron-left"></i></a>
        <a href="javascript:;" class="arrow next"><i class="fa fa-chevron-right"></i></a>
      </div>
      <div class="panel focus">
        <h3>焦点关注</h3>
        <ul>
          <li class="large">
            <a href="javascript:;">
              <img src="static/uploads/hots_1.jpg" alt="">
              <span>XIU主题演示</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_2.jpg" alt="">
              <span>星球大战：原力觉醒视频演示 电影票68</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_3.jpg" alt="">
              <span>你敢骑吗？全球第一辆全功能3D打印摩托车亮相</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_4.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_5.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="panel top">
        <h3>一周热门排行</h3>
        <ol>
        <?php  $i=1 ?>
          <?php foreach ($hotArr as $key => $value) {?>
            <li>
            <i><?php echo $i++ ?></i>
            <a href="detail.php?postId=<?php echo $value['id'] ?>"><?php echo $value['title'] ?></a>
            <a href="javascript:;" class="like">赞(<?php echo $value['likes'] ?>)</a>
            <span>阅读 (<?php echo $value['views'] ?>)</span>
          </li>
         <?php } ?>
          <!-- <li>
            <i>1</i>
            <a href="javascript:;">你敢骑吗？全球第一辆全功能3D打印摩托车亮相</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>2</i>
            <a href="javascript:;">又现酒窝夹笔盖新技能 城里人是不让人活了！</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span class="">阅读 (18206)</span>
          </li>
          <li>
            <i>3</i>
            <a href="javascript:;">实在太邪恶！照亮妹纸绝对领域</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>4</i>
            <a href="javascript:;">没有任何防护措施的摄影师在水下拍到了这些画面</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li>
          <li>
            <i>5</i>
            <a href="javascript:;">废灯泡的14种玩法 妹子见了都会心动</a>
            <a href="javascript:;" class="like">赞(964)</a>
            <span>阅读 (18206)</span>
          </li> -->
        </ol>
      </div>
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_2.jpg" alt="">
              <span>星球大战:原力觉醒视频演示 电影票68</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_3.jpg" alt="">
              <span>你敢骑吗？全球第一辆全功能3D打印摩托车亮相</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_4.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="static/uploads/hots_5.jpg" alt="">
              <span>实在太邪恶！照亮妹纸绝对领域</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="panel new">
        <h3>最新发布</h3>
        <?php
foreach ($postArr as $key => $value) {?>
   <div class="entry">
          <div class="head">
            <span class="sort"><?php echo $value['name'] ?></span>
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
              <a href="list.php?categories-id=<?php echo $value['category_id'] ?>" class="tags">
                分类：<span><?php echo $value['name'] ?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $value['feature'] ?>" alt="">
            </a>
          </div>
        </div>
<?php }
        ?>
<script type="text/template" id="postTemp">
        <% for(var i=0;i<items.length;i++){%>
         <div class="entry">
          <div class="head">
            <span class="sort"><%=items[i].name%></span>
            <a href="javascript:;"><%=items[i].title%></a>
          </div>
          <div class="main">
            <p class="info"><%=items[i].nickname%> 发表于 <%=items[i].created%></p>
            <p class="brief"><%=items[i].content%></p>
            <p class="extra">
              <span class="reading">阅读(<%=items[i].views%>)</span>
              <span class="comment">评论(<%=items[i].commentCount%>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<%=items[i].likes%>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><%=items[i].name%></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<%=items[i].feature%>" alt="">
            </a>
          </div>
        </div>
        <% }%>
      </script>
      </div>
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
  <script src="static/assets/vendors/swipe/swipe.js"></script>
  <script src="static/assets/vendors/art-template/template-native.js"></script>
  <script>
  $(function() {
    var currentPage = 1;
    var pageSize = 5;
    $(".loadmore").on("click", function() {
        currentPage++;
        $.ajax({
            type: "post",
            url: "../getMoreData.php",
            data: { "currentPage": currentPage, pageSize: pageSize },
            datatype: "json",
            success: function(response) {
              if (response.code == 1) {
                var html = template("postTemp", {"items":response.data});
                $(".new").append(html);
                }
                var maxPage = Math.ceil(response.totalCount / pageSize);
                if (currentPage >= maxPage) {
                    $(".loadmore").hide();
                }
            }
        });
    })
})
</script>
  <script>
    //
    var swiper = Swipe(document.querySelector('.swipe'), {
      auto: 3000,
      transitionEnd: function (index) {
        // index++;

        $('.cursor span').eq(index).addClass('active').siblings('.active').removeClass('active');
      }
    });

    // 上/下一张
    $('.swipe .arrow').on('click', function () {
      var _this = $(this);

      if(_this.is('.prev')) {
        swiper.prev();
      } else if(_this.is('.next')) {
        swiper.next();
      }
    })
  </script>
</body>
</html>
